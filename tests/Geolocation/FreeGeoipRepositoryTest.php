<?php

use App\Repositories\Geolocation\FreeGeoipRepository;

class FreeGeoipRepositoryTest extends TestCase
{
   /**
    * Create an FreeGeoipRepository instance
    *
    * @return App\Repositories\Geolocation\FreeGeoipRepository
    */
   protected function getFreeGeoipRepository($client)
   {
      return new FreeGeoipRepository(
         $client,
         config('geolocation.services.'.FreeGeoipRepository::NAME)['host']
      );
   }

   /**
    * Test Geolocation with no ip address (client ip should be used).
    *
    * @return void
    */
   public function testGetInfo()
   {
      $client = $this->getMockBuilder(\GuzzleHttp\Client::class)
                     ->setMethods(['get'])
                     ->getMock();

      $response = $this->getMockBuilder(\GuzzleHttp\Psr7::class)
                       ->setMethods(['getBody'])
                       ->getMock();

      $ip_address = '127.0.0.1';
      $endpoint = rtrim(config('geolocation.services.'.FreeGeoipRepository::NAME)['host'], '/')."/$ip_address";
      $result = [
         'city' => 'Montreal',
         'region_code' => 'QC',
         'region_name' => 'Quebec',
         'country_name' => 'Canada',
         'ip' => $ip_address,
         'status' => 'success'
      ];

      //Set up the expectation for the get method
      $client->expects($this->once())
         ->method('get')
         ->with($this->equalTo($endpoint))
         ->willReturn($response);

      //Set up the expectation for the getBody method of Response
      $response->expects($this->once())
               ->method('getBody')
               ->willReturn(json_encode($result));

      $service = $this->getFreeGeoipRepository($client);

      $this->assertEquals(
         $service->getInfo($ip_address),
         [
            'ip' => $result['ip'],
            'geo' => [
               'service' => FreeGeoipRepository::NAME,
               'city' => $result['city'],
               'region' => $result['region_name'],
               'country' => $result['country_name']
            ]
         ]
      );
   }
}
