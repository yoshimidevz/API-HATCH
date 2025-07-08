<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return ApiResponse::unauthorized();
        }

        $user = auth()->user();

        // Define as abilities com base no papel do usuário
        $abilities = match ($user->role) {
            'admin' => ['admin'], // ou ['admin']
            'client' => ['clients:list', 'clients:view'], // apenas as rotas permitidas
            default => [],
        };

        $token = $user->createToken($user->name, $abilities, now()->addHour())->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'email' => $user->email,
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client', // ou outro valor default
        ]);

        // Define as abilities conforme o papel
        $abilities = ['clients:list', 'clients:view'];

        $token = $user->createToken($user->name, $abilities, now()->addHour())->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return ApiResponse::success([
            'message' => 'Logout realizado com sucesso'
        ]);
    }
}
