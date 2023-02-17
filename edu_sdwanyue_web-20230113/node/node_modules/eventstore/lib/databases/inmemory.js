var util = require('util'),
  Store = require('../base'),
  _ = require('lodash'),
  jsondate = require('jsondate'),
  debug = require('debug')('eventstore:store:inmemory');

function InMemory(options) {
  Store.call(this, options);
  this.store = {};
  this.snapshots = {};
  this.undispatchedEvents = { _direct: {} };
  this.options = options;
  if (options.trackPosition)
    this.position = 0;    
}

util.inherits(InMemory, Store);

function deepFind (obj, pattern) {
  var found;

  if (pattern) {
    var parts = pattern.split('.');

    found = obj;
    for (var i in parts) {
      found = found[parts[i]];
      if (_.isArray(found)) {
        found = _.filter(found, function (item) {
          var deepFound = deepFind(item, parts.slice(i + 1).join('.'));
          return !!deepFound;

        });
        break;
      }

      if (!found) {
        break;
      }
    }
  }

  return found;
}

_.extend(InMemory.prototype, {

  connect: function (callback) {
    this.emit('connect');
    if (callback) callback(null, this);
  },

  disconnect: function (callback) {
    this.emit('disconnect');
    if (callback) callback(null);
  },

  clear: function (callback) {
    this.store = {};
    this.snapshots = {};
    this.undispatchedEvents = { _direct: {} };
    this.position = 0;
    if (callback) callback(null);
  },

  getNextPositions: function(positions, callback) {
    if (!this.options.trackPosition) {
      return callback(null);
    }
    
    var range = [];
    for(var i=0; i<positions; i++) {
      range.push(++this.position);
    }
    callback(null, range);
  },

  addEvents: function (events, callback) {
    if (!events || events.length === 0) {
      callback(null);
      return;
    }

    var found = _.find(events, function(evt) {
      return !evt.aggregateId;
    });

    if (found) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var aggregateId = events[0].aggregateId;
    var aggregate = events[0].aggregate || '_general';
    var context = events[0].context || '_general';

    this.store[context] = this.store[context] || {};
    this.store[context][aggregate] = this.store[context][aggregate] || {};
    this.store[context][aggregate][aggregateId] = this.store[context][aggregate][aggregateId] || [];
    this.store[context][aggregate][aggregateId] = this.store[context][aggregate][aggregateId].concat(events);

    this.undispatchedEvents[context] = this.undispatchedEvents[context] || {};
    this.undispatchedEvents[context][aggregate] = this.undispatchedEvents[context][aggregate] || {};
    this.undispatchedEvents[context][aggregate][aggregateId] = this.undispatchedEvents[context][aggregate][aggregateId] || [];
    this.undispatchedEvents[context][aggregate][aggregateId] = this.undispatchedEvents[context][aggregate][aggregateId].concat(events);

    var self = this;
    _.forEach(events, function(evt) {
      self.undispatchedEvents._direct[evt.id] = evt;
    });

    callback(null);
  },

  getEvents: function (query, skip, limit, callback) {
    var res = [];
    for (var s in this.store) {
      for (var ss in this.store[s]) {
        for (var sss in this.store[s][ss]) {
          res = res.concat(this.store[s][ss][sss]);
        }
      }
    }

    res = _.sortBy(res, function (e) {
      return e.commitStamp.getTime();
    });

    if (!_.isEmpty(query)) {
      res = _.filter(res, function(e) {
        var keys = _.keys(query);
        var values = _.values(query);
        var found = false;
        for (var i in keys) {
          var key = keys[i];
          var deepFound = deepFind(e, key);
          if (_.isArray(deepFound) && deepFound.length > 0) {
            found = true;
          } else if (deepFound === values[i]) {
            found = true;
          } else {
            found = false;
            break;
          }
        }
        return found;
      });
    }

    if (limit === -1) {
      return callback(null, _.cloneDeep(res.slice(skip)));
    }

    if (res.length <= skip) {
      return callback(null, []);
    }

    callback(null, _.cloneDeep(res.slice(skip, skip + limit)));
  },

  getEventsSince: function (date, skip, limit, callback) {
    var res = [];
    for (var s in this.store) {
      for (var ss in this.store[s]) {
        for (var sss in this.store[s][ss]) {
          res = res.concat(this.store[s][ss][sss]);
        }
      }
    }

    res = _.sortBy(res, function (e) {
      return e.commitStamp.getTime();
    });

    res = _.filter(res, function(e) {
      return e.commitStamp.getTime() >= date.getTime();
    });

    if (limit === -1) {
      return callback(null, _.cloneDeep(res.slice(skip)));
    }

    if (res.length <= skip) {
      return callback(null, []);
    }

    callback(null, _.cloneDeep(res.slice(skip, skip + limit)));
  },

  getEventsByRevision: function (query, revMin, revMax, callback) {
    var res = [];

    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    if (query.context && query.aggregate) {
      this.store[query.context] = this.store[query.context] || {};
      this.store[query.context][query.aggregate] = this.store[query.context][query.aggregate] || {};

      if (!this.store[query.context][query.aggregate][query.aggregateId]) {
        return callback(null, _.cloneDeep(res));
      }
      else {
        if (revMax === -1) {
          res = res.concat(this.store[query.context][query.aggregate][query.aggregateId].slice(revMin));
        }
        else {
          res = res.concat(this.store[query.context][query.aggregate][query.aggregateId].slice(revMin, revMax));
        }
      }
      return callback(null, _.cloneDeep(res));
    }

    if (!query.context && query.aggregate) {
      for (var s in this.store) {
        var c = this.store[s];
        if (c[query.aggregate] && c[query.aggregate][query.aggregateId]) {
          if (revMax === -1) {
            res = res.concat(c[query.aggregate][query.aggregateId].slice(revMin));
          }
          else {
            res = res.concat(c[query.aggregate][query.aggregateId].slice(revMin, revMax));
          }
        }
      }
      return callback(null, _.cloneDeep(res));
    }

    if (query.context && !query.aggregate) {
      var cc = this.store[query.context] || {};
      for (var ss in cc) {
        var a = cc[ss];
        if (a[query.aggregateId]) {
          if (revMax === -1) {
            res = res.concat(a[query.aggregateId].slice(revMin));
          }
          else {
            res = res.concat(a[query.aggregateId].slice(revMin, revMax));
          }
        }
      }
      return callback(null, _.cloneDeep(res));
    }

    if (!query.context && !query.aggregate) {
      for (var sc in this.store) {
        var cont = this.store[sc];
        for (var sa in cont) {
          var agg = cont[sa];
          if (agg[query.aggregateId]) {
            if (revMax === -1) {
              res = res.concat(agg[query.aggregateId].slice(revMin));
            }
            else {
              res = res.concat(agg[query.aggregateId].slice(revMin, revMax));
            }
          }
        }
      }
      return callback(null, _.cloneDeep(res));
    }
  },

  getLastEvent: function (query, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var res = [];
    for (var s in this.store) {
      for (var ss in this.store[s]) {
        for (var sss in this.store[s][ss]) {
          res = res.concat(this.store[s][ss][sss]);
        }
      }
    }

    res = _.sortBy(res, function (e) {
      return e.commitStamp.getTime();
    });

    if (!_.isEmpty(query)) {
      res = _.filter(res, function(e) {
        var keys = _.keys(query);
        var values = _.values(query);
        var found = false;
        for (var i in keys) {
          var key = keys[i];
          var deepFound = deepFind(e, key);
          if (_.isArray(deepFound) && deepFound.length > 0) {
            found = true;
          } else if (deepFound === values[i]) {
            found = true;
          } else {
            found = false;
            break;
          }
        }
        return found;
      });
    }

    callback(null, res[res.length - 1]);
  },

  getUndispatchedEvents: function (query, callback) {
    var res = [];
    for (var s in this.undispatchedEvents) {
      if (s === '_direct') continue;
      for (var ss in this.undispatchedEvents[s]) {
        for (var sss in this.undispatchedEvents[s][ss]) {
          res = res.concat(this.undispatchedEvents[s][ss][sss]);
        }
      }
    }

    res = _.sortBy(res, function (e) {
      return e.commitStamp.getTime();
    });

    if (!_.isEmpty(query)) {
      res = _.filter(res, function(e) {
        var keys = _.keys(query);
        var values = _.values(query);
        var found = false;
        for (var i in keys) {
          var key = keys[i];
          var deepFound = deepFind(e, key);
          if (_.isArray(deepFound) && deepFound.length > 0) {
            found = true;
          } else if (deepFound === values[i]) {
            found = true;
          } else {
            found = false;
            break;
          }
        }
        return found;
      });
    }

    callback(null, res);
  },

  setEventToDispatched: function (id, callback) {
    var evt = this.undispatchedEvents._direct[id];
    var aggregateId = evt.aggregateId;
    var aggregate = evt.aggregate || '_general';
    var context = evt.context || '_general';

    this.undispatchedEvents[context][aggregate][aggregateId] = _.reject(this.undispatchedEvents[context][aggregate][aggregateId], evt);
    delete this.undispatchedEvents._direct[id];
    callback(null);
  },

  addSnapshot: function(snap, callback) {
    var aggregateId = snap.aggregateId;
    var aggregate = snap.aggregate || '_general';
    var context = snap.context || '_general';

    if (!snap.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    this.snapshots[context] = this.snapshots[context] || {};
    this.snapshots[context][aggregate] = this.snapshots[context][aggregate] || {};
    this.snapshots[context][aggregate][aggregateId] = this.snapshots[context][aggregate][aggregateId] || [];

    this.snapshots[context][aggregate][aggregateId].push(snap);
    callback(null);
  },

  getSnapshot: function (query, revMax, callback) {

    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var all = [];
    for (var s in this.snapshots) {
      for (var ss in this.snapshots[s]) {
        for (var sss in this.snapshots[s][ss]) {
          all = all.concat(this.snapshots[s][ss][sss]);
        }
      }
    }

//    all = _.sortBy(all, function (s) {
//      return [(-s.revision), (-s.version)].join('_');
//    });

    all = _.sortBy(all, function (s) {
      return (-s.commitStamp.getTime());
    });

    if (!_.isEmpty(query)) {
      all = _.filter(all, function(a) {
        var keys = _.keys(query);
        var values = _.values(query);
        var found = false;
        for (var i in keys) {
          var key = keys[i];
          var deepFound = deepFind(a, key);
          if (_.isArray(deepFound) && deepFound.length > 0) {
            found = true;
          } else if (deepFound === values[i]) {
            found = true;
          } else {
            found = false;
            break;
          }
        }
        return found;
      });
    }

    if (revMax === -1) {
      return callback(null, all[0] ? jsondate.parse(JSON.stringify(all[0])) : null);
    }
    else {
      for (var i = all.length - 1; i >= 0; i--) {
        if (all[i].revision <= revMax) {
          return callback(null, jsondate.parse(JSON.stringify(all[i])));
        }
      }
    }
    callback(null, null);
  },

  cleanSnapshots: function(query, callback) {
    var aggregateId = query.aggregateId;
    var aggregate = query.aggregate || '_general';
    var context = query.context || '_general';

    if (!aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var snapshots = this.snapshots[context][aggregate][aggregateId] || [];
    var length = snapshots.length;
    snapshots = snapshots.slice(-1 * this.options.maxSnapshotsCount);
    this.snapshots[context][aggregate][aggregateId] = snapshots;

    callback(null, length - snapshots.length);
  }

});

module.exports = InMemory;
