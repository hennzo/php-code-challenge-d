<?php

namespace App\Repositories\Geolocation;

use GuzzleHttp\ClientInterface;

class IpApiRepository implements GeolocationInterface
{
   const NAME = 'ip-api';

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
      $response = $this->client->get($endpoint);
      $data = json_decode($response->getBody());
      
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
}
