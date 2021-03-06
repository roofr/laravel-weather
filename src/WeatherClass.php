<?php

namespace Roofr\Weather;

use Geocoder\Laravel\Facades\Geocoder;
use GuzzleHttp\Client;
use Illuminate\Cache\CacheManager;
use Illuminate\View\Factory;

class WeatherClass
{
    /**
     * Create a new WeatherClass Instance.
     * @param CacheManager $cache
     * @param Factory $view
     * @param $config
     */
    public function __construct(CacheManager $cache, Factory $view, $config)
    {
        $this->cache = $cache;
        $this->view = $view;
        $this->config = $config;
    }

    /**
     * Generate an html view
     * @param $address
     * @return \Illuminate\Contracts\Cache\Repository|\Illuminate\Contracts\View\View
     */
    public function generate($address)
    {
        if (!$this->config['enabled']) return null;

        $address = strtolower($address);
        $data = $this->query($address);
        $view = "laravel-weather::widget.{$this->config['view']}";
        $cacheKey = "{$view}-{$address}";

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $view = $this->view->make($view, $data)->render();

        $this->cache->put($cacheKey, $view, 60*24);

        return $view;
    }

    /**
     * Returns weather for a given location (ie. Naples, Fl)
     * @param $address
     * @return array|\Illuminate\Contracts\Cache\Repository
     */
    public function query($address) {
        $address = strtolower($address);
        $cacheKey = "laravel-weather.{$address}";

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        // Get location data + coordinates
        $location = $this->getLocation($address);
        $coordinates = implode(',', $this->getCoordinates($location));

        // Fire API call
        $client = new Client();
        $uri = "https://api.darksky.net/forecast/{$this->config['api_key']}/{$coordinates}?exclude=hourly,minutely,alerts";
        $response = $client->get($uri);
        $weather = json_decode($response->getBody(), true);

        // Build response
        $data = [
            'weather' => $weather,
            'location' => $location,
            'meta_data' => [
                'max_windspeed' => $this->getMaxWindspeed($weather),
                'rainy_days' => $this->getRainyDays($weather),
            ]
        ];

        $this->cache->put($cacheKey, $data, 60*24);

        return $data;
    }

    /**
     * Get the number of rainy days this week
     * @param $weather
     * @return array|null
     */
    private function getRainyDays($weather) {
        if (!isset($weather['daily']) || !count($daily = $weather['daily']['data']) > 0) return null;
        $rainyDayCount = collect($daily)->where('icon', 'rain')->count();
        return $rainyDayCount;
    }

    /**
     * Get the number of rainy days this week
     * @param $weather
     * @return array|null
     */
    private function getMaxWindspeed($weather) {
        if (!isset($weather['daily']) || !count($daily = $weather['daily']['data']) > 0) return null;
        return round(collect($daily)->pluck('windGust')->max());
    }

    /**
     * Returns the coordinates from a given location
     * @param $location
     * @return array
     */
    private function getCoordinates($location) {
        return [$location->getLatitude(),$location->getLongitude()];
    }

    /**
     * Gets the location via geocoder
     * @param $address
     * @return mixed
     */
    private function getLocation($address) {
        return Geocoder::geocode($address)->get()->first();
    }
}
