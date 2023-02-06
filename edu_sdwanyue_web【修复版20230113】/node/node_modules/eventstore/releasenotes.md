#### [v1.15.5](https://github.com/adrai/node-eventstore/compare/v1.15.4...v1.15.5)
- Fixing omitting & duplication issue on mongoDB streamEventsByRevision [#169](https://github.com/adrai/node-eventstore/pull/169) thanks to [ScripterSugar](https://github.com/ScripterSugar)

#### [v1.15.4](https://github.com/adrai/node-eventstore/compare/v1.15.3...v1.15.4)
- make sure revision is of correct type [#167](https://github.com/adrai/node-eventstore/pull/167) thanks to [ScripterSugar](https://github.com/ScripterSugar)

#### [v1.15.3](https://github.com/adrai/node-eventstore/compare/v1.15.2...v1.15.3)
- mongodb: useUnifiedTopology [#162](https://github.com/adrai/node-eventstore/pull/162) thanks to [odin16](https://github.com/odin16)

#### [v1.15.2](https://github.com/adrai/node-eventstore/compare/v1.15.1...v1.15.2)
- tingodb: use default implementation for getNewId

#### [v1.15.1](https://github.com/adrai/node-eventstore/compare/v1.15.0...v1.15.1)
- Bugfix/emit eventstore event without callback [#151](https://github.com/adrai/node-eventstore/pull/151) thanks to [DaNautilus](https://github.com/DaNautilus)

#### [v1.15.0](https://github.com/adrai/node-eventstore/compare/v1.14.42...v1.15.0)
- Feature: optionally emit eventstore before and after events [#149](https://github.com/adrai/node-eventstore/pull/149) thanks to [DaNautilus](https://github.com/DaNautilus)

#### [v1.14.42](https://github.com/adrai/node-eventstore/compare/v1.14.41...v1.14.42)
- mongodb: speedup getLastEvent by adding a new index [#147](https://github.com/adrai/node-eventstore/pull/147) thanks to [robinfehr](https://github.com/robinfehr)

#### [v1.14.41](https://github.com/adrai/node-eventstore/compare/v1.14.40...v1.14.41)
- update deps

#### [v1.14.4](https://github.com/adrai/node-eventstore/compare/v1.40.4...v1.14.3)
- replace deprecated mongodb methods with suggested replacements

#### [v1.14.3](https://github.com/adrai/node-eventstore/compare/v1.40.3...v1.14.2)
- revisit the position option, to make it mappable to payload and part of the commit flow

#### [v1.14.2](https://github.com/adrai/node-eventstore/compare/v1.40.1...v1.14.2)
- add non-breaking, optional position in store option for mongodb and inmemory implementations

#### [v1.14.1](https://github.com/adrai/node-eventstore/compare/v1.40.0...v1.14.1)
- add streamEventsByRevision method to the streaming api thanks to [nanov](https://github.com/nanov)

#### [v1.14.0](https://github.com/adrai/node-eventstore/compare/v1.13.4...v1.14.0)
- Introduce stream API for getEvents and getEventsSince and add mongodb implementation thanks to [nanov](https://github.com/nanov)

#### [v1.13.4](https://github.com/adrai/node-eventstore/compare/v1.13.3...v1.13.4)
- Fix store.cleanSnapshots for redis implementation [#127](https://github.com/adrai/node-eventstore/pull/127) thanks to [TyGuy](https://github.com/TyGuy)

#### [v1.13.3](https://github.com/adrai/node-eventstore/compare/v1.13.2...v1.13.3)
- remove deprecated option max_attempts from redis options

#### [v1.13.2](https://github.com/adrai/node-eventstore/compare/v1.13.0...v1.13.2)
- Fix concurrency issue in createTableIfNotExists (DynamoDB) [#118](https://github.com/adrai/node-eventstore/pull/118) thanks to [DannyRyman](https://github.com/DannyRyman)

#### [v1.13.0](https://github.com/adrai/node-eventstore/compare/v1.12.9...v1.13.0)
- compatibility with new mongodb version (3.x)

#### [v1.12.9](https://github.com/adrai/node-eventstore/compare/v1.12.8...v1.12.9)
- fixing [#116](https://github.com/adrai/node-eventstore/pull/116) correctly thanks to [wrobel](https://github.com/wrobel)

#### [v1.12.8](https://github.com/adrai/node-eventstore/compare/v1.12.7...v1.12.8)
- Fix sort key/range key ordering in dynamodb events table [#115](https://github.com/adrai/node-eventstore/pull/115) thanks to [chriscosgriff](https://github.com/chriscosgriff)

#### [v1.12.7](https://github.com/adrai/node-eventstore/compare/v1.12.6...v1.12.7)
- Optionally enable dynamodb stream on the events table [#113](https://github.com/adrai/node-eventstore/pull/113) thanks to [chriscosgriff](https://github.com/chriscosgriff)

#### [v1.12.6](https://github.com/adrai/node-eventstore/compare/v1.12.5...v1.12.6)
- try to fix memory leak when calling next page [#112](https://github.com/adrai/node-eventstore/issues/112) thanks to [repkins](https://github.com/repkins)

#### [v1.12.5](https://github.com/adrai/node-eventstore/compare/v1.12.4...v1.12.5)
- fix DynamoDB concurrency [#110](https://github.com/adrai/node-eventstore/pull/110) thanks to [DannyRyman](https://github.com/DannyRyman)

#### [v1.12.4](https://github.com/adrai/node-eventstore/compare/v1.12.3...v1.12.4)
- fixing dynamodb DocumentClient initialization [#109](https://github.com/adrai/node-eventstore/pull/109) thanks to [Glockenbeat](https://github.com/Glockenbeat)

#### [v1.12.3](https://github.com/adrai/node-eventstore/compare/v1.12.2...v1.12.3)
- dynamodb: Fixed TypeError [#107](https://github.com/adrai/node-eventstore/pull/107) thanks to [jrutley](https://github.com/jrutley)

#### [v1.12.2](https://github.com/adrai/node-eventstore/compare/v1.12.0...v1.12.2)
- fix for new mongodb driver

#### [v1.12.0](https://github.com/adrai/node-eventstore/compare/v1.11.1...v1.12.0)
- Clean snapshots automatically whenever activated [#101](https://github.com/adrai/node-eventstore/pull/101) thanks to [rehia](https://github.com/rehia)

#### [v1.11.1](https://github.com/adrai/node-eventstore/compare/v1.11.0...v1.11.1)
- loop fix for node 0.12.x [#99](https://github.com/adrai/node-eventstore/pull/99) thanks to [pingchen](https://github.com/pingchen)

#### [v1.11.0](https://github.com/adrai/node-eventstore/compare/v1.10.3...v1.11.0)
- Fix empty array conversion due to Lua json decode [#97](https://github.com/adrai/node-eventstore/pull/97) thanks to [rehia](https://github.com/rehia)

#### [v1.10.3](https://github.com/adrai/node-eventstore/compare/v1.10.2...v1.10.3)
- update deps

#### [v1.10.2](https://github.com/adrai/node-eventstore/compare/v1.10.1...v1.10.2)
- dynamodb: changed addEvents to use sequential async methods instead of parallel [#93](https://github.com/adrai/node-eventstore/pull/93) thanks to [developmentalmadness](https://github.com/developmentalmadness)

#### [v1.10.1](https://github.com/adrai/node-eventstore/compare/v1.10.0...v1.10.1)
- fix calculated streamRevision for redis to avoid events sorting issues [#92](https://github.com/adrai/node-eventstore/pull/92) thanks to [rehia](https://github.com/rehia)

#### [v1.10.0](https://github.com/adrai/node-eventstore/compare/v1.9.0...v1.10.0)
- improve event revision accuracy in redis, to avoid revisions duplications for an aggregate [#91](https://github.com/adrai/node-eventstore/pull/91) thanks to [rehia](https://github.com/rehia)

#### [v1.9.0](https://github.com/adrai/node-eventstore/compare/v1.8.4...v1.9.0)
- ability to use custom elasticsearch client in the elasticsearch storage  [#88](https://github.com/adrai/node-eventstore/pull/88) thanks to [evereq](https://github.com/evereq)
- redis snapshots are retrieved recursively to match 'max revision' option  [#89](https://github.com/adrai/node-eventstore/pull/89) thanks to [rehia](https://github.com/rehia)

#### [v1.8.4](https://github.com/adrai/node-eventstore/compare/v1.8.3...v1.8.4)
- Ensuring concurrency for dynamodb  [#87](https://github.com/adrai/node-eventstore/pull/87) thanks to [developmentalmadness](https://github.com/developmentalmadness)

#### [v1.8.3](https://github.com/adrai/node-eventstore/compare/v1.8.2...v1.8.3)
- Optional dispatch table for dynamodb  [#86](https://github.com/adrai/node-eventstore/pull/86) thanks to [developmentalmadness](https://github.com/developmentalmadness)

#### [v1.8.2](https://github.com/adrai/node-eventstore/compare/v1.8.1...v1.8.2)
- Prevent uncommittedEvents to be overwritten accidentally [#84](https://github.com/adrai/node-eventstore/issues/84), [#85](https://github.com/adrai/node-eventstore/pull/85) thanks to [albe](https://github.com/albe)

#### [v1.8.1](https://github.com/adrai/node-eventstore/compare/v1.8.0...v1.8.1)
- Early abort when events were fetched without limit and calling next [#81](https://github.com/adrai/node-eventstore/pull/81) thanks to [johanneslumpe](https://github.com/johanneslumpe)

#### [v1.8.0](https://github.com/adrai/node-eventstore/compare/v1.7.11...v1.8.0)
- dynamodb store implementation [#75](https://github.com/adrai/node-eventstore/pull/75) and [#78](https://github.com/adrai/node-eventstore/pull/78) thanks to [developmentalmadness](https://github.com/developmentalmadness)

#### [v1.7.11](https://github.com/adrai/node-eventstore/compare/v1.7.8...v1.7.11)
- This resolves an issue where the maximum call stack size could be hit when processing 1000s of undispatched events on startup [#74](https://github.com/adrai/node-eventstore/pull/74) thanks to [ben-moore](https://github.com/ben-moore)

#### [v1.7.8](https://github.com/adrai/node-eventstore/compare/v1.7.7...v1.7.8)
- redis, mongodb: call disconnect on ping error

#### [v1.7.7](https://github.com/adrai/node-eventstore/compare/v1.7.6...v1.7.7)
- Support mongo connection string [#70](https://github.com/adrai/node-eventstore/pull/70) [#68](https://github.com/adrai/node-eventstore/issues/68) thanks to [danwkennedy](https://github.com/danwkennedy) and [mmmdreg](https://github.com/mmmdreg)

#### [v1.7.6](https://github.com/adrai/node-eventstore/compare/v1.7.5...v1.7.6)
- redis, mongodb: call disconnect on ping error

#### [v1.7.5](https://github.com/adrai/node-eventstore/compare/v1.7.4...v1.7.5)
- inmemory: keep events immutable [#67](https://github.com/adrai/node-eventstore/pull/67) thanks to [hilkeheremans](https://github.com/hilkeheremans)

#### [v1.7.4](https://github.com/adrai/node-eventstore/compare/v1.7.3...v1.7.4)
- MongoDb: Add index used when querying for all events for an aggregate type [#64](https://github.com/adrai/node-eventstore/pull/65) thanks to [HCanber](https://github.com/HCanber)

#### [v1.7.3](https://github.com/adrai/node-eventstore/compare/v1.7.2...v1.7.3)
- redis: added optional heartbeat

#### [v1.7.2](https://github.com/adrai/node-eventstore/compare/v1.7.1...v1.7.2)
- update azure dependencies
- Adding getLastEvent support in azure table provider [#64](https://github.com/adrai/node-eventstore/pull/64) thanks to [sbiaudet](https://github.com/sbiaudet)

#### [v1.7.1](https://github.com/adrai/node-eventstore/compare/v1.7.0...v1.7.1)
- Fix eventmappings when value is empty or 0 [#61](https://github.com/adrai/node-eventstore/pull/61) thanks to [rehia](https://github.com/rehia)

#### [v1.7.0](https://github.com/adrai/node-eventstore/compare/v1.6.2...v1.7.0)
- added Elasticsearch support [#59](https://github.com/adrai/node-eventstore/pull/59) thanks to [gerbenmeyer](https://github.com/gerbenmeyer)

#### [v1.6.2](https://github.com/adrai/node-eventstore/compare/v1.5.3...v1.6.2)
- added getLastEvent and getLastEventAsStream function

#### [v1.5.3](https://github.com/adrai/node-eventstore/compare/v1.5.2...v1.5.3)
- redis: fix for new redis lib

#### [v1.5.1](https://github.com/adrai/node-eventstore/compare/v1.5.0...v1.5.1)
- give possibility to use mongodb with authSource

#### [v1.5.0](https://github.com/adrai/node-eventstore/compare/v1.4.2...v1.5.0)
- added possibility to getUndispatchedEvents by query

#### [v1.4.2](https://github.com/adrai/node-eventstore/compare/v1.4.1...v1.4.2)
- optimization for `npm link`'ed development

#### [v1.4.1](https://github.com/adrai/node-eventstore/compare/v1.4.0...v1.4.1)
- redis: replace .keys() calls with .scan() calls => scales better

#### [v1.4.0](https://github.com/adrai/node-eventstore/compare/v1.3.1...v1.4.0)
- added possibility to map/copy some values of the raw-event to the real event
- added possibility to fetch all events since a date
- IMPORTANT for redis: the keys have a new format

#### [v1.3.1](https://github.com/adrai/node-eventstore/compare/v1.2.0...v1.3.1)
- mongodb: added possibility to repair failed transaction (insert of multiple events) from outside

#### [v1.2.0](https://github.com/adrai/node-eventstore/compare/v1.1.7...v1.2.0)
- performance improvements in inmemory and mongodb store [#31](https://github.com/adrai/node-eventstore/pull/31) thanks to [surlemur](https://github.com/surlemur)
- IMPORTANT for mongodb: removed data compatibility for events older v1.0.0

#### [v1.1.7](https://github.com/adrai/node-eventstore/compare/v1.1.6...v1.1.7)
- performance improvements in inmemory store

#### [v1.1.6](https://github.com/adrai/node-eventstore/compare/v1.1.5...v1.1.6)
- fix inmemory store

#### [v1.1.5](https://github.com/adrai/node-eventstore/compare/v1.1.4...v1.1.5)
- fix usage with own db implementation [#29](https://github.com/adrai/node-eventstore/pull/29)

#### [v1.1.4](https://github.com/adrai/node-eventstore/compare/v1.1.2...v1.1.4)
- fix usage with own db implementation [#27](https://github.com/adrai/node-eventstore/issues/27)

#### [v1.1.2](https://github.com/adrai/node-eventstore/compare/v1.1.1...v1.1.2)
- azure-table: fix issue in getEvents [#23](https://github.com/adrai/node-eventstore/pull/23) thanks to [rvin100](https://github.com/rvin100)

#### [v1.1.1](https://github.com/adrai/node-eventstore/compare/v1.1.0...v1.1.1)
- azure-table storage optimization [#22](https://github.com/adrai/node-eventstore/pull/22) thanks to [sbiaudet](https://github.com/sbiaudet) and [rvin100](https://github.com/rvin100)

#### [v1.1.0](https://github.com/adrai/node-eventstore/compare/v1.0.5...v1.1.0)
- added azure-table support [#21](https://github.com/adrai/node-eventstore/pull/21) thanks to [sbiaudet](https://github.com/sbiaudet)

#### v1.0.5
- mongodb get all events fix [#20](https://github.com/adrai/node-eventstore/pull/20) thanks to [nikolaylukyanchuk](https://github.com/nikolaylukyanchuk)

#### v1.0.4
- mongodb get all events fix [#20](https://github.com/adrai/node-eventstore/pull/20) thanks to [nikolaylukyanchuk](https://github.com/nikolaylukyanchuk)

#### v1.0.3
- little fix for redis

#### v1.0.2
- optimized indexes

#### v1.0.1
- optimized getSnapshot when using versioning of same revision

#### v1.0.0
- refactored whole module
- added possibility to define aggregateId, aggregate and context
- added a lot of tests
- stabilized everything
- optimized performance
- mongodb legacy data should be usable to (so you can update from eventstore.mongodb to eventstore) without migrating data
- IMPORTANT: changed API!!!

#### v0.7.0
- make using of eventDispatcher configurable
- map getUndispatchedEvents and setEventToDispatched to eventstore

#### v0.6.2
- optimized storage initialization

#### v0.6.1
- forking of event dispatching is configurable now

#### v0.6.0
- removed couchDb implementation
- rewritten tests in mocha and expect.js
- updated to node.js 0.6.15

#### v0.5.0
- simplified API for storage usage
- if possible fork dispatching to own childprocess
- optimized lastRevision handling

#### v0.3.0
- eventstreams
- snapshoting
- get all events with paging for replay
- console.logger
- db implementations for mongoDb, couchDb, redis
