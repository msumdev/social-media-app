<?php

namespace App\Traits;

trait PusherConnectionTrait
{
    /**
     * @param $object
     * @param $propertyName
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getProtectedProperty($object, $propertyName)
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
    protected function extractCookies($rawHeader): array
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
