<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OsrmService;
use Illuminate\Http\Request;

class RoutingController extends Controller
{
    protected $osrmService;
    public function __construct(OsrmService $osrmService)
    {
        $this->osrmService = $osrmService;
    }



//    public function calculateDistance(Request $request)
//    {
//        $request->validate([
//            'start_lat' => 'required|numeric',
//            'start_lng' => 'required|numeric',
//            'end_lat' => 'required|numeric',
//            'end_lng' => 'required|numeric',
//        ]);
//        try {
//            $result = $this->osrmService->getDistanceAndDuration(
//                $request->start_lat,
//                $request->start_lng,
//                $request->end_lat,
//                $request->end_lng
//            );
//            return response()->json([
//                'success' => true,
//                'message' => 'تم حساب المسار بنجاح',
//                'data' => $result
//            ]);
//        }catch (\Exception $e){
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], 400);
//        }
//    }


}
