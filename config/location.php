<?php

return [

    'driver' => Stevebauman\Location\Drivers\Cloudflare::class,

    'fallbacks' => [
        Stevebauman\Location\Drivers\Ip2locationio::class,
        Stevebauman\Location\Drivers\IpInfo::class,
        Stevebauman\Location\Drivers\GeoPlugin::class,
        Stevebauman\Location\Drivers\MaxMind::class,
    ],

    'position' => Stevebauman\Location\Position::class,

    'testing' => [
        'ip' => '66.102.0.0',
        'enabled' => env('LOCATION_TESTING', true),
    ],

    'maxmind' => [
        'web' => [
            'enabled' => false,
            'user_id' => env('MAXMIND_USER_ID'),
            'license_key' => env('MAXMIND_LICENSE_KEY'),
            'options' => ['host' => 'geoip.maxmind.com'],
        ],

        'local' => [
            'type' => 'city',
            'path' => database_path('maxmind/GeoLite2-City.mmdb'),
            'url' => sprintf('https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&license_key=%s&suffix=tar.gz', env('MAXMIND_LICENSE_KEY')),
        ],
    ],

    'ip_api' => [
        'token' => env('IP_API_TOKEN'),
    ],

    'ipinfo' => [
        'token' => env('IPINFO_TOKEN'),
    ],

    'ipdata' => [
        'token' => env('IPDATA_TOKEN'),
    ],

    'ip2locationio' => [
        'token' => env('IP2LOCATIONIO_TOKEN'),
    ],

    'kloudend' => [
        'token' => env('KLOUDEND_TOKEN'),
    ],

];
