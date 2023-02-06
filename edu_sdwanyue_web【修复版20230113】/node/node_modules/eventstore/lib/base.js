var util = require('util'),
  EventEmitter = require('events').EventEmitter,
  prequire = require('parent-require'),
  _ = require('lodash'),
  uuid = require('uuid').v4;

/**
 * Store constructor
 * @param {Object} options The options can have information like host, port, etc. [optional]
 */
function Store(options) {
  options = options || {};

  EventEmitter.call(this);
}

util.inherits(Store, EventEmitter);

function implementError (callback) {
  var err = new Error('Please implement this function!');
  if (callback) callback(err);
  throw err;
}

function silentWarning(callback) {
  console.warn('Snapshot cleaning is not implemented for this kind of store');
  callback();
}

_.extend(Store.prototype, {

  /**
   * Initiate communication with the queue.
   * @param  {Function} callback The function, that will be called when the this action is completed. [optional]
   *                             `function(err, queue){}`
   */
  connect: implementError,

  /**
   * Terminate communication with the queue.
   * @param  {Function} callback The function, that will be called when the this action is completed. [optional]
   *                             `function(err){}`
   */
  disconnect: implementError,

  /**
   * Use this function to obtain a new id.
   * @param  {Function} callback The function, that will be called when the this action is completed.
   *                             `function(err, id){}` id is of type String.
   */
  getNewId: function (callback) {
    var id = uuid().toString();
    if (callback) callback(null, id);
  },

  /**
   * Use this function to an array containing the next position numbers
   * @param  {number} positins Number of positions to provide.
   * @param  {Function} callback The function, that will be called when the this action is completed.
   *                             `function(err, positions){}` positions is either undefined if option is not enabled/supported or array with positions
   */
  getNextPositions: function(positions, callback) {
    callback(null);
  },

  /**
   * loads the events
   * @param {Object}   query    the query object
   * @param {Number}   skip     how many events should be skipped?
   * @param {Number}   limit    how many events do you want in the result?
   * @param {Function} callback the function that will be called when this action has finished
   *                            `function(err, events){}`
   */
  getEvents: function (query, skip, limit, callback) {
    implementError(callback);
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
    implementError(callback);
  },

  /**
   * loads the events
   * @param {Object}   query    the query object
   * @param {Number}   revMin   revision start point
   * @param {Number}   revMax   revision end point (hint: -1 = to end)
   * @param {Function} callback the function that will be called when this action has finished
   *                            `function(err, events){}`
   */
  getEventsByRevision: function (query, revMin, revMax, callback) {
    implementError(callback);
  },

  /**
   * loads the next snapshot back from given max revision
   * @param {Object}   query    the query object
   * @param {Number}   revMax   revision end point (hint: -1 = to end)
   * @param {Function} callback the function that will be called when this action has finished
   *                            `function(err, snapshot){}`
   */
  getSnapshot: function (query, revMax, callback) {
    implementError(callback);
  },

  /**
   * stores a new snapshot
   * @param {Object}   snap     the snapshot data
   * @param {Function} callback the function that will be called when this action has finished [optional]
   */
  addSnapshot: function(snap, callback) {
    implementError(callback);
  },

  /**
   * stores a new snapshot
   * @param {Object}   query    the query object
   * @param {Function} callback the function that will be called when this action has finished [optional]
   */
  cleanSnapshots: function(query, callback) {
    silentWarning(callback);
  },

  /**
   * stores the passed events
   * @param {Array}    evts     the events
   * @param {Function} callback the function that will be called when this action has finished [optional]
   */
  addEvents: function (evts, callback) {
    implementError(callback);
  },

  /**
   * loads the last event
   * @param {Object}   query    the query object [optional]
   * @param {Function} callback the function that will be called when this action has finished
   *                            `function(err, event){}`
   */
  getLastEvent: function (query, callback) {
    implementError(callback);
  },

  /**
   * loads all undispatched events
   * @param {Object}   query    the query object [optional]
   * @param {Function} callback the function that will be called when this action has finished
   *                            `function(err, events){}`
   */
  getUndispatchedEvents: function (query, callback) {
    implementError(callback);
  },

  /**
   * Sets the given event to dispatched.
   * @param {String}   id       the event id
   * @param {Function} callback the function that will be called when this action has finished [optional]
   */
  setEventToDispatched: function (id, callback) {
    implementError(callback);
  },

  /**
   * NEVER USE THIS FUNCTION!!! ONLY FOR TESTS!
   * clears the complete store...
   * @param {Function} callback the function that will be called when this action has finished [optional]
   */
  clear: function (callback) {
    implementError(callback);
  }
});

Store.use = function (toRequire) {
  var required;
  try {
    required = require(toRequire);
  } catch (e) {
    // workaround when `npm link`'ed for development
    required = prequire(toRequire);
  }
  return required;
};

module.exports = Store;
