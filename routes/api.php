<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return request()->all();
});
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function (){

    Route::get('/login',[loginController::class , 'login']);
    Route::post('/register',[RegisterUserController::class , 'store']);

});