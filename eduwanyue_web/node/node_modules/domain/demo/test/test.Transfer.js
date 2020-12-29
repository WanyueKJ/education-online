var should = require("should")
    , Transfer = require("../Transfer")
    , User = require("../User")
    , Domain = require("../../lib/Domain")
    , domain = new Domain();

domain.register("User", User);
domain.register("Transfer", Transfer);

var uid1, uid2;

domain.repos.User.create({}, function (err, u) {
    uid1 = u.get("id");
    u.recharge({money: 10, id: "t1"});
    u.finish({id: "t1"});
})

domain.repos.User.create({}, function (err, u) {
    uid2 = u.get("id");
})


describe("Transfer", function () {

    var transfer;

    it("#new", function (done) {

        domain.repos.Transfer.create({}, function (err, t) {
            transfer = t;
            done()
        })

    })

    it("#start event", function () {
        transfer.get("start").should.eql(false);
        transfer.transfer({user1_id: uid1, user2_id: uid2, money: 5});
        transfer.get("start").should.eql(true);
    })

    it("#listener deduct event", function () {
        transfer.isCompleted().should.eql(false);
        transfer.get("deducted").should.eql(false);
        transfer.emit("deduct");
        transfer.get("deducted").should.eql(true);
        transfer.isCompleted().should.eql(false);

    })

    it("#listener recharge event", function () {
        transfer.isCompleted().should.eql(false);
        transfer.get("recharged").should.eql(false);
        transfer.emit("recharge");
        transfer.get("recharged").should.eql(true);
        transfer.isCompleted().should.eql(true);
    })


})



