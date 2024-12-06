<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Trait\SanctumToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    use SanctumToken;

    public function index(): void {}
    public function login(LoginRequest $request)
    {

        $user =  $request->authenticate();

        $remember = "true";
        return response()->json([
            "data" => [
                'user' => Auth::user(),
                "token" => $this->GenerateToken($user, $remember)
            ],
            "massage" => " login successful"
        ],200);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
            ]
            ,200
        );
    }
}
