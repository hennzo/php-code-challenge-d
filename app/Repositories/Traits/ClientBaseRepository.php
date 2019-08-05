<?php

namespace App\Repositories\Traits;

use GuzzleHttp\ClientInterface;

trait ClientBaseRepository
{
   protected $client = null;

   protected $host = '';

   public function __construct(ClientInterface $client, $host = '')
   {
      $this->client = $client;
      $this->host = $host;
   }

   protected function fetch($query)
   {
      $endpoint = rtrim($this->host, '/')."/{$query}";

      $response = $this->client->get($endpoint, $this->getOptions());

      $data = json_decode($response->getBody());

      return $data;
   }
}
