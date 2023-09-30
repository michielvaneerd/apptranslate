<?php

declare(strict_types=1);

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'loginPost']);

Route::get('/email/verify/{token}', [LoginController::class, 'emailVerify'])->name('email.verify');

Route::middleware('auth')->group(function () {

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/tfa/verify', [LoginController::class, 'tfaVerify'])->name('tfa.verify');
    Route::post('/tfa/verify', [LoginController::class, 'tfaVerifyPost']);

    Route::middleware('tfa_verified')->group(function () {

        Route::get('/tfa/authenticate', [LoginController::class, 'tfaAuthenticate'])->name('tfa.authenticate');
        Route::post('/tfa/authenticate', [LoginController::class, 'tfaAuthenticatePost']);

        Route::middleware('tfa_authenticated')->group(function () {

            Route::get('/', function () {
                return view('home');
            })->name('home');

            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users/create', [UserController::class, 'store']);
            Route::get('/users/{user}/view', [UserController::class, 'view'])->name('users.view');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::post('/users/{user}/edit', [UserController::class, 'update']);

        });
    });
});
