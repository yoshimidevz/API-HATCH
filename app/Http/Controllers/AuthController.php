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

        $abilities = match ($user->role) {
            'admin' => ['admin'],
            'client' => ['clients:list', 'clients:view'],
            'enterprise' => ['enterprise'],
            'escotilha' => ['escotilha:send-data'],
            default => [],
        };

        if($user->role === 'escotilha'){
            $token = $user->createToken($user->name, $abilities)->plainTextToken;
        } else {
            $token = $user->createToken($user->name, $abilities, now()->addHour())->plainTextToken;
        }

        return ApiResponse::success([
            'message' => 'Escotilha registrada/login efetuado',
            'data' => [
                'token' => $token,
            ],
        ], 201);
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

    public function registerEscotilha(Request $request){

        $request->validate([
            'serial_number' => 'required|string|exists:escotilhas,serial_number',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6'
        ]);

        $escotilha = Escotilha::where('serial_number', $request->serial_number)->first();

        if (!$escotilha) {
            return response()->json(['error' => 'Serial não encontrado'], 404);
        }

        if ($escotilha->user_id) {
            return response()->json(['error' => 'Escotilha já vinculada'], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->serial_number . '@escotilha.local',
            'password' => Hash::make($request->password),
            'role' => 'escotilha',
        ]);

        $escotilha->user_id = $user->id;
        $escotilha->save();

        $token = $user->createToken("escotilha-{$request->serial_number}", ['escotilha:send-data'])->plainTextToken;

        return response()->json([
            'user' => $user,
            'serial_number' => $request->serial_number,
            'token' => $token,
            'endpoint' => url('/api/sensores')
        ], 201);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return ApiResponse::success([
            'message' => 'Logout realizado com sucesso'
        ]);
    }
}
