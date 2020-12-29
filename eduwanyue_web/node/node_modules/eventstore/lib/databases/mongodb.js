var util = require('util'),
  Store = require('../base'),
  _ = require('lodash'),
  async = require('async'),
  stream = require('stream'),
  mongo = Store.use('mongodb'),
  mongoVersion = Store.use('mongodb/package.json').version,
  isNew = mongoVersion.indexOf('1.') !== 0,
  ObjectID = isNew ? mongo.ObjectID : mongo.BSONPure.ObjectID,
  debug = require('debug')('eventstore:store:mongodb');

function streamEventsByRevision(self, findStatement, revMin, revMax, resultStream, lastEvent) {

  findStatement.streamRevision = (revMax === -1) ? { '$gte': revMin } : { '$gte': revMin, '$lt': revMax };

  var mongoStream = self.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] });

  async.during(function(clb) {
    mongoStream.hasNext(clb);
  },
  function(clb) {
      mongoStream.next(function(error, e) {
      if (error)
        return clb(error);

      if (!lastEvent) {
        lastEvent = e;
        return resultStream.write(lastEvent, clb); // Should write the event to resultStream as if there's no lastEvent when there's an event in stream, the event must be first entry of this query.
      }

      // if for some reason we have written this event alredy
      if ((e.streamRevision === lastEvent.streamRevision && e.restInCommitStream <= lastEvent.restInCommitStream) ||
          (e.streamRevision <= lastEvent.streamRevision)) {
          return clb();
      }

      lastEvent = e;
      resultStream.write(lastEvent, clb);
    });
  },
  function (error) {
    if (error) {
      return resultStream.destroy(error);
    }

    if (!lastEvent) {
      return resultStream.end();
    }

    var txOk = (revMax === -1 && !lastEvent.restInCommitStream) ||
                (revMax !== -1 && (lastEvent.streamRevision === revMax - 1 || !lastEvent.restInCommitStream));

    if (txOk) {
      // the following is usually unnecessary
      self.removeTransactions(lastEvent);
      resultStream.end(); // lastEvent was keep duplicated from this line. We should not re-write last event into the stream when ending it. thus end() rather then end(lastEvent).
    }

    self.repairFailedTransaction(lastEvent, function (err) {
      if (err) {
        if (err.message.indexOf('missing tx entry') >= 0) {
          return resultStream.end(lastEvent); // Maybe we should check on this line too?
        }
        debug(err);
        return resultStream.destroy(error);
      }

      streamEventsByRevision(self, findStatement, lastEvent.revMin, revMax, resultStream, lastEvent);
    });
  });
};
  
function Mongo(options) {
  options = options || {};

  Store.call(this, options);

  var defaults = {
    host: 'localhost',
    port: 27017,
    dbName: 'eventstore',
    eventsCollectionName: 'events',
    snapshotsCollectionName: 'snapshots',
    transactionsCollectionName: 'transactions'//,
    // heartbeat: 60 * 1000
  };

  _.defaults(options, defaults);

  var defaultOpt = {
    ssl: false
  };

  options.options = options.options || {};

  if (isNew) {
    defaultOpt.autoReconnect = false;
    defaultOpt.useNewUrlParser = true;
    defaultOpt.useUnifiedTopology = true;
    _.defaults(options.options, defaultOpt);
  } else {
    defaultOpt.auto_reconnect = false;
    _.defaults(options.options, defaultOpt);
  }

  this.options = options;
}

util.inherits(Mongo, Store);

_.extend(Mongo.prototype, {

  connect: function (callback) {
    var self = this;

    var options = this.options;

    var connectionUrl;

    if (options.url) {
      connectionUrl = options.url;
    } else {
      var members = options.servers
        ? options.servers
        : [{host: options.host, port: options.port}];

      var memberString = _(members).map(function(m) { return m.host + ':' + m.port; });
      var authString = options.username && options.password
        ? options.username + ':' + options.password + '@'
        : '';
      var optionsString = options.authSource
        ? '?authSource=' + options.authSource
        : '';

      connectionUrl = 'mongodb://' + authString + memberString + '/' + options.dbName + optionsString;
    }

    var client;
    var ensureIndex = 'ensureIndex';

    if (mongo.MongoClient.length === 2) {
      ensureIndex = 'createIndex';
      client = new mongo.MongoClient(connectionUrl, options.options);
      client.connect(function(err, cl) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        self.db = cl.db(cl.s.options.dbName);
        if (!self.db.close) {
          self.db.close = cl.close.bind(cl);
        }
        initDb();
      });
    } else {
      client = new mongo.MongoClient();
      client.connect(connectionUrl, options.options, function(err, db) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        self.db = db;
        initDb();
      });
    }

    function initDb() {
      self.db.on('close', function() {
        self.emit('disconnect');
        self.stopHeartbeat();
      });


      function finish (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        self.events = self.db.collection(options.eventsCollectionName);
        self.events[ensureIndex]({ aggregateId: 1, streamRevision: 1 },
          function (err) { if (err) { debug(err); } });
        self.events[ensureIndex]({ commitStamp: 1 },
          function (err) { if (err) { debug(err); } });
        self.events[ensureIndex]({ dispatched: 1 }, { sparse: true },
          function (err) { if (err) { debug(err); } });
        self.events[ensureIndex]({ commitStamp: 1, streamRevision: 1, commitSequence: 1 },
          function (err) { if (err) { debug(err); } });
  
        self.snapshots = self.db.collection(options.snapshotsCollectionName);
        self.snapshots[ensureIndex]({ aggregateId: 1, revision: -1 },
          function (err) { if (err) { debug(err); } });

        self.transactions = self.db.collection(options.transactionsCollectionName);
        self.transactions[ensureIndex]({ aggregateId: 1, 'events.streamRevision': 1 },
          function (err) { if (err) { debug(err); } });
        self.events[ensureIndex]({ aggregate: 1, aggregateId: 1, commitStamp: -1, streamRevision: -1, commitSequence: -1 },
          function (err) { if (err) { debug(err); } });

        if (options.positionsCollectionName) {
          self.positions = self.db.collection(options.positionsCollectionName);
          self.positionsCounterId = options.eventsCollectionName;
        }

        self.emit('connect');
        if (self.options.heartbeat) {
          self.startHeartbeat();
        }
        if (callback) callback(null, self);
      }

      finish();
    }
  },

  stopHeartbeat: function () {
    if (this.heartbeatInterval) {
      clearInterval(this.heartbeatInterval);
      delete this.heartbeatInterval;
    }
  },

  startHeartbeat: function () {
    var self = this;

    var gracePeriod = Math.round(this.options.heartbeat / 2);
    this.heartbeatInterval = setInterval(function () {
      var graceTimer = setTimeout(function () {
        if (self.heartbeatInterval) {
          console.error((new Error ('Heartbeat timeouted after ' + gracePeriod + 'ms (mongodb)')).stack);
          self.disconnect();
        }
      }, gracePeriod);

      self.db.command({ ping: 1 }, function (err) {
        if (graceTimer) clearTimeout(graceTimer);
        if (err) {
          console.error(err.stack || err);
          self.disconnect();
        }
      });
    }, this.options.heartbeat);
  },

  disconnect: function (callback) {
    this.stopHeartbeat();

    if (!this.db) {
      if (callback) callback(null);
      return;
    }

    this.db.close(function (err) {
      if (err) {
        debug(err);
      }
      if (callback) callback(err);
    });
  },

  clear: function (callback) {
    var self = this;
    async.parallel([
      function (callback) {
        self.events.deleteMany({}, callback);
      },
      function (callback) {
        self.snapshots.deleteMany({}, callback);
      },
      function (callback) {
        self.transactions.deleteMany({}, callback);
      },
      function (callback) {
        if (!self.positions)
          return callback(null);
        self.positions.deleteMany({}, callback);
      }
    ], function (err) {
      if (err) {
        debug(err);
      }
      if (callback) callback(err);
    });
  },

  getNewId: function(callback) {
    callback(null, new ObjectID().toString());
  },

  getNextPositions: function(positions, callback) {
    if (!this.positions)
      return callback(null);

    this.positions.findOneAndUpdate({ _id: this.positionsCounterId }, { $inc: { position: positions } }, { returnOriginal: false, upsert: true }, function (err, pos) {
      if (err)
        return callback(err);

      pos.value.position += 1;

      callback(null, _.range(pos.value.position - positions, pos.value.position));
    });
  },

  addEvents: function (events, callback) {
    if (events.length === 0) {
      if (callback) { callback(null); }
      return;
    }

    var commitId = events[0].commitId;

    var noAggregateId = false,
      invalidCommitId = false;

    var self = this;
    // this.getNextPositions(events.length, function(err, positions) {
      /*
      if (err) {
        debug(err);
        if (callback) callback(err);
        return;
      }
      */

      _.forEach(events, function (evt, index) {
        if (!evt.aggregateId) {
          noAggregateId = true;
        }

        if (!evt.commitId || evt.commitId !== commitId) {
          invalidCommitId = true;
        }

        evt._id = evt.id;
        evt.dispatched = false;
      });

      if (noAggregateId) {
        var errMsg = 'aggregateId not defined!';
        debug(errMsg);
        if (callback) callback(new Error(errMsg));
        return;
      }

      if (invalidCommitId) {
        var errMsg = 'commitId not defined or different!';
        debug(errMsg);
        if (callback) callback(new Error(errMsg));
        return;
      }

      if (events.length === 1) {
        return self.events.insertOne(events[0], callback);
      }

      var tx = {
        _id: commitId,
        events: events,
        aggregateId: events[0].aggregateId,
        aggregate: events[0].aggregate,
        context: events[0].context
      };

      self.transactions.insertOne(tx, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        self.events.insertMany(events, function (err) {
          if (err) {
            debug(err);
            if (callback) callback(err);
            return;
          }

          self.removeTransactions(events[events.length - 1], callback);
        });
      });
    // });
  },

  // streaming API
  streamEvents: function (query, skip, limit) {
    var findStatement = {};

    if (query.aggregate) {
      findStatement.aggregate = query.aggregate;
    }

    if (query.context) {
      findStatement.context = query.context;
    }

    if (query.aggregateId) {
      findStatement.aggregateId = query.aggregateId;
    }

    var query = this.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] });

    if (skip) {
      query.skip(skip);
    }
    
    if (limit && limit > 0) {
      query.limit(limit);
    }

    return query;
  },

  streamEventsSince: function (date, skip, limit) {
    var findStatement = { commitStamp: { '$gte': date } };

    var query = this.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] });

    if (skip)
      query.skip(skip);
    
    if (limit && limit > 0)
      query.limit(limit);
    
    return query;
  },

  streamEventsByRevision: function (query, revMin, revMax) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = {
      aggregateId: query.aggregateId,
    };

    if (query.aggregate) {
      findStatement.aggregate = query.aggregate;
    }

    if (query.context) {
      findStatement.context = query.context;
    }

    var self = this;

    var resultStream = new stream.PassThrough({ objectMode: true, highWaterMark: 1 });
    streamEventsByRevision(self, findStatement, revMin, revMax, resultStream);
    return resultStream;
  },

  getEvents: function (query, skip, limit, callback) {
    this.streamEvents(query, skip, limit).toArray(callback);
  },

  getEventsSince: function (date, skip, limit, callback) {
    this.streamEventsSince(date, skip, limit).toArray(callback);
  },

  getEventsByRevision: function (query, revMin, revMax, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var streamRevOptions = { '$gte': revMin, '$lt': revMax };
    if (revMax === -1) {
      streamRevOptions = { '$gte': revMin };
    }

    var findStatement = {
      aggregateId: query.aggregateId,
      streamRevision: streamRevOptions
    };

    if (query.aggregate) {
      findStatement.aggregate = query.aggregate;
    }

    if (query.context) {
      findStatement.context = query.context;
    }

    var self = this;

    this.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] }).toArray(function (err, res) {
      if (err) {
        debug(err);
        return callback(err);
      }

      if (!res || res.length === 0) {
        return callback(null, []);
      }

      var lastEvt = res[res.length - 1];

      var txOk = (revMax === -1 && !lastEvt.restInCommitStream) ||
                 (revMax !== -1 && (lastEvt.streamRevision === revMax - 1 || !lastEvt.restInCommitStream));

      if (txOk) {
        // the following is usually unnecessary
        self.removeTransactions(lastEvt);

        return callback(null, res);
      }

      self.repairFailedTransaction(lastEvt, function (err) {
        if (err) {
          if (err.message.indexOf('missing tx entry') >= 0) {
            return callback(null, res);
          }
          debug(err);
          return callback(err);
        }

        self.getEventsByRevision(query, revMin, revMax, callback);
      });
    });
  },

  getUndispatchedEvents: function (query, callback) {
    var findStatement = {
      dispatched: false
    };

    if (query && query.aggregate) {
      findStatement.aggregate = query.aggregate;
    }

    if (query && query.context) {
      findStatement.context = query.context;
    }

    if (query && query.aggregateId) {
      findStatement.aggregateId = query.aggregateId;
    }

    this.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] }).toArray(callback);
  },

  setEventToDispatched: function (id, callback) {
    var updateCommand = { '$unset' : { 'dispatched': null } };
    this.events.updateOne({'_id' : id}, updateCommand, callback);
  },

  addSnapshot: function(snap, callback) {
    if (!snap.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    snap._id = snap.id;
    this.snapshots.insertOne(snap, callback);
  },

  cleanSnapshots: function (query, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = {
      aggregateId: query.aggregateId
    };

    if (query.aggregate) {
      findStatement.aggregate = query.aggregate;
    }

    if (query.context) {
      findStatement.context = query.context;
    }

    this.snapshots.find(findStatement, {
      sort: [['revision', 'desc'], ['version', 'desc'], ['commitStamp', 'desc']]
    })
      .skip(this.options.maxSnapshotsCount)
      .toArray(removeElements(this.snapshots, callback));
  },

  getSnapshot: function (query, revMax, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = {
      aggregateId: query.aggregateId
    };

    if (query.aggregate) {
      findStatement.aggregate = query.aggregate;
    }

    if (query.context) {
      findStatement.context = query.context;
    }

    if (revMax > -1) {
      findStatement.revision = { '$lte': revMax };
    }

    this.snapshots.findOne(findStatement, { sort: [['revision', 'desc'], ['version', 'desc'], ['commitStamp', 'desc']] }, callback);
  },

  removeTransactions: function (evt, callback) {
    if (!evt.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = { aggregateId: evt.aggregateId };

    if (evt.aggregate) {
      findStatement.aggregate = evt.aggregate;
    }

    if (evt.context) {
      findStatement.context = evt.context;
    }

    // the following is usually unnecessary
    this.transactions.deleteMany(findStatement, function (err) {
      if (err) {
        debug(err);
      }
      if (callback) { callback(err); }
    });
  },

  getPendingTransactions: function (callback) {
    var self = this;
    this.transactions.find({}).toArray(function (err, txs) {
      if (err) {
        debug(err);
        return callback(err);
      }

      if (txs.length === 0) {
        return callback(null, txs);
      }

      var goodTxs = [];

      async.map(txs, function (tx, clb) {
        var findStatement = { commitId: tx._id, aggregateId: tx.aggregateId };

        if (tx.aggregate) {
          findStatement.aggregate = tx.aggregate;
        }

        if (tx.context) {
          findStatement.context = tx.context;
        }

        self.events.findOne(findStatement, function (err, evt) {
          if (err) {
            return clb(err);
          }

          if (evt) {
            goodTxs.push(evt);
            return clb(null);
          }
          
          self.transactions.deleteOne({ _id: tx._id }, clb);
        });
      }, function (err) {
        if (err) {
          debug(err);
          return callback(err);
        }

        callback(null, goodTxs);
      })
    });
  },

  getLastEvent: function (query, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = { aggregateId: query.aggregateId };

    if (query.aggregate) {
      findStatement.aggregate = query.aggregate;
    }

    if (query.context) {
      findStatement.context = query.context;
    }

    this.events.findOne(findStatement, { sort: [['commitStamp', 'desc'], ['streamRevision', 'desc'], ['commitSequence', 'desc']] }, callback);
  },

  repairFailedTransaction: function (lastEvt, callback) {
    var self = this;

    //var findStatement = {
    //  aggregateId: lastEvt.aggregateId,
    //  'events.streamRevision': lastEvt.streamRevision + 1
    //};
    //
    //if (lastEvt.aggregate) {
    //  findStatement.aggregate = lastEvt.aggregate;
    //}
    //
    //if (lastEvt.context) {
    //  findStatement.context = lastEvt.context;
    //}

    //this.transactions.findOne(findStatement, function (err, tx) {
    this.transactions.findOne({ _id: lastEvt.commitId }, function (err, tx) {
      if (err) {
        debug(err);
        return callback(err);
      }

      if (!tx) {
        var err = new Error('missing tx entry for aggregate ' + lastEvt.aggregateId);
        debug(err);
        return callback(err);
      }

      var missingEvts = tx.events.slice(tx.events.length - lastEvt.restInCommitStream);

      self.events.insertMany(missingEvts, function (err) {
        if (err) {
          debug(err);
          return callback(err);
        }

        self.removeTransactions(lastEvt);

        callback(null);
      });
    });
  }

});

function removeElements(collection, callback) {
  return function (error, elements) {
    if (error) {
      debug(error);
      return callback(error);
    }
    async.each(elements, function (element, callback) {
      try {
        collection.deleteOne({_id: element._id});
        callback();
      } catch (error) {
        callback(error);
      }
    }, function(error) {
      callback(error, elements.length);
    });
  }
}

module.exports = Mongo;
