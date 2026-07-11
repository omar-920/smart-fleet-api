<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Driver;

class LiveLocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $userId = auth()->id();

        $driver = Driver::where('user_id', $userId)->first();

        if (!$driver || $driver->is_active == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'عفواً، حسابك غير مفعل أو أنت في وضع الأوفلاين حالياً.'
            ], 403);
        }

        Redis::geoadd('drivers_locations', $request->lng, $request->lat, $userId);

        $currentDriverLocation = Redis::geopos('drivers_locations', $userId)[0];

        return response()->json([
            'status' => 'success',
            'message' => 'Live Location Added Successfully',
            'driver_id' => $userId,
            'saved_location' => [
                'lng' => $currentDriverLocation[0],
                'lat' => $currentDriverLocation[1],
            ]
        ]);
    }
}
