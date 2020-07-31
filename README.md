# AsyncConstruct

![Build status](https://github.com/danog/asyncConstruct/workflows/build/badge.svg)
[![codecov](https://codecov.io/gh/danog/asyncConstruct/branch/master/graph/badge.svg)](https://codecov.io/gh/danog/asyncConstruct)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

`danog/asyncConstruct` provides async class constructor and wakeup functions for AMPHP.  

## Installation

```bash
composer require danog/async-construct
```

## API

* Async
  * [AsyncConstruct](#AsyncConstruct)
  * [AsyncWakeup](#AsyncWakeup)
* Blocking
  * [BlockingConstruct](#BlockingConstruct)
  * [BlockingWakeup](#BlockingWakeup)

### AsyncConstruct

[Example](https://github.com/danog/asyncConstruct/blob/master/examples/async.php)  

Simply `use` this trait in your class, and define a `__construct_async` async method.  
The trait will automatically run the async method on construction, and expose a set of APIs to wait on the result of the constructor for initialization.  

#### `init()`

Will blockingly wait for execution of the async constructor, returning its return value.  

#### `initAsynchronously()`

Will return a promise that, when `yield`ed, will wait for and return the return value of the constructor.

#### `inited()`

Returns a boolean, indicating whether the class was initialized or not.  


### BlockingConstruct

[Example](https://github.com/danog/asyncConstruct/blob/master/examples/blocking.php)  

Exactly like [AsyncConstruct], except that the blocking `init()` function will be automatically called on construction.  

### AsyncWakeup

[Example](https://github.com/danog/asyncConstruct/blob/master/examples/asyncWakeup.php)  

Simply `use` this trait in your class, and define a `__wakeup_async` async method.  
The trait will automatically run the async method on deserialization, and expose a set of APIs to wait on the result of the wakeup function for re-initialization.  

#### `wakeup()`

Will blockingly wait for execution of the async wakeup function, returning its return value.  

#### `wakeupAsynchronously()`

Will return a promise that, when `yield`ed, will wait for and return the return value of the wakeup function.

#### `wokenUp()`

Returns a boolean, indicating whether the class was initialized or not.  


### BlockingWakeup

[Example](https://github.com/danog/asyncConstruct/blob/master/examples/blockingWakeup.php)  

Exactly like [AsyncWakeup](#AsyncWakeup), except that the blocking `wakeup()` function will be automatically called on wakeup.  