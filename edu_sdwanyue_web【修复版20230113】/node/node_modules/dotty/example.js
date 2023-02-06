var dotty = require("./lib/index");

var object = {
  a: {
    b: {
      x: "y",
    },
    c: {
      x: "z",
    },
  },
};

console.log(dotty.exists(object, "a.b.x")); // true
console.log(dotty.exists(object, ["a", "b", "x"])); // true
console.log(dotty.exists(object, "a.b.z")); // false
console.log(dotty.exists(object, ["a", "b", "z"])); // false

console.log(dotty.get(object, "a.b.x")); // "y"
console.log(dotty.get(object, ["a", "b", "x"])); // "y"
console.log(dotty.get(object, "a.b.z")); // undefined
console.log(dotty.get(object, ["a", "b", "z"])); // undefine

dotty.put(object, "a.b.hello", "hi");
dotty.put(object, ["a", "c", "yo"], "sup");

console.log(dotty.search(object, "a.b.*"));
console.log(dotty.search(object, ["a", "b", "*"]));
console.log(dotty.search(object, "a.*.x"));
console.log(dotty.search(object, ["a", "*", "x"]));
console.log(dotty.search(object, ["a", "*", /..+/]));

console.log(dotty.remove(object, "a.b.x"));
console.log(dotty.remove(object, "a.b.y"));

console.log(dotty.deepKeys(object));
console.log(dotty.deepKeys(object).map(function(e) { return e.join("."); }));

console.log(object);
