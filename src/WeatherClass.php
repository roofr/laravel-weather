<?php

namespace Roofr\Weather;

use GuzzleHttp\Client;
use Illuminate\Cache\CacheManager;

class WeatherClass
{
    /**
     * Create a new Skeleton Instance.
     * @param CacheManager $cache
     * @param $config
     */
    public function __construct(CacheManager $cache, $config)
    {
        $this->cache = $cache;
        $this->config = $config;
    }

    /**
     * Returns weather for a given location (ie. Naples, Fl)
     *
     * @param $address
     * @return array|\Illuminate\Contracts\Cache\Repository
     */
    public function query($address) {
        $address = strtolower(str_replace(' ', '', $address));
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

        // Build response
        $data = [
            'weather' => json_decode($response->getBody(), true),
            'location' => $location,
        ];

        $this->cache->put($cacheKey, $data, 60*24);

        return $data;
    }

    /**
     * Returns the coordinates from a given location
     *
     * @param $location
     * @return array
     */
    private function getCoordinates($location) {
        return [$location->getLatitude(),$location->getLongitude()];
    }

    /**
     * Gets the location via geocoder
     *
     * @param $address
     * @return mixed
     */
    private function getLocation($address) {
        return \Geocoder::geocode($address)->get()->first();
    }
}
