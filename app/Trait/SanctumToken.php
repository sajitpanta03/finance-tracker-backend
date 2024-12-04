<?php

namespace App\Trait;

use App\Models\User;

trait SanctumToken
{
    /**
     * to generate sanctum using trait
     * 
     * @parems \App\Models\User $user
     * 
     * @return string token
     */
    public function GenerateToken(User $user, bool $remember):string
    {
        $experation = $remember ? null : now()->addHours(2);
        return $user->createToken('login',[],$experation)->plainTextToken;
        // $remember ? now()->addMonth() : now()->addHours(2)
    }
}
