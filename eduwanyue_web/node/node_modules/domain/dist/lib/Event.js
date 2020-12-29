System.register("../../lib/Event", [], function() {
  "use strict";
  var __moduleName = "../../lib/Event";
  var uid = require("shortid");
  var dataKey = Symbol("dataKey");
  var name = Symbol("name");
  var id = Symbol("id");
  var contextId = Symbol("contextId");
  var time = Symbol("time");
  var Event = function Event() {
    var _name = arguments[0] !== (void 0) ? arguments[0] : "";
    var data = arguments[1] !== (void 0) ? arguments[1] : {};
    var contextId = arguments[2] !== (void 0) ? arguments[2] : null;
    this[$traceurRuntime.toProperty(name)] = _name;
    this[$traceurRuntime.toProperty(dataKey)] = data;
    this[$traceurRuntime.toProperty(id)] = uid();
    this[$traceurRuntime.toProperty(time)] = Date.now();
    this[$traceurRuntime.toProperty(contextId)] = contextId;
  };
  ($traceurRuntime.createClass)(Event, {
    get id() {
      return this[$traceurRuntime.toProperty(id)];
    },
    get time() {
      return this[$traceurRuntime.toProperty(time)];
    },
    get name() {
      return this[$traceurRuntime.toProperty(name)];
    },
    get contextId() {
      return this[$traceurRuntime.toProperty(contextId)];
    },
    get data() {
      return JSON.parse(JSON.stringify(this[$traceurRuntime.toProperty(dataKey)]));
    },
    get json() {
      return {
        data: this.data,
        id: this.id,
        name: this.name,
        time: this.time,
        contextId: this.contextId
      };
    }
  }, {reborn: function(json) {
      var event = new this();
      event[$traceurRuntime.toProperty(time)] = json.time;
      event[$traceurRuntime.toProperty(dataKey)] = json.data;
      event[$traceurRuntime.toProperty(name)] = json.name;
      event[$traceurRuntime.toProperty(id)] = json.id;
      event[$traceurRuntime.toProperty(contextId)] = json.contextId;
      return event;
    }});
  module.exports = Event;
  return {};
});
System.get("../../lib/Event" + '');
