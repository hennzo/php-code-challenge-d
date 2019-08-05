<?php

namespace App\Repositories\Weather;

/**
 * Contracts for all Weather service
 */
interface WeatherInterface
{
   /**
    * Retrieve Weather information of the givien city name
    *
    * @param $city string
    * @return array
    */
   public function getWeatherInfo($city);
}
