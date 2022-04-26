var debug = require('debug')('eventstore'),
  util = require('util'),
  EventEmitter = require('events').EventEmitter,
  _ = require('lodash'),
  async = require('async'),
  tolerate = require('tolerance'),
  EventDispatcher = require('./eventDispatcher'),
  EventStream = require('./eventStream'),
  Snapshot = require('./snapshot');

/**
 * Eventstore constructor
 * @param {Object} options The options.
 * @param {Store}  store   The db implementation.
 * @constructor
 */
function Eventstore(options, store) {
  this.options = options || {};
  this.store = store;

  this.eventMappings = {};

  EventEmitter.call(this);
}

util.inherits(Eventstore, EventEmitter);

_.extend(Eventstore.prototype, {

  /**
   * Inject function for event publishing.
   * @param {Function} fn the function to be injected
   * @returns {Eventstore}  to be able to chain...
   */
  useEventPublisher: function (fn) {
    if (fn.length === 1) {
      fn = _.wrap(fn, function(func, evt, callback) {
        func(evt);
        callback(null);
      });
    }

    this.publisher = fn;

    return this;
  },

  /**
   * Define which values should be mapped/copied to the payload event. [optional]
   * @param {Object} mappings the mappings in dotty notation
   *                          {
   *                            id: 'id',
   *                            commitId: 'commitId',
   *                            commitSequence: 'commitSequence',
   *                            commitStamp: 'commitStamp',
   *                            streamRevision: 'streamRevision'
   *                          }
   * @returns {Eventstore}  to be able to chain...
   */
  defineEventMappings: function (mappings) {
    if (!mappings || !_.isObject(mappings)) {
      var err = new Error('Please pass a valid mapping values!');
      debug(err);
      throw err;
    }

    this.eventMappings = mappings;

    return this;
  },

  /**
   * Call this function to initialize the eventstore.
   * If an event publisher function was injected it will additionally initialize an event dispatcher.
   * @param {Function} callback the function that will be called when this action has finished [optional]
   */
  init: function (callback) {
    var self = this;

    function initDispatcher() {
      debug('init event dispatcher');
      self.dispatcher = new EventDispatcher(self.publisher, self);
      self.dispatcher.start(callback);
    }

    this.store.on('connect', function () {
      self.emit('connect');
    });

    this.store.on('disconnect', function () {
      self.emit('disconnect');
    });

    process.nextTick(function() {
      tolerate(function(callback) {
        self.store.connect(callback);
      }, self.options.timeout || 0, function (err) {
        if (err) {
          debug(err);
          if (callback) callback(err);
          return;
        }
        if (!self.publisher) {
          debug('no publisher defined');
          if (callback) callback(null);
          return;
        }
        initDispatcher();
      });
    });
  },


  // streaming api

  /**
   * streams the events
   * @param {Object || String} query    the query object [optional]
   * @param {Number}           skip     how many events should be skipped? [optional]
   * @param {Number}           limit    how many events do you want in the result? [optional]
   * @returns {Stream} a stream with the events
   */
  streamEvents: function (query, skip, limit) {
    if (!this.store.streamEvents) {
      throw new Error('Streaming API is not suppoted by '+(this.options.type || 'inmemory') +' db implementation.');
    }

    if (typeof query === 'number') {
      limit = skip;
      skip = query;
      query = {};      
    };

    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    return this.store.streamEvents(query, skip, limit);
  },

  /**
   * streams all the events since passed commitStamp
   * @param {Date}     commitStamp the date object
   * @param {Number}   skip        how many events should be skipped? [optional]
   * @param {Number}   limit       how many events do you want in the result? [optional]
   * @returns {Stream} a stream with the events
   */
  streamEventsSince: function (commitStamp, skip, limit) {
    if (!this.store.streamEvents) {
      throw new Error('Streaming API is not suppoted by '+(this.options.type || 'inmemory') +' db implementation.');
    }

    if (!commitStamp) {
      var err = new Error('Please pass in a date object!');
      debug(err);
      throw err;
    }

    var self = this;
    commitStamp = new Date(commitStamp);

    return this.store.streamEventsSince(commitStamp, skip, limit);
  },


  /**
   * stream events by revision
   * @param {Object || String} query    the query object
   * @param {Number}           revMin   revision start point [optional]
   * @param {Number}           revMax   revision end point (hint: -1 = to end) [optional]
   * @returns {Stream} a stream with the events
   */
  streamEventsByRevision: function (query, revMin, revMax) {
    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    if (!query.aggregateId) {
      var err = new Error('An aggregateId should be passed!');
      debug(err);
      if (callback) callback(err);
      return;
    }

    return this.store.streamEventsByRevision(query, revMin, revMax);
  },

  /**
   * loads the events
   * @param {Object || String} query    the query object [optional]
   * @param {Number}           skip     how many events should be skipped? [optional]
   * @param {Number}           limit    how many events do you want in the result? [optional]
   * @param {Function}         callback the function that will be called when this action has finished
   *                                    `function(err, events){}`
   */
  getEvents: function (query, skip, limit, callback) {
    if (typeof query === 'function') {
      callback = query;
      skip = 0;
      limit = -1;
      query = {};
    } else if (typeof skip === 'function') {
      callback = skip;
      skip = 0;
      limit = -1;
      if (typeof query === 'number') {
        skip = query;
        query = {};
      }
    } else if (typeof limit === 'function') {
      callback = limit;
      limit = -1;
      if (typeof query === 'number') {
        limit = skip;
        skip = query;
        query = {};
      }
    }

    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    var self = this;

    function nextFn(callback) {
      if (limit < 0) {
        var resEvts = [];
        resEvts.next = nextFn;
        return process.nextTick(function () { callback(null, resEvts) });
      }
      skip += limit;
      _getEvents(query, skip, limit, callback);
    }

    function _getEvents(query, skip, limit, callback) {
      self.store.getEvents(query, skip, limit, function (err, evts) {
        if (err) return callback(err);
        evts.next = nextFn;
        callback(null, evts);
      });
    }

    _getEvents(query, skip, limit, callback);
  },

  /**
   * loads all the events since passed commitStamp
   * @param {Date}     commitStamp the date object
   * @param {Number}   skip        how many events should be skipped? [optional]
   * @param {Number}   limit       how many events do you want in the result? [optional]
   * @param {Function} callback    the function that will be called when this action has finished
   *                               `function(err, events){}`
   */
  getEventsSince: function (commitStamp, skip, limit, callback) {
    if (!commitStamp) {
      var err = new Error('Please pass in a date object!');
      debug(err);
      throw err;
    }

    if (typeof skip === 'function') {
      callback = skip;
      skip = 0;
      limit = -1;
    } else if (typeof limit === 'function') {
      callback = limit;
      limit = -1;
    }

    var self = this;

    function nextFn(callback) {
      if (limit < 0) {
        var resEvts = [];
        resEvts.next = nextFn;
        return process.nextTick(function () { callback(null, resEvts) });
      }
      skip += limit;
      _getEventsSince(commitStamp, skip, limit, callback);
    }

    commitStamp = new Date(commitStamp);

    function _getEventsSince(commitStamp, skip, limit, callback) {
      self.store.getEventsSince(commitStamp, skip, limit, function (err, evts) {
        if (err) return callback(err);
        evts.next = nextFn;
        callback(null, evts);
      });
    }

    _getEventsSince(commitStamp, skip, limit, callback);
  },

  /**
   * loads the events
   * @param {Object || String} query    the query object
   * @param {Number}           revMin   revision start point [optional]
   * @param {Number}           revMax   revision end point (hint: -1 = to end) [optional]
   * @param {Function}         callback the function that will be called when this action has finished
   *                                    `function(err, events){}`
   */
  getEventsByRevision: function (query, revMin, revMax, callback) {
    if (typeof revMin === 'function') {
      callback = revMin;
      revMin = 0;
      revMax = -1;
    } else if (typeof revMax === 'function') {
      callback = revMax;
      revMax = -1;
    }

    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    if (!query.aggregateId) {
      var err = new Error('An aggregateId should be passed!');
      debug(err);
      if (callback) callback(err);
      return;
    }

    this.store.getEventsByRevision(query, revMin, revMax, callback);
  },

  /**
   * loads the event stream
   * @param {Object || String} query    the query object
   * @param {Number}           revMin   revision start point [optional]
   * @param {Number}           revMax   revision end point (hint: -1 = to end) [optional]
   * @param {Function}         callback the function that will be called when this action has finished
   *                                    `function(err, eventstream){}`
   */
  getEventStream: function (query, revMin, revMax, callback) {
    if (typeof revMin === 'function') {
      callback = revMin;
      revMin = 0;
      revMax = -1;
    } else if (typeof revMax === 'function') {
      callback = revMax;
      revMax = -1;
    }

    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    if (!query.aggregateId) {
      var err = new Error('An aggregateId should be passed!');
      debug(err);
      if (callback) callback(err);
      return;
    }

    var self = this;

    this.getEventsByRevision(query, revMin, revMax, function(err, evts) {
      if (err) {
        return callback(err);
      }
      callback(null, new EventStream(self, query, evts));
    });
  },

  /**
   * loads the next snapshot back from given max revision
   * @param {Object || String} query    the query object
   * @param {Number}           revMax   revision end point (hint: -1 = to end) [optional]
   * @param {Function}         callback the function that will be called when this action has finished
   *                                    `function(err, snapshot, eventstream){}`
   */
  getFromSnapshot: function (query, revMax, callback) {
    if (typeof revMax === 'function') {
      callback = revMax;
      revMax = -1;
    }

    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    if (!query.aggregateId) {
      var err = new Error('An aggregateId should be passed!');
      debug(err);
      if (callback) callback(err);
      return;
    }

    var self = this;

    async.waterfall([

      function getSnapshot(callback) {
        self.store.getSnapshot(query, revMax, callback);
      },

      function getEventStream(snap, callback) {
        var rev = 0;

        if (snap && (snap.revision !== undefined && snap.revision !== null)) {
          rev = snap.revision + 1;
        }

        self.getEventStream(query, rev, revMax, function(err, stream) {
          if (err) {
            return callback(err);
          }

          if (rev > 0 && stream.lastRevision == -1) {
            stream.lastRevision = snap.revision;
          }

          callback(null, snap, stream);
        });
      }],

      callback
    );
  },

  /**
   * stores a new snapshot
   * @param {Object}   obj      the snapshot data
   * @param {Function} callback the function that will be called when this action has finished [optional]
   */
  createSnapshot: function(obj, callback) {
    if (obj.streamId && !obj.aggregateId) {
      obj.aggregateId = obj.streamId;
    }

    if (!obj.aggregateId) {
      var err = new Error('An aggregateId should be passed!');
      debug(err);
      if (callback) callback(err);
      return;
    }

    obj.streamId = obj.aggregateId;

    if (obj.revision) {
      if (typeof (obj.revision) === 'string') {
        const castedRevision = parseFloat(obj.revision);

        if (castedRevision && castedRevision.toString() === obj.revision) { // Determines if the revision was parsed correctly, for the cases where user using custom typed revisions that's not in valid float format like: obj.revision = '1,2,3'
          obj.revision = castedRevision;
        }
      }
    }

    var self = this;

    async.waterfall([
        function getNewIdFromStorage(callback) {
          self.getNewId(callback);
        },
        function commit(id, callback) {
          try {
            var snap = new Snapshot(id, obj);
            snap.commitStamp = new Date();
          } catch (err) {
            return callback(err);
          }

          self.store.addSnapshot(snap, function(error) {
            if (self.options.maxSnapshotsCount) {
              self.store.cleanSnapshots(_.pick(obj, 'aggregateId', 'aggregate', 'context'), callback);
            } else {
              callback(error);
            }
          });
        }],
      callback
    );
  },

  /**
   * commits all uncommittedEvents in the eventstream
   * @param eventstream the eventstream that should be saved (hint: directly use the commit function on eventstream)
   * @param {Function}  callback the function that will be called when this action has finished
   *                             `function(err, eventstream){}` (hint: eventstream.eventsToDispatch)
   */
  commit: function(eventstream, callback) {

    var self = this;

    async.waterfall([

      function getNewCommitId(callback) {
        self.getNewId(callback);
      },

      function commitEvents(id, callback) {
        // start committing.
        var event,
          currentRevision = eventstream.currentRevision(),
          uncommittedEvents = [].concat(eventstream.uncommittedEvents);
        eventstream.uncommittedEvents = [];

        self.store.getNextPositions(uncommittedEvents.length, function(err, positions) {
          if (err)
            return callback(err)

          for (var i = 0, len = uncommittedEvents.length; i < len; i++) {
            event = uncommittedEvents[i];
            event.id = id + i.toString();
            event.commitId = id;
            event.commitSequence = i;
            event.restInCommitStream = len - 1 - i;
            event.commitStamp = new Date();
            currentRevision++;
            event.streamRevision = currentRevision;
            if (positions)
              event.position = positions[i];

            event.applyMappings();
          }

          self.store.addEvents(uncommittedEvents, function(err) {
            if (err) {
              // add uncommitted events back to eventstream
              eventstream.uncommittedEvents = uncommittedEvents.concat(eventstream.uncommittedEvents);
              return callback(err);
            }

            if (self.publisher && self.dispatcher) {
              // push to undispatchedQueue
              self.dispatcher.addUndispatchedEvents(uncommittedEvents);
            } else {
              eventstream.eventsToDispatch = [].concat(uncommittedEvents);
            }

            // move uncommitted events to events
            eventstream.events = eventstream.events.concat(uncommittedEvents);
            eventstream.currentRevision();

            callback(null, eventstream);
          });
        });
      }],

      callback
    );
  },

  /**
   * loads all undispatched events
   * @param {Object || String} query    the query object [optional]
   * @param {Function}         callback the function that will be called when this action has finished
   *                                    `function(err, events){}`
   */
  getUndispatchedEvents: function (query, callback) {
    if (!callback) {
      callback = query;
      query = null;
    }

    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    this.store.getUndispatchedEvents(query, callback);
  },

  /**
   * loads the last event
   * @param {Object || String} query    the query object [optional]
   * @param {Function}         callback the function that will be called when this action has finished
   *                                    `function(err, event){}`
   */
  getLastEvent: function (query, callback) {
    if (!callback) {
      callback = query;
      query = null;
    }

    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    this.store.getLastEvent(query, callback);
  },

  /**
   * loads the last event in a stream
   * @param {Object || String} query    the query object [optional]
   * @param {Function}         callback the function that will be called when this action has finished
   *                                    `function(err, eventstream){}`
   */
  getLastEventAsStream: function (query, callback) {
    if (!callback) {
      callback = query;
      query = null;
    }

    if (typeof query === 'string') {
      query = { aggregateId: query };
    }

    var self = this;

    this.store.getLastEvent(query, function (err, evt) {
      if (err) return callback(err);

      callback(null, new EventStream(self, query, evt ? [evt] : []));
    });
  },

  /**
   * Sets the given event to dispatched.
   * @param {Object || String} evtOrId  the event object or its id
   * @param {Function}         callback the function that will be called when this action has finished [optional]
   */
  setEventToDispatched: function (evtOrId, callback) {
    if (typeof evtOrId === 'object') {
      evtOrId = evtOrId.id;
    }
    this.store.setEventToDispatched(evtOrId, callback);
  },

  /**
   * loads a new id from store
   * @param {Function} callback the function that will be called when this action has finished
   */
  getNewId: function (callback) {
    this.store.getNewId(callback);
  }

});

module.exports = Eventstore;
