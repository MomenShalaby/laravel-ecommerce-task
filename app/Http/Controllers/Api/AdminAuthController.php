<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthController extends Controller
{
    use HttpResponses;

    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated($request->all());
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('admin')->attempt($credentials);
        if (!$token) {
            return $this->error('Credentials do not match', 401);
        }
        $user = Auth::guard('admin')->user();
        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(): JsonResponse
    {
        JWTAuth::parseToken()->invalidate(true);
        return $this->success('', 'logged out successfully');
    }

    public function me(): JsonResponse
    {
        return $this->success([
            'user' => Auth::guard('admin')->user(),
        ]);
    }

    public function refresh()
    {
        $user = Auth::user();
        $token = Auth::refresh();
        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }

}

