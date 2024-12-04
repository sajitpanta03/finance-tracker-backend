<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return request()->all();
});


Route::prefix('v1')->group(function (){
    Route::get('/login',[loginController::class , 'login']);
    Route::post('/register',[RegisterUserController::class , 'store']);

    // password change
    Route::post('/password-change', [PasswordChangeController::class, 'passwordChange'])->name('password-change');

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/logout' , [loginController::class , 'logout']);
    });
});

Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');
Route::post('password-reset', [ResetPasswordController::class, 'reset'])->name('password-reset');
