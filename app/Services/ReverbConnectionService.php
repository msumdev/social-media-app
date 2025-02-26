<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Cache;

/**
 * Class ReverbConnectionService
 * @package App\Services
 */
class ReverbConnectionService
{
    /**
     * @param int $userId
     * @return void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function add(int $userId): void
    {
        if (Cache::store('redis')->has('user:' . $userId . ':session-count')) {
            Cache::store('redis')->increment('user:' . $userId . ':session-count');
        } else {
            Cache::store('redis')->put('user:' . $userId . ':session-count', 1);
        }
    }

    /**
     * @param int $userId
     * @return void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function remove(int $userId): void
    {
        if (Cache::store('redis')->has('user:' . $userId . ':session-count')) {
            $currentCount = Cache::store('redis')->get('user:' . $userId . ':session-count');

            if (intval($currentCount) === 1) {
                Cache::store('redis')->forget('user:' . $userId . ':session-count');
            } else {
                Cache::store('redis')->decrement('user:' . $userId . ':session-count');
            }
        }
    }
}
