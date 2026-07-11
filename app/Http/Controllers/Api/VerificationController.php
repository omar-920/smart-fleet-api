<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VerificationService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected $verificationService;
    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function sendOTP(Request $request)
    {
        try{
        $driver = $request->user()->driver;
        $this->verificationService->sendOTP($driver , 'driver');
        return response()->json([
            'message' => 'تم إرسال كود التحقق بنجاح، الكود صالح لمدة 5 دقائق.'
        ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);


        try {
            $otp = $request->otp;
            $driver = $request->user()->driver;
            $this->verificationService->verifyOTP($driver , $otp , 'driver');
            return response()->json([
                'message' => 'تم توثيق الحساب بنجاح!'
            ]);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

}
