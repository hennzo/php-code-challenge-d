<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Weather\OpenWeatherMapRepository;
use GuzzleHttp\Client;

class WeatherServiceProvider extends ServiceProvider
{
   /**
     * Register Geolocation services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind(
         'App\Repositories\Weather\WeatherInterface',
         'App\Repositories\Weather\OpenWeatherMap'
      );

      $this->app->bind('App\Repositories\Weather\OpenWeatherMap', function ($app) {
         $host = config('weather.host');
         return new OpenWeatherMapRepository(new Client(), $host);
      });
    }
}
