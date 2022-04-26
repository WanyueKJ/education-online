//

var jsondate = require('./jsondate')
  , assert = require('assert')
  
var date1 = new Date()
var date2 = new Date()

var o = {
  date: date1
, deep: {
    date: date2
  }
}

var s = JSON.stringify(o)
console.log(s)
var p = JSON.parse(s)
assert.deepEqual(p.date, o.date.toISOString())
p = jsondate.parse(s)
assert.deepEqual(p.date, o.date)
jsondate()
p = JSON.parse(s)
assert.deepEqual(p.date, o.date)
