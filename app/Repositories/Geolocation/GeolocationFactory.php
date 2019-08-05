<?php

namespace App\Repositories\Geolocation;

use GuzzleHttp\ClientInterface;
use Illuminate\Http\Request;
use App\Repositories\Geolocation\IpApiRepository;

/**
 * class GeolocationFactory
 *
 * @package App\Repositories\Geolocation
 */
class GeolocationFactory implements GeolocationFactoryInterface
{
   protected $client = null;

   public function __construct(ClientInterface $client)
   {
      $this->client = $client;
   }

   /**
    * @see GeolocationFactoryInterface::build()
    */
   public function build(Request $request)
   {
      //Use Default geolocation service if not specify
      $service_name = $request->get('service', config('geolocation.default'));

      //Use Default geolocation service if service specified does not exist
      if (!$config = config('geolocation.services.'.$service_name)) {
         $config = config('geolocation.services.'.config('geolocation.default'));
      }

      return new $config['handler']($this->client, $config['host']);
    }
}
