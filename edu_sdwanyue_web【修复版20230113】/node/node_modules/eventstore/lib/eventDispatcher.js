var debug = require('debug')('eventstore:eventdispatcher');

/**
 * Eventstore constructor
 * @param {Object} options The options.
 * @param publisher the publisher that should be injected
 * @param store the store that should be injected
 * @constructor
 */
function EventDispatcher(publisher, store) {
  this.publisher = publisher;
  this.store = store;
  this.undispatchedEventsQueue = [];
}

/**
 * Triggers to publish all events in undispatchedEventsQueue.
 */
function trigger (dispatcher) {
  var queue = dispatcher.undispatchedEventsQueue || []
  var event;

  // if the last loop is still in progress leave this loop
  if (dispatcher.isRunning) return;

  dispatcher.isRunning = true;

  (function next (e) {

    // dipatch one event in queue and call the _next_ callback, which
    // will call _process_ for the next undispatched event in queue.
    function process (event, nxt) {

      // Publish it now...
      debug('publish event...');
      dispatcher.publisher(event.payload, function(err) {
        if (err) {
          return debug(err);
        }
        // ...and set the published event to dispatched.
        debug('set event to dispatched...');
        dispatcher.store.setEventToDispatched(event, function(err) {
          if (err) {
            debug(err);
          } else {
            debug('event set to dispatched');
          }
        });
      });

      nxt();
    }

    // serial process all events in queue
    if (!e && queue.length) {
      process(queue.shift(), next)
    } else {
      debug(e);
    }
  })();

  dispatcher.isRunning = false;
}

EventDispatcher.prototype = {

  /**
   * Queues the passed in events for dispatching.
   * @param events
   */
  addUndispatchedEvents: function(events) {
    var self = this;
    events.forEach(function(event) {
      self.undispatchedEventsQueue.push(event);
    });
    trigger(this);
  },

  /**
   * Starts the instance to publish all undispatched events.
   * @param callback the function that will be called when this action has finished
   */
  start: function(callback) {

    if (typeof this.publisher !== 'function') {
      var pubErrMsg = 'publisher not injected!';
      debug(pubErrMsg);
      if (callback) callback(new Error(pubErrMsg));
      return;
    }

    if (!this.store || typeof this.store.getUndispatchedEvents !== 'function'
                    || typeof this.store.setEventToDispatched !== 'function') {
      var storeErrMsg = 'store not injected!';
      debug(storeErrMsg);
      if (callback) callback(new Error(storeErrMsg))
      return;
    }

    var self = this;

    // Get all undispatched events from store and queue them
    // before all other events passed in by the addUndispatchedEvents function.
    this.store.getUndispatchedEvents(function(err, events) {

      if (err) {
        debug(err);
        if (callback) callback(err);
        return;
      }

      var triggered = false;
      if (events) {
        for (var i = 0, len = events.length; i < len; i++) {
          self.undispatchedEventsQueue.push(events[i]);
          // If there are a lot of events then we can hit issues with the call stack size when processing in one go
          triggered = false;
          if (i % 1000 === 0){
            triggered = true;
            trigger(self);
          }
        }
      }

      if (!triggered) {
        trigger(self);
      }

      if (callback) callback(null);
    });
  }
};

module.exports = EventDispatcher;
