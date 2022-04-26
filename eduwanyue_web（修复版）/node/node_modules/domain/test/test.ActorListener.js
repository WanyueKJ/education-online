var ActorListener = require("../lib/ActorListener");


var Actor = require("../lib/Actor");

var should = require("should");

var Order = Actor.extend({
    typeName: "Order",
    methods: {
        change(name, di) {
            di.apply("change", name);
        }
    },
    when: function (event, set) {

        if (event.name === "change") {
            set("name", event.data.data);
        }
    }
});

var order = new Order();

describe("ActorListener", function () {

    var listener;

    it("#new", function () {


        listener = new ActorListener({
            "Order": {
                get: function* () {
                    return order;
                }
            }
        });

    })

    it("#listen", function () {
        listener.listen(order, "test", "change");
        var rs = listener.findListener("test");

        rs.length.should.eql(1);
        rs[0].actorId.should.eql(order.id);
        rs[0].actorType.should.eql("Order");
        rs[0].handleMethodName.should.eql("change");
    })

    it("#emit", function (done) {


        should.not.exist(order.get("name"));

        listener.emit({name: "test", data: "leo"});
        setTimeout(()=>(order.get("name").data.should.eql("leo"), done()), 10);
    })

})