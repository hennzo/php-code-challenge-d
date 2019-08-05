<?php

namespace App\Repositories\Geolocation;

use GuzzleHttp\ClientInterface;

/**
 * class FreeGeoipRepository
 *
 * @package App\Repositories\Geolocation
 */
class FreeGeoipRepository implements GeolocationInterface
{
   const NAME = 'freegeoip';

   protected $client = null;

   protected $host = '';

   public function __construct(ClientInterface $client, $host = '')
   {
      $this->client = $client;
      $this->host = $host;
   }

   /*
    * @see GeolocationInterface::getInfo()
    */
   public function getInfo($query)
   {
      $endpoint = rtrim($this->host, '/')."/{$query}";

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
