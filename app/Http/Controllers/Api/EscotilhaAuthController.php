<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Escotilha;
use App\Models\User;
use App\Services\ApiResponse;

class EscotilhaAuthController extends Controller
{
    public function registerOrLogin(Request $request){
        $request->validate([
            'serial_number' => 'required|string'
        ]);


        $escotilha = Escotilha::firstOrCreate([
            'serial_number' => $request->serial_number
        ]);

        $user = User::firstOrCreate(
            [
                'id' => 1
            ],
            [
                'name' => 'Default User',
                'email' => 'default@example.com',
                'password' => bcrypt('password')
            ]
        );
        $token = $user->createToken("escotilha-{$escotilha->serial_number}", ['escotilha:send-data'])->plainTextToken;

        return ApiResponse::success([
            'message' => 'Escotilha registrada/login efetuado',
            'token' => $token,
        ], 201);
    }
}
