System.register("../../lib/ActorListener", [], function() {
  "use strict";
  var __moduleName = "../../lib/ActorListener";
  var co = require("co");
  var ActorListener = function ActorListener(actorRepos) {
    this.repos = {};
    this.actorRepos = actorRepos;
  };
  ($traceurRuntime.createClass)(ActorListener, {
    listen: function(actor, eventName, handleMethodName, cxt, onlyContext) {
      var actorId = actor.id,
          actorType = actor.typeName,
          repo,
          listener = {
            actorId: actorId,
            actorType: actorType,
            handleMethodName: handleMethodName,
            cxt: cxt,
            onlyContext: onlyContext
          };
      repo = (repo = this.repos[$traceurRuntime.toProperty(eventName)]) ? this.repos[$traceurRuntime.toProperty(eventName)] : (this.repos[$traceurRuntime.toProperty(eventName)] = []);
      repo.push(listener);
    },
    findListener: function(eventName) {
      return this.repos[$traceurRuntime.toProperty(eventName)] || [];
    },
    emit: function(event) {
      var self = this;
      co($traceurRuntime.initGeneratorFunction(function $__1() {
        var list,
            i,
            len,
            listener,
            actorRepo,
            actor;
        return $traceurRuntime.createGeneratorInstance(function($ctx) {
          while (true)
            switch ($ctx.state) {
              case 0:
                list = self.findListener(event.name);
                $ctx.state = 13;
                break;
              case 13:
                i = 0, len = list.length;
                $ctx.state = 11;
                break;
              case 11:
                $ctx.state = (i < len) ? 5 : -2;
                break;
              case 8:
                i++;
                $ctx.state = 11;
                break;
              case 5:
                listener = list[$traceurRuntime.toProperty(i)];
                actorRepo = self.actorRepos[$traceurRuntime.toProperty(listener.actorType)];
                $ctx.state = 6;
                break;
              case 6:
                $ctx.state = 2;
                return actorRepo.get(listener.actorId);
              case 2:
                actor = $ctx.sent;
                $ctx.state = 4;
                break;
              case 4:
                if (actor) {
                  actor.call(listener.handleMethodName, event, null, listener.contextId);
                }
                $ctx.state = 8;
                break;
              default:
                return $ctx.end();
            }
        }, $__1, this);
      }))();
    }
  }, {});
  module.exports = ActorListener;
  return {};
});
System.get("../../lib/ActorListener" + '');
