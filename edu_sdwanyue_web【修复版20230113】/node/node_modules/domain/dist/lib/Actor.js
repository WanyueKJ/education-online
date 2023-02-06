System.register("../../lib/Actor", [], function() {
  "use strict";
  var $__2;
  var __moduleName = "../../lib/Actor";
  var dataKye = Symbol("dataKey"),
      set = Symbol[$traceurRuntime.toProperty("set")],
      isLoadEvents = Symbol("isLoadEvents"),
      apply = Symbol("apply"),
      listen = Symbol("listen"),
      when = Symbol("when"),
      create = Symbol("create"),
      otherWhen = Symbol("otherWhen"),
      getDI = Symbol("getDI"),
      uid = require("shortid"),
      Event = require("./Event"),
      EventEmitter = require("events").EventEmitter;
  var Actor = function Actor() {
    var data = arguments[0] !== (void 0) ? arguments[0] : {};
    var isCreate = arguments[1] !== (void 0) ? arguments[1] : false;
    this[$traceurRuntime.toProperty(dataKye)] = data;
    this[$traceurRuntime.toProperty(set)]("id", uid());
    this[$traceurRuntime.toProperty(set)]("alive", true);
    this.uncommittedEvents = [];
    if (isCreate) {
      this[$traceurRuntime.toProperty(create)](this[$traceurRuntime.toProperty(dataKye)]);
    }
  };
  var $Actor = Actor;
  ($traceurRuntime.createClass)(Actor, ($__2 = {}, Object.defineProperty($__2, set, {
    value: function(k, v) {
      if (arguments.length === 1) {
        for (var n in k)
          if (!$traceurRuntime.isSymbolString(n)) {
            this[$traceurRuntime.toProperty(set)](n, k[$traceurRuntime.toProperty(n)]);
          }
      } else {
        this[$traceurRuntime.toProperty(dataKye)][$traceurRuntime.toProperty(k)] = v;
      }
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, "get", {
    value: function(k) {
      return this[$traceurRuntime.toProperty(dataKye)][$traceurRuntime.toProperty(k)];
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, "id", {
    get: function() {
      return this.get("id");
    },
    configurable: true,
    enumerable: true
  }), Object.defineProperty($__2, "json", {
    get: function() {
      var data = JSON.parse(JSON.stringify(this[$traceurRuntime.toProperty(dataKye)]));
      data.id = this.get("id");
      data.alive = this.get("alive");
      return data;
    },
    configurable: true,
    enumerable: true
  }), Object.defineProperty($__2, "loadEvents", {
    value: function(events) {
      var $__0 = this;
      if (this[$traceurRuntime.toProperty(isLoadEvents)])
        return;
      var set = this.set.bind(this);
      events.forEach((function(event) {
        $__0[$traceurRuntime.toProperty(when)](event, set);
      }));
      this[$traceurRuntime.toProperty(isLoadEvents)] = true;
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, "loadSnap", {
    value: function(snap) {
      if (this[$traceurRuntime.toProperty(isLoadEvents)])
        return;
      this[$traceurRuntime.toProperty(set)](snap);
      this[$traceurRuntime.toProperty(isLoadEvents)] = true;
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, when, {
    value: function(event, set) {
      if (event.name === "remove") {
        set("alive", false);
      } else if (event.name === "create") {
        this[$traceurRuntime.toProperty(dataKye)] === event.data;
      }
      if (this[$traceurRuntime.toProperty(otherWhen)])
        this[$traceurRuntime.toProperty(otherWhen)](event, set);
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, apply, {
    value: function(name, data) {
      var caller = arguments[2] !== (void 0) ? arguments[2] : {};
      var contextId = arguments[3] !== (void 0) ? arguments[3] : null;
      caller = caller || {};
      if (this.get("alive")) {
        var event = new Event(name, {
          callerId: caller.id,
          callerType: caller.typeName,
          targetId: this.get("id"),
          targetType: this.typeName,
          data: data
        }, contextId);
        this[$traceurRuntime.toProperty(when)](event, this[$traceurRuntime.toProperty(set)].bind(this));
        this.uncommittedEvents.push(event.json);
        this.emit("apply", this);
      }
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, create, {
    value: function(data) {
      this.create(data, this[$traceurRuntime.toProperty(getDI)]());
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, "remove", {
    value: function() {
      this[$traceurRuntime.toProperty(apply)]("remove");
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, "create", {
    value: function(data, di) {
      di.apply("create", data);
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, getDI, {
    value: function() {
      var data = arguments[0] !== (void 0) ? arguments[0] : {};
      var caller = arguments[1] !== (void 0) ? arguments[1] : {};
      var contextId = arguments[2] !== (void 0) ? arguments[2] : null;
      var $__0 = this;
      return {
        apply: (function(eventName, data, cxt) {
          $__0[$traceurRuntime.toProperty(apply)](eventName, data, caller, cxt || contextId);
        }),
        listen: (function(eventName, handleName, cxt, onlyContext) {
          if (cxt === true) {
            onlyContext = cxt;
            cxt = contentId;
          }
          $__0.emit('listen', $__0, eventName, handleName, cxt || contextId, onlyContext);
        }),
        call: (function(actorId, commandName, data, cxt) {
          $__0.emit("call", actorId, commandName, data, $__0, cxt || contextId);
        })
      };
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), Object.defineProperty($__2, "call", {
    value: function(commandName) {
      var data = arguments[1] !== (void 0) ? arguments[1] : {};
      var caller = arguments[2] !== (void 0) ? arguments[2] : {};
      var contextId = arguments[3] !== (void 0) ? arguments[3] : null;
      this[$traceurRuntime.toProperty(commandName)](data, this[$traceurRuntime.toProperty(getDI)](data, caller, contextId));
    },
    configurable: true,
    enumerable: true,
    writable: true
  }), $__2), {extend: function(options) {
      var typeName = options.typeName;
      var methods = options.methods || {};
      var Type = function Type() {
        $traceurRuntime.defaultSuperCall(this, $Type.prototype, arguments);
      };
      var $Type = Type;
      ($traceurRuntime.createClass)(Type, {}, {}, $Actor);
      if (methods.create) {
        var createFun = methods.create;
        delete methods.create;
        Object.defineProperty(Type, constructor, {value: createFun});
      }
      Object.defineProperty(Type, "typeName", {get: function() {
          return typeName;
        }});
      Object.defineProperty(Type.prototype, "typeName", {get: function() {
          return typeName;
        }});
      Object.defineProperty(Type.prototype, otherWhen, {value: options.when});
      for (var k in methods)
        if (!$traceurRuntime.isSymbolString(k)) {
          Object.defineProperty(Type.prototype, k, {value: methods[$traceurRuntime.toProperty(k)]});
        }
      return Type;
    }}, EventEmitter);
  module.exports = Actor;
  return {};
});
System.get("../../lib/Actor" + '');
