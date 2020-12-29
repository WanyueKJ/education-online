System.register("../../lib/EventBus", [], function() {
  "use strict";
  var __moduleName = "../../lib/EventBus";
  var Emiter = require("events").EventEmitter,
      Event = require("./Event"),
      co = require("co");
  var EventBus = function EventBus(eventstore, repos) {
    var $__0 = this;
    this.repos = repos;
    this.es = eventstore;
    this.es.useEventPublisher((function(evt, cb) {
      var event = Event.reborn(evt);
      $__0.emit(evt.data.targetType + "." + evt.data.targetId + ":" + evt.name, event);
      $__0.emit(evt.data.targetType + ":" + evt.name, event);
      $__0.emit("." + evt.data.targetId + ":" + evt.name, event);
      $__0.emit(":" + evt.name, event);
      $__0.emit(evt.data.targetType, event);
      if (evt.contextId) {
        $__0.emit(evt.data.targetType + "." + evt.data.targetId + ":" + evt.name + "&" + evt.contextId, event);
        $__0.emit(evt.data.targetType + ":" + evt.name + "&" + evt.contextId, event);
        $__0.emit("." + evt.data.targetId + ":" + evt.name + "&" + evt.contextId, event);
        $__0.emit(":" + evt.name + "&" + evt.contextId, event);
        $__0.emit(evt.data.targetType + "&" + evt.contextId, event);
      }
    }));
    this.es.init();
  };
  ($traceurRuntime.createClass)(EventBus, {publish: function(actor) {
      this.es.getEventStream(actor.id, function(err, stream) {
        if (actor.uncommittedEvents.length) {
          stream.addEvents(actor.uncommittedEvents);
          stream.commit();
          actor.uncommittedEvents = [];
        }
      });
    }}, {}, Emiter);
  module.exports = EventBus;
  return {};
});
System.get("../../lib/EventBus" + '');
