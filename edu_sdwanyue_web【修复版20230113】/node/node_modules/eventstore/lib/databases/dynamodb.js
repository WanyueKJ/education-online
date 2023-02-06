var util = require('util'),
  Store = require('../base'),
  _ = require('lodash'),
  async = require('async'),
  aws = Store.use('aws-sdk'),
  dbg = require('debug');

var debug = dbg('eventstore:store:dynamodb'),
  error = dbg("eventstore:store:dynamodb:error");

/*
for information on optimizing access patterns see: https://medium.com/building-timehop/one-year-of-dynamodb-at-timehop-f761d9fe5fa1
  - query when possible
  - scan when you will really be visiting all items (or almost all, ex: clear, or undispatched)
  - secondary index when you need only a subset (instead of scan) and it will still be cheaper to run SELECT N+1 query (get partition keys from 2ndary then get full item using getItem(key) )
*/
function DynamoDB(options) {

  options = options || {};

  var awsConf = {
    // don't put AWS credentials in files. Use IAM, ~/.aws/credentials with $AWS_PROFILE, or env vars
    // see: http://docs.aws.amazon.com/AWSJavaScriptSDK/guide/node-configuring.html
    // example using credentials file with $AWS_PROFILE:
    // $ AWS_PROFILE=my-non-default-profile npm test
    region: "us-west-2",
    endpointConf: {}
  };

  // support setting a specific endpoint for dynamodb (ex: DynamoDB local)
  // examples usage for testing:
  // $ AWS_DYNAMODB_ENDPOINT=http://localhost:8000 npm test
  if (process.env["AWS_DYNAMODB_ENDPOINT"]) {
    awsConf.endpointConf = { endpoint: process.env["AWS_DYNAMODB_ENDPOINT"] };
  }

  this.options = _.defaults(options, awsConf);

  var defaults = {
    eventsTableName: 'events',
    undispatchedEventsTableName: 'undispatchedevents',
    snapshotsTableName: 'snapshots',
    // 3 write units / 1 read unit for events & undispatched is just low enough
    // to trigger throttling w/o hitting the 20 test timeout. Takes about 5 minutes to run storeTest.js.
    UndispatchedEventsReadCapacityUnits: 1,
    UndispatchedEventsWriteCapacityUnits: 3,
    EventsReadCapacityUnits: 1,
    EventsWriteCapacityUnits: 3,
    SnapshotReadCapacityUnits: 1,
    SnapshotWriteCapacityUnits: 1,
    useUndispatchedEventsTable: true,
    eventsTableStreamEnabled: false,
    eventsTableStreamViewType: "NEW_IMAGE"
  };

  this.options = _.defaults(this.options, defaults);
}

util.inherits(DynamoDB, Store);

_.extend(DynamoDB.prototype, {

  connect: function (callback) {
    var self = this;
    self.client = new aws.DynamoDB(self.options.endpointConf);
    self.documentClient = new aws.DynamoDB.DocumentClient({ service: self.client });
    self.isConnected = true;

    var createEventsTable = function (callback) {
      createTableIfNotExists(self.client, EventsTableDefinition(self.options), callback);
    };

    var createSnapshotTable = function (callback) {
      createTableIfNotExists(self.client, SnapshotTableDefinition(self.options), callback);
    };

    var createUndispatchedEventsTable = function (done) {
      if (self.options.useUndispatchedEventsTable) {
        createTableIfNotExists(self.client, UndispatchedEventsTableDefinition(self.options), done)
      } else {
        done();
      }
    };

    async.parallel([
      createEventsTable,
      createSnapshotTable,
      createUndispatchedEventsTable
    ], function (err) {
      if (err) {
        error("connect error: " + err);
        if (callback) callback(err);
      } else {
        self.emit('connect');
        if (callback) callback(null, self);
      }
    });
  },

  disconnect: function (callback) {
    this.emit('disconnect');
    if (callback) callback(null);
  },

  removeTables: function (done) {
    var self = this;
    // AWS has a limit on the number of DynamoDB tables for an account. Let's clean up when we're done
    debug("remove all tables created for testing");
    deleteAllTempTables(self.client, self.options, function (err, result) {
      if (err) {
        error("removeTables error: " + err);
        return done(err);
      }
      return done(null, result);
    });
  },

  clear: function (done) {
    var self = this;

    var clearEvents = function (callback) {
      clearEventTables(self.options, self.documentClient, function (err) {
        if (err) {
          error("clearEventTables error: " + err);
          return callback(err);
        }

        callback(null, "events");
      });
    };

    var clearSnapshots = function (callback) {
      clearSnapshotsTable(self.options, self.documentClient, function (err) {
        if (err) {
          error("clearSnapshotsTable error: " + err);
          return callback(err);
        }

        callback(null, "snapshots");
      });
    };

    async.parallel([
      clearEvents,
      clearSnapshots
    ], function (err, data) {
      if (err) {
        error("removeTables error: " + err);
        if (done) done(err);
        return;
      }
      if (done) done(null, self);
    });
  },

  addEvents: function (events, callback) {
    var self = this;

    var noAggId = _.every(events, function (event) {
      return !event.aggregateId
    });

    if (noAggId) {
      var errMsg = 'aggregateId not defined!';
      error(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    async.concatSeries(events,
      function (event, callback) {

        var results = [
          function (callback) {
            var storedEvent = {
              TableName: self.options.eventsTableName,
              Item: new StoredEvent(event),
              ExpressionAttributeNames: {
                "#name": "aggregate"
              },
              ConditionExpression: "attribute_not_exists(aggregateId) and attribute_not_exists(streamRevision) and attribute_not_exists(context) and attribute_not_exists(#name)"
            };

            debug("Saving event to events table: " + JSON.stringify(storedEvent, null, 2));
            self.documentClient.put(storedEvent, function (err, data) {
              if (err) {
                error("dynamodb.addEvents error: " + JSON.stringify(err));
                return callback(err);
              } else {
                debug("event saved");
                callback(null, data);
              }
            });
          }];

        if (self.options.useUndispatchedEventsTable) {
          debug("using undispatchedevents table");
          results.push(function (callback) {
            var storedEvent = {
              TableName: self.options.undispatchedEventsTableName,
              Item: new StoredEvent(event),
              ConditionExpression: "attribute_not_exists(id)"
            };

            debug("Saving event to undispatchedevents table " + JSON.stringify(storedEvent, null, 2));
            self.documentClient.put(storedEvent, function (err, data) {
              if (err) {
                error("dynamodb.addUndispatchedEvents error: " + JSON.stringify(err));
                return callback(err);
              } else {
                debug("undispatched event saved");
                callback(null, data);
              }
            });
          });
        }

        callback(null, results);
      },
      function (err, results) {
        if (err) {
          error("addEvents error: " + JSON.stringify(err));
        }
        async.series(results, callback);
      }
    );
  },

  getEvents: function (query, skip, limit, callback) {
    var self = this;
    var client = new aws.DynamoDB.DocumentClient(self.options.endpointConf);
    var exclusiveStartKey = null;
    var entities = [];

    var tableQuery = {
      TableName: self.options.eventsTableName
    };

    var vals = {};

    if (query && query.aggregateId) {
      vals[":a"] = query.aggregateId;
      tableQuery.KeyConditionExpression = "aggregateId = :a";
    }

    if (query && query.aggregate) {
      vals[":name"] = query.aggregate;
      tableQuery.FilterExpression = "#name = :name";
      tableQuery.ExpressionAttributeNames = {
        "#name": "aggregate"
      };
    }

    if (query && query.context) {
      vals[":c"] = query.context;
      if (tableQuery.FilterExpression && tableQuery.FilterExpression.length !== 0)
        tableQuery.FilterExpression += " and context = :c";
      else
        tableQuery.FilterExpression = "context = :c";
    }

    if (Object.keys(vals).length !== 0) {
      tableQuery.ExpressionAttributeValues = vals;
    }

    var pageSize = skip + limit;
    if (limit !== -1) {
      tableQuery.Limit = pageSize;
    }

    async.doWhilst(function (end) {
      if (exclusiveStartKey) tableQuery.ExclusiveStartKey = exclusiveStartKey;

      if (tableQuery.KeyConditionExpression) {
        client.query(tableQuery, function (err, results) {
          if (err) {
            error("getEvents query error: " + err);
            return end(err);
          }
          exclusiveStartKey = results.LastEvaluatedKey || null;
          entities = entities.concat(results.Items);
          end(null);
        });
      } else {
        // no great 2ndary index possibilities here - avoid calling getItems w/o aggregateId
        client.scan(tableQuery, function (err, results) {
          if (err) {
            error("getEvents scan error: " + err);
            return end(err);
          }

          exclusiveStartKey = results.LastEvaluatedKey || null;
          entities = entities.concat(results.Items);
          end(null);
        })
      }
    }, function () {
      return (entities.length < pageSize || pageSize == -1) ? exclusiveStartKey !== null : false;
    }, function (err) {
      if (err) {
        error("getEvents error: " + err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, function (e) {
        return [new Date(e.commitStamp).getTime(), e.streamRevision];
      });

      if (limit === -1) {
        entities = entities.slice(skip);
      } else {
        entities = entities.slice(skip, skip + limit);
      }

      callback(null, entities);
    });
  },

  getEventsSince: function (date, skip, limit, callback) {
    var self = this;
    var client = new aws.DynamoDB.DocumentClient(self.options.endpointConf);
    var exclusiveStartKey = null;
    var entities = [];

    var tableQuery = {
      TableName: self.options.eventsTableName,
      FilterExpression: "commitStamp >= :date",
      ExpressionAttributeValues: { ":date": date.getTime() }
    };

    var pageSize = skip + limit;
    if (limit !== -1) {
      tableQuery.Limit = pageSize;
    }

    async.doWhilst(function (end) {
      if (exclusiveStartKey) tableQuery.ExclusiveStartKey = exclusiveStartKey;

      // scan is just really inefficient but if you need to do it often a query on a 2ndary IDX *might* help
      client.scan(tableQuery, function (err, results) {
        if (err) {
          error("getEventsSince scan error: " + err);
          return end(err);
        }

        exclusiveStartKey = results.LastEvaluatedKey || null;
        entities = entities.concat(results.Items);
        end(null);
      });
    }, function () {
      return (entities.length < pageSize || pageSize == -1) ? exclusiveStartKey !== null : false;
    }, function (err) {
      if (err) {
        error("getEventsSince error: " + err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, function (e) {
        return e.commitStamp.getTime();
      });

      if (limit === -1) {
        entities = entities.slice(skip);
      } else {
        entities = entities.slice(skip, pageSize);
      }

      callback(null, entities);
    });
  },

  getEventsByRevision: function (query, revMin, revMax, callback) {

    var self = this;
    var client = new aws.DynamoDB.DocumentClient(self.options.endpointConf);
    var exclusiveStartKey = null;
    var entities = [];

    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      error(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var tableQuery = {
      TableName: self.options.eventsTableName,
      KeyConditionExpression: "aggregateId = :a",
      FilterExpression: "streamRevision >= :rmin",
      ExpressionAttributeValues: {
        ":a": query.aggregateId,
        ":rmin": revMin
      }
    };

    if (revMax !== -1) {
      tableQuery.FilterExpression = "streamRevision BETWEEN :rmin AND :rmax";
      tableQuery.ExpressionAttributeValues[":rmax"] = revMax;
    }

    if (query && query.aggregate) {
      tableQuery.FilterExpression += " AND #name = :name";
      tableQuery.ExpressionAttributeValues[":name"] = query.aggregate;
      tableQuery.ExpressionAttributeNames = { "#name": "aggregate" };
    }

    if (query && query.context) {
      tableQuery.FilterExpression += " AND context = :ctx";
      tableQuery.ExpressionAttributeValues[":ctx"] = query.context;
    }

    async.doWhilst(function (end) {
      if (exclusiveStartKey) tableQuery.ExclusiveStartKey = exclusiveStartKey;

      client.query(tableQuery, function (err, results) {
        if (err) {
          error("getEventsByRevision query error: " + err);
          return end(err);
        }
        exclusiveStartKey = results.LastEvaluatedKey || null;
        entities = entities.concat(results.Items);
        end(null);
      });
    }, function () {
      return exclusiveStartKey !== null;
    }, function (err) {
      if (err) {
        error("getEventsByRevision error: " + err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, function (e) {
        return new Date(e.commitStamp).getTime();
      });

      callback(null, entities);
    });
  },

  getUndispatchedEvents: function (query, callback) {
    var self = this;
    var client = new aws.DynamoDB.DocumentClient(self.options.endpointConf);
    var exclusiveStartKey = null;
    var entities = [];

    if (!self.options.useUndispatchedEventsTable) return entities;

    // TODO: use DynamoDB Streams instead
    var tableQuery = {
      TableName: self.options.undispatchedEventsTableName
    };

    if (query && query.aggregateId) {
      tableQuery.ExpressionAttributeValues = { ":a": query.aggregateId };
      tableQuery.FilterExpression = "aggregateId = :a";
    }

    if (query && query.context) {
      if (tableQuery.FilterExpression && tableQuery.FilterExpression.length !== 0) {
        tableQuery.ExpressionAttributeValues[":ctx"] = query.context;
        tableQuery.FilterExpression += " and context = :ctx";
      } else {
        tableQuery.ExpressionAttributeValues = { ":ctx": query.context };
        tableQuery.FilterExpression = "context = :ctx";
      }
    }

    if (query && query.aggregate) {
      tableQuery.ExpressionAttributeNames = { "#name": "aggregate" };
      if (tableQuery.FilterExpression && tableQuery.FilterExpression.length !== 0) {
        tableQuery.ExpressionAttributeValues[":name"] = query.aggregate;
        tableQuery.FilterExpression += " AND #name = :name";
      } else {
        tableQuery.ExpressionAttributeValues = { ":name": query.aggregate };
        tableQuery.FilterExpression = "#name = :name";
      }
    }

    async.doWhilst(function (end) {
      if (exclusiveStartKey) tableQuery.ExclusiveStartKey = exclusiveStartKey;

      client.scan(tableQuery, function (err, results) {
        if (err) {
          error("getUndispatchedEvents scan error: " + err);
          return end(err);
        }

        exclusiveStartKey = results.LastEvaluatedKey || null;
        entities = entities.concat(results.Items);
        end(null);
      });
    }, function () {
      return exclusiveStartKey !== null;
    }, function (err) {
      if (err) {
        error("getUndispatchedEvents error: " + err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, [function (e) {
        return new Date(e.commitStamp).getTime();
      }, 'id']);

      callback(null, entities);
    });
  },

  getLastEvent: function (query, callback) {

    var self = this;
    var client = new aws.DynamoDB.DocumentClient(self.options.endpointConf);
    var exclusiveStartKey = null;
    var entities = [];

    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      error(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var tableQuery = {
      TableName: self.options.eventsTableName,
      KeyConditionExpression: "aggregateId = :a",
      ExpressionAttributeValues: {
        ":a": query.aggregateId
      }
    };

    async.doWhilst(function (end) {
      if (exclusiveStartKey) tableQuery.ExclusiveStartKey = exclusiveStartKey;

      client.query(tableQuery, function (err, results) {
        if (err) {
          error("getLastEvent query error: " + err);
          return end(err);
        }
        exclusiveStartKey = results.LastEvaluatedKey || null;
        entities = entities.concat(results.Items);
        end(null);
      });
    }, function () {
      return exclusiveStartKey !== null;
    }, function (err) {
      if (err) {
        error("getLastEvent error: " + err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, function (e) {
        return [new Date(e.commitStamp).getTime(),
          e.streamRevision,
          e.commitSequence];
      }).reverse();

      callback(null, entities[0]);
    });
  },

  setEventToDispatched: function (id, callback) {
    var self = this;
    var client = new aws.DynamoDB.DocumentClient(self.options.endpointConf);

    if (!self.options.useUndispatchedEventsTable) return callback();

    var objDescriptor = {
      TableName: self.options.undispatchedEventsTableName,
      Key: {
        id: id
      }
    };

    client.delete(objDescriptor, callback);
  },

  addSnapshot: function (snap, callback) {
    var self = this;
    var client = new aws.DynamoDB.DocumentClient(self.options.endpointConf);

    if (!snap.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      error(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var snapshot = {
      TableName: self.options.snapshotsTableName,
      Item: new StoredSnapshot(snap),
      ConditionExpression: "attribute_not_exists(aggregateId) and attribute_not_exists(id)"
    }
    client.put(snapshot, function (err, data) {
      if (err) {
        error("addSnapshot error: " + err);
        return callback(err);
      }
      callback(null, data);
    });
  },

  getSnapshot: function (query, revMax, callback) {

    var self = this;
    var client = new aws.DynamoDB.DocumentClient(self.options.endpointConf);
    var exclusiveStartKey = null;
    var entities = [];

    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      if (callback) callback(new Error(errMsg));
      return;
    }

    var tableQuery = {
      TableName: self.options.snapshotsTableName,
      KeyConditionExpression: "aggregateId = :a",
      ExpressionAttributeValues: {
        ":a": query.aggregateId
      }
    };

    if (query && query.aggregate) {
      tableQuery.ExpressionAttributeNames = { "#name": "aggregate" };
      tableQuery.ExpressionAttributeValues[":name"] = query.aggregate;
      tableQuery.FilterExpression = "#name = :name";
    }

    if (query && query.context) {
      tableQuery.ExpressionAttributeValues[":ctx"] = query.context;
      if (tableQuery.FilterExpression && tableQuery.FilterExpression.length !== 0)
        tableQuery.FilterExpression += " AND context = :ctx";
      else
        tableQuery.FilterExpression = "context = :ctx";
    }

    if (revMax != -1) {
      if (tableQuery.FilterExpression && tableQuery.FilterExpression.length !== 0)
        tableQuery.FilterExpression += " AND revision <= :rmax";
      else
        tableQuery.FilterExpression = "revision <= :rmax";
      tableQuery.ExpressionAttributeValues[":rmax"] = revMax;
    }

    async.doWhilst(function (end) {
      if (exclusiveStartKey) tableQuery.ExclusiveStartKey = exclusiveStartKey;

      client.query(tableQuery, function (err, results) {
        if (err) {
          debug("getSnapshot query error: " + err);
          return end(err);
        }
        exclusiveStartKey = results.LastEvaluatedKey || null;
        entities = entities.concat(results.Items);
        end(null);
      });
    }, function () {
      return exclusiveStartKey !== null;
    }, function (err) {
      if (err) {
        error("getSnapshot error: " + err);
        return callback(err);
      }

      entities = entities.map(MapStoredSnapshotToSnapshot);

      entities = _.sortBy(entities, function (e) {
        return - new Date(e.commitStamp).getTime();
      });

      callback(null, entities[0]);
    });
  }
});

var StoredEvent = function (event) {
  debug("Converting event to StoredEvent: " + JSON.stringify(event, null, 2));
  this.aggregateId = event.aggregateId;
  this.rowKey = (event.context || "") + ":" + (event.aggregate || "") + ":" + _.padStart(event.streamRevision, 16, '0');
  this.id = event.id;
  this.context = event.context;
  this.aggregate = event.aggregate;
  this.streamRevision = event.streamRevision;
  this.commitId = event.commitId;
  this.commitSequence = event.commitSequence;
  this.commitStamp = new Date(event.commitStamp).getTime();
  this.header = event.header;
  this.dispatched = event.dispatched || false;
  this.payload = JSON.stringify(event.payload);
  debug("Event converted to StoredEvent: " + JSON.stringify(this, null, 2));
};

function MapStoredEventToEvent(storedEvent) {
  var event = {
    aggregateId: storedEvent.aggregateId,
    id: storedEvent.id,
    context: storedEvent.context,
    aggregate: storedEvent.aggregate,
    streamRevision: storedEvent.streamRevision,
    commitId: storedEvent.commitId,
    commitSequence: storedEvent.commitSequence,
    commitStamp: new Date(storedEvent.commitStamp) || null,
    header: storedEvent.header || null,
    dispatched: storedEvent.dispatched,
    payload: JSON.parse(storedEvent.payload) || null
  };

  return event;
}

var StoredSnapshot = function (snapshot) {
  this.id = snapshot.id;
  this.aggregateId = snapshot.aggregateId;
  this.aggregate = snapshot.aggregate || undefined;
  this.context = snapshot.context || undefined;
  this.revision = snapshot.revision;
  this.version = snapshot.version;
  this.commitStamp = new Date(snapshot.commitStamp).getTime();
  this.data = JSON.stringify(snapshot.data);
};

function MapStoredSnapshotToSnapshot(storedSnapshot) {
  var snapshot = {
    id: storedSnapshot.id,
    aggregateId: storedSnapshot.aggregateId,
    aggregate: storedSnapshot.aggregate || undefined,
    context: storedSnapshot.context || undefined,
    revision: storedSnapshot.revision,
    version: storedSnapshot.version,
    commitStamp: new Date(storedSnapshot.commitStamp) || null,
    data: JSON.parse(storedSnapshot.data) || null
  };

  return snapshot;
}

function EventsTableDefinition(opts) {
  var def = {
    TableName: opts.eventsTableName,
    KeySchema: [
      { AttributeName: "aggregateId", KeyType: "HASH" },
      { AttributeName: "rowKey", KeyType: "RANGE" }
    ],
    AttributeDefinitions: [
      { AttributeName: "aggregateId", AttributeType: "S" },
      { AttributeName: "rowKey", AttributeType: "S" }
    ],
    ProvisionedThroughput: {
      ReadCapacityUnits: opts.EventsReadCapacityUnits,
      WriteCapacityUnits: opts.EventsWriteCapacityUnits
    }
  };

  if(opts.eventsTableStreamEnabled) {
    _.assign(def, {
      StreamSpecification: {
        StreamEnabled: true,
        StreamViewType: opts.eventsTableStreamViewType
      }
    })
  };

  return def;
}

function SnapshotTableDefinition(opts) {
  var def = {
    TableName: opts.snapshotsTableName,
    KeySchema: [
      { AttributeName: "aggregateId", KeyType: "HASH" },
      { AttributeName: "id", KeyType: "RANGE" }
    ],
    AttributeDefinitions: [
      { AttributeName: "aggregateId", AttributeType: "S" },
      { AttributeName: "id", AttributeType: "S" }
    ],
    ProvisionedThroughput: {
      ReadCapacityUnits: opts.SnapshotReadCapacityUnits,
      WriteCapacityUnits: opts.SnapshotWriteCapacityUnits
    }
  };

  return def;
}

function UndispatchedEventsTableDefinition(opts) {
  var def = {
    TableName: opts.undispatchedEventsTableName,
    KeySchema: [
      { AttributeName: "id", KeyType: "HASH" }
    ],
    AttributeDefinitions: [
      { AttributeName: "id", AttributeType: "S" }
    ],
    ProvisionedThroughput: {
      ReadCapacityUnits: opts.UndispatchedEventsReadCapacityUnits,
      WriteCapacityUnits: opts.UndispatchedEventsWriteCapacityUnits
    }
  };

  return def;
}

function isTableAlreadyExistsError (err) {
  return err.code === "ResourceInUseException" && err.message === "Cannot create preexisting table";
}

var createTableIfNotExists = function (client, params, callback) {
  var exists = function (p, cbExists) {
    client.describeTable({ TableName: p.TableName }, function (err, data) {
      if (err) {
        if (err.code === "ResourceNotFoundException") {
          debug("Table " + p.TableName + " doesn't exist yet: " + JSON.stringify(p, null, 2));
          cbExists(null, { exists: false, definition: p });
        } else {
          error("Table " + p.TableName + " doesn't exist yet but describeTable: " + JSON.stringify(err, null, 2));
          cbExists(err);
        }
      } else {
        debug("Table " + p.TableName + " already exists.");
        cbExists(null, { exists: true, description: data });
      }
    });
  };

  var create = function (r, cbCreate) {
    if (!r.exists) {
      debug("Creating " + r.definition.TableName);
      client.createTable(r.definition, function (err, data) {
        if (err && !isTableAlreadyExistsError(err)) {
          error("Error while creating " + r.definition.TableName + ": " + JSON.stringify(err, null, 2));
          cbCreate(err);
        } else {
          debug(params.TableName + " created. Waiting for activiation.");
          cbCreate(null, { Table: { TableName: params.TableName, TableStatus: data ? data.TableDescription.TableStatus : "UNKNOWN"} });
        }
      });
    } else {
      cbCreate(null, r.description);
    }
  };

  var active = function (d, cbActive) {
    var status = d.Table.TableStatus;
    async.until(
      function () { return status === "ACTIVE" },
      function (cbUntil) {
        debug("checking " + d.Table.TableName + " status.");
        client.describeTable({ TableName: d.Table.TableName }, function (err, data) {
          if (err) {
            error("There was an error checking " + d.Table.TableName + " status: " + JSON.stringify(err, null, 2));
            cbUntil(err);
          } else {
            status = data.Table.TableStatus;
            setTimeout(cbUntil, 1000);
          }
        });
      },
      function (err, r) {
        if (err) {
          error("connect create table error: " + err);
          return cbActive(err);
        }
        debug("Table " + d.Table.TableName + " is active.");
        cbActive(null, r);
      });
  };

  async.compose(active, create, exists)(params, function (err, result) {
    if (err) callback(err);
    else callback(null, result);
  });
};

var deleteTableIfExists = function (client, tableName, callback) {
  var exists = function (name, cbExists) {
    client.describeTable({ TableName: name }, function (err, data) {
      if (err) {
        if (err.code === "ResourceNotFoundException") {
          cbExists(null, { exists: false, definition: { TableName: name } });
        } else {
          error("deleteTableIfExists - describeTable error: " + JSON.stringify(err, null, 2));
          cbExists(err);
        }
      } else {
        cbExists(null, { exists: true, description: { TableName: data.Table.TableName } });
      }
    });
  };

  var deleteTable = function (r, cbDelete) {
    if (r.exists) {
      client.deleteTable(r.description, function (err, data) {
        if (err) {
          error("Error deleting '" + r.description.TableName + "': " + JSON.stringify(err, null, 2));
          cbDelete(err);
        } else {
          cbDelete(null, { Table: { TableName: data.TableDescription.TableName, TableStatus: data.TableDescription.TableStatus } });
        }
      });
    } else {
      cbDelete(null, r.description);
    }
  };

  var active = function (d, cbActive) {
    var status = d.Table.TableStatus;
    async.until(
      function () { return status === "DELETED" },
      function (cbUntil) {
        client.describeTable({ TableName: d.Table.TableName }, function (err, data) {
          if (err) {
            if (err.code === "ResourceNotFoundException") {
              status = "DELETED";
              return cbUntil(null, d.Table.TableName);
            }
            error("Error calling describeTable for '" + d.Table.TableName + "'");
            cbUntil(err);
          } else {
            setTimeout(cbUntil, 1000);
          }
        });
      },
      function (err, r) {
        if (err) {
          error("connect delete table error: " + err);
          return cbActive(err);
        }
        cbActive(null, r);
      });
  };

  async.compose(active, deleteTable, exists)(tableName, function (err, result) {
    if (err) callback(err);
    else callback(null, result);
  });
};

var clearEventTables = function (opts, documentClient, cleared) {
  var exclusiveStartKey = null;
  var retryCount = 0;

  debug("clearning events tables");
  var maps = [
    {
      TableName: opts.eventsTableName,
      keyMap: function (n) {
        return { DeleteRequest: { Key: { aggregateId: n.aggregateId, rowKey: n.rowKey } } };
      }
    },
  ];

  if (opts.useUndispatchedEventsTable) {
    maps.push({
      TableName: opts.undispatchedEventsTableName,
      keyMap: function (n) {
        return { DeleteRequest: { Key: { id: n.id } } };
      }
    });
  }

  var read = function (task, callback) {
    documentClient.scan(task.params, function (err, page) {
      if (err) {
        error("clearEventTables scan error: " + err);
        return callback(err);
      }

      retryCount = 0;
      exclusiveStartKey = page.LastEvaluatedKey || null;

      if (page.Count == 0) {
        return callback(null, {});
      }

      var batch = {
        RequestItems: {},
        ReturnConsumedCapacity: "INDEXES",
        ReturnItemCollectionMetrics: "SIZE"
      };

      _.forEach(task.maps, function (m) {
        var keys = _.map(page.Items, m.keyMap);
        batch.RequestItems[m.TableName] = keys;
      });

      callback(null, batch);
    });
  };

  var write = function (batch, callback) {
    if (batch && batch.RequestItems) {
      debug("Clear: calling batchWrite: " + JSON.stringify(batch, null, 2));
      documentClient.batchWrite(batch, function (err, result) {
        if (err) {
          error("Clear (batchWrite) error): " + JSON.stringify(batch, null, 2));
          return callback(err);
        }

        if (Object.keys(result.UnprocessedItems).length !== 0) {
          retryCount++;
          var retry = {
            RequestItems: result.UnprocessedItems,
            ReturnConsumedCapacity: "INDEXES",
            ReturnItemCollectionMetrics: "SIZE"
          };
          debug("Clear batchWrite throttling: " + JSON.stringify(retry, null, 2));
          // retry with exponential backoff
          var delay = retryCount > 0 ? (50 * Math.pow(2, retryCount - 1)) : 0;
          setTimeout(write, delay, retry, callback);
          return;
        }

        callback(null, result);
      });
    } else {
      callback(null);
    }
  };

  var tasks = [
    {
      params: {
        TableName: opts.eventsTableName,
        ProjectionExpression: "aggregateId,rowKey,id",
        Limit: 25, // max 25 per batchWrite call
        ConsistentRead: false,
        ReturnConsumedCapacity: "TOTAL"
      },
      maps: [maps[0]]
    }
  ];

  if (opts.useUndispatchedEventsTable) {
    tasks.splice(0, 0, {
      params: {
        TableName: opts.undispatchedEventsTableName,
        ProjectionExpression: "aggregateId,rowKey,id",
        Limit: 12, // max 25 per batchWrite call divided by 2 tables
        ConsistentRead: false,
        ReturnConsumedCapacity: "TOTAL"
      },
      maps: maps
    });
  }

  async.eachSeries(tasks, function (t, afterEach) {

    async.doWhilst(function (next) {

      if (exclusiveStartKey)
        t.params.ExclusiveStartKey = exclusiveStartKey;

      async.seq(read, write)(t, function (err, result) {
        if (err) next(err);
        else next(null, result);
      });

    }, function () {
      return exclusiveStartKey !== null;
    }, function (err, r) {
      if (err) {
        error("clearEvents error: " + err);
        return afterEach(err);
      }
      return afterEach();
    });

  }, function (err) {
    if (err) {
      error("Error while clearning events tables: " + JSON.stringify(err, null, 2));
      return cleared(err);
    }
    debug("Events tables successfully cleared.");
    return cleared();
  });

};

var clearSnapshotsTable = function (opts, documentClient, cleared) {
  var exclusiveStartKey = null;

  var query = {
    TableName: opts.snapshotsTableName,
    ProjectionExpression: "aggregateId,id",
    Limit: 25, // max 25 per batchWrite call
    ConsistentRead: false,
    ReturnConsumedCapacity: "TOTAL"
  };

  async.doWhilst(function (end) {
    if (exclusiveStartKey)
      query.ExclusiveStartKey = exclusiveStartKey;

    documentClient.scan(query, function (err, page) {
      if (err) {
        error("clearSnapshotsTable scan error: " + err);
        return end(err);
      }

      exclusiveStartKey = page.LastEvaluatedKey || null;

      if (page.Count === 0) {
        return end(err);
      }

      var keys = _.map(page.Items, function (n) {
        return { DeleteRequest: { Key: n } }
      });

      var batch = {
        RequestItems: {},
        ReturnConsumedCapacity: "TOTAL",
        ReturnItemCollectionMetrics: "SIZE"
      };
      batch.RequestItems[opts.snapshotsTableName] = keys;

      documentClient.batchWrite(batch, function (err2, data) {
        error("clearSnapshotsTable batchWrite error: " + err2);
        return end(err2);
      });
    });
  }, function () {
    return exclusiveStartKey !== null;
  }, function (err, r) {
    if (err) {
      error("clearSnapshotsTable error: " + err);
      return cleared(error);
    }
    return cleared(null);;
  });
};

var deleteAllTempTables = function (client, opts, done) {
  var exclusiveStartTableName = null;

  var read = function (query, callback) {
    if (exclusiveStartTableName)
      query.ExclusiveStartTableName = exclusiveStartTableName;

    client.listTables(query, function (err, list) {
      if (err) {
        error("deleteAllTempTables listTables error: " + err);
        return callback(err);
      }

      exclusiveStartTableName = list.LastEvaluatedTableName || null;
      var targets = _.filter(list.TableNames, function (t) {
        return t === opts.eventsTableName ||
          t === opts.undispatchedEventsTableName ||
          t === opts.snapshotsTableName;
      });

      callback(null, targets);
    });
  };

  var drop = function (targets, callback) {
    async.each(targets, function (t, deleted) {
      deleteTableIfExists(client, t, deleted);
    }, function (err) {
      if (err) {
        error("deleteAllTempTables drop error: " + err);
        return callback(err);
      }
      return callback(null);
    });
  };

  async.doWhilst(function (next) {
    async.compose(drop, read)({}, function (err, result) {
      if (err) next(err);
      else next(null, result);
    });
  }, function () {
    return exclusiveStartTableName !== null;
  }, function (err, result) {
    if (err) {
      error("deleteAllTempTables error: " + err);
      return done(err);
    }
    done(null, result);
  });
};

module.exports = DynamoDB;
