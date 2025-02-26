<?php

namespace App\Events\Reverb;

use Illuminate\Foundation\Events\Dispatchable;

class ConnectionClosed
{
    use Dispatchable;

    public function __construct(public string $socketId, public array $cookies = [])
    {
        //
    }
}
