<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Repositories\Geolocation\GeolocationFactoryInterface;
use App\Http\Controllers\Traits\IpAddressValidator;

class GeolocationController extends Controller
{
   use IpAddressValidator;

   protected $factory = null;

   public function __construct(GeolocationFactoryInterface $factory)
   {
      $this->factory = $factory;
   }

   /**
    * Retrieve Geolocation informations.
    *
    * @return void
    */
   public function show(Request $request, $ip_address = null)
   {
      try {
         $service = $this->factory->build($request);

         $ip_address = $this->validateIpAddress($request, $ip_address);
         return response()->json($service->getInfo($ip_address));
      } catch (\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 422);
      }
   }
}
