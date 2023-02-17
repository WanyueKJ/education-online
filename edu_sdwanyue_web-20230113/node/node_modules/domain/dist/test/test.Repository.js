System.register("../../test/test.Repository", [], function() {
  "use strict";
  var __moduleName = "../../test/test.Repository";
  require("traceur");
  var Repository = require("../lib/Repository");
  var Actor = require("../lib/Actor");
  var ES = require("eventstore");
  var co = require("co");
  var should = require("should");
  var thunkify = require("thunkify");
  describe("Repository", function() {
    var User = Actor.extend({typeName: "People"}),
        es = new ES,
        repos;
    var uid;
    it("#new", function() {
      repos = new Repository(User, es);
    });
    it("#create", function(done) {
      co($traceurRuntime.initGeneratorFunction(function $__0() {
        var user;
        return $traceurRuntime.createGeneratorInstance(function($ctx) {
          while (true)
            switch ($ctx.state) {
              case 0:
                $ctx.state = 2;
                return repos.create({name: "leo"});
              case 2:
                user = $ctx.sent;
                $ctx.state = 4;
                break;
              case 4:
                uid = user.id;
                user.get("name").should.eql("leo");
                done();
                $ctx.state = -2;
                break;
              default:
                return $ctx.end();
            }
        }, $__0, this);
      }))();
    });
  });
  return {};
});
System.get("../../test/test.Repository" + '');
