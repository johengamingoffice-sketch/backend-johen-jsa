<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required_without:username|string|email',
            'username' => 'required_without:email|string',
            'password' => 'required|string',
        ]);

        $user = User::when($request->email, fn($q) => $q->where('email', $request->email))
            ->when($request->username, fn($q) => $q->where('username', $request->username))
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email/username atau password salah.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user->load('employee'),
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load('employee'),
        ]);
    }
}
