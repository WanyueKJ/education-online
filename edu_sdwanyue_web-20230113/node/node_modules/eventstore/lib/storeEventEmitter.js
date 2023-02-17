var _ = require('lodash');
var Eventstore = require('./eventstore');

/**
 * Emit events before and after execution of eventstore methods.
 * @param {Function} eventstore - eventstore store instance
 */
function StoreEventEmitter(eventstore) {
  if (!eventstore || !(eventstore instanceof Eventstore)) {
    throw new Error('Provided eventstore must be instance of Eventstore');
  }

  var self = this;
  self.eventstore = eventstore;
  self.store = self.eventstore.store;

  /**
   * Get arguments which are emitted
   * @param {Array} args - arguments from original implementation except callback
   */
  function getEmitArguments(args) {
    return {
      milliseconds: Date.now(),
      arguments: args
    };
  }

  /**
   * Enhance original callback to emit an event
   * @param {string} name - name which will be used to emit
   * @param {Function} callback - callback function from original implementation
   * @param {Array} args - arguments from original implementation except callback
   */
  function enhanceCallback(name, callback, args) {
    var originalCallback = callback;

    callback = function () {
      self.eventstore.emit.call(self.eventstore, 'after-' + name, getEmitArguments(args));

      if (originalCallback) {
        return originalCallback.apply(this, arguments);
      }

      return;
    };

    return callback;
  }

  /**
   * add event emitter advice to eventstore method
   * @param {string} name - name which will be used to emit
   * @param {Function} eventstore - eventstore store instance
   * @param {Function} originalMethod  - original implementation which will be extended
   * @param {Array} args - arguments from original implementation except callback
   * @param {Function} callback - callback function from original implementation
   */
  function addEventEmitterToMethod(name, originalMethod, args, callback) {
    args = _.without(args, undefined, null);

    callback = enhanceCallback(name, callback, args);

    self.eventstore.emit.call(self.eventstore, 'before-' + name, getEmitArguments(args));
    return originalMethod.apply(this, _.concat(args, callback || []));
  };

  /**
   * Add event emitter to eventstore methods
   */
  StoreEventEmitter.prototype.addEventEmitter = function () {
    /**
     * add event emitter as an around advice to database methods
     */
    if (this.store.clear) {
      var originalClear = this.store.clear;
      this.store.clear = function (callback) {
        return addEventEmitterToMethod.call(this, 'clear', originalClear, [], callback);
      };
    }

    if (this.store.getNextPositions) {
      var originalGetNextPositions = this.store.getNextPositions;
      this.store.getNextPositions = function (positions, callback) {
        return addEventEmitterToMethod.call(this, 'get-next-positions', originalGetNextPositions, [positions], callback);
      };
    }

    if (this.store.addEvents) {
      var originalAddEvents = this.store.addEvents;
      this.store.addEvents = function (events, callback) {
        return addEventEmitterToMethod.call(this, 'add-events', originalAddEvents, [events], callback);
      };
    }

    if (this.eventstore.getEvents) {
      var originalGetEvents = this.eventstore.getEvents;

      this.eventstore.getEvents = function (query, skip, limit, callback) {
        if (typeof query === 'function') {
          callback = query;
          query = undefined;
        } else if (typeof skip === 'function') {
          callback = skip;
          skip = undefined;
        } else if (typeof limit === 'function') {
          callback = limit;
          limit = undefined;
        }

        return addEventEmitterToMethod.call(this, 'get-events', originalGetEvents, [query, skip, limit], callback);
      };
    }

    if (this.eventstore.getEventsSince) {
      var originalGetEventsSince = this.eventstore.getEventsSince;
      this.eventstore.getEventsSince = function (date, skip, limit, callback) {
        if (typeof skip === 'function') {
          callback = skip;
          skip = undefined;
        } else if (typeof limit === 'function') {
          callback = limit;
          limit = undefined;
        }

        return addEventEmitterToMethod.call(this, 'get-events-since', originalGetEventsSince, [date, skip, limit], callback);
      };
    }

    if (this.eventstore.getEventsByRevision) {
      var originalGetEventsByRevision = this.eventstore.getEventsByRevision;
      this.eventstore.getEventsByRevision = function (query, revMin, revMax, callback) {
        if (typeof revMin === 'function') {
          callback = revMin;
          revMin = undefined;
        } else if (typeof revMax === 'function') {
          callback = revMax;
          revMax = undefined;
        }

        return addEventEmitterToMethod.call(this, 'get-events-by-revision', originalGetEventsByRevision, [query, revMin, revMax], callback);
      };
    }

    if (this.eventstore.getLastEvent) {
      var originalGetLastEvent = this.eventstore.getLastEvent;
      this.eventstore.getLastEvent = function (query, callback) {
        return addEventEmitterToMethod.call(this, 'get-last-event', originalGetLastEvent, [query], callback);
      };
    }

    if (this.eventstore.getUndispatchedEvents) {
      var originalGetUndispatchedEvents = this.eventstore.getUndispatchedEvents;
      this.eventstore.getUndispatchedEvents = function (query, callback) {
        if (!callback) {
          callback = query;
          query = undefined;
        }

        return addEventEmitterToMethod.call(this, 'get-undispatched-events', originalGetUndispatchedEvents, [query], callback);
      };
    }

    if (this.eventstore.setEventToDispatched) {
      var originalSetEventToDispatched = this.eventstore.setEventToDispatched;
      this.eventstore.setEventToDispatched = function (id, callback) {
        return addEventEmitterToMethod.call(this, 'set-event-to-dispatched', originalSetEventToDispatched, [id], callback);
      };
    }

    if (this.store.addSnapshot) {
      var originalAddSnapshots = this.store.addSnapshot;
      this.store.addSnapshot = function (snap, callback) {
        return addEventEmitterToMethod.call(this, 'add-snapshot', originalAddSnapshots, [snap], callback);
      };
    }

    if (this.store.cleanSnapshots) {
      var originalCleanSnapshots = this.store.cleanSnapshots;
      this.store.cleanSnapshots = function (query, callback) {
        return addEventEmitterToMethod.call(this, 'clean-snapshots', originalCleanSnapshots, [query], callback);
      };
    }

    if (this.store.getSnapshot) {
      var originalGetSnapshot = this.store.getSnapshot;
      this.store.getSnapshot = function (query, revMax, callback) {
        return addEventEmitterToMethod.call(this, 'get-snapshot', originalGetSnapshot, [query, revMax], callback);
      };
    }

    if (this.store.removeTransactions) {
      var originalRemoveTransactions = this.store.removeTransactions;
      this.store.removeTransactions = function (evt, callback) {
        return addEventEmitterToMethod.call(this, 'remove-transactions', originalRemoveTransactions, [evt], callback);
      };
    }

    if (this.store.getPendingTransactions) {
      var originalGetPendingTransactions = this.store.getPendingTransactions;
      this.store.getPendingTransactions = function (callback) {
        return addEventEmitterToMethod.call(this, 'get-pending-transactions', originalGetPendingTransactions, [], callback);
      };
    }

    if (this.store.repairFailedTransaction) {
      var originalRepairFailedTransaction = this.store.repairFailedTransaction;
      this.store.repairFailedTransaction = function (lastEvt, callback) {
        return addEventEmitterToMethod.call(this, 'repair-failed-transactions', originalRepairFailedTransaction, [lastEvt], callback);
      };
    }

    if (this.store.removeTables) {
      var originalRemoveTables = this.store.removeTables;
      this.store.removeTables = function (callback) {
        return addEventEmitterToMethod.call(this, 'remove-tables', originalRemoveTables, [], callback);
      };
    }

    if (this.eventstore.streamEvents) {
      var originalStreamEvents = this.eventstore.streamEvents;
      this.eventstore.streamEvents = function (query, skip, limit) {
        return addEventEmitterToMethod.call(this, 'stream-events', originalStreamEvents, [query, skip, limit]);
      };
    }

    if (this.eventstore.streamEventsSince) {
      var originalStreamEventsSince = this.eventstore.streamEventsSince;
      this.eventstore.streamEventsSince = function (date, skip, limit) {
        return addEventEmitterToMethod.call(this, 'stream-events-since', originalStreamEventsSince, [date, skip, limit]);
      };
    }

    if (this.eventstore.streamEventsByRevision) {
      var originalStreamEventsByRevision = this.eventstore.streamEventsByRevision;
      this.eventstore.streamEventsByRevision = function (query, revMin, revMax) {
        return addEventEmitterToMethod.call(this, 'stream-events-by-revision', originalStreamEventsByRevision, [query, revMin, revMax]);
      };
    }

    if (this.eventstore.getEventStream) {
      var originalGetEventStream = this.eventstore.getEventStream;
      this.eventstore.getEventStream = function (query, revMin, revMax, callback) {
        if (typeof revMin === 'function') {
          callback = revMin;
          revMin = undefined;
        } else if (typeof revMax === 'function') {
          callback = revMax;
          revMax = undefined;
        }

        return addEventEmitterToMethod.call(this, 'get-event-stream', originalGetEventStream, [query, revMin, revMax], callback);
      };
    }

    if (this.eventstore.getFromSnapshot) {
      var originalGetFromSnapshot = this.eventstore.getFromSnapshot;
      this.eventstore.getFromSnapshot = function (query, revMax, callback) {
        if (typeof revMax === 'function') {
          callback = revMax;
          revMax = undefined;
        }

        return addEventEmitterToMethod.call(this, 'get-from-snapshot', originalGetFromSnapshot, [query, revMax], callback);
      };
    }

    if (this.eventstore.createSnapshot) {
      var originalCreateSnapshot = this.eventstore.createSnapshot;
      this.eventstore.createSnapshot = function (obj, callback) {
        return addEventEmitterToMethod.call(this, 'create-snapshot', originalCreateSnapshot, [obj], callback);
      };
    }

    if (this.eventstore.commit) {
      var originalCommit = this.eventstore.commit;
      this.eventstore.commit = function (eventstream, callback) {
        return addEventEmitterToMethod.call(this, 'commit', originalCommit, [eventstream], callback);
      };
    }

    if (this.eventstore.getLastEventAsStream) {
      var originalGetLastEventAsStream = this.eventstore.getLastEventAsStream;
      this.eventstore.getLastEventAsStream = function (query, callback) {
        return addEventEmitterToMethod.call(this, 'get-last-event-as-stream', originalGetLastEventAsStream, [query], callback);
      };
    }
  };
}

module.exports = StoreEventEmitter;
