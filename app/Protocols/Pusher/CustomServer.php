<?php

namespace App\Protocols\Pusher;

use App\Events\Reverb\ConnectionClosed;
use App\Events\Reverb\ConnectionOpened;
use Laravel\Reverb\Contracts\Connection;
use Laravel\Reverb\Loggers\Log;
use Laravel\Reverb\Protocols\Pusher\Server as BaseServer;

class CustomServer extends BaseServer
{
    public function open(Connection $connection): void
    {
        try {
            $this->verifyOrigin($connection);

            $connection->touch();

            $this->handler->handle($connection, 'pusher:connection_established');

            $connectionData = $this->getProtectedProperty($connection, 'connection');
            $buffer = $connectionData->connection->buffer();
            $cookies = $this->extractCookies($buffer);

            $socketId = $connection->id();

            ConnectionOpened::dispatch($socketId, $cookies);

            Log::info('Connection Established', $connection->id());
        } catch (Exception $e) {
            $this->error($connection, $e);
        }
    }

    public function close(Connection $connection): void
    {
        $this->channels
            ->for($connection->app())
            ->unsubscribeFromAll($connection);

        $connection->disconnect();

        $socketId = $connection->id();

        $connectionData = $this->getProtectedProperty($connection, 'connection');
        $buffer = $connectionData->connection->buffer();
        $cookies = $this->extractCookies($buffer);

        ConnectionClosed::dispatch($socketId, $cookies);

        Log::info('Connection Closed', $connection->id());
    }

    private function getProtectedProperty($object, $propertyName)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @param $rawHeader
     * @return array
     */
    private function extractCookies($rawHeader): array
    {
        if (preg_match('/Cookie: (.*)/', $rawHeader, $matches)) {
            $cookieHeader = $matches[1];

            $cookies = [];
            $cookiePairs = explode('; ', $cookieHeader);

            foreach ($cookiePairs as $pair) {
                $cookieParts = explode('=', $pair, 2);
                if (count($cookieParts) === 2) {
                    $key = $cookieParts[0];
                    $value = $cookieParts[1];
                    $cookies[$key] = $value;
                }
            }

            return $cookies;
        }

        return [];
    }
}
