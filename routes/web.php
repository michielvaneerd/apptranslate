<?php

declare(strict_types=1);

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'loginPost']);

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/tfa/verify', [LoginController::class, 'tfaVerify'])->name('tfa.verify');
    Route::post('/tfa/verify', [LoginController::class, 'tfaVerifyPost']);
    Route::middleware('tfa_verified')->group(function () {
        Route::get('/tfa/authenticate', [LoginController::class, 'tfaAuthenticate'])->name('tfa.authenticate');
        Route::post('/tfa/authenticate', [LoginController::class, 'tfaAuthenticatePost']);
        Route::middleware('tfa_authenticated')->group(function () {
            Route::get('/', function () {
                return view('index');
            })->name('index');
        });
    });
});
