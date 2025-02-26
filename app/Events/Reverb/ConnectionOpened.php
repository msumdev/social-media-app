<?php

namespace App\Events\Reverb;

use Illuminate\Foundation\Events\Dispatchable;

class ConnectionOpened
{
    use Dispatchable;

    public function __construct(public string $socketId, public array $cookies = [])
    {
        //
    }
}
