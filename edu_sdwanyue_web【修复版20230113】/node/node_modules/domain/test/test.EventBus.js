require("traceur");
var Repository = require("../lib/Repository");
var Actor = require("../lib/Actor");
var ES = require("eventstore");
var es = new ES();
var EventBus = require("../lib/EventBus");
var co = require("co");
var should = require("should");

//es.init(function (err) {
//});

describe("EventBus", function () {

    let bus, actor, repos = {}

    it("#new", function () {
        bus = new EventBus(es,repos);
    })

    it("#init", function (done) {
        var User = Actor.extend({
            typeName: "User",
            methods: {
                changeName(name, di) {
                    di.apply("changeName", name);
                },
                finish(name,di){
                    di.apply("finish");
                }

            },
            when(event,set){
                if(event.name === "finish"){
                    set("finish","ok");
                }
            }
        });

        var uid;

        repos[User.typeName] = new Repository(User, es);

        co(function *() {
            actor = yield repos[User.typeName].create({name: "leo"});
            done();
        })()
    })

    it("#publsh", function () {

        actor.call("changeName", "bright");
        actor.uncommittedEvents.length.should.eql(2);
        bus.publish(actor);
        actor.uncommittedEvents.should.eql([]);

    })

})