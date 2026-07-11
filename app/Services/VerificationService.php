<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class VerificationService
{


    public function sendOTP($model , $prefix = "driver")
    {
        if ($model->is_verified){
            throw new Exception('الحساب موثق بالفعل!');
        }
        $otp = rand(1000,9999);

        $cacheKey = "otp_{$prefix}_{$model->id}";
        Cache::put($cacheKey, $otp, now()->addMinutes(1));
        Log::info("رمز التحقق لـ {$prefix} رقم {$model->id} هو: {$otp}");
        return true;
    }

    public function verifyOTP($model , $otp ,$prefix = "driver")
    {
        $cacheKey = "otp_{$prefix}_{$model->id}";
        $cachedOTP = Cache::get($cacheKey);
        if (!$cachedOTP) {
            throw new Exception('الكود منتهي الصلاحية أو غير موجود.');
        }

        if ($cachedOTP != $otp) {
            throw new Exception('الكود غير صحيح.');
        }

        $model->update(['is_verified' => true]);

        // مسح الكود من الذاكرة لعدم استخدامه مرة أخرى
        Cache::forget($cacheKey);
        return true;

    }
}
