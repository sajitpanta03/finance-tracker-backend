<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\ApiResponseService;
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

        $remember = $request->has('remember');
        $authToken = $this->GenerateToken($user, $remember);

        return ApiResponseService::success([
            "user" => $user,
            "token" => $authToken
        ], "User login successful", 200);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return ApiResponseService::success(
            [],
            "logout successful",
            204
        );
    }
}
