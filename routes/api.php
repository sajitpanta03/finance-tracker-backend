<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Auth\RegisterUserController;
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