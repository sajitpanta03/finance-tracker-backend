<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);


        $checkInRestTable = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$checkInRestTable) {
            return response()->json(['message' => 'Invalid email.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $checkToken = Hash::check($request->token, $checkInRestTable->token);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($checkToken) {
            $user->password = Hash::make($request->password);
            $user->setRememberToken(Str::random(60));

            $user->save();

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        } else {
            return response()->json(['message' => 'Sorry problem occured.'], 200);
        }

        return response()->json(['message' => 'Password reset successfully.'], 200);
    }
}
