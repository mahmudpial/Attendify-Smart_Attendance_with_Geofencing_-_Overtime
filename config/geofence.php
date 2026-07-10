<?php

return [
    'enabled' => env('GEOFENCE_ENABLED', false),
    'office_lat' => env('OFFICE_LAT'),
    'office_lng' => env('OFFICE_LNG'),
    'radius_meters' => env('GEOFENCE_RADIUS', 100),
];