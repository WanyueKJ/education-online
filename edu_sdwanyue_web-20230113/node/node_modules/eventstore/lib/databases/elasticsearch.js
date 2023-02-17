var util = require('util'),
  Store = require('../base'),
  _ = require('lodash'),
  uuid = require('uuid'),
  elasticsearch = Store.use('elasticsearch'),
  elasticsearchVersion = Store.use('elasticsearch/package.json').version,
  debug = require('debug')('eventstore:store:elasticsearch');

function Elastic(options) {
  options = options || {};

  Store.call(this, options);

  var defaults = {
    host: 'localhost:9200',
    indexName: 'eventstore',
    eventsTypeName: 'events',
    snapshotsTypeName: 'snapshots',
    log: 'warning',
    maxSearchResults: 10000
  };

  _.defaults(options, defaults);

  var defaultOpt = {
    auto_reconnect: false,
    ssl: false
  };

  options.options = options.options || {};

  _.defaults(options.options, defaultOpt);

  this.options = options;
}

util.inherits(Elastic, Store);

_.extend(Elastic.prototype, {

  connect: function (callback) {
    var options = this.options;
    if (options.client) {
  		this.client = options.client;
  	} else {
  		this.client = new elasticsearch.Client({host: options.host, log: options.log});
  	}
    this.emit('connect');
    if (callback) callback(null);
  },

  disconnect: function (callback) {
    this.client = null;
    this.emit('disconnect');
    if (callback) callback(null);
  },

  clear: function (callback) {
    var self = this;
    var options = this.options;
    this.client.indices.exists({index: options.indexName}, function (err, result) {
      if (result){
        self.client.indices.delete({index: options.indexName}, function (err) {
          if (callback) callback(err);
        });
      } else {
        if (callback) callback(err);
      }
    });
  },

  getNewId: function(callback) {
    callback(null, uuid.v4());
  },

  addEvents: function (events, callback) {
    var options = this.options;

    if (events.length === 0) {
      if (callback) callback(null);
      return;
    }

    var noAggId = false;
    var bulkMap = [];

    _.forEach(events, function (evt) {
      if (!evt.aggregateId) {
        noAggId = true;
      }
      evt.dispatched = false;
      bulkMap.push({create: {_index: options.indexName, _type: options.eventsTypeName, _id: evt.id}});
      bulkMap.push(evt);
    });

    if (noAggId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }
    this.client.bulk({body: bulkMap, refresh: true}, function(error, response){
      if (callback) callback(error);
    });
  },

  _buildQuery: function(type, find, sort, skip, limit) {
    var query = {
      index: this.options.indexName,
      type: type,
      defaultOperator: 'AND',
      from: (!skip ? 0 : skip),
      size: (!limit || limit === -1 ? this.options.maxSearchResults - (skip || 0) : limit)
    };
    if (find && find.length) query.q = find.join(' AND ');
    if (sort && sort.length) query.sort = sort;
    return query;
  },

  _search: function (type, find, sort, skip, limit, callback) {
    var options = this.options;
    var searchOptions = this._buildQuery(type, find, sort, skip, limit);

    this.client.search(searchOptions, function (error, response) {
      var dataList = [];
      if (response) {
        if (response.error) {
          var error = new Error(response.error.root_cause ? response.error.root_cause[0].reason : response.error);
          debug(error.message);
          return callback(error);
        }
        if (response.hits && response.hits.hits && response.hits.hits.length) {
          if (response.hits.hits.length >= options.maxSearchResults){
            var errMsg = 'reached maximum of ' + options.maxSearchResults + ' search results!';
            debug(errMsg);
            if (callback) callback(new Error(errMsg));
            return;
          }
          dataList = response.hits.hits.map(function (data) {
            data._source.commitStamp = new Date(data._source.commitStamp);
            return data._source;
          });
        }
      }

      callback(null, dataList);
    });
  },

  _searchEvents: function(find, skip, limit, callback) {
    this._search(this.options.eventsTypeName, find, ['commitStamp:asc', 'streamRevision:asc', 'commitSequence:asc'], skip, limit, callback);
  },

  _searchSnapshots: function(find, skip, limit, callback) {
    this._search(this.options.snapshotsTypeName, find, ['revision:desc', 'version:desc', 'commitStamp:desc'], skip, limit, callback);
  },

  getEvents: function (query, skip, limit, callback) {
    var findStatement = [];
    if (query.aggregate) findStatement.push('aggregate:' + query.aggregate);
    if (query.context) findStatement.push('context:' + query.context);
    if (query.aggregateId) findStatement.push('aggregateId:' + query.aggregateId);

    this._searchEvents(findStatement, skip, limit, callback);
  },

  getLastEvent: function (query, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = [ 'aggregateId:' + query.aggregateId ];
    if (query.aggregate) findStatement.push('aggregate:' + query.aggregate);
    if (query.context) findStatement.push('context:' + query.context);

    this._search(this.options.eventsTypeName, findStatement, ['commitStamp:desc', 'streamRevision:desc', 'commitSequence:desc'], 0, 1,  function(error, response){
      var event = response && response.length ? response[0] : null;
      if (callback) callback(null, event);
    });
  },

  getEventsSince: function (date, skip, limit, callback) {
    var findStatement = ['commitStamp:[' + date.toJSON() + ' TO *]'];

    this._searchEvents(findStatement, skip, limit, callback);
  },

  getEventsByRevision: function (query, revMin, revMax, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = [];
    if (revMax === -1) {
      findStatement.push('streamRevision:[' + revMin + ' TO *]');
    } else {
      findStatement.push('streamRevision:[' + revMin + ' TO ' + revMax + '}');
    }
    findStatement.push('aggregateId:' + query.aggregateId);
    if (query.aggregate) findStatement.push('aggregate:' + query.aggregate);
    if (query.context) findStatement.push('context:' + query.context);

    this._searchEvents(findStatement, null, null, callback);
  },

  getUndispatchedEvents: function (query, callback) {
    var findStatement = ['dispatched:false'];
    if (query && query.aggregate) findStatement.push('aggregate:' + query.aggregate);
    if (query && query.context) findStatement.push('context:' + query.context);
    if (query && query.aggregateId) findStatement.push('aggregateId:' + query.aggregateId);

    this._searchEvents(findStatement, null, null, callback);
  },

  setEventToDispatched: function (id, callback) {
    this.client.update({
      index: this.options.indexName,
      type: this.options.eventsTypeName,
      id: id,
      body: {
        doc: {
          dispatched: true
        }
      },
      refresh: true
    }, function (error, response) {
      if (callback) callback(error);
    });
  },

  addSnapshot: function(snap, callback) {
    if (!snap.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    this.client.create({
      index: this.options.indexName,
      type: this.options.snapshotsTypeName,
      id: snap.id,
      body: snap,
      refresh: true
    }, function (error, response) {
      if (callback) callback(error);
    });
  },

  cleanSnapshots: function (query, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = ['aggregateId:' + query.aggregateId];

    if (query.aggregate) findStatement.push('aggregate:' + query.aggregate);
    if (query.context) findStatement.push('context:' + query.context);

    var self = this;
    this._searchSnapshots(findStatement, this.options.maxSnapshotsCount, -1, function (error, response) {
      if (error) {
        return callback(error);
      }
      self._bulkDelete(self.options.snapshotsTypeName, response, callback);
    });
  },

  _bulkDelete: function (type, items, callback) {
    var index = this.options.indexName;
    var deleteStatements = items.map(function(item) {
      return {
        delete: {
          _index: index,
          _type: type,
          _id: item.id
        }
      };
    });
    this.client.bulk({
      body: deleteStatements
    }, function(error, response) {
      callback(error, response ? response.items.length : 0);
    });
  },

  getSnapshot: function (query, revMax, callback) {
    if (!query.aggregateId) {
      var errMsg = 'aggregateId not defined!';
      debug(errMsg);
      if (callback) callback(new Error(errMsg));
      return;
    }

    var findStatement = ['aggregateId:' + query.aggregateId];

    if (query.aggregate) findStatement.push('aggregate:' + query.aggregate);
    if (query.context) findStatement.push('context:' + query.context);
    if (revMax > -1) findStatement.push('revision:[* TO ' + revMax + ']');

    this._searchSnapshots(findStatement, 0, 1, function(error, response){
      var snap = response && response.length ? response[0] : null;
      if (callback) callback(null, snap);
    });
  }

});

module.exports = Elastic;
