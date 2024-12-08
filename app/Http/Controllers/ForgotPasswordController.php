<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Models\User;
use App\Services\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
        } catch (ValidationException $e) {

            return ApiResponseService::error('Error', $e->errors());
        }

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
