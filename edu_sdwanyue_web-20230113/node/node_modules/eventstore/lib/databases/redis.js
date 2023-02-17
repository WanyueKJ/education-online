var util = require('util'),
  fs = require('fs'),
  Store = require('../base'),
  _ = require('lodash'),
  async = require('async'),
  redis = Store.use('redis'),
  jsondate = require('jsondate'),
  debug = require('debug')('eventstore:store:redis');

function Redis(options) {
  options = options || {};

  Store.call(this, options);

  var defaults = {
    host: 'localhost',
    port: 6379,
    prefix: 'eventstore',
    eventsCollectionName: 'events',
    snapshotsCollectionName: 'snapshots',
    retry_strategy: function (options) {
      return undefined;
    }//,
    // heartbeat: 60 * 1000
  };

  _.defaults(options, defaults);

  if (options.url) {
    var url = require('url').parse(options.url);
    if (url.protocol === 'redis:') {
      if (url.auth) {
        var userparts = url.auth.split(':');
        options.user = userparts[0];
        if (userparts.length === 2) {
          options.password = userparts[1];
        }
      }
      options.host = url.hostname;
      options.port = url.port;
      if (url.pathname) {
        options.db = url.pathname.replace('/', '', 1);
      }
    }
  }

  this.options = options;
}

util.inherits(Redis, Store);

// helpers
function handleResultSet(err, res, callback) {
  if (err) {
    debug(err);
    return callback(err);
  }

  if (!res || res.length === 0) {
    return callback(null, []);
  }
  var arr = [];

  res.forEach(function (item) {
    arr.push(jsondate.parse(item));
  });

  callback(null, arr);
}

_.extend(Redis.prototype, {

  connect: function (callback) {
    var self = this;

    var options = this.options;

    this.client = new redis.createClient(options.port || options.socket, options.host, _.omit(options, 'prefix'));

    var calledBack = false;

    if (options.password) {
      this.client.auth(options.password, function (err) {
        if (err && !calledBack && callback) {
          calledBack = true;
          if (callback) callback(err, self);
          return;
        }

        if (err) {
          debug(err);
        }
      });
    }

    if (options.db) {
      this.client.select(options.db);
    }

    this.client.on('end', function () {
      self.disconnect();
      self.stopHeartbeat();
    });

    this.client.on('error', function (err) {
      debug(err);

      if (calledBack) return;
      calledBack = true;
      if (callback) callback(null, self);
    });

    this.client.on('connect', function () {
      if (options.db) {
        self.client.send_anyways = true;
        self.client.select(options.db);
        self.client.send_anyways = false;
      }

      self.emit('connect');

      if (self.options.heartbeat) {
        self.startHeartbeat();
      }

      if (calledBack) return;
      calledBack = true;
      if (callback) callback(null, self);
    });
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
          console.error((new Error('Heartbeat timeouted after ' + gracePeriod + 'ms (redis)')).stack);
          self.disconnect();
        }
      }, gracePeriod);

      self.client.ping(function (err) {
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

    if (this.client) {
      this.client.end(true);
    }
    this.emit('disconnect');
    if (callback) callback(null, this);
  },

  clear: function (callback) {
    var self = this;
    async.parallel([
      function (callback) {
        self.client.del('nextItemId:' + self.options.prefix, callback);
      },
      function (callback) {
        self.client.keys(self.options.prefix + ':*', function (err, keys) {
          if (err) {
            return callback(err);
          }
          async.each(keys, function (key, callback) {
            self.client.del(key, callback);
          }, callback);
        });
      }
    ], function (err) {
      if (err) {
        debug(err);
      }
      if (callback) callback(err);
    });
  },

  getNewId: function (callback) {
    this.client.incr('nextItemId:' + this.options.prefix, function (err, id) {
      if (err) {
        debug(err);
        return callback(err);
      }
      callback(null, id.toString());
    });
  },

  addEvents: function (events, callback) {

    var self = this;

    var aggregateId = events[0].aggregateId;
    var aggregate = events[0].aggregate || '_general';
    var context = events[0].context || '_general';

    var noAggId = events.filter(function (event) {
      return !event.aggregateId
    }).length > 0;

    if (noAggId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    if (!events || events.length === 0) {
      return callback(null);
    }

    function eventKey(event) {
      return event.commitStamp.getTime() + ':' + event.commitSequence.toString() + ':' + context + ':' + aggregate + ':' + aggregateId + ':' + event.id;
    }

    var prefix = self.options.prefix + ':' + self.options.eventsCollectionName;
    var revisionKey = prefix + ':' + context + ':' + aggregate + ':' + aggregateId + ':revision';
    var multi = events.reduce(function (multi) {
      return multi.incr(revisionKey);
    }, this.client.multi());

    multi.exec(function (error, revisions) {
      if (error) {
        debug(error);
        return callback(error);
      }

      var errors = revisions.filter(function (reply) {
        return reply instanceof Error;
      });

      if (errors.length) {
        var message = 'error while adding events for aggregate ' + aggregate + ' ' + aggregateId;
        return callback(new Error(message + '\n' + errors.join('\n')));
      }

      var savedKeysAndEvents = events.map(function(event, index) {
        var key = prefix + ':' + eventKey(event);
        event.streamRevision = parseInt(revisions[index], 10) - 1;
        event.applyMappings();
        return [key, JSON.stringify(event)];
      });

      var undispatchedKeysAndEvents = events.map(function (event) {
        var key = self.options.prefix + ':undispatched_' + self.options.eventsCollectionName + ':' + eventKey(event);
        return [key, JSON.stringify(event)];
      });

      var args = _.flatten(savedKeysAndEvents)
        .concat(_.flatten(undispatchedKeysAndEvents))
        .concat(callback);
      self.client.mset.apply(self.client, args);
    });
  },

  scan: function (key, cursor, handleKeys, callback) {
    var self = this;

    if (!callback) {
      callback = handleKeys;
      handleKeys = cursor;
      cursor = 0;
    }

    (function scanRecursive(curs) {
      self.client.scan(curs, 'match', key, function (err, res) {
        if (err) {
          return callback(err);
        }

        function next() {
          if (res[0] === '0') {
            callback(null);
          } else {
            scanRecursive(res[0]);
          }
        }

        if (res[1].length === 0) {
          return next();
        }

        handleKeys(res[1], function (err) {
          if (err) {
            return callback(err);
          }
          next();
        });
      });
    })(cursor);
  },

  getEvents: function (query, skip, limit, callback) {
    var aggregateId = query.aggregateId || '*';
    var aggregate = query.aggregate || '*';
    var context = query.context || '*';

    var self = this;

    var allKeys = [];

    this.scan(this.options.prefix + ':' + this.options.eventsCollectionName + ':*:*:' + context + ':' + aggregate + ':' + aggregateId + ':*',
      function (keys, fn) {
        allKeys = allKeys.concat(keys);
        fn();
      }, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        allKeys = _.sortBy(allKeys, function (s) {
          return s;
        });

        if (limit === -1) {
          allKeys = allKeys.slice(skip);
        }
        else {
          allKeys = allKeys.slice(skip, skip + limit);
        }

        if (allKeys.length === 0) {
          return callback(null, []);
        }

        var args = allKeys.concat(function (err, res) {
          handleResultSet(err, res, callback);
        });

        self.client.mget.apply(self.client, args);
      }
    );
  },

  getEventsSince: function (date, skip, limit, callback) {
    var self = this;

    var allKeys = [];

    this.scan(this.options.prefix + ':' + this.options.eventsCollectionName + ':*:*:*:*:*:*',
      function (keys, fn) {
        keys = _.filter(keys, function (s) {
          var parts = s.split(':');
          var timePart = parseInt(parts[2], 10);
          return timePart >= date.getTime();
        });

        allKeys = allKeys.concat(keys);
        fn();
      }, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        allKeys = _.sortBy(allKeys, function (s) {
          return s;
        });

        if (limit === -1) {
          allKeys = allKeys.slice(skip);
        }
        else {
          allKeys = allKeys.slice(skip, skip + limit);
        }

        if (allKeys.length === 0) {
          return callback(null, []);
        }

        var args = allKeys.concat(function (err, res) {
          handleResultSet(err, res, callback);
        });

        self.client.mget.apply(self.client, args);
      }
    );
  },

  getEventsByRevision: function (query, revMin, revMax, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var aggregateId = query.aggregateId;
    var aggregate = query.aggregate || '*';
    var context = query.context || '*';

    var self = this;

    var allKeys = [];

    this.scan(this.options.prefix + ':' + this.options.eventsCollectionName + ':*:*:' + context + ':' + aggregate + ':' + aggregateId + ':*',
      function (keys, fn) {
        allKeys = allKeys.concat(keys);
        fn();
      }, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        allKeys = _.sortBy(allKeys, function (s) {
          return s;
        });

        if (revMax === -1) {
          allKeys = allKeys.slice(revMin);
        }
        else {
          allKeys = allKeys.slice(revMin, revMax);
        }

        if (allKeys.length === 0) {
          return callback(null, []);
        }

        var args = allKeys.concat(function (err, res) {
          handleResultSet(err, res, callback);
        });

        self.client.mget.apply(self.client, args);
      }
    );
  },

  getLastEvent: function (query, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var aggregateId = query.aggregateId || '*';
    var aggregate = query.aggregate || '*';
    var context = query.context || '*';

    var self = this;

    var allKeys = [];

    this.scan(this.options.prefix + ':' + this.options.eventsCollectionName + ':*:*:' + context + ':' + aggregate + ':' + aggregateId + ':*',
      function (keys, fn) {
        allKeys = allKeys.concat(keys);
        fn();
      }, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        allKeys = _.sortBy(allKeys, function (s) {
          return s;
        });

        if (allKeys.length === 0) {
          return callback(null, null);
        }

        var args = allKeys.concat(function (err, res) {
          handleResultSet(err, res, function (err, evts) {
            if (err) return callback(err);
            if (evts.length === 0) return callback(null, null);
            callback(null, evts[evts.length - 1])
          });
        });

        self.client.mget.apply(self.client, args);
      }
    );
  },

  getUndispatchedEvents: function (query, callback) {
    var self = this;

    var aggregateId = '*';
    var aggregate = '*';
    var context = '*';

    if (query) {
      aggregateId = query.aggregateId || '*';
      aggregate = query.aggregate || '*';
      context = query.context || '*';
    }

    var evts = [];

    this.scan(this.options.prefix + ':undispatched_' + this.options.eventsCollectionName + ':*:*:' + context + ':' + aggregate + ':' + aggregateId + ':*',
      function (keys, fn) {
        var args = keys.concat(function (err, res) {
          handleResultSet(err, res, function (err, events) {
            if (err) {
              return fn(err);
            }

            evts = evts.concat(events);
            fn();
          });
        });

        self.client.mget.apply(self.client, args);
      }, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        evts = _.sortBy(evts, function (s) {
          return s.commitStamp.getTime() + ':' + s.commitSequence.toString();
        });

        callback(null, evts);
      }
    );
  },

  setEventToDispatched: function (id, callback) {
    var self = this;

    this.scan(this.options.prefix + ':undispatched_' + this.options.eventsCollectionName + ':*:*:*:*:*:' + id,
      function (keys, fn) {
        var args = keys.concat(fn);
        self.client.del.apply(self.client, args);
      }, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }

        if (callback) callback(null);
      }
    );
  },

  addSnapshot: function (snap, callback) {
    if (!snap.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var aggregateId = snap.aggregateId;
    var aggregate = snap.aggregate || '_general';
    var context = snap.context || '_general';

    this.client.set(this.options.prefix + ':' + this.options.snapshotsCollectionName + ':' + snap.commitStamp.getTime() + ':' + context + ':' + aggregate + ':' + aggregateId + ':' + snap.id, JSON.stringify(snap), function (err) {
      if (callback) callback(err);
    });
  },

  cleanSnapshots: function (query, callback) {
    var self = this;

    this.scanSnapshots(query, function(error, keys) {
      if (error) {
        debug(error);
        if (callback) callback(error);
        return;
      }

      var keysToDelete = keys
        .sort()
        .slice(0, -1 * self.options.maxSnapshotsCount)

      if (keysToDelete.length === 0) {
        if (callback) callback(null, 0);
        return;
      }

      var delArgs = keysToDelete.concat(callback);

      self.client.del.apply(self.client, delArgs);
    });
  },

  scanSnapshots: function (query, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var aggregateId = query.aggregateId;
    var aggregate = query.aggregate || '*';
    var context = query.context || '*';

    var allKeys = [];

    this.scan(this.options.prefix + ':' + this.options.snapshotsCollectionName + ':*:' + context + ':' + aggregate + ':' + aggregateId + ':*',
      function (keys, fn) {
        allKeys = allKeys.concat(keys);
        fn();
      }, function (error) {
        callback(error, allKeys);
      }
    );
  },

  getSnapshot: function (query, revMax, callback) {
    var self = this;

    this.scanSnapshots(query, function (err, allKeys) {
      if (err) {
        debug(err);
        if (callback) callback(err);
        return;
      }

      allKeys = _.sortBy(allKeys, function (s) {
        return s;
      }).reverse();

      if (revMax === -1) { // by default the last snapshot is kept
        allKeys = allKeys.slice(0, 1);
      }

      if (allKeys.length === 0) {
        return callback(null, null);
      }

      // iterating recursively over snapshots, from latest to oldest
      (function nextSnapshot(key) {
        self.client.get(key, function (err, res) {
          if (err) {
            debug(err);
            return callback(err);
          }

          var snapshot = jsondate.parse(res);
          if (revMax > -1 && snapshot.revision > revMax) {
            if (allKeys.length > 0) {
              nextSnapshot(allKeys.shift());
            } else {
              callback(null, null);
            }
          } else {
            callback(null, snapshot);
          }
        });
      })(allKeys.shift());
    })
  }

});

module.exports = Redis;
