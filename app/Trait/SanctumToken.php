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
    public function GenerateToken(User $user):string
    {

        return $user->createToken('login')->plainTextToken;
    }
}
