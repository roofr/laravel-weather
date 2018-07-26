<?php

namespace Roofr\Weather;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Roofr\Weather\SkeletonClass
 */
class WeatherFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'weather';
    }
}
