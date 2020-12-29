var debug = require('debug')('eventstore:snapshot');

/**
 * Snapshot constructor
 * The snapshot object will be persisted to the store.
 * @param {String} id  the id of the snapshot
 * @param {Object} obj the snapshot object infos
 * @constructor
 */
function Snapshot (id, obj) {
  if (!id) {
    var errIdMsg = 'id not injected!';
    debug(errIdMsg);
    throw new Error(errIdMsg);
  }

  if (!obj) {
    var errObjMsg = 'object not injected!';
    debug(errObjMsg);
    throw new Error(errObjMsg);
  }

  if (!obj.aggregateId) {
    var errAggIdMsg = 'object.aggregateId not injected!';
    debug(errAggIdMsg);
    throw new Error(errAggIdMsg);
  }

  if (!obj.data) {
    var errDataMsg = 'object.data not injected!';
    debug(errDataMsg);
    throw new Error(errDataMsg);
  }

  this.id = id;
  this.streamId = obj.aggregateId;
  this.aggregateId = obj.aggregateId;
  this.aggregate = obj.aggregate || null;
  this.context = obj.context || null;
  this.commitStamp = null;
  this.revision = obj.revision;
  this.version = obj.version;
  this.data = obj.data;
}

module.exports = Snapshot;
