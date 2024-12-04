<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('password/reset', [ResetPasswordController::class, 'reset']);
