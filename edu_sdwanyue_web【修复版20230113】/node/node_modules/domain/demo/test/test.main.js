var main = require("../main");
var should = require("should");

describe("main",function(){

    var uid01,uid02;

    it("#createUser",function(){
        main.createUser(function(err,userId){
            uid01 = userId;
            should.exist(userId);
        })
        main.createUser(function(err,userId){
            uid02 = userId;
            should.exist(userId);
        })
    })

    it("#recharge",function(done){
        main.recharge(uid01,100,function(err){
            should.not.exist(err);
            done()
        })
    })

    it("#deduct",function(done){
        main.deduct(uid01,50,function(err){
            should.not.exist(err);
            done()
        })
    })

    it("#transfer",function(done){
        main.transfer(uid01,uid02,25);
        done();
    })

    it("#test result",function(){
        var domain = main.getDomain();
        domain.repos.User.get(uid01,function(err,u1){
            u1.get("money").should.eql(25);
        })
        domain.repos.User.get(uid02,function(err,u1){
            u1.get("money").should.eql(25);
        })
    })
})