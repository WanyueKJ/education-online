var util = require('util'),
  Store = require('../base'),
  _ = require('lodash'),
  async = require('async'),
  azure = Store.use('azure-storage'),
  eg = azure.TableUtilities.entityGenerator,
  debug = require('debug')('eventstore:store:azuretable');

function AzureTable(options) {

  options = options || {};

  var azureConf = {
    storageAccount: 'nodeeventstore',
    storageAccessKey: 'aXJaod96t980AbNwG9Vh6T3ewPQnvMWAn289Wft9RTv+heXQBxLsY3Z4w66CI7NN12+1HUnHM8S3sUbcI5zctg==',
    storageTableHost: 'https://nodeeventstore.table.core.windows.net/'
  };

  this.options = _.defaults(options, azureConf);

  var defaults = {
    eventsTableName: 'events',
    undispatchedEventsTableName: 'undispatchedevents',
    snapshotsTableName: 'snapshots'
  };

  this.options = _.defaults(this.options, defaults);
}

util.inherits(AzureTable, Store);

_.extend(AzureTable.prototype, {

  connect: function (callback) {
    var self = this;
    var retryOperations = new azure.ExponentialRetryPolicyFilter();
    var server = azure.createTableService(this.options.storageAccount, this.options.storageAccessKey, this.options.storageTableHost).withFilter(retryOperations);

    self.client = server;
    self.isConnected = true;

    var createEventsTable = function (callback) {
      self.client.createTableIfNotExists(self.options.eventsTableName, callback);
    };

    var createSnapshotTable = function (callback) {
      self.client.createTableIfNotExists(self.options.snapshotsTableName, callback);
    };

    var createUndispatchedEventsTable = function (done) {
      self.client.createTableIfNotExists(self.options.undispatchedEventsTableName, done)
    };

    async.parallel([
      createEventsTable,
      createSnapshotTable,
      createUndispatchedEventsTable
    ], function (err) {
      if (err) {
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

  clear: function (done) {
    var self = this;
    var query = new azure.TableQuery();

    var clearEventsTable = function (callback) {
      self.client.queryEntities(self.options.eventsTableName, query, null, function (err, entities) {
        if (err) {
          return callback(err);
        }

        async.each(entities.entries, function (entity, callback) {
            self.client.deleteEntity(self.options.eventsTableName, entity, function (error, response) {
              callback(error);
            });
          },
          callback);
      });
    };

    var clearSnapshotsTable = function (callback) {
      self.client.queryEntities(self.options.snapshotsTableName, query, null, function (err, entities) {
        if (err) {
          return callback(err);
        }
        async.each(entities.entries, function (entity, callback) {
            self.client.deleteEntity(self.options.snapshotsTableName, entity, function (error, response) {
              callback(error);
            });
          },
          callback);
      });
    };

    var clearUndispatchedEventsTable = function (callback) {
      self.client.queryEntities(self.options.undispatchedEventsTableName, query, null, function (err, entities) {
        if (err) {
          return callback(err);
        }
        async.each(entities.entries, function (entity, callback) {
            self.client.deleteEntity(self.options.undispatchedEventsTableName, entity, function (error, response) {
              callback(error);
            });
          },
          callback);
      });
    };

    async.parallel([
      clearEventsTable,
      clearSnapshotsTable,
      clearUndispatchedEventsTable
    ], function (err) {
      if (err) {
        debug(err);
        if (done) done(err);
//        if (done) done(null); // strange on azure...
        return;
      }
      if (done) done(null, self);
    });
  },

  addEvents: function (events, callback) {
    var self = this;
    var batch = new azure.TableBatch();

    var noAggId = _.every(events, function (event) {
      return !event.aggregateId
    });

    if (noAggId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    async.concat(events,
      function (event, callback) {

        var results = [
          function (callback) {
            var storedEvent = new StoredEvent(event);
            self.client.insertEntity(self.options.eventsTableName, storedEvent, callback);
          },
          function (callback) {
            var storedUndispatchedEvent = new StoredUndispatchedEvent(event);
            self.client.insertEntity(self.options.undispatchedEventsTableName, storedUndispatchedEvent, callback);
          }
        ]

        callback(null, results);
      },
      function (err, results) {
        async.parallel(results, callback);
      }
    );
  },

  getEvents: function (query, skip, limit, callback) {
    var self = this;
    var tableQuery = new azure.TableQuery();
    var continuationToken = null;
    var entities = [];

    var pageSize = skip + limit;

    tableQuery = _(query)
      .pick(['aggregate', 'context', 'aggregateId'])
      .reduce(function (result, val, key) {
        key = key === 'aggregateId' ? 'PartitionKey' : key;
        if (result._where.length === 0) return tableQuery.where(key + ' eq ?', val);
        return result.and(key + ' eq ?', val)
      }, tableQuery);


    if (limit !== -1) {
      tableQuery = tableQuery.top(pageSize);
    }

    async.doWhilst(function (end) {
      // retrieve entities
      self.client.queryEntities(self.options.eventsTableName, tableQuery, continuationToken, function (err, results) {
        if (err) {
          debug(err);
          return end(err);
        }
        continuationToken = results.continuationToken;
        entities = entities.concat(results.entries);
        end(null);
      });
    }, function () {
      // test if we need to load more
      return (entities.length < pageSize || pageSize == -1) ? continuationToken !== null : false;
    }, function (err) {
      // return results
      if (err) {
        debug(err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, function (e) {
        return e.commitStamp.getTime();
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
    var tableQuery = new azure.TableQuery();
    var continuationToken = null;
    var entities = [];

    var pageSize = skip + limit;

    tableQuery.where('commitStamp >= ?', date);

    async.doWhilst(function (end) {
      // retrieve entities
      self.client.queryEntities(self.options.eventsTableName, tableQuery, continuationToken, function (err, results) {
        if (err) {
          debug(err);
          return end(err);
        }
        continuationToken = results.continuationToken;
        entities = entities.concat(results.entries);
        end(null);
      });
    }, function () {
      // test if we need to load more
      return (entities.length < pageSize || pageSize == -1) ? continuationToken !== null : false;
    }, function (err) {
      // return results
      if (err) {
        debug(err);
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
    var tableQuery = new azure.TableQuery();
    var continuationToken = null;
    var entities = [];

    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    tableQuery = _(query)
      .pick(['aggregate', 'context', 'aggregateId'])
      .reduce(function (result, val, key) {
        key = key === 'aggregateId' ? 'PartitionKey' : key;
        if (result._where.length === 0) return tableQuery.where(key + ' eq ?', val);
        return result.and(key + ' eq ?', val)
      }, tableQuery);


    tableQuery = tableQuery.and('streamRevision >= ?', revMin);
    if (revMax != -1) tableQuery = tableQuery.and('streamRevision < ?', revMax);

    async.doWhilst(function (end) {
      // retrieve entities
      self.client.queryEntities(self.options.eventsTableName, tableQuery, continuationToken, function (err, results) {
        if (err) {
          debug(err);
          return end(err);
        }
        continuationToken = results.continuationToken;
        entities = entities.concat(results.entries);
        end(null);
      });
    }, function () {
      // test if we need to load more
      return continuationToken !== null;
    }, function (err) {
      // return results
      if (err) {
        debug(err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, function (e) {
        return e.commitStamp.getTime();
      });

      callback(null, entities);
    });
  },

  getUndispatchedEvents: function (query, callback) {

    var self = this;
    var tableQuery = new azure.TableQuery();
    var continuationToken = null;
    var entities = [];

    if (query && query.aggregate) tableQuery.where('aggregate eq ?', query.aggregate);
    if (query && query.context) tableQuery.where('context eq ?', query.context);
    if (query && query.aggregateId) tableQuery.where('aggregateId eq ?', query.aggregateId);

    async.doWhilst(function (end) {
      // retrieve entities
      self.client.queryEntities(self.options.undispatchedEventsTableName, tableQuery, continuationToken, function (err, results) {
        if (err) {
          debug(err);
          return end(err);
        }
        continuationToken = results.continuationToken;
        entities = entities.concat(results.entries);
        end(null);
      });
    }, function () {
      // test if we need to load more
      return continuationToken !== null;
    }, function (err) {
      // return results
      if (err) {
        debug(err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, function (e) {
        return e.commitStamp.getTime();
      });

      callback(null, entities);
    });
  },

  getLastEvent: function (query, callback) {

    var self = this;
    var tableQuery = new azure.TableQuery();
    var continuationToken = null;
    var entities = [];


    tableQuery = _(query)
      .pick(['aggregate', 'context', 'aggregateId'])
      .reduce(function (result, val, key) {
        key = key === 'aggregateId' ? 'PartitionKey' : key;
        if (result._where.length === 0) return tableQuery.where(key + ' eq ?', val);
        return result.and(key + ' eq ?', val)
      }, tableQuery);

    async.doWhilst(function (end) {
      // retrieve entities
      self.client.queryEntities(self.options.eventsTableName, tableQuery, continuationToken, function (err, results) {
        if (err) {
          debug(err);
          return end(err);
        }
        continuationToken = results.continuationToken;
        entities = entities.concat(results.entries);
        end(null);
      });
    }, function () {
      // test if we need to load more
      return  continuationToken !== null;
    }, function (err) {
      // return results
      if (err) {
        debug(err);
        return callback(err);
      }

      entities = entities.map(MapStoredEventToEvent);

      entities = _.sortBy(entities, function (e) {
        return [e.commitStamp.getTime(),
                e.streamRevision,
                e.commitSequence];
      }).reverse();

      callback(null, entities[0]);
    });
    //this.events.findOne(findStatement, {sort: [['commitStamp', 'desc'], ['streamRevision', 'desc'], ['commitSequence', 'desc']]}, callback);
  },

  setEventToDispatched: function (id, callback) {
    var self = this;

    var objDescriptor = {
      PartitionKey: eg.String(id),
      RowKey: eg.String(id)
    };

    self.client.deleteEntity(self.options.undispatchedEventsTableName, objDescriptor, null, callback);
  },

  addSnapshot: function (snap, callback) {
    var self = this;

    if (!snap.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    self.client.insertEntity(self.options.snapshotsTableName, new StoredSnapshot(snap), callback);
  },

  getSnapshot: function (query, revMax, callback) {

    var self = this;
    var tableQuery = new azure.TableQuery();
    var continuationToken = null;
    var entities = [];

    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    tableQuery = _(query)
      .pick(['aggregate', 'context', 'aggregateId'])
      .reduce(function (result, val, key) {
        key = key === 'aggregateId' ? 'PartitionKey' : key;
        if (result._where.length === 0) return tableQuery.where(key + ' eq ?', val);
        return result.and(key + ' eq ?', val)
      }, tableQuery);


    if (revMax != -1) tableQuery = tableQuery.and('revision le ?', revMax);

    async.doWhilst(function (end) {
      // retrieve entities
      self.client.queryEntities(self.options.snapshotsTableName, tableQuery, continuationToken, function (err, results) {
        if (err) {
          debug(err);
          return end(err);
        }
        continuationToken = results.continuationToken;
        entities = entities.concat(results.entries);
        end(null);
      });
    }, function () {
      // test if we need to load more
      return continuationToken !== null;
    }, function (err) {
      // return results
      if (err) {
        debug(err);
        return callback(err);
      }

      entities = entities.map(MapStoredSnapshotToSnapshot);

      entities = _.sortBy(entities, function (e) {
        return -e.commitStamp.getTime();
      });

      callback(null, entities[0]);
    });

  }

});

var StoredEvent = function (event) {
  this.PartitionKey = eg.EntityProperty(event.aggregateId);
  this.RowKey = eg.EntityProperty(event.id);
  this.aggregateId = eg.EntityProperty(event.aggregateId);
  this.id = eg.EntityProperty(event.id);
  this.context = eg.EntityProperty(event.context);
  this.aggregate = eg.EntityProperty(event.aggregate);
  this.streamRevision = eg.EntityProperty(event.streamRevision);
  this.commitId = eg.EntityProperty(event.commitId);
  this.commitSequence = eg.EntityProperty(event.commitSequence);
  this.commitStamp = eg.EntityProperty(event.commitStamp);
  this.header = eg.EntityProperty(event.header);
  this.dispatched = eg.EntityProperty(event.dispatched || false);
  this.payload = eg.EntityProperty(JSON.stringify(event.payload));
};

function MapStoredEventToEvent(storedEvent) {
  var event = {
    aggregateId: getEntityProperty(storedEvent.aggregateId),
    id: getEntityProperty(storedEvent.id),
    context: getEntityProperty(storedEvent.context),
    aggregate: getEntityProperty(storedEvent.aggregate),
    streamRevision: getEntityProperty(storedEvent.streamRevision),
    commitId: getEntityProperty(storedEvent.commitId),
    commitSequence: getEntityProperty(storedEvent.commitSequence),
    commitStamp: getEntityProperty(storedEvent.commitStamp) || null,
    header: getEntityProperty(storedEvent.header) || null,
    dispatched: getEntityProperty(storedEvent.dispatched),
    payload: JSON.parse(getEntityProperty(storedEvent.payload)) || null
  };

  return event;
}

var StoredUndispatchedEvent = function (event) {
  this.PartitionKey = eg.EntityProperty(event.id);
  this.RowKey = eg.EntityProperty(event.id);
  this.aggregateId = eg.EntityProperty(event.aggregateId);
  this.id = eg.EntityProperty(event.id);
  this.context = eg.EntityProperty(event.context);
  this.aggregate = eg.EntityProperty(event.aggregate);
  this.streamRevision = eg.EntityProperty(event.streamRevision);
  this.commitId = eg.EntityProperty(event.commitId);
  this.commitSequence = eg.EntityProperty(event.commitSequence);
  this.commitStamp = eg.EntityProperty(event.commitStamp);
  this.header = eg.EntityProperty(event.header);
  this.dispatched = eg.EntityProperty(event.dispatched || false);
  this.payload = eg.EntityProperty(JSON.stringify(event.payload));
};

var StoredSnapshot = function (snapshot) {
  this.PartitionKey = eg.EntityProperty(snapshot.aggregateId);
  this.RowKey = eg.EntityProperty(snapshot.id);
  this.id = eg.EntityProperty(snapshot.id);
  this.aggregateId = eg.EntityProperty(snapshot.aggregateId);
  this.aggregate = eg.EntityProperty(snapshot.aggregate) || undefined;
  this.context = eg.EntityProperty(snapshot.context) || undefined;
  this.revision = eg.EntityProperty(snapshot.revision);
  this.version = eg.EntityProperty(snapshot.version);
  this.commitStamp = eg.EntityProperty(snapshot.commitStamp);
  this.data = eg.EntityProperty(JSON.stringify(snapshot.data));
};

function MapStoredSnapshotToSnapshot(storedSnapshot) {
  var snapshot = {
    id: getEntityProperty(storedSnapshot.id),
    aggregateId: getEntityProperty(storedSnapshot.PartitionKey),
    aggregate: getEntityProperty(storedSnapshot.aggregate) || undefined,
    context: getEntityProperty(storedSnapshot.context) || undefined,
    revision: getEntityProperty(storedSnapshot.revision),
    version: getEntityProperty(storedSnapshot.version),
    commitStamp: getEntityProperty(storedSnapshot.commitStamp),
    data: JSON.parse(getEntityProperty(storedSnapshot.data)) || null
  };

  return snapshot;
}

var getEntityProperty = function (propertyField) {
  if (propertyField != null) {
    return propertyField['_'];
  } else {
    return null;
  }
};

module.exports = AzureTable;
