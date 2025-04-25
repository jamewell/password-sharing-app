<?php

use App\Http\Controllers\PasswordShareController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PasswordShareController::class, 'create'])
    ->name('password.create')
    ->middleware('throttle:10,1');

Route::get('/password/{id}', [PasswordShareController::class, 'show'])
    ->name('password.show');
