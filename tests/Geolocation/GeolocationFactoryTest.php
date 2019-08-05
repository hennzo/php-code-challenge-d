<?php

use App\Repositories\Geolocation\GeolocationFactory;
use Illuminate\Http\Request;

class GeolocationFactoryTest extends TestCase
{
   /**
    * Create a GeolocationFactory instance
    *
    * @return App\Repositories\Geolocation\GeolocationFactory
    */
   protected function getGeolocationFactory($client)
   {
      return new GeolocationFactory($client);
   }

   /**
    * Test Default Geolocation service creation
    *
    * @return void
    */
   public function testCreateDefaultGeolocationService()
   {
      $client = $this->getMockBuilder(\GuzzleHttp\Client::class)
                     ->getMock();

      $factory = $this->getGeolocationFactory($client);
      $request = Request::create('/geolocation');
      $service = $factory->build($request);

      $this->assertInstanceOf('App\Repositories\Geolocation\IpApiRepository', $service);
   }

   /**
    * Test FreeGeoip Geolocation service creation
    *
    * @return void
    */
   public function testCreateFreeGeoipGeolocationService()
   {
      $client = $this->getMockBuilder(\GuzzleHttp\Client::class)
                     ->getMock();

      $factory = $this->getGeolocationFactory($client);
      $request = Request::create('/geolocation?service=freegeoip');
      $service = $factory->build($request);

      $this->assertInstanceOf('App\Repositories\Geolocation\FreeGeoipRepository', $service);
   }
}
