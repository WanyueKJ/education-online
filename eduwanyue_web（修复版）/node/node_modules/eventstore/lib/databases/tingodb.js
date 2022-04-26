var util = require('util'),
  Store = require('../base'),
  _ = require('lodash'),
  async = require('async'),
  tingodb = Store.use('tingodb')(),
  ObjectID = tingodb.ObjectID,
  debug = require('debug')('eventstore:store:tingodb');

function Tingo(options) {
  options = options || {};

  Store.call(this, options);

  var defaults = {
    dbPath: require('path').join(__dirname, '../../'),
    eventsCollectionName: 'events',
    snapshotsCollectionName: 'snapshots',
    transactionsCollectionName: 'transactions'
  };

  _.defaults(options, defaults);

  this.options = options;
}

util.inherits(Tingo, Store);

_.extend(Tingo.prototype, {

  connect: function (callback) {
    var options = this.options;

    this.db = new tingodb.Db(options.dbPath, {});
    // this.db.on('close', function() {
    //   self.emit('disconnect');
    // });
    this.events = this.db.collection(options.eventsCollectionName + '.tingo');
    this.events.ensureIndex({ aggregateId: 1, streamRevision: 1 },
      function (err) { if (err) { debug(err); } });
    this.events.ensureIndex({ commitStamp: 1 },
        function (err) { if (err) { debug(err); } });
    this.events.ensureIndex({ dispatched: 1 }, { sparse: true },
      function (err) { if (err) { debug(err); } });
    this.events.ensureIndex({ commitStamp: 1, streamRevision: 1, commitSequence: 1 },
      function (err) { if (err) { debug(err); } });
    this.events.ensureIndex({ aggregate: 1, aggregateId: 1, commitStamp: -1, streamRevision: -1, commitSequence: -1 },
      function (err) { if (err) { debug(err); } });

    this.snapshots = this.db.collection(options.snapshotsCollectionName + '.tingo');
    this.snapshots.ensureIndex({ aggregateId: 1, revision: -1 },
      function (err) { if (err) { debug(err); } });

    this.transactions = this.db.collection(options.transactionsCollectionName + '.tingo');
    this.transactions.ensureIndex({ aggregateId: 1, 'events.streamRevision': 1 },
      function (err) { if (err) { debug(err); } });

    this.emit('connect');
    if (callback) callback(null, this);
  },

  disconnect: function (callback) {
    if (!this.db) {
      if (callback) callback(null);
      return;
    }

    this.emit('disconnect');
    this.db.close(callback || function () {});
  },

  clear: function (callback) {
    var self = this;
    async.parallel([
      function (callback) {
        self.events.remove({}, callback);
      },
      function (callback) {
        self.snapshots.remove({}, callback);
      },
      function (callback) {
        self.transactions.remove({}, callback);
      }
    ], function (err) {
      if (err) {
        debug(err);
      }
      if (callback) callback(err);
    });
  },

  // getNewId: function(callback) {
  //   callback(null, new ObjectID().toString());
  // },

  addEvents: function (events, callback) {
    var noAggId = false;

    _.each(events, function (evt) {
      if (!evt.aggregateId) {
        noAggId = true;
      }

      evt._id = evt.id;
      evt.dispatched = false;
    });

    if (noAggId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var self = this;

    if (events.length === 1) {
      return this.events.insert(events, callback);
    }

    var tx = {
      _id: events[0].commitId,
      events: events,
      aggregateId: events[0].aggregateId,
      aggregate: events[0].aggregate,
      context: events[0].context
    };

    this.transactions.insert(tx, function (err) {
      if (err) {
        debug(err);
        if (callback) callback(err);
        return;
      }

      self.events.insert(events, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        self.removeTransactions(events[events.length - 1]);

        if (callback) { callback(null); }
      });
    });
  },

  getEvents: function (query, skip, limit, callback) {
    var findStatement = {};

    if (query.aggregate) {
      findStatement.aggregate = query.aggregate;
    }

    if (query.context) {
      findStatement.context = query.context;
    }

    if (query.aggregateId) {
      findStatement['$or'] = [
        { aggregateId: query.aggregateId },
        { streamId: query.aggregateId } // just for compatability of < 1.0.0
      ];
    }

    if (limit === -1) {
      return this.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] }).skip(skip).toArray(callback);
    }

    this.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] }).skip(skip).limit(limit).toArray(callback);
  },

  getEventsSince: function (date, skip, limit, callback) {
    var findStatement = { commitStamp: { '$gte': date } };

    if (limit === -1) {
      return this.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] }).skip(skip).toArray(callback);
    }

    this.events.find(findStatement, { sort: [['commitStamp', 'asc'], ['streamRevision', 'asc'], ['commitSequence', 'asc']] }).skip(skip).limit(limit).toArray(callback);
  },

  getEventsByRevision: function (query, revMin, revMax, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var streamRevOptions = { '$gte': revMin, '$lt': revMax };
    if (revMax == -1) {
      streamRevOptions = { '$gte': revMin };
    }

    var findStatement = {
      '$or': [
        { aggregateId: query.aggregateId },
        { streamId: query.aggregateId } // just for compatability of < 1.0.0
      ],
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
    this.events.update({'_id' : id}, updateCommand, callback);
  },

  addSnapshot: function(snap, callback) {
    if (!snap.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    snap._id = snap.id;
    this.snapshots.insert(snap, callback);
  },

  cleanSnapshots: function (query, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = {
      '$or': [
        { aggregateId: query.aggregateId },
        { streamId: query.aggregateId } // just for compatability of < 1.0.0
      ]
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
      '$or': [
        { aggregateId: query.aggregateId },
        { streamId: query.aggregateId } // just for compatability of < 1.0.0
      ]
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
    this.transactions.remove(findStatement, function (err) {
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
          } else {
            self.transactions.remove({ _id: tx._id }, function (err) {
              if (err) {
                debug(err);
              }
            });
          }

          clb(null);
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

      self.events.insert(missingEvts, function (err) {
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
        collection.remove({_id: element._id});
        callback();
      } catch (error) {
        callback(error);
      }
    }, function(error) {
      callback(error, elements.length);
    });
  }
}

module.exports = Tingo;
