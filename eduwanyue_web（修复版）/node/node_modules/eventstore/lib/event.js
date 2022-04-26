var debug = require('debug')('eventstore:event'),
  dotty = require('dotty'),
  _ = require('lodash');

/**
 * Event constructor
 * @param {EventStream} eventstream the corresponding event stream object
 * @param {Object}      event       the event object
 * @constructor
 */
function Event (eventstream, event, eventMappings) {
  if (!eventstream) {
    var errStreamMsg = 'eventstream not injected!';
    debug(errStreamMsg);
    throw new Error(errStreamMsg);
  }

  if (!event) {
    var errEvtMsg = 'event not injected!';
    debug(errEvtMsg);
    throw new Error(errEvtMsg);
  }

  if (!eventstream.aggregateId) {
    var errAggIdMsg = 'eventstream.aggregateId not injected!';
    debug(errAggIdMsg);
    throw new Error(errAggIdMsg);
  }

  if (!_.isArray(eventstream.uncommittedEvents)) {
    var errAggIdMsg = 'eventstream.uncommittedEvents not injected!';
    debug(errAggIdMsg);
    throw new Error(errAggIdMsg);
  }

  eventMappings = eventMappings || {};

  this.streamId = eventstream.aggregateId;
  this.aggregateId = eventstream.aggregateId;
  this.aggregate = eventstream.aggregate;
  this.context = eventstream.context;
  this.streamRevision = null;
  this.commitId = null;
  this.commitSequence = null;
  this.commitStamp = null;
  this.payload = event || null;
  this.position = null;

  this.applyMappings = function applyMappings() {
    _.keys(eventMappings).forEach(function (key) {
      if (this[key] !== undefined && this[key] !== null) {
        dotty.put(this.payload, eventMappings[key], this[key]);
      }
    }.bind(this));
  };

  eventstream.uncommittedEvents.push(this);
}

module.exports = Event;
