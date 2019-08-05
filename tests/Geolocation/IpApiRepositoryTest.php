<?php

class IpApiRepositoryTest extends TestCase
{
   /**
    * Create an IpApiRepository instance
    *
    * @return App\Repositories\Geolocation\IpApiRepository
    */
   protected function getIpApiRepository($client)
   {
      return new App\Repositories\Geolocation\IpApiRepository($client);
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
      $endpoint = rtrim(config('geolocation.services.ip-api')['host'])."/$ip_address";
      $result = [
         'city' => 'Montreal',
         'region' => 'QC',
         'regionName' => 'Quebec',
         'country' => 'Canada',
         'query' => $ip_address,
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

      $service = $this->getIpApiRepository($client);


      $this->assertEquals(
         $service->getInfo($endpoint),
         [
            'ip' => $result['query'],
            'geo' => [
               'service' => 'ip-api',
               'city' => $result['city'],
               'region' => $result['regionName'],
               'country' => $result['country']
            ]
         ]
      );
   }
}
