var co = require("co");

class ActorListener {

    constructor(actorRepos) {

        this.repos = {}
        this.actorRepos = actorRepos;

    }

    listen(actor, eventName, handleMethodName, cxt, onlyContext) {

        let actorId = actor.id
            , actorType = actor.typeName
            , repo
            , listener = {actorId, actorType, handleMethodName, cxt, onlyContext};

        repo = (repo = this.repos[eventName]) ? this.repos[eventName] : (this.repos[eventName] = []);

        repo.push(listener);

    }

    findListener(eventName) {
        return this.repos[eventName] || [];
    }


    emit(event) {
        var self = this;
        co(function *() {
            var list = self.findListener(event.name);

            for (var i = 0, len = list.length; i < len; i++) {
                var listener = list[i];
                var actorRepo = self.actorRepos[listener.actorType];

                var actor = yield actorRepo.get(listener.actorId);
                if (actor) {
                    actor.call(listener.handleMethodName, event, null, listener.contextId);
                }
            }
        })()
    }

}


module.exports = ActorListener;