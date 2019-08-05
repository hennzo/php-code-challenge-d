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
         throw new \Exception("'No Ip Address found", 1);
      }

      return $ip_address;
   }
}
