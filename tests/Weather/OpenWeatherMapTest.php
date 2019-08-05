<?php

use App\Repositories\Weather\OpenWeatherMapRepository;

class OpenWeatherMapTest extends TestCase
{
   /**
    * Create a OpenWeatherMapRepository instance
    *
    * @return App\Repositories\Weather\OpenWeatherMapRepository
    */
   protected function getOpenWeatherMapRepository($client)
   {
      return new OpenWeatherMapRepository(
         $client,
         config('weather.host')
      );
   }

   /**
    * Test Geolocation with no ip address (client ip should be used).
    *
    * @return void
    */
   public function testGetWeatherInfo()
   {
      $client = $this->getMockBuilder(\GuzzleHttp\Client::class)
                     ->setMethods(['get'])
                     ->getMock();

      $response = $this->getMockBuilder(\GuzzleHttp\Psr7::class)
                       ->setMethods(['getBody'])
                       ->getMock();

      $ip_address = '127.0.0.1';
      $city = 'Montreal';
      $endpoint = rtrim(config('weather.host'), '/');
      $result = [
         'name' => $city,
         'status' => 'success',
         'main' => [
            'temp' => 20,
            'temp_min' => 12,
            'temp_max' => 28
         ],
         'wind' => [
            'speed' => 40,
            'deg' => 120
         ]
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

      $service = $this->getOpenWeatherMapRepository($client);

      $this->assertEquals(
         $service->getWeatherInfo($city),
         [
            'city' => $result['name'],
            'temperature' => [
               'current' => $this->fahrenheitToCelcius($result['main']['temp']),
               'low' => $this->fahrenheitToCelcius($result['main']['temp_min']),
               'high' => $this->fahrenheitToCelcius($result['main']['temp_max'])
            ],
            'wind' => [
               'speed' => $result['wind']['speed'],
               'direction' => $result['wind']['deg'],
            ]
         ]
      );
   }

   protected function fahrenheitToCelcius($temperature = 0)
   {
      return round(($temperature - 32) * 5/9);
   }
}
