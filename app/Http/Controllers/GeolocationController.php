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

   /**
    * Retrieve Client IP address.
    *
    * @return string|null // Ip Address or null if unable to find client ip address
    */
   protected function getClientIpAddress(Request $request)
   {
      //check if the IP given by Request is good.
      if ($this->isValidIpAddress($ip_address = $request->getClientIp())) {
         return $ip_address;
      }

      // Otherwise, use the Native PHP REMOTE_ADDR
      if ($this->isValidIpAddress($ip_address = $request->server->get('REMOTE_ADDR'))) {
         return $ip_address;
      }

      //If we get here, then there is a problem. Couldnt detect ip address
      return null;
   }

   protected function isValidIpAddress($ip_address = null)
   {
      return filter_var($ip_address, FILTER_VALIDATE_IP);
   }
}
