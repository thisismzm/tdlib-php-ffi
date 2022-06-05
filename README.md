# TDLib PHP FFI
An implementation of Telegram's TDLib in PHP by FFI extension

## Docs

### Create an instance
```php
/**
* @param string|null $tdlibFilePath An optional file path/name to `libtdjson.so` library
*/
public function __construct(string $tdlibFilePath = null)
```

### Create client id
Returns an opaque identifier of a new TDLib instance.
The TDLib instance will not send updates until the first request is sent to it.
```php
/**
 * @return int an opaque identifier of a new TDLib instance
 */
public function createClientId(): int
```

### Send request
Sends request to the TDLib client.
May be called from any thread.
```php
/**
 * @param int $clientId TDLib client id
 * @param string $request $request JSON-serialized request to TDLib.
 * 
 * @return void
 */
public function send(int $clientId, string $request): void
```

### Receive response and updates
Receives incoming updates and request responses.
Must not be called simultaneously from two different threads.
```php
/**
 * @param int $timeout The maximum number of seconds allowed for this function to wait for new data
 * 
 * @return string|null incoming update or request response or may be null if the timeout expires.
 */
public function receive(int $timeout): ?string
```

### Synchronously executes a request
Synchronously executes a TDLib request.
A request can be executed synchronously, only if it is documented with "Can be called synchronously".
```php
/**
* @param string $request JSON-serialized request to TDLib.
* 
* @return string|null JSON-serialized null-terminated request response.
*/
public function execute(string $request): ?string
```