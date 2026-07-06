<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return $this->authResponse($user, 'Personal Access Token');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->authResponse(Auth::user());
    }

    private function authResponse(User $user, string $tokenName = 'auth-token'): JsonResponse
    {
        return response()->json([
            'type' => 'success',
            'token' => $user->createToken($tokenName)->plainTextToken,
            'user' => $user,
        ]);
    }
}
