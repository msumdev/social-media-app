<?php

namespace App\Facades\Authentication;

use Illuminate\Support\Facades\Facade;

/**
 * Class JWTServiceFacade
 *
 * @mixin \App\Services\Authentication\JWTService
 */
class JWTServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jwt-service';
    }
}
