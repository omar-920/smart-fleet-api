<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VerificationService;
use Illuminate\Http\Request;

class ShopVerificationController extends Controller
{
    protected $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function sendOtp(Request $request)
    {
        $shop = $request->user()->shop;

        $this->verificationService->sendOTP($shop, 'shop');

        return response()->json(['message' => 'تم إرسال الكود للمتجر!']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        try {
            $shop = $request->user()->shop;

            $this->verificationService->verifyOtp($shop, $request->otp, 'shop');

            return response()->json([
                'message' => 'تم توثيق حساب المتجر بنجاح!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
