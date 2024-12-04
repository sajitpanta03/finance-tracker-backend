<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found.'], 404);
        }

        $token = Password::createToken($user);

        $resetUrl = url("/password/reset/{$token}?email={$request->email}");

        Mail::to($user->email)->send(new ForgotPassword($resetUrl));

        return response()->json(['message' => 'Reset link sent to your email.'], 200);
    }
}
