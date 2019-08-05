<?php

namespace App\Repositories\Geolocation;

use App\Repositories\Traits\ClientBaseRepository;

/**
 * class IpApiRepository
 *
 * @package App\Repositories\Geolocation
 */
class IpApiRepository implements GeolocationInterface
{
   use ClientBaseRepository;

   const NAME = 'ip-api';

   /*
    * @see GeolocationInterface::getInfo()
    */
   public function getInfo($query)
   {
      $data = $this->fetch($query);

      return [
         'ip' => $data->query,
         'geo' => [
            'service' => IpApiRepository::NAME,
            'city' => $data->city,
            'region' => $data->regionName,
            'country' => $data->country
         ]
      ];
   }

   protected function getOptions()
   {
      return [];
   }
}
