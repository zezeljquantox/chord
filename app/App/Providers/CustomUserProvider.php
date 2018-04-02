<?php

namespace Chord\App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class CustomUserProvider extends EloquentUserProvider
{
    /**
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        return true;
    }

    /**
     * @param UserContract $user
     * @param string $token
     */
    public function updateRememberToken(UserContract $user, $token){}
}