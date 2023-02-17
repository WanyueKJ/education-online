var retry = require('retry');

module.exports = function(func, timeout, retryfn, callback) {

  if (!callback) {
    callback = retryfn;
    retryfn = null;
  }

  var start = new Date();

  func(function(err) {

    if (!err && (!retryfn || !retryfn.apply(this, arguments))) {
      return callback.apply(this, arguments);
    } else {

      var funcDuration = (new Date()).getTime() - start.getTime();

      if (funcDuration <= 1) {
        funcDuration = 1;
      }

      if (timeout < funcDuration) {
        return callback(err);
      }

      var retries = timeout / funcDuration - 1;
      if (retries < 0 || !isFinite(retries)) {
        retries = 0;
      }

      var operation = retry.operation({
        retries: retries,
        factor: 1,
        minTimeout: funcDuration / 2,
        maxTimeout: timeout,
        randomize: false
      });

      operation.attempt(function(currentAttempt) {

        func(function(err) {

          var diff = (new Date()).getTime() - start.getTime();

          if (diff >= timeout) {
            return callback.apply(this, arguments);
          }

          if (err && retryfn && retryfn.apply(this, arguments)) {
            if (operation.retry(err)) {
              return;
            }
          } else if (!retryfn) {
            if (operation.retry(err)) {
              return;
            }
          }

          callback.apply(this, arguments);
        });

      });
    }

  });

};