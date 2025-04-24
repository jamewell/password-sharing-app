<?php

use App\Http\Controllers\PasswordShareController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PasswordShareController::class, 'create'])->name('password.create');
