System.register("../../test/test.EventBus", [], function() {
  "use strict";
  var __moduleName = "../../test/test.EventBus";
  require("traceur");
  var Repository = require("../lib/Repository");
  var Actor = require("../lib/Actor");
  var ES = require("eventstore");
  var es = new ES();
  var EventBus = require("../lib/EventBus");
  var co = require("co");
  var should = require("should");
  describe("EventBus", function() {
    var bus,
        actor,
        repos = {};
    it("#new", function() {
      bus = new EventBus(es, repos);
    });
    it("#init", function(done) {
      var User = Actor.extend({
        typeName: "User",
        methods: {
          changeName: function(name, di) {
            di.apply("changeName", name);
          },
          finish: function(name, di) {
            di.apply("finish");
          }
        },
        when: function(event, set) {
          if (event.name === "finish") {
            set("finish", "ok");
          }
        }
      });
      var uid;
      repos[$traceurRuntime.toProperty(User.typeName)] = new Repository(User, es);
      co($traceurRuntime.initGeneratorFunction(function $__0() {
        return $traceurRuntime.createGeneratorInstance(function($ctx) {
          while (true)
            switch ($ctx.state) {
              case 0:
                $ctx.state = 2;
                return repos[$traceurRuntime.toProperty(User.typeName)].create({name: "leo"});
              case 2:
                actor = $ctx.sent;
                $ctx.state = 4;
                break;
              case 4:
                done();
                $ctx.state = -2;
                break;
              default:
                return $ctx.end();
            }
        }, $__0, this);
      }))();
    });
    it("#publsh", function() {
      actor.call("changeName", "bright");
      actor.uncommittedEvents.length.should.eql(2);
      bus.publish(actor);
      actor.uncommittedEvents.should.eql([]);
    });
  });
  return {};
});
System.get("../../test/test.EventBus" + '');
