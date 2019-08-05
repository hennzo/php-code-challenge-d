# PHP Code Challenge

### Requirements

Build a basic HTTP API web application using the PHP technologies of your choice. You will be evaluated on your ability to *architect* a miniature application.

The web application will be responsible for returning the following information serialized as JSON:

1. Geolocation information:
    * Target IP address (use client IP if none specified)
    * City/State/Country of IP
2. Weather information using the geolocated city of the target IP address
    * Current temperature (in **celcius**)
    * Wind speeds

For IP Geolocation, your application should use the following two free services:

1. [ip-api.com](http://ip-api.com/)
2. [freegeoip.net](http://freegeoip.net/)

The client should be able to use different geolocation services based on a query parameter (?service=ip-api, or ?service=freegeoip) if provided, but default to one of them if nothing is specified. The response should also contain a value indicating to the client which service was used to return the Geolocation response.

For weather information, you should use the [OpenWeatherMap API](http://openweathermap.org/current) to get the current weather information for a city by name. You may use our API key: `6103b0f582e78c7382bc6b0cdc06deb8`.

> **NOTE:** Our OpenWeatherMap API key has a rate limit of 60 requests/minute. If you are throttled, you need to wait a full minute before it will work again.

### Endpoints

##### `GET /geolocation`

##### `GET /geolocation/:ip_address`

##### `GET /weather`

##### `GET /weather/:ip_address`

### Sample Requests

```
GET /geolocation
```

```json
{
    "ip": "8.8.8.8",
    "geo": {
        "service": "ip-api",
        "city": "Mountain View",
        "region": "California",
        "country": "United States"
    }
}
```

```
GET /weather/8.8.8.8
```

```json
{
    "ip": "8.8.8.8",
    "city": "Mountain View",
    "temperature": {
        "current": 13,
        "low": 11,
        "high": 16,
    },
    "wind": {
        "speed": 11,
        "direction": 240
    }
}
```

### Instructions

1. Begin by forking this repository to your own GitHub account
2. Create your PHP implementation of the requirements above, committing your code as you progress
3. Create a `README.md` with instructions detailing installation and configuration of your project so we can run it
4. When finished, open a Pull Request to [lxrco/php-code-challenge-a](https://github.com/lxrco/php-code-challenge-c)

### Remarks

Feel free to use any technology you want or add features you think would make it better.

We're looking for developers who:
* make good use of object-oriented programming using classes and services
* are comfortable relying on high-quality existing packages and knowing when is the appropriate time to use them
* take pride in their creation instead of rushing to get it through the door
* commit often and showcase work through a descriptive commit history

Bonus points if you cover unit and/or functional testing.

Happy coding!


### Installation

For this task, I use Lumen Laravel framework.
Lumen requirements are satisfied by [Laravel Homestead](https://laravel.com/docs/5.8/homestead) virtual machine.

However, if you are not using Homestead, you will need to make sure your server meets the following requirements:

* PHP >= 7.1.3
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension

### Serving Your Application

To serve your project locally, you may use:

* [Laravel Homestead](https://laravel.com/docs/5.8/homestead) virtual machine.

*  the built-in PHP development server:

```
cd path/to/your/project
php -S localhost:8000 -t public
```

### Configuration

It's time to configure your application.

* After cloning this project, you need to duplicate `.env_example` and name it `.env`
* Open `.env` file, and specify the following values:

```
APP_KEY=random string
APP_URL=
FREEGEOIP=free geoip access key api
OPENWEATHERMAP_ACCESS_KEY=openweathermap access key api

```
