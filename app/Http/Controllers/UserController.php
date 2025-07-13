<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function login(Request $requests): JsonResponse|array
    {
        $payload = $requests->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($requests->only('username', 'password'))) {
            return response()->json([
                'message' => 'Username atau password salah',
            ], 403);
        }

        Log::channel('stderr')->info($requests->user());
        $token = $requests->user()->createToken('auth_token');

        return ['token' => $token->plainTextToken];
    }

    public function logout(Request $requests): JsonResponse
    {
        $requests->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil logout',
        ]);
    }
}
