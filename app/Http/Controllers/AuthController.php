<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterDriverRequest;
use App\Http\Requests\RegisterShopRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Driver;
use App\Models\Shop;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registerShop(RegisterShopRequest $request)
    {
        $result = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role'=> 'shop',
            ]);

            $shop = Shop::create([
                'user_id' => $user->id,
                'store_name' => $request->store_name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'shop' => $shop,
                'token'=> $token
            ];
        });
        return response()->json([
            'message' => 'Shop registered successfully',
            'data' => $result,
        ], 201);
    }
    public function registerDriver(RegisterDriverRequest $request)
    {
        $result = DB::transaction(function() use ($request) {
            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> Hash::make($request->password),
                'role'=> 'driver',
            ]);
            $driver = Driver::create([
                'user_id'=> $user->id,
                'vehicle_type'=> $request->vehicle_type,
                'plate_number'=> $request->plate_number,
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return [
                'user'=> $user,
                'driver'=> $driver,
                'token'=> $token
            ];
        });
        return response()->json([
            'message'=> 'Driver registered successfully',
            'data'=> $result,
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details please try again',
            ], 401);
        }
        $user = User::where('email' , $request->email)->firstOrFail();
        if ($user->role === 'shop' ) {
            $user->load('shop');
        }
        if ($user->role === 'driver') {
            $user->load('driver');
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => $user,
            'token' => $token
        ]);

    }

     public function logoutAll()
     {
         Auth::user()->tokens()->delete();
         return response()->json([
             'message' => 'تم تسجيل الخروج بنجاح',
         ]);
     }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'logged out successfully',
        ]);
    }


}
