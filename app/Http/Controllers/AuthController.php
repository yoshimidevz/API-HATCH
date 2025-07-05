<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiResponse;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $attempt = auth()->attempt(['email' => $email, 'password' => $password]);

        if (!$attempt) {
            return ApiResponse::unauthorized();
        }

        $user = auth()->user();
        $token = $user->createToken($user->name)->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'email' => $email,
            'token' => $token
        ]);
    }
}
