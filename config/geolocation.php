<?php
return [
   /*
   |--------------------------------------------------------------------------
   | Default Geolocation service
   |--------------------------------------------------------------------------
   |
   | This option defines the default Geolocation service.
   | The name specified in this option should match
   | one of the services defined in the "services" configuration array.
   |
   | Supported: "ip-api", "freegeoip"
   */
   'default' => env('GEOLOCATION_SERVICE', 'ip-api'),
   /*
   |--------------------------------------------------------------------------
   | Geolocation services
   |--------------------------------------------------------------------------
   |
   | Here you may configure the geolocation services for your application.
   |
   */
   'services' => [
      'ip-api' => [
         'host'  => 'http://ip-api.com/json/',
         'handler' => App\Repositories\Geolocation\IpApiRepository::class
      ],
      'freegeoip' => [
         'host'  => 'http://api.ipstack.com/',
         'secret'   => env('FREEGEOIP'),
         'handler' => App\Repositories\Geolocation\FreeGeoipRepository::class
      ],
   ],
];
