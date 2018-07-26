<h2>
    {{$location->getLocality()}}
</h2>

<p>
    {{$weather['daily']['summary']}}
</p>

<p>
    It is supposed to rain {{$meta_data['rainy_days']}} {{str_plural('day', $meta_data['rainy_days'])}} this week
</p>

<p>
    The max wind speed will be {{$meta_data['max_windspeed']}}mph
</p>
