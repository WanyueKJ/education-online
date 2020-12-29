// MIT licenced

var parseJSON = JSON.parse
var ISO = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/

module.exports = exports = function() {
  JSON.parse = exports.parse
}

exports.parse = function(s, f) {
  return parseJSON(s, f ? f : function(key, value) {
    return typeof value === 'string'
      ? ISO.exec(value) && new Date(value) || value
      : value
  })
}