<?php

namespace App\Repositories\Geolocation;


/**
 * Contracts for all Geolocation service
 */
interface GeolocationInterface
{
   /**
    * Retrieve Geolocation informations:
    * - Target IP address (use client IP if none specified)
    * - City/State/Country of IP
    *
    * @param $endpoint string
    * @return array
    */
   public function getInfo($endpoint);
}
