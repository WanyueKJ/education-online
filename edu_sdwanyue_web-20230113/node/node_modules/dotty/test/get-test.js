var dotty = require("../lib/index"),
    vows = require("vows"),
    assert = require("assert");

vows.describe("get").addBatch({
  "A simple path": {
    "as a string": {
      topic: dotty.get({"a": "b"}, "a"),
      "should return the correct value": function(res) {
        assert.equal(res, "b");
      },
    },
    "as an array": {
      topic: dotty.get({"a": "b"}, ["a"]),
      "should return the correct value": function(res) {
        assert.equal(res, "b");
      },
    },
  },
  "A two-level path": {
    "as a string": {
      topic: dotty.get({"a": {"b": "c"}}, "a.b"),
      "should return the correct value": function(res) {
        assert.equal(res, "c");
      },
    },
    "as an array": {
      topic: dotty.get({"a": {"b": "c"}}, ["a", "b"]),
      "should return the correct value": function(res) {
        assert.equal(res, "c");
      },
    },
  },
  "An unresolved path": {
    "as a string": {
      topic: dotty.get({"a": {"b": "c"}}, "a.x"),
      "should return undefined": function(res) {
        assert.isUndefined(res);
      },
    },
    "as an array": {
      topic: dotty.get({"a": {"b": "c"}}, ["a", "x"]),
      "should return undefined": function(res) {
        assert.isUndefined(res);
      },
    },
  },
  "A property which is literally undefined, but with a resolved path": {
    "as a string": {
      topic: dotty.get({"a": {"b": undefined}}, "a.b"),
      "should return undefined": function(res) {
        assert.isUndefined(res);
      },
    },
    "as an array": {
      topic: dotty.get({"a": {"b": undefined}}, ["a", "b"]),
      "should return undefined": function(res) {
        assert.isUndefined(res);
      },
    },
  },
}).export(module);
