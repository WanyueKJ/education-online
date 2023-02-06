require("traceur");
var Repository = require("../lib/Repository");
var Actor = require("../lib/Actor");
var ES = require("eventstore");
var co = require("co");
var should = require("should");
var thunkify = require("thunkify");


describe("Repository", function () {

    var User = Actor.extend({
        typeName:"People"
    }),es = new ES,repos;

    var uid;

    it("#new", function () {
       repos  = new Repository(User,es);
    })

    it("#create", function (done) {
        co(function *() {
            var user = yield repos.create({name:"leo"});
            uid = user.id;
            user.get("name").should.eql("leo");
            done();
        })()
    })

    //it("#get", function (done) {
    //    setTimeout(()=>{
    //        co(function* () {
    //            repos.clear(uid);
    //            var user = yield repos.get(uid);
    //            console.log(user.json);
    //            user.get("name").should.eql("leo");
    //            done();
    //        })()
    //    },100)
    //
    //})
})