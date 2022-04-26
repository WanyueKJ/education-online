module.exports = function(id) {
  var parent = module.parent;
  for (; parent; parent = parent.parent) {
    try {
      return parent.require(id);
    } catch(ex) {}
  }
  throw new Error("Cannot find module '" + id + "' from parent")
}
