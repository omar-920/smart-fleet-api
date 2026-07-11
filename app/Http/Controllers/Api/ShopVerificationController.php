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
        // هنا بنجيب المتجر بدل السائق
        $shop = $request->user()->shop;

        // بنمرر المتجر ونقوله إن الـ Prefix اسمه 'shop'
        $this->verificationService->sendOTP($shop, 'shop');

        return response()->json(['message' => 'تم إرسال الكود للمتجر!']);
    }

    public function verifyOtp(Request $request)
    {
        // التأكد إن المتجر باعت الكود في الريكويست
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        try {
            // بنجيب بيانات المتجر اللي عامل لوجين
            $shop = $request->user()->shop;

            // بنبعت المتجر، والكود اللي دخله، وبنأكد إن الـ Prefix هو 'shop'
            $this->verificationService->verifyOtp($shop, $request->otp, 'shop');

            return response()->json([
                'message' => 'تم توثيق حساب المتجر بنجاح!'
            ]);
        } catch (\Exception $e) {
            // لو الكود غلط أو وقته خلص، الـ Service هترمي Exception وهنمسكه هنا
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
