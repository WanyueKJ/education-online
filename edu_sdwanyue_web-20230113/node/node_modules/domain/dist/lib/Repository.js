System.register("../../lib/Repository", [], function() {
  "use strict";
  var __moduleName = "../../lib/Repository";
  var thunkify = require("thunkify");
  var Emitter = require("events").EventEmitter;
  var cache = Symbol("cache");
  var Repository = function Repository(Actor, eventstore) {
    this.Actor = Actor;
    this.eventstore = eventstore;
    this.getFromSnapShot = thunkify(this.eventstore.getFromSnapshot).bind(this.eventstore);
    this.createSnapshot = thunkify(this.eventstore.createSnapshot).bind(this.eventstore);
    this[$traceurRuntime.toProperty(cache)] = {};
  };
  ($traceurRuntime.createClass)(Repository, {
    create: $traceurRuntime.initGeneratorFunction(function $__1(data) {
      var actor;
      return $traceurRuntime.createGeneratorInstance(function($ctx) {
        while (true)
          switch ($ctx.state) {
            case 0:
              actor = new this.Actor(data, true);
              this[$traceurRuntime.toProperty(cache)][$traceurRuntime.toProperty(actor.id)] = actor;
              this.emit("create", actor);
              $ctx.state = 4;
              break;
            case 4:
              $ctx.returnValue = actor;
              $ctx.state = -2;
              break;
            default:
              return $ctx.end();
          }
      }, $__1, this);
    }),
    clear: function(id) {
      delete this[$traceurRuntime.toProperty(cache)][$traceurRuntime.toProperty(id)];
    },
    getFromCache: function(id) {
      return this[$traceurRuntime.toProperty(cache)][$traceurRuntime.toProperty(id)];
    },
    get: $traceurRuntime.initGeneratorFunction(function $__2(id) {
      var actor,
          result,
          snapshot,
          stream,
          snap,
          history,
          historyv;
      return $traceurRuntime.createGeneratorInstance(function($ctx) {
        while (true)
          switch ($ctx.state) {
            case 0:
              $ctx.state = (actor = this.getFromCache(id)) ? 1 : 2;
              break;
            case 1:
              $ctx.returnValue = actor;
              $ctx.state = -2;
              break;
            case 2:
              $ctx.state = 5;
              return this.getFromSnapShot(id);
            case 5:
              result = $ctx.sent;
              $ctx.state = 7;
              break;
            case 7:
              $ctx.state = (actor = this.getFromCache(id)) ? 8 : 9;
              break;
            case 8:
              $ctx.returnValue = actor;
              $ctx.state = -2;
              break;
            case 9:
              snapshot = result[0];
              stream = result[1];
              snap = snapshot ? snapshot.data : {};
              history = stream.events;
              actor = new this.Actor();
              actor.loadSnap(snap);
              historyv = [];
              history.forEach(function(e) {
                historyv.push(e.payload);
              });
              actor.loadEvents(historyv);
              this[$traceurRuntime.toProperty(cache)][$traceurRuntime.toProperty(actor.id)] = actor;
              this.emit("reborn", actor);
              $ctx.state = 14;
              break;
            case 14:
              $ctx.returnValue = actor;
              $ctx.state = -2;
              break;
            default:
              return $ctx.end();
          }
      }, $__2, this);
    })
  }, {}, Emitter);
  module.exports = Repository;
  return {};
});
System.get("../../lib/Repository" + '');
