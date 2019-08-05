<?php

namespace App\Repositories\Geolocation;

use GuzzleHttp\ClientInterface;

class FreeGeoipRepository implements GeolocationInterface
{
   const NAME = 'freegeoip';

   protected $client = null;

   public function __construct(ClientInterface $client)
   {
      $this->client = $client;
   }

   /*
    * @see GeolocationInterface::getInfo()
    */
   public function getInfo($endpoint)
   {
      $response = $this->client->get($endpoint, [
         'query' => ['access_key' => $this->getAccessKey()]
      ]);

      $data = json_decode($response->getBody());

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

   protected function getAccessKey()
   {
      return config('geolocation.services.'.FreeGeoipRepository::NAME.'.secret');
   }
}
