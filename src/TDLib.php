<?php
declare(strict_types=1);

namespace Thisismzm\TdlibPhpFfi;

use FFI;
use InvalidArgumentException;

class TDLib
{
    private const TDLIB_HEADER_FILE = <<<HEADER
int td_create_client_id();
void td_send(int client_id, const char *request);
const char* td_receive(double timeout);
const char* td_execute(const char *request);
HEADER;

    private FFI $ffi;

    /**
     * @param string|null $tdlibFilePath An optional file path/name to `libtdjson.so` library
     */
    public function __construct(string $tdlibFilePath = null)
    {
        try {
            $this->ffi = FFI::cdef(static::TDLIB_HEADER_FILE, $tdlibFilePath);
        } catch (FFI\Exception $exception) {
            echo $exception->getMessage();
            throw new InvalidArgumentException(sprintf('Failed loading TdLib library "%s"', $tdlibFilePath));
        }
    }

    /**
     * Returns an opaque identifier of a new TDLib instance.
     * The TDLib instance will not send updates until the first request is sent to it.
     * 
     * @return int an opaque identifier of a new TDLib instance
     */
    public function createClientId(): int
    {
        return $this->ffi->td_create_client_id();
    }

    /**
     * Sends request to the TDLib client.
     * May be called from any thread.
     * 
     * @param int $clientId TDLib client id
     * @param string $request $request JSON-serialized request to TDLib.
     * 
     * @return void
     */
    public function send(int $clientId, string $request): void
    {
        $this->ffi->td_send($clientId, $request);
    }

    /**
     * Receives incoming updates and request responses.
     * Must not be called simultaneously from two different threads.
     * 
     * @param int $timeout The maximum number of seconds allowed for this function to wait for new data
     * 
     * @return string|null incoming update or request response or may be null if the timeout expires.
     */
    public function receive(int $timeout): ?string
    {
        return $this->ffi->td_receive($timeout);
    }

    /**
     * Synchronously executes a TDLib request.
     * A request can be executed synchronously, only if it is documented with "Can be called synchronously".
     * 
     * @param string $request JSON-serialized request to TDLib.
     * 
     * @return string|null JSON-serialized null-terminated request response.
     */
    public function execute(string $request): ?string
    {
        return $this->ffi->td_execute($request);
    }
}
