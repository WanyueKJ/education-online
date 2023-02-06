var Eventstore = require('./lib/eventstore'),
  Base = require('./lib/base'),
  _ = require('lodash'),
  debug = require('debug')('eventstore'),
  StoreEventEmitter = require('./lib/storeEventEmitter');

function exists(toCheck) {
  var _exists = require('fs').existsSync || require('path').existsSync;
  if (require('fs').accessSync) {
    _exists = function (toCheck) {
      try {
        require('fs').accessSync(toCheck);
        return true;
      } catch (e) {
        return false;
      }
    };
  }
  return _exists(toCheck);
}

function getSpecificStore(options) {
  options = options || {};

  options.type = options.type || 'inmemory';

  if (_.isFunction(options.type)) {
    return options.type;
  }

  options.type = options.type.toLowerCase();

  var dbPath = __dirname + "/lib/databases/" + options.type + ".js";

  if (!exists(dbPath)) {
    var errMsg = 'Implementation for db "' + options.type + '" does not exist!';
    console.log(errMsg);
    debug(errMsg);
    throw new Error(errMsg);
  }

  try {
    var db = require(dbPath);
    return db;
  } catch (err) {

    if (err.message.indexOf('Cannot find module') >= 0 &&
      err.message.indexOf("'") > 0 &&
      err.message.lastIndexOf("'") !== err.message.indexOf("'")) {

      var moduleName = err.message.substring(err.message.indexOf("'") + 1, err.message.lastIndexOf("'"));
      var msg = 'Please install module "' + moduleName +
        '" to work with db implementation "' + options.type + '"!';
      console.log(msg);
      debug(msg);
    }

    throw err;
  }
}

module.exports = function(options) {
  options = options || {};

  var Store;

  try {
    Store = getSpecificStore(options);
  } catch (err) {
    throw err;
  }

  var eventstore = new Eventstore(options, new Store(options));

  if (options.emitStoreEvents) {
    var storeEventEmitter = new StoreEventEmitter(eventstore);
    storeEventEmitter.addEventEmitter();
  }

  return eventstore;
};

module.exports.Store = Base;
