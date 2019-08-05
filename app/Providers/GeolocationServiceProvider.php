<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Geolocation\GeolocationFactory;
use GuzzleHttp\Client;

class GeolocationServiceProvider extends ServiceProvider
{
   /**
     * Register Geolocation services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind(
         'App\Repositories\Geolocation\GeolocationFactoryInterface',
         'App\Repositories\Geolocation\GeolocationFactory'
      );

      $this->app->bind('App\Repositories\Geolocation\GeolocationFactory', function ($app) {
         return new GeolocationFactory(new Client());
      });
    }
}
