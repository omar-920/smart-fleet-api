<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OsrmService
{
    protected $baseUrl = 'http://router.project-osrm.org/route/v1/driving';

    public function getDistanceAndDuration($lat1, $lon1, $lat2, $lon2)
    {
        $coordinates = "{$lat1},{$lon1};{$lat2},{$lon2}";
        $url = "{$this->baseUrl}/{$coordinates}";
try{
        $response = Http::timeout(5)
            ->retry(2, 100)
            ->get($url);
        if ($response->failed()) {
            Log::error('OSRM API Error: ' . $response->body());
            throw new \Exception('فشل في الاتصال بخدمة الخرائط.');
        }
        $data = $response->json();
        if ($data['code'] !== 'Ok' || empty($data['routes'])) {
            throw new \Exception('لم يتم العثور على مسار بين النقطتين.');
        }
    $distanceInMeters = $data['routes'][0]['distance'];
    $durationInSeconds = $data['routes'][0]['duration'];
        return [
            'distance_km' => round($distanceInMeters / 1000, 2),
            'duration_min' => round($durationInSeconds / 60, 2),
        ];

        } catch (\Exception $e) {
            Log::error('OSRM Exception: ' . $e->getMessage());
        throw new \Exception('حدث خطأ أثناء حساب المسافة.');
        }

    }
}
