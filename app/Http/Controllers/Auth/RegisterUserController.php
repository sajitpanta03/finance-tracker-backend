<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\User;
use App\Services\ApiResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Trait\SanctumToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class RegisterUserController extends Controller
{
    use SanctumToken;


    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */


    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|unique:" . User::class,
            "password" => "required|min:8|max:16"
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'remember' => $request->remember
            ]);

            $this->sendVerificationEmail($user);

            $authToken = $this->GenerateToken($user, $request->has('remember'));

            DB::commit();

            return ApiResponseService::success([
                "user" => $user,
                "token" => $authToken
            ], "User registration successful", 201);
        } catch (Exception $e) {

            DB::rollback();

            return ApiResponseService::error(
                "User registration successful",
                $e->getMessage(),
                500
            );
        }
    }


    /**
     * Summary of emailVerification
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function emailVerification(Request $request)
    {
        try {
            $user = User::whereRaw('SHA1(email) = ?', $request->hash)->first();
            if (! $user) {
                return response()->json([
                    'massage' => 'Email not found'
                ], 200);
            }

            if (! Hash::check($request->token, $user->email_verification_token)) {
                return response()->json([
                    'massage' => `Token  didn't matched or expired`
                ], 200);
            }

            DB::beginTransaction();
            $user->update([
                'email_verified_at' => now(),
                'email_verification_token' => null
            ]);

            DB::commit();
            return response()->json([
                "massage" => "Email-verification successful"
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "massage" => $e
            ], 400);
        }
    }


    /**
     * Summary of resendVerificationEmail
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function resendVerificationEmail(Request $request)
    {
        // for resend email verification
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || $user->email_verified_at) {
                return response()->json([
                    "massage" => 'User not found or email is verified'
                ], 400);
            }
            // send verification email to user
            $this->sendVerificationEmail($user);
            return response()->json([
                "massage" => "Verification email sent successful"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "massage" => $e
            ]);
        }
    }

    /**
     * Summary of sendVerificationEmail
     * @param \App\Models\User $user
     * @return void
     */

    public function sendVerificationEmail(User $user)
    {
        //checks for token collision
        do {
            $verificationToken = sha1(Str::random(60));
        } while (User::where('email_verification_token', $verificationToken)->exists());


        $userDetails = $user->update([
            'email_verification_token' => Hash::make($verificationToken),
        ]);

        // creates url for email verification
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(2),
            ['token' => $verificationToken, 'hash' => sha1($user->email)]
        );


        Mail::to($user->email)->send(new EmailVerification($verificationUrl));

        return;
    }
}
