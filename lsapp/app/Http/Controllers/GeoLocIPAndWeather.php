<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use Validator;
class BaseGeoLocation
{
    protected $service;
    protected $city;
    protected $region;
    protected $country;
    protected function setValue($service,$city,$state,$country) {
        $this->service=$service;
        $this->city=$city;
        $this->region=$state;
        $this->country=$country; 
    }
    public function getGeoElement() {
        $geo = array(
            'service' => $this->service,
            'city'    => $this->city,
            'region'  => $this->region,
            'country' => $this->country
         );
        return $geo;
    }
}
//inheritance ,
class GeoLocation1 extends BaseGeoLocation{
    public function __construct($service,$city,$state,$country){
        $this->setValue($service,$city,$state,$country);
    }
    
}
//inheritance 
class GeoLocation2 extends BaseGeoLocation{
    public function __construct($service,$country){
        $this->setValue($service,'undefined','undefined',$country);
    }
    
}

//composition
Class GeoIP{
    private $ip;
    private $geo;
    public function __construct($ip,$geo){
        $this->ip=$ip;
        $this->geo=$geo;
    }
    public function getGeoIPElement(){
        $geoIP=array(
            "ip"  => $this->ip,
            "geo" => $this->geo->getGeoElement()
        );
    return json_encode($geoIP);   
    }
}

class Temperature{
    private $current;
    private $low;
    private $high;
    public function __construct($current,$low,$high){
        $this->current=$current-273.16;
        $this->low=$low-273.16;//Kelvin to Sentigrad
        $this->high=$high-273.16;
    }
    public function getTemperatureElement() {
        $temperature = array(
            'current' => round($this->current),
            'low'     => round($this->low),
            'high'    => round($this->high),
         );
        return $temperature;
    }
}


Class Wind{
    private $speed;
    private $direction;
    public function __construct($speed,$direction){
        $this->speed=$speed;
        $this->direction=$direction;
    }
    public function getWindElement() {
        $wind = array(
            'speed' => $this->speed,
            'direction' => $this->direction,
                        
         );
        return $wind;
    }

}

//Weather class in an aggregator - composition
Class Weather{
    function __construct($ip,$city,$temperature,$wind){
        $this->ip=$ip;
        $this->city=$city;
        $this->temperature=$temperature;
        $this->wind=$wind;
    }
    public function getWeatherElement(){
        $weather=array(
            "ip"  => $this->ip,
            "city" => $this->city,
            "temperature" => $this->temperature->getTemperatureElement(),
            "wind" => $this->wind->getWindElement(),

        );
    return json_encode($weather); 
    } 
}

class GeoLocIPAndWeather extends Controller
{
    //validation mechanism in Larvel Framework
    private function validation($itemName,$itemValue,$condition)
    {
        $input = [       
            $itemName => $itemValue,
        ];
               
        $validator = Validator::make($input, [
            $itemName => $condition,
        ]);

        if ($validator->fails())
          return false;
        return true;
    }

    public function f1(Request $request,$ipAddress=null){
        if (!isset($ipAddress) )
            $ipAddress="142.118.154.126";  //Montreal 
        if (!$this->validation('IPAddress',$ipAddress,'ip'))
          dd('ip wrong');
        $toWhichGeoService="";
        $service = $request->get('service');
        $d=null;
        
        //SER1_IP,SER2_IP,ALKO1,ALKO2 and ACCESS_KEY are define in .env file
        switch (strtolower($service)){
            case env('SER1_IP'):
                $toWhichGeoService=env('ALKO1').$ipAddress;
                $obj=$this->jsonElem($toWhichGeoService);
                $d=new GeoLocation1(env('SER1_IP'),$obj->city,$obj->regionName,$obj->country);
            break;
            case env('SER2_IP'):
                $toWhichGeoService=env('ALKO2').$ipAddress.'?access_key='.env('ACCESS_KEY');
                $obj=$this->jsonElem($toWhichGeoService);
                $d=new GeoLocation2(env('SER2_IP'),$obj->country_name);
            break;

            default:
                $toWhichGeoService=env('ALKO1').$ipAddress;
                $obj=$this->jsonElem($toWhichGeoService);
                $d=new GeoLocation1(env('SER1_IP'),$obj->city,$obj->regionName,$obj->country);
            break;
        }
        $geoObj=new GeoIP($ipAddress,$d);
        echo($geoObj->getGeoIPElement());
  

        

    }

    private function jsonElem($toWhichGeoService)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $toWhichGeoService);
        $jsonResult = $response->getBody()->getContents();
        $obj = json_decode($jsonResult, false);
        return $obj;
    }

    public function f2($ip){
        if (!isset($ip) )
            $ip="142.118.154.126"; //Montreal 
        if (!$this->validation('IPAddress',$ip,'ip'))
          dd('ip wrong');
        $toWhichGeoService=env('ALKO1').$ip;
        $obj=$this->jsonElem($toWhichGeoService);
        $toWhichGeoService=env('WEATHER').$obj->city."&APPID=".env("APPID");
        $obj2=$this->jsonElem($toWhichGeoService);
        $temperatureObj=new  Temperature($obj2->main->temp,$obj2->main->temp_min,$obj2->main->temp_max);
        $windObj=new Wind($obj2->wind->speed,$obj2->wind->deg);
        //dd($w);
        $weatherObj=new Weather($ip,$obj->city,$temperatureObj,$windObj);
        echo($weatherObj->getWeatherElement());
    }



}