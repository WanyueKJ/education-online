var dotty = require("../lib/index"),
    vows = require("vows"),
    assert = require("assert");

vows.describe("search").addBatch({
  "A simple path": {
    "as a string": {
      topic: dotty.search({"a": "b"}, "a"),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return one value": function(res) {
        assert.equal(res.length, 1);
      },
      "should return the correct value": function(res) {
        assert.equal(res[0], "b");
      },
    },
    "as an array": {
      topic: dotty.search({"a": "b"}, ["a"]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return one value": function(res) {
        assert.equal(res.length, 1);
      },
      "should return the correct value": function(res) {
        assert.equal(res[0], "b");
      },
    },
    "as an array with a regex": {
      topic: dotty.search({"a": "b"}, [/a/]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return one value": function(res) {
        assert.equal(res.length, 1);
      },
      "should return the correct value": function(res) {
        assert.equal(res[0], "b");
      },
    },
  },
  "A two-level path": {
    "as a string": {
      topic: dotty.search({"a": {"b": "c"}}, "a.b"),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return one value": function(res) {
        assert.equal(res.length, 1);
      },
      "should return the correct value": function(res) {
        assert.equal(res[0], "c");
      },
    },
    "as an array": {
      topic: dotty.search({"a": {"b": "c"}}, ["a", "b"]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return one value": function(res) {
        assert.equal(res.length, 1);
      },
      "should return the correct value": function(res) {
        assert.equal(res[0], "c");
      },
    },
    "as an array with regexes": {
      topic: dotty.search({"a": {"b": "c"}}, [/a/, /b/]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return one value": function(res) {
        assert.equal(res.length, 1);
      },
      "should return the correct value": function(res) {
        assert.equal(res[0], "c");
      },
    },
  },
  "A two-level path matching two values": {
    "as a string": {
      topic: dotty.search({"a": {"b": "c", "d": "e"}}, "a.*"),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return two values": function(res) {
        assert.equal(res.length, 2);
      },
      "should return the correct value": function(res) {
        assert.equal(res[0], "c");
        assert.equal(res[1], "e");
      },
    },
    "as an array": {
      topic: dotty.search({"a": {"b": "c", "d": "e"}}, ["a", "*"]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return two values": function(res) {
        assert.equal(res.length, 2);
      },
      "should return the correct values": function(res) {
        assert.equal(res[0], "c");
        assert.equal(res[1], "e");
      },
    },
    "as an array with regexes": {
      topic: dotty.search({"a": {"b": "c", "d": "e"}}, [/a/, /.*/]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return two values": function(res) {
        assert.equal(res.length, 2);
      },
      "should return the correct values": function(res) {
        assert.equal(res[0], "c");
        assert.equal(res[1], "e");
      },
    },
  },
  "A three-level mixed path matching two values": {
    "as a string": {
      topic: dotty.search({"a": {"b": {"x": "y"}, "c": {"x": "z"}}}, "a.*.x"),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return two values": function(res) {
        assert.equal(res.length, 2);
      },
      "should return the correct value": function(res) {
        assert.equal(res[0], "y");
        assert.equal(res[1], "z");
      },
    },
    "as an array": {
      topic: dotty.search({"a": {"b": {"x": "y"}, "c": {"x": "z"}}}, ["a", "*", "x"]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return two values": function(res) {
        assert.equal(res.length, 2);
      },
      "should return the correct values": function(res) {
        assert.equal(res[0], "y");
        assert.equal(res[1], "z");
      },
    },
    "as an array with regexes": {
      topic: dotty.search({"a": {"b": {"x": "y"}, "c": {"x": "z"}}}, [/a/, /.*/, /x/]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return two values": function(res) {
        assert.equal(res.length, 2);
      },
      "should return the correct values": function(res) {
        assert.equal(res[0], "y");
        assert.equal(res[1], "z");
      },
    },
  },
  "An unresolved path": {
    "as a string": {
      topic: dotty.search({"a": {"b": "c"}}, "a.x"),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return zero values": function(res) {
        assert.equal(res.length, 0);
      },
    },
    "as an array": {
      topic: dotty.search({"a": {"b": "c"}}, ["a", "x"]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return zero values": function(res) {
        assert.equal(res.length, 0);
      },
    },
    "as an array with regexes": {
      topic: dotty.search({"a": {"b": "c"}}, [/a/, /x/]),
      "should return an array": function(res) {
        assert.isArray(res);
      },
      "should return zero values": function(res) {
        assert.equal(res.length, 0);
      },
    },
  },
}).export(module);
