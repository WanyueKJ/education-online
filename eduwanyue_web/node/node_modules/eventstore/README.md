# Introduction

[![JS.ORG](https://img.shields.io/badge/js.org-eventstore-ffb400.svg?style=flat-square)](http://js.org)
[![travis](https://img.shields.io/travis/adrai/node-eventstore.svg)](https://travis-ci.org/adrai/node-eventstore) [![npm](https://img.shields.io/npm/v/eventstore.svg)](https://npmjs.org/package/eventstore)

The project goal is to provide an eventstore implementation for node.js:

- load and store events via EventStream object
- event dispatching to your publisher (optional)
- supported Dbs (inmemory, mongodb, redis, tingodb, elasticsearch, azuretable, dynamodb)
- snapshot support
- query your events

# Consumers

- [cqrs-domain](https://github.com/adrai/node-cqrs-domain)
- [cqrs](https://github.com/leogiese/cqrs)

# Installation

    npm install eventstore

# Usage

## Require the module and init the eventstore:
```javascript
var eventstore = require('eventstore');

var es = eventstore();
```

By default the eventstore will use an inmemory Storage.

### Logging

For logging and debugging you can use [debug](https://github.com/visionmedia/debug) by [TJ Holowaychuk](https://github.com/visionmedia)

simply run your process with

    DEBUG=eventstore* node app.js

## Provide implementation for storage

example with mongodb:

```javascript
var es = require('eventstore')({
  type: 'mongodb',
  host: 'localhost',                             // optional
  port: 27017,                                   // optional
  dbName: 'eventstore',                          // optional
  eventsCollectionName: 'events',                // optional
  snapshotsCollectionName: 'snapshots',          // optional
  transactionsCollectionName: 'transactions',    // optional
  timeout: 10000,                                // optional
  // emitStoreEvents: true                       // optional, by default no store events are emitted
  // maxSnapshotsCount: 3                        // optional, defaultly will keep all snapshots
  // authSource: 'authedicationDatabase'         // optional
  // username: 'technicalDbUser'                 // optional
  // password: 'secret'                          // optional
  // url: 'mongodb://user:pass@host:port/db?opts // optional
  // positionsCollectionName: 'positions'        // optional, defaultly wont keep position
});
```

example with redis:
```javascript
var es = require('eventstore')({
  type: 'redis',
  host: 'localhost',                          // optional
  port: 6379,                                 // optional
  db: 0,                                      // optional
  prefix: 'eventstore',                       // optional
  eventsCollectionName: 'events',             // optional
  snapshotsCollectionName: 'snapshots',       // optional
  timeout: 10000                              // optional
  // emitStoreEvents: true,                   // optional, by default no store events are emitted
  // maxSnapshotsCount: 3                     // optional, defaultly will keep all snapshots
  // password: 'secret'                       // optional
});
```

example with tingodb:
```javascript
var es = require('eventstore')({
  type: 'tingodb',
  dbPath: '/path/to/my/db/file',              // optional
  eventsCollectionName: 'events',             // optional
  snapshotsCollectionName: 'snapshots',       // optional
  transactionsCollectionName: 'transactions', // optional
  timeout: 10000,                             // optional
  // emitStoreEvents: true,                   // optional, by default no store events are emitted
  // maxSnapshotsCount: 3                     // optional, defaultly will keep all snapshots
});
```

example with elasticsearch:
```javascript
var es = require('eventstore')({
  type: 'elasticsearch',
  host: 'localhost:9200',                     // optional
  indexName: 'eventstore',                    // optional
  eventsTypeName: 'events',                   // optional
  snapshotsTypeName: 'snapshots',             // optional
  log: 'warning',                             // optional
  maxSearchResults: 10000,                    // optional
  // emitStoreEvents: true,                   // optional, by default no store events are emitted
  // maxSnapshotsCount: 3                     // optional, defaultly will keep all snapshots
});
```

example with custom elasticsearch client (e.g. with AWS ElasticSearch client. Note ``` http-aws-es ``` package usage in this example):
```javascript
var elasticsearch = require('elasticsearch');

var esClient = = new elasticsearch.Client({
  hosts: 'SOMETHING.es.amazonaws.com',
  connectionClass: require('http-aws-es'),
  amazonES: {
    region: 'us-east-1',
    accessKey: 'REPLACE_AWS_accessKey',
    secretKey: 'REPLACE_AWS_secretKey'
  }
});

var es = require('eventstore')({
  type: 'elasticsearch',
  client: esClient,
  indexName: 'eventstore',
  eventsTypeName: 'events',
  snapshotsTypeName: 'snapshots',
  log: 'warning',
  maxSearchResults: 10000
});
```

example with azuretable:
```javascript
var es = require('eventstore')({
  type: 'azuretable',
  storageAccount: 'nodeeventstore',
  storageAccessKey: 'aXJaod96t980AbNwG9Vh6T3ewPQnvMWAn289Wft9RTv+heXQBxLsY3Z4w66CI7NN12+1HUnHM8S3sUbcI5zctg==',
  storageTableHost: 'https://nodeeventstore.table.core.windows.net/',
  eventsTableName: 'events',               // optional
  snapshotsTableName: 'snapshots',         // optional
  timeout: 10000,                          // optional
  emitStoreEvents: true                    // optional, by default no store events are emitted
});
```

example with dynamodb:
```javascript
var es = require('eventstore')({
    type: 'dynamodb',
    eventsTableName: 'events',                  // optional
    snapshotsTableName: 'snapshots',            // optional
    undispatchedEventsTableName: 'undispatched' // optional
    EventsReadCapacityUnits: 1,                 // optional
    EventsWriteCapacityUnits: 3,                // optional
    SnapshotReadCapacityUnits: 1,               // optional
    SnapshotWriteCapacityUnits: 3,              // optional
    UndispatchedEventsReadCapacityUnits: 1,     // optional
    UndispatchedEventsReadCapacityUnits: 1,     // optional
    useUndispatchedEventsTable: true            // optional
    eventsTableStreamEnabled: false             // optional
    eventsTableStreamViewType: 'NEW_IMAGE',     // optional
    emitStoreEvents: true                       // optional, by default no store events are emitted
});
```

DynamoDB credentials are obtained by eventstore either from environment vars or credentials file. For setup see [AWS Javascript SDK](http://docs.aws.amazon.com/AWSJavaScriptSDK/guide/node-configuring.html).

DynamoDB provider supports [DynamoDB local](http://docs.aws.amazon.com/amazondynamodb/latest/developerguide/DynamoDBLocal.html) for local development via the AWS SDK `endpoint` option. Just set the `$AWS_DYNAMODB_ENDPOINT` (or `%AWS_DYNAMODB_ENDPOINT%` in Windows) environment variable to point to your running instance of Dynamodb local like this:

    $ export AWS_DYNAMODB_ENDPOINT=http://localhost:8000

Or on Windows:

    > set AWS_DYNAMODB_ENDPOINT=http://localhost:8000

The **useUndispatchedEventsTable** option to available for those who prefer to use DyanmoDB.Streams to pull events from the store instead of the UndispatchedEvents table. The default is true. Setting this option to false will result in the UndispatchedEvents table not being created at all, the getUndispatchedEvents method will always return an empty array, and the setEventToDispatched will effectively do nothing.

Refer to [StreamViewType](http://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_StreamSpecification.html#DDB-Type-StreamSpecification-StreamViewType) for a description of the **eventsTableStreamViewType** option

## Built-in event publisher (optional)

if defined the eventstore will try to publish AND set event do dispatched on its own...

### sync interface
```javascript
es.useEventPublisher(function(evt) {
  // bus.emit('event', evt);
});
```

### async interface

```javascript
es.useEventPublisher(function(evt, callback) {
  // bus.sendAndWaitForAck('event', evt, callback);
});
```

## catch connect and disconnect events

```javascript
es.on('connect', function() {
  console.log('storage connected');
});

es.on('disconnect', function() {
  console.log('connection to storage is gone');
});
```

## define event mappings [optional]

Define which values should be mapped/copied to the payload event.

```javascript
es.defineEventMappings({
  id: 'id',
  commitId: 'commitId',
  commitSequence: 'commitSequence',
  commitStamp: 'commitStamp',
  streamRevision: 'streamRevision'
});
```

## initialize
```javascript
es.init(function (err) {
  // this callback is called when all is ready...
});

// or

es.init(); // callback is optional
```

## working with the eventstore

### get the eventhistory (of an aggregate)

```javascript
es.getEventStream('streamId', function(err, stream) {
  var history = stream.events; // the original event will be in events[i].payload

  // myAggregate.loadFromHistory(history);
});
```

or

```javascript
es.getEventStream({
  aggregateId: 'myAggregateId',
  aggregate: 'person',          // optional
  context: 'hr'                 // optional
}, function(err, stream) {
  var history = stream.events; // the original event will be in events[i].payload

  // myAggregate.loadFromHistory(history);
});
```

'streamId' and 'aggregateId' are the same...
In ddd terms aggregate and context are just to be more precise in language.
For example you can have a 'person' aggregate in the context 'human ressources' and a 'person' aggregate in the context of 'business contracts'...
So you can have 2 complete different aggregate instances of 2 complete different aggregates (but perhaps with same name) in 2 complete different contexts

you can request an eventstream even by limit the query with a 'minimum revision number' and a 'maximum revision number'

```javascript
var revMin = 5,
    revMax = 8; // if you omit revMax or you define it as -1 it will retrieve until the end

es.getEventStream('streamId' || {/* query */}, revMin, revMax, function(err, stream) {
  var history = stream.events; // the original event will be in events[i].payload

  // myAggregate.loadFromHistory(history);
});
```

store a new event and commit it to store

```javascript
es.getEventStream('streamId', function(err, stream) {
  stream.addEvent({ my: 'event' });
  stream.addEvents([{ my: 'event2' }]);

  stream.commit();

  // or

  stream.commit(function(err, stream) {
    console.log(stream.eventsToDispatch); // this is an array containing all added events in this commit.
  });
});
```

if you defined an event publisher function the committed event will be dispatched to the provided publisher

if you just want to load the last event as stream you can call getLastEventAsStream instead of ´getEventStream´.

## working with snapshotting

get snapshot and eventhistory from the snapshot point

```javascript
es.getFromSnapshot('streamId', function(err, snapshot, stream) {
  var snap = snapshot.data;
  var history = stream.events; // events history from given snapshot

  // myAggregate.loadSnapshot(snap);
  // myAggregate.loadFromHistory(history);
});
```

or

```javascript
es.getFromSnapshot({
  aggregateId: 'myAggregateId',
  aggregate: 'person',          // optional
  context: 'hr'                 // optional
}, function(err, snapshot, stream) {
  var snap = snapshot.data;
  var history = stream.events; // events history from given snapshot

  // myAggregate.loadSnapshot(snap);
  // myAggregate.loadFromHistory(history);
});
```

you can request a snapshot and an eventstream even by limit the query with a 'maximum revision number'

```javascript
var revMax = 8; // if you omit revMax or you define it as -1 it will retrieve until the end

es.getFromSnapshot('streamId' || {/* query */}, revMax, function(err, snapshot, stream) {
  var snap = snapshot.data;
  var history = stream.events; // events history from given snapshot

  // myAggregate.loadSnapshot(snap);
  // myAggregate.loadFromHistory(history);
});
```


create a snapshot point

```javascript
es.getFromSnapshot('streamId', function(err, snapshot, stream) {

  var snap = snapshot.data;
  var history = stream.events; // events history from given snapshot

  // myAggregate.loadSnapshot(snap);
  // myAggregate.loadFromHistory(history);

  // create a new snapshot depending on your rules
  if (history.length > myLimit) {
    es.createSnapshot({
      streamId: 'streamId',
      data: myAggregate.getSnap(),
      revision: stream.lastRevision,
      version: 1 // optional
    }, function(err) {
      // snapshot saved
    });

    // or

    es.createSnapshot({
      aggregateId: 'myAggregateId',
      aggregate: 'person',          // optional
      context: 'hr'                 // optional
      data: myAggregate.getSnap(),
      revision: stream.lastRevision,
      version: 1 // optional
    }, function(err) {
      // snapshot saved
    });
  }

  // go on: store new event and commit it
  // stream.addEvents...

});
```

You can automatically clean older snapshots by configuring the number of snapshots to keep with `maxSnapshotsCount` in `eventstore` options.

## own event dispatching (no event publisher function defined)

```javascript
es.getUndispatchedEvents(function(err, evts) {
  // or es.getUndispatchedEvents('streamId', function(err, evts) {
  // or es.getUndispatchedEvents({ // free choice (all, only context, only aggregate, only aggregateId...)
  //                                context: 'hr',
  //                                aggregate: 'person',
  //                                aggregateId: 'uuid'
  //                              }, function(err, evts) {

  // all undispatched events
  console.log(evts);

  // dispatch it and set the event as dispatched

  for (var e in evts) {
    var evt = evts[r];
    es.setEventToDispatched(evt, function(err) {});
    // or
    es.setEventToDispatched(evt.id, function(err) {});
  }

});
```

## query your events

for replaying your events or for rebuilding a viewmodel or just for fun...

skip, limit always optional
```javascript
var skip = 0,
    limit = 100; // if you omit limit or you define it as -1 it will retrieve until the end

es.getEvents(skip, limit, function(err, evts) {
  // if (events.length === amount) {
  //   events.next(function (err, nextEvts) {}); // just call next to retrieve the next page...
  // } else {
  //   // finished...
  // }
});

// or

es.getEvents('streamId', skip, limit, function(err, evts) {
  // if (events.length === amount) {
  //   events.next(function (err, nextEvts) {}); // just call next to retrieve the next page...
  // } else {
  //   // finished...
  // }
});

// or

es.getEvents({ // free choice (all, only context, only aggregate, only aggregateId...)
  context: 'hr',
  aggregate: 'person',
  aggregateId: 'uuid'
}, skip, limit, function(err, evts) {
  // if (events.length === amount) {
  //   events.next(function (err, nextEvts) {}); // just call next to retrieve the next page...
  // } else {
  //   // finished...
  // }
});
```

by revision

revMin, revMax always optional

```javascript
var revMin = 5,
    revMax = 8; // if you omit revMax or you define it as -1 it will retrieve until the end

es.getEventsByRevision('streamId', revMin, revMax, function(err, evts) {});

// or

es.getEventsByRevision({
  aggregateId: 'myAggregateId',
  aggregate: 'person',          // optional
  context: 'hr'                 // optional
}, revMin, revMax, function(err, evts) {});
```
by commitStamp

skip, limit always optional

```javascript
var skip = 0,
    limit = 100; // if you omit limit or you define it as -1 it will retrieve until the end

es.getEventsSince(new Date(2015, 5, 23), skip, limit, function(err, evts) {
  // if (events.length === amount) {
  //   events.next(function (err, nextEvts) {}); // just call next to retrieve the next page...
  // } else {
  //   // finished...
  // }
});

// or

es.getEventsSince(new Date(2015, 5, 23), limit, function(err, evts) {
  // if (events.length === amount) {
  //   events.next(function (err, nextEvts) {}); // just call next to retrieve the next page...
  // } else {
  //   // finished...
  // }
});

// or

es.getEventsSince(new Date(2015, 5, 23), function(err, evts) {
  // if (events.length === amount) {
  //   events.next(function (err, nextEvts) {}); // just call next to retrieve the next page...
  // } else {
  //   // finished...
  // }
});
```

## streaming your events
Some databases support streaming your events, the api is similar to the query one

skip, limit always optional

```javascript
var skip = 0,
    limit = 100; // if you omit limit or you define it as -1 it will retrieve until the end

var stream = es.streamEvents(skip, limit);
// or
var stream = es.streamEvents('streamId', skip, limit);
// or by commitstamp
var stream = es.streamEventsSince(new Date(2015, 5, 23), skip, limit);
// or by revision
var stream = es.streamEventsByRevision({
  aggregateId: 'myAggregateId',
  aggregate: 'person',
  context: 'hr',
});

stream.on('data', function(e) {
  doSomethingWithEvent(e);
});

stream.on('end', function() {
  console.log('no more evets');
});

// or even better
stream.pipe(myWritableStream);
```

currently supported by:

1. mongodb

## get the last event
for example to obtain the last revision nr
```javascript
es.getLastEvent('streamId', function(err, evt) {
});

// or

es.getLastEvent({ // free choice (all, only context, only aggregate, only aggregateId...)
  context: 'hr',
  aggregate: 'person',
  aggregateId: 'uuid'
} function(err, evt) {
});
```

## obtain a new id

```javascript
es.getNewId(function(err, newId) {
  if(err) {
    console.log('ohhh :-(');
    return;
  }

  console.log('the new id is: ' + newId);
});
```

## position of event in store

some db implementations support writing the position of the event in the whole store additional to the streamRevision.

currently those implementations support this:

1. inmemory ( by setting ```trackPosition`` option )
1. mongodb ( by setting ```positionsCollectionName``` option)

## special scaling handling with mongodb

Inserting multiple events (documents) in mongodb, is not atomic.
For the eventstore tries to repair itself when calling `getEventsByRevision`.
But if you want you can trigger this from outside:

```javascript
es.store.getPendingTransactions(function(err, txs) {
  if(err) {
    console.log('ohhh :-(');
    return;
  }

  // txs is an array of objects like:
  // {
  //   _id: '/* the commitId of the committed event stream */',
  //   events: [ /* all events of the committed event stream */ ],
  //   aggregateId: 'aggregateId',
  //   aggregate: 'aggregate', // optional
  //   context: 'context'      // optional
  // }

  es.store.getLastEvent({
    aggregateId: txs[0].aggregateId,
    aggregate: txs[0].aggregate, // optional
    context: txs[0].context      // optional
  }, function (err, lastEvent) {
    if(err) {
      console.log('ohhh :-(');
      return;
    }

    es.store.repairFailedTransaction(lastEvent, function (err) {
      if(err) {
        console.log('ohhh :-(');
        return;
      }

      console.log('everything is fine');
    });
  });
});
```
## Catch before and after eventstore events
Optionally the eventstore can emit brefore and after events, to enable this feature set the `emitStoreEvents` to true.

```javascript
var eventstore = require('eventstore');
var es = eventstore({
  emitStoreEvents: true,
});

es.on('before-clear', function({milliseconds}) {});
es.on('after-clear', function({milliseconds}) {});

es.on('before-get-next-positions', function({milliseconds, arguments: [positions]}) {});
es.on('after-get-next-positions', function({milliseconds, arguments: [positions]}) {});

es.on('before-add-events', function({milliseconds, arguments: [events]}) {});
es.on('after-add-events', function(milliseconds, arguments: [events]) {});

es.on('before-get-events', function({milliseconds, arguments: [query, skip, limit]}) {});
es.on('after-get-events', function({milliseconds, arguments: [query, skip, limit]}) {});

es.on('before-get-events-since', function({milliseconds, arguments: [milliseconds, date, skip, limit]}) {});
es.on('after-get-events-since', function({milliseconds, arguments: [date, skip, limit]}) {});

es.on('before-get-events-by-revision', function({milliseconds, arguments: [query, revMin, revMax]}) {});
es.on('after-get-events-by-revision', function({milliseconds, arguments, [query, revMin, revMax]}) {});

es.on('before-get-last-event', function({milliseconds, arguments: [query]}) {});
es.on('after-get-last-event', function({milliseconds, arguments: [query]}) {});

es.on('before-get-undispatched-events', function({milliseconds, arguments: [query]}) {});
es.on('after-get-undispatched-events', function({milliseconds, arguments: [query]}) {});

es.on('before-set-event-to-dispatched', function({milliseconds, arguments: [id]}) {});
es.on('after-set-event-to-dispatched', function({milliseconds, arguments: [id]}) {});

es.on('before-add-snapshot', function({milliseconds, arguments: [snap]}) {});
es.on('after-add-snapshot', function({milliseconds, arguments: [snap]}) {});

es.on('before-clean-snapshots', function({milliseconds, arguments: [query]}) {});
es.on('after-clean-snapshots', function({milliseconds, arguments: [query]}) {});

es.on('before-get-snapshot', function({milliseconds, arguments: [query, revMax]}) {});
es.on('after-get-snapshot', function({milliseconds, arguments: [query, revMax]}) {});

es.on('before-remove-transactions', function({milliseconds}, arguments: [event]) {});
es.on('after-remove-transactions', function({milliseconds}, arguments: [event]) {});

es.on('before-get-pending-transactions', function({milliseconds}) {});
es.on('after-get-pending-transactions', function({milliseconds}) {});

es.on('before-repair-failed-transactions', function({milliseconds, arguments: [lastEvt]}) {});
es.on('after-repair-failed-transactions', function({milliseconds, arguments: [lastEvt]}) {});

es.on('before-remove-tables', function({milliseconds}) {});
es.on('after-remove-tables', function({milliseconds}) {});

es.on('before-stream-events', function({milliseconds, arguments: [query, skip, limit]}) {});
es.on('after-stream-events', function({milliseconds, arguments: [query, skip, limit]}) {});

es.on('before-stream-events-since', function({milliseconds, arguments: [date, skip, limit]}) {});
es.on('after-stream-events-since', function({milliseconds, arguments: [date, skip, limit]}) {});

es.on('before-get-event-stream', function({milliseconds, arguments: [query, revMin, revMax]}) {});
es.on('after-get-event-stream', function({milliseconds, arguments: [query, revMin, revMax]}) {});

es.on('before-get-from-snapshot', function({milliseconds, arguments: [query, revMax]}) {});
es.on('after-get-from-snapshot', function({milliseconds, arguments: [query, revMax]}) {});

es.on('before-create-snapshot', function({milliseconds, arguments: [obj]}) {});
es.on('after-create-snapshot', function({milliseconds, arguments: [obj]}) {});

es.on('before-commit', function({milliseconds, arguments: [eventstream]}) {});
es.on('after-commit', function({milliseconds, arguments: [eventstream]}) {});

es.on('before-get-last-event-as-stream', function({milliseconds, arguments: [query]}) {});
es.on('after-get-last-event-as-stream', function({milliseconds, arguments: [query]}) {});
```

# Sample Integration

- [nodeCQRS](https://github.com/jamuhl/nodeCQRS) A CQRS sample integrating eventstore

# Inspiration

- Jonathan Oliver's [EventStore](https://github.com/joliver/EventStore) for .net.

# [Release notes](https://github.com/adrai/node-eventstore/blob/master/releasenotes.md)

# Database Support

Currently these databases are supported:

1. inmemory
2. mongodb ([node-mongodb-native](https://github.com/mongodb/node-mongodb-native))
3. redis ([redis](https://github.com/mranney/node_redis))
4. tingodb ([tingodb](https://github.com/sergeyksv/tingodb))
5. azuretable ([azure-storage](https://github.com/Azure/azure-storage-node))
6. dynamodb ([aws-sdk](https://github.com/aws/aws-sdk-js))

## own db implementation

You can use your own db implementation by extending this...

```javascript
var Store = require('eventstore').Store,
    util = require('util'),
    _ = require('lodash');

function MyDB(options) {
  options = options || {};
  Store.call(this, options);
}

util.inherits(MyDB, Store);

_.extend(MyDB.prototype, {

  // ...

});

module.exports = MyDB;
```

and you can use it in this way

```javascript
var es = require('eventstore')({
  type: MyDB
});
// es.init...
```

# License

Copyright (c) 2018 Adriano Raiano, Jan Muehlemann

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
