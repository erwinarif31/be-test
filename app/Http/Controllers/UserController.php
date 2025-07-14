<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponse;

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

        $token = $requests->user()->createToken('auth_token');
        $user = $requests->user();


        return $this->success(
            message: 'Login successful',
            data: [
                'token' => $token->plainTextToken,
                'admin' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
            ]
        );
    }

    public function logout(Request $requests): JsonResponse
    {
        $requests->user()->currentAccessToken()->delete();

        return $this->success(
            message: 'Logout successful',
            data: null
        );
    }
}
