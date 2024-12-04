<?php

use App\Http\Controllers\Auth\loginController;
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

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/logout' , [loginController::class , 'logout']);
    });
});

Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('password/reset', [ResetPasswordController::class, 'reset']);
