<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return request()->all();
});


Route::prefix('v1')->group(function () {
    Route::post('/login', [loginController::class, 'login']);
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::post('/resend/email-Verification',[RegisterUserController::class, 'resendVerificationEmail']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/password-reset', [ResetPasswordController::class, 'reset'])->name('password-reset');
    Route::post('/password-change', [PasswordChangeController::class, 'passwordChange'])->name('password-change');
    Route::post('/expenses', [ExpensesController::class, 'store'])->name('add-expenses');
    Route::get('/expenses', [ExpensesController::class, 'index'])->name('retrive-expenses');
    Route::get('/expenses/{expense}', [ExpensesController::class, 'show'])->name('showSpecific-expense');
    Route::get('/expenses/{expense}/edit', [ExpensesController::class, 'edit'])->name('edit-expense');
    Route::put('/expenses/{expense}', [ExpensesController::class, 'update'])->name('update-expense');
    Route::delete('/expenses/{expense}', [ExpensesController::class, 'destroy'])->name('delete-expense');
    Route::get(
        '/email/verify/{token}/{hash}',
        [RegisterUserController::class, 'emailVerification']
    )->middleware(['signed'])->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [loginController::class, 'logout']);
    });
});
