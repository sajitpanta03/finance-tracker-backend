<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return request()->all();
});


Route::prefix('v1')->group(function (){
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/logout' , [loginController::class , 'logout']);
    });

    /**
     * login and register routes
     * dont need any middleware
     */
    Route::get('/login',[loginController::class , 'login']);
    Route::post('/register',[RegisterUserController::class , 'store']);
});

Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('password/reset', [ResetPasswordController::class, 'reset']);
