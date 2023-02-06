System.register("test/test.Actor", [], function() {
  "use strict";
  var __moduleName = "test/test.Actor";
  require("traceur");
  var should = require("should");
  var Actor = require("../lib/Actor");
  describe("Actor", function() {
    var actor;
    it("#new", function() {
      actor = new Actor({
        name: "leo",
        book: "xxx"
      });
      should.exist(actor.get("id"));
      actor.get("name").should.eql("leo");
      actor.get("book").should.eql("xxx");
      should.exist(actor.get("id"));
      actor.get("alive").should.eql(true);
      should.not.exist(actor.set);
    });
    it("#json", function() {
      var data = actor.json;
      data.name.should.eql("leo");
      data.book.should.eql("xxx");
      should.exist(data.id);
      should.exist(data.alive);
    });
    it("#apply", function() {
      var User = Actor.extend({
        typeName: "User",
        methods: {changeName: function(name, service) {
            service.apply("changeName", name);
          }}
      });
      var caller = new User();
      var actor = new User();
      actor.on("apply", function() {
        this.uncommittedEvents.length.should.eql(1);
        var event = this.uncommittedEvents[0];
        should.exist(event.id);
        should.exist(event.time);
        event.name.should.eql("changeName");
        var data = event.data;
        data.callerId.should.eql(caller.get("id"));
        data.callerType.should.eql("User");
        data.targetId.should.eql(actor.get("id"));
        data.targetType.should.eql("User");
        data.data.should.eql("leo");
      });
      actor.call("changeName", "leo", caller);
    });
    it("#listen", function(done) {
      var User = Actor.extend({
        typeName: "User",
        methods: {
          changeName: function(name, service) {
            service.listen("change", "finishChange");
          },
          finishChange: function(data, service) {
            done();
          }
        }
      });
      var user = new User();
      user.on("listen", function(event, methodname) {
        this.call("finishChange", methodname);
      });
      user.call("changeName");
    });
    it("#when", function() {
      var User = Actor.extend({
        typeName: "People",
        methods: {changeName: function(name, service) {
            service.apply("changeName", name);
          }},
        when: function(event, set) {
          if (event.name === "changeName") {
            set("name", event.data.data);
          }
        }
      });
      var user = new User();
      user.call("changeName", "leo");
      user.get("name").should.eql("leo");
    });
  });
  return {};
});
System.get("test/test.Actor" + '');
