var dotty = require("../lib/index"),
    vows = require("vows"),
    assert = require("assert");

vows.describe("put").addBatch({
  "A simple path": {
    "as a string": {
      topic: (function() { var x = {}; dotty.put(x, "a", "b"); return x; }()),
      "should set the correct value": function(res) {
        assert.equal(res.a, "b");
      },
    },
    "as an array": {
      topic: (function() { var x = {}; dotty.put(x, ["a"], "b"); return x; }()),
      "should set the correct value": function(res) {
        assert.equal(res.a, "b");
      },
    },
  },
  "A two-level path": {
    "as a string": {
      topic: (function() { var x = {}; dotty.put(x, "a.b", "c"); return x; }()),
      "should set the correct value": function(res) {
        assert.equal(res.a.b, "c");
      },
    },
    "as an array": {
      topic: (function() { var x = {}; dotty.put(x, ["a", "b"], "c"); return x; }()),
      "should set the correct value": function(res) {
        assert.equal(res.a.b, "c");
      },
    },
  },
  "An interrupted path": {
    "as a string": {
      topic: (function() { var x = {a: 1}; return dotty.put(x, "a.b", "c"); }()),
      "should return false": function(res) {
        assert.isFalse(res);
      },
    },
    "as an array": {
      topic: (function() { var x = {a: 1}; return dotty.put(x, ["a", "b"], "c"); }()),
      "should return false": function(res) {
        assert.isFalse(res);
      },
    },
  },
}).export(module);
