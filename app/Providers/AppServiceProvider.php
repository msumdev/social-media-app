<?php

namespace App\Providers;

use App\Protocols\Pusher\CustomConnection;
use App\Protocols\Pusher\CustomServer as CustomPusherServer;
use App\Services\Authentication\AuthenticationService;
use App\Services\Authentication\ForgottenPasswordService;
use App\Services\Authentication\JWTService;
use App\Services\ElasticSearchService;
use App\Services\User\Country\CountryService;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Image;
use Laravel\Reverb\Protocols\Pusher\Server as PusherServer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PusherServer::class, CustomPusherServer::class);

        $this->app->bind('jwt-service', function () {
            return new JWTService();
        });

        $this->app->bind('elasticsearch-service', function () {
            return new ElasticSearchService();
        });

        $loader = AliasLoader::getInstance();

        $loader->alias('Image', Image::class);

        View::composer('*', function ($view) {
            if (Route::current() && Route::current()->uri() === 'chat') {
                $view->with('isChat', true);
            } else {
                $view->with('isChat', false);
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
