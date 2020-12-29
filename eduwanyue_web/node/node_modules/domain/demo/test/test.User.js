var User = require("../User");
var should = require("should");
var FreezeMoney = require("../FreezeMoney");

describe("User",function(){

    var user;

    it("#new",function(){
        user = new User();
        user.get("money").should.eql(0);
        user.get("freezeMoneyList").should.eql([]);
    })

    it("#recharge",function(){
        user.recharge({money:10,id:"t01"});
        user.get("money").should.eql(0);
        user.finish({id:"t01"});
        user.get("money").should.eql(10);
        user.get("freezeMoneyList").should.eql([]);
    })

    it("#deduct",function(){
        user.deduct({money:3,id:"t02"});
        user.get("money").should.eql(7);
        user.finish({id:"t02"});
        user.get("money").should.eql(7);
        user.get("freezeMoneyList").should.eql([]);
    })

    it("#cancel",function(){
        user.deduct({money:3,id:"t03"});
        user.get("money").should.eql(4);
        user.cancel({id:"t03"});
        user.get("money").should.eql(7);
        user.get("freezeMoneyList").should.eql([]);

        user.recharge({money:3,id:"t04"});
        user.get("money").should.eql(7);
        user.cancel({id:"t04"})
        user.get("money").should.eql(7);
        user.get("freezeMoneyList").should.eql([]);
    })

    it("#loadSnap",function(){
        // first user
        var user1 = new User();
        user1.recharge({money:12,id:"t01"});
        user1.finish({id:"t01"});
        user1.recharge({money:5,id:"t02"});

        var data = user1.json();

        // second user
        var user2 = new User();
        user2.loadSnap(data);

        user2.get("money").should.eql(12);
        user2.get("freezeMoneyList")[0].should.be.an.instanceof(FreezeMoney);

    })
})