<?php

use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\TestMailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-email', [ForgotPasswordController::class, 'sendTestEmail']);


Route::get(
        '/email/verify/{token}/{hash}',
        [RegisterUserController::class, 'emailVerification']
    )->middleware(['signed'])->name('verification.verify');
