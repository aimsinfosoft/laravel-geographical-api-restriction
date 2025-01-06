<?php

namespace Laravel\GeoRestriction\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeoRestrictionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $restriction = config('georestriction.restriction', 0);
        $error_message = config('georestriction.error_message', '');
        if($error_message == ''){
            $error_message = 'Access denied due to geographical restrictions.';
        }
        $userIp = $request->ip();
        $geoData = Cache::remember("geoData_{$userIp}", 3600, function () use ($userIp) {
            return $this->getGeoData($userIp);
        });
        if($restriction == 1){

            if (!$geoData || !isset($geoData['countryCode'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unable to determine your location.'
                ], 403);
            }

            $userCountry = $geoData['countryCode'];
            $allowedCountries = config('georestriction.allowed_countries', []);
            $blockedCountries = config('georestriction.blocked_countries', []);
            
            if (in_array($userCountry, $blockedCountries)) {
                return response()->json([
                    'status' => 'error',
                    'message' => $error_message
                ], 403);
            }

            if (!empty($allowedCountries) && !in_array($userCountry, $allowedCountries)) {
                return response()->json([
                    'status' => 'error',
                    'message' => $error_message
                ], 403);
            }
        } else if($restriction == 2){

            if (!$geoData || !isset($geoData['region'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unable to determine your location.'
                ], 403);
            }

            $userRegion = $geoData['region'];
            $allowedRegions = config('georestriction.allowed_regions', []);
            $blockedRegions = config('georestriction.blocked_regions', []);
            
            if (in_array($userRegion, $blockedRegions)) {
                return response()->json([
                    'status' => 'error',
                    'message' => $error_message
                ], 403);
            }

            if (!empty($allowedRegions) && !in_array($userRegion, $allowedRegions)) {
                return response()->json([
                    'status' => 'error',
                    'message' => $error_message
                ], 403);
            }
        }

        return $next($request);
    }

    private function getGeoData(string $ip): ?array
    {
        $response = Http::get("http://ip-api.com/json/{$ip}");

        if (!$response->successful()) {
            \Log::error('GeoIP API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        return $response->json();
    }
}
