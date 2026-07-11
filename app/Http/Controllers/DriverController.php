<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function toggleStatus(Request $request)
    {
        $driver = $request->user()->driver;

        // بنعكس الحالة (لو 1 تبقى 0، ولو 0 تبقى 1)
        $driver->is_active = !$driver->is_active;
        $driver->save();

        $statusMessage = $driver->is_active ? 'أنت الآن متصل وتتلقى الطلبات' : 'أنت الآن غير متصل';

        // ملحوظة مهمة: لو بقى أوفلاين، نمسح مكانه القديم من Redis عشان ميتعلقش
        if ($driver->is_active == 0) {
            \Illuminate\Support\Facades\Redis::zrem('drivers_locations', $driver->user_id);
        }

        return response()->json([
            'message' => $statusMessage,
            'is_active' => $driver->is_active
        ]);
    }
}
