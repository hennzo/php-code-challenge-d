<?php

namespace App\Repositories\Geolocation;

use Illuminate\Http\Request;

/**
 * Contracts for all GeolocationFactory service
 */
interface GeolocationFactoryInterface
{
   /**
    * Build Geolocation instance
    *
    * @param $request Request
    * @return GeolocationInterface
    */
   public function build(Request $request);
}
