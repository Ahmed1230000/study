<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserLogin extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserLoginFormRequest $request)
    {
        try {
            $Credentials = $request->only('email', 'password');
            if (!Auth::attempt($Credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;
            return response()->json([
                'message' => 'User logged in successfully',
                'meta' => [
                    'User' => $user,
                    'Token' => $token,
                    'token_type' => 'Bearer',
                    'date_now' => date('Y-m-d H:i:s'),
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to login user', $e->getMessage()], 500);
        }
    }
}
