<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait IpAddressValidator
{
   protected function validateIpAddress(Request $request, $ip_address)
   {
      if (!$ip_address) {
         $ip_address = $this->getClientIpAddress($request);
      }

      //Return Error message if not Ip Address
      if (!$ip_address) {
         throw new \Exception("No Ip Address found", 1);
      }

      return $ip_address;
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
