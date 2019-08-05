<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Geolocation\GeolocationFactoryInterface;
use App\Repositories\Weather\WeatherInterface;
use App\Http\Controllers\Traits\IpAddressValidator;

class WeatherController extends Controller
{
   use IpAddressValidator;

   protected $factory = null;

   protected $weather = null;

   public function __construct(GeolocationFactoryInterface $factory, WeatherInterface $weather)
   {
      $this->factory = $factory;
      $this->weather = $weather;
   }

   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function show(Request $request, $ip_address = null)
   {
      try {
         $geolocation = $this->factory->build($request);

         $ip_address = $this->validateIpAddress($request, $ip_address);
         $data = $geolocation->getInfo($ip_address);

         if (!isset($data['geo'])) {
            throw new \Exception($data['message'], 1);
         }
      
         $result = $this->weather->getWeatherInfo($data['geo']['city']);
         $result = array_merge(['ip' => $data['ip']], $result);

         return response()->json($result);
      } catch (\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 422);
      }
   }
}
