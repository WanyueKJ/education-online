// init
var Transfer = require("./Transfer")
    ,User = require("./User")
    ,Domain  = require("../lib/Domain")
    ,uid = require("shortid")
    ,domain = new Domain();

domain.register("User",User);
domain.register("Transfer",Transfer);

exports.createUser = function(cb){
    domain.repos.User.create({},function(err,u){
        cb(null, u.get("id"));
    })
}

exports.getDomain = function(){
    return domain;
}

exports.recharge = function(id,money,cb){
    domain.repos.User.get(id,function(err,user){
        if(user){
            var tid = uid();
            user.recharge(money,tid);
            user.finish(tid);
            cb();
        }else{
            cb(new Error("no user"));
        }
    })
}

exports.deduct = function(id,money,cb){
    domain.repos.User.get(id,function(err,user){
        if(user){
            var tid = uid();
            user.deduct(money,tid);
            user.finish(tid);
            cb();
        }else{
            cb(new Error("no user"));
        }
    })
}

exports.transfer = function(fromId,toId,money){
    domain.repos.Transfer.create(null,function(err,transfer){
        transfer.transfer(fromId,toId,money);
    })
}