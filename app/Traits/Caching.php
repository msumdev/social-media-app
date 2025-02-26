<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait Caching
{
    /**
     * @var string $cachePrefix
     */
    public string $cachePrefix = '';

    /**
     * @var int $ttl
     */
    public int $ttl = 60;

    /**
     * @var int $version
     */
    public int $version = 1;

    /**
     * @return string
     */
    public function versionKey(): string
    {
        return $this->cachePrefix . ':cache:version';
    }

    /**
     * @param string $prefix
     * @return void
     */
    public function setCachePrefix(string $prefix): void
    {
        $this->cachePrefix = $prefix;
    }

    /**
     * @param int $ttl
     * @return void
     */
    public function setTtl(int $ttl): void
    {
        $this->ttl = $ttl;
    }

    /**
     * @return int
     */
    public function getCurrentVersion(): int
    {
        if (!Redis::exists($this->versionKey())) {
            $this->version = Redis::set($this->versionKey(), $this->version);
        } else {
            $this->version = Redis::get($this->versionKey());
        }

        return $this->version;
    }

    /**
     * @return void
     */
    public function increment(): void
    {
        Redis::pipeline(function ($pipe) {
            $pipe->incr($this->versionKey());
        });
    }

    /**
     * @param string $key
     * @return string
     */
    public function getFormattedKey(string $key): string
    {
        return $this->cachePrefix . ':' . $this->getCurrentVersion() . ':' . $key;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasCachedData(string $key): bool
    {
        $key = $this->getFormattedKey($key);

        return Redis::exists($key);
    }

    /**
     * @param string $key
     * @return void
     */
    public function resetExpireTime(string $key): void
    {
        Redis::expire($key, $this->ttl);
    }

    /**
     * @param string $key
     * @param $data
     * @param bool $increment
     * @return void
     */
    public function set(string $key, $data, bool $increment = true): void
    {
        if ($increment) {
            $this->increment();
        }

        $key = $this->getFormattedKey($key);

        Redis::setex(
            $key,
            $this->ttl,
            gzcompress(json_encode($data))
        );
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        $key = $this->getFormattedKey($key);

        $data = Redis::get($key);

        if ($data) {
            $this->resetExpireTime($key);

            return json_decode(gzuncompress($data), true);
        }

        return null;
    }
}
