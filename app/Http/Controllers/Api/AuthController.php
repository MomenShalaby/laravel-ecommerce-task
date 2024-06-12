<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HttpResponses;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */



    public function register(RegisterUserRequest $request): JsonResponse
    {
        // dd("asd");
        $request->validated($request->all());

        $user = User::create([
            'fname' => ucfirst($request->fname),
            'lname' => ucfirst($request->lname),
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = Auth::login($user);

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'Registered successfully', 201);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated($request->all());
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return $this->error('Email or password is wrong', 401);
        }
        $user = Auth::user();
        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'Logged in Successfully');
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->success([
            'user' => Auth::user(),
        ]);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {

        JWTAuth::parseToken()->invalidate(true);

        return $this->success('', 'logged out successfully');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $user = Auth::user();
        $token = Auth::refresh();
        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }


}
