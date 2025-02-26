<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ElasticSearchServiceFacade
 * @mixin \App\Services\ElasticSearchService
 * @package App\Facades
 */
class ElasticSearchServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'elasticsearch-service'; }
}
