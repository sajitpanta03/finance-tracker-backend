<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Models\User;
use App\Services\ApiResponseService;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use ApiResponseTrait;

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ApiResponseService::error('Email not found');
        }

        $token = Password::createToken($user);
        $resetUrl = route('forgot-password', ['token' => $token, 'email' => $request->email]);

        Mail::to($user->email)->send(new ForgotPassword($resetUrl));

        return ApiResponseService::custom(true, 'Reset link sent to your email');
    }
}
