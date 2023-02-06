var thunkify = require("thunkify");
var Emitter = require("events").EventEmitter;
var cache = Symbol("cache");


class Repository extends Emitter{

    constructor(Actor, eventstore) {
        this.Actor = Actor;
        this.eventstore = eventstore;
        this.getFromSnapShot = thunkify(this.eventstore.getFromSnapshot).bind(this.eventstore);
        this.createSnapshot = thunkify(this.eventstore.createSnapshot).bind(this.eventstore);
        this[cache] = {}
    }


    *create(data) {
        let actor = new this.Actor(data,true);
        this[cache][actor.id] = actor;
        this.emit("create",actor);
        return actor;
    }


    clear(id) {
        delete this[cache][id];
    }

    getFromCache(id) {
        return this[cache][id];
    }

    *get(id) {

        let actor;

        if (actor = this.getFromCache(id)) {

            return actor;
        }

        let result = yield this.getFromSnapShot(id);
        if (actor = this.getFromCache(id)) {
            return actor;
        }

        let snapshot = result[0];
        let stream = result[1];

        let snap = snapshot ? snapshot.data : {};
        let history = stream.events;
        actor = new this.Actor();

        // mix aggregate object.
        actor.loadSnap(snap);

        var historyv = [];

        history.forEach(function (e) {
            historyv.push(e.payload);
        })

        actor.loadEvents(historyv);

        this[cache][actor.id] = actor;

        this.emit("reborn",actor);
        return actor;

    }


}

module.exports = Repository;