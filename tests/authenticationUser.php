<?php

namespace Tests;

use App\Models\User;

trait AuthenticationUser
{
    public function authUser()
    {
        $user = User::factory()->create();

        if ($user) {
            $token =  $user->createToken('authToken')->accessToken;
            $this->actingAs($user, 'api');
            return [
                'user' => $user,
                'token' => $token
            ];
        }
    }
}
