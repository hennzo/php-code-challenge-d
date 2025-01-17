<?php

namespace App\Repositories\Weather;

use App\Repositories\Traits\ClientBaseRepository;

class OpenWeatherMapRepository implements WeatherInterface
{
   use ClientBaseRepository;

   /*
    * @see WeatherInterface::getWeatherInfo()
    */
   public function getWeatherInfo($city)
   {
      $response = $this->client->get($this->host, [
         'query' => ['q' => $city, 'appid' => $this->getAccessKey()]
      ]);
      $data = json_decode($response->getBody());

      return [
         'city' => $data->name,
         'temperature' => [
            'current' => $this->fahrenheitToCelcius($data->main->temp),
            'low' => $this->fahrenheitToCelcius($data->main->temp_min),
            'high' => $this->fahrenheitToCelcius($data->main->temp_max)
         ],
         'wind' => [
            'speed' => $data->wind->speed,
            'direction' => $data->wind->deg
         ]
      ];
   }

   protected function getAccessKey()
   {
      return config('weather.access_key');
   }

   protected function fahrenheitToCelcius($temperature = 0)
   {
      return round(($temperature - 32) * 5/9);
   }
}
