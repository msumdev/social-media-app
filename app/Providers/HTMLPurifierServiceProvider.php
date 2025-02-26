<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use HTMLPurifier;
use HTMLPurifier_Config;

class HTMLPurifierServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('htmlpurifier', function ($app) {
            $config = HTMLPurifier_Config::createDefault();

            $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
            $config->set('URI.AllowedSchemes', [
                'http' => true,
                'https' => true,
            ]);

            return new HTMLPurifier($config);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
