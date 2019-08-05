<?php

namespace App\Repositories\Geolocation;

use App\Repositories\Traits\ClientBaseRepository;

/**
 * class FreeGeoipRepository
 *
 * @package App\Repositories\Geolocation
 */
class FreeGeoipRepository implements GeolocationInterface
{
   use ClientBaseRepository;

   const NAME = 'freegeoip';

   /*
    * @see GeolocationInterface::getInfo()
    */
   public function getInfo($query)
   {
      $data = $this->fetch($query);

      return [
         'ip' => $data->ip,
         'geo' => [
            'service' => FreeGeoipRepository::NAME,
            'city' => $data->city,
            'region' => $data->region_name,
            'country' => $data->country_name
         ]
      ];
   }

   protected function getOptions()
   {
      return [
         'query' => ['access_key' => $this->getAccessKey()]
      ];
   }

   protected function getAccessKey()
   {
      return config('geolocation.services.'.FreeGeoipRepository::NAME.'.secret');
   }
}
