<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PasswordShareController extends Controller
{
    public function create(): View
    {
        return view('password.create');
    }
}
