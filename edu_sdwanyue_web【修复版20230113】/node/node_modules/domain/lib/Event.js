var uid = require("shortid");

var dataKey = Symbol("dataKey");
var name = Symbol("name");
var id = Symbol("id");
var contextId = Symbol("contextId");
var time = Symbol("time");

class Event{

    /**
     * @param data {targetId,targetType,sourceId,sourceType}
     */
    constructor(_name = "",data = {},contextId=null){
        this[name] = _name;
        this[dataKey] = data;
        this[id] = uid();
        this[time] = Date.now();
        this[contextId] = contextId;
    }

    get id(){
        return this[id];
    }

    get time(){
        return this[time];
    }

    get name(){
        return this[name];
    }

    get contextId(){
        return this[contextId];
    }

    get data(){
        return JSON.parse(JSON.stringify(this[dataKey]));
    }

    get json(){
        return {
            data:this.data,
            id:this.id,
            name:this.name,
            time:this.time,
            contextId:this.contextId
        }
    }

    static reborn(json){
        var event = new this();
        event[time] = json.time;
        event[dataKey] = json.data;
        event[name] = json.name;
        event[id] = json.id;
        event[contextId] = json.contextId;
        return event;
    }

}

module.exports = Event;