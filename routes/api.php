<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::post('/register/shop', [AuthController::class, 'registerShop']);
    Route::post('/register/driver', [AuthController::class, 'registerDriver']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);

        Route::prefix('shop')->group(function () {
            Route::post('/orders', [OrderController::class, 'store']);
            Route::get('/orders', [OrderController::class, 'index']);
            Route::post('/send-otp', [\App\Http\Controllers\Api\ShopVerificationController::class, 'sendOTP'])->middleware('throttle:3,1');
            Route::post('/verify-otp', [\App\Http\Controllers\Api\ShopVerificationController::class, 'verifyOTP']);
        });

        Route::prefix('driver')->group(function () {
            Route::post('/location', [\App\Http\Controllers\LiveLocationController::class, 'store']);
            Route::post('/orders/{id}/accept', [OrderController::class, 'acceptOrder']);
            Route::post('/orders/{id}/deliver', [OrderController::class, 'deliverOrder']);
            // حماية الـ OTP
            Route::post('/send-otp', [\App\Http\Controllers\Api\VerificationController::class, 'sendOTP'])->middleware('throttle:3,1');
            Route::post('/verify-otp', [\App\Http\Controllers\Api\VerificationController::class, 'verifyOTP']);
            Route::post('/toggle-status', [\App\Http\Controllers\DriverController::class, 'toggleStatus']);
        });
    });

