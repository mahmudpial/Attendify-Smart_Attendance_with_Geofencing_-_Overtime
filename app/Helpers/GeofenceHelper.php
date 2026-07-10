<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class GeofenceHelper
{
    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    public static function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    /**
     * Check if coordinates are within office geofence
     */
    public static function isWithinGeofence($lat, $lng)
    {
        // Geofencing is opt-in. If it's not explicitly enabled, don't enforce it.
        if (!config('geofence.enabled')) {
            return true;
        }

        $officeLat = config('geofence.office_lat');
        $officeLng = config('geofence.office_lng');
        $radius = config('geofence.radius_meters');

        // Fail closed: geofencing is turned on but office coordinates are missing.
        // This is a misconfiguration, not a reason to silently let everyone through.
        if ($officeLat === null || $officeLng === null) {
            return false;
        }

        $distance = self::distance($lat, $lng, $officeLat, $officeLng);
        return $distance <= $radius;
    }
}