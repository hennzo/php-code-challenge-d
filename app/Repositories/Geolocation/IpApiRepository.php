<?php

namespace App\Repositories\Geolocation;

use GuzzleHttp\ClientInterface;

/**
 * class IpApiRepository
 *
 * @package App\Repositories\Geolocation
 */
class IpApiRepository implements GeolocationInterface
{
   const NAME = 'ip-api';

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
