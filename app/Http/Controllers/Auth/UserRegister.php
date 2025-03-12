<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterFormRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRegister extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserRegisterFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create($request->validated());
            DB::commit();
            $user->save();
            if ($user) {
                return response()->json(['message' => 'User registered successfully', UserResource::make($user)], 201);
            } else {
                return response()->json(['message' => 'Failed to register user'], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to register user'], 500);
        }
    }
}
