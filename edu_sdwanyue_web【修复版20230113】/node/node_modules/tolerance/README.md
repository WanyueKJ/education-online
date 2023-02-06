**The world needs more TOLERANCE!!!**

# Introduction

[![Build Status](https://secure.travis-ci.org/adrai/tolerance.png)](http://travis-ci.org/adrai/tolerance)

Tolerance is a node.js module that wraps a function with retry functionality by passing a timeout. So the function will be more tolerant.

You can use it for example if you want to connect to a database that is not running immediately...

# Installation

    $ npm install tolerance

# Usage

	var tolerate = require('tolerance');

	tolerate(
		function(callback) {
			db.connect(callback);
		},
		1500,
		function(err, res) {
			// If the db comes up after 800 ms
			// this callback will be called as the connection
			// would have been established immediately.
			//
			// If the db does not come up within the 1500 ms
			// this callback will be called with the appropriate error.
			//
			// In the background 'tolerance' will retry to connect to the db.
		}
	);


## Use own retry indication

	var tolerate = require('tolerance');

	tolerate(
		function(callback) {
			db.connect(callback);
		},
		1500,
		function(err) { // should return true if you want to trigger a retry...
			if (err === 'not reached') {
				return true;
			} else {
				return false;
			}
		},
		function(err, res) {
			// If the db comes up after 800 ms
			// this callback will be called as the connection
			// would have been established immediately.
			//
			// If the db does not come up within the 1500 ms
			// this callback will be called with the appropriate error.
			//
			// In the background 'tolerance' will retry to connect to the db.
		}
	);

# License

Copyright (c) 2013 Adriano Raiano

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
