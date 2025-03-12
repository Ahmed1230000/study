<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLogOut extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->tokens()->delete();
                return response()->json(['message' => 'User logged out successfully'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to logout user', $e->getMessage()], 500);
        }
    }
}
