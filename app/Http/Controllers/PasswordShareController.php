<?php

namespace App\Http\Controllers;

use App\Models\PasswordShare;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordShareController extends Controller
{
    public function create(): View
    {
        return view('password.create');
    }

    public function show(Request $request, string $id): View
    {
        if (! $request->hasValidSignature()) {
            abort(Response::HTTP_FORBIDDEN, 'Invalid or expired Link.');
        }

        /* @phpstan-ignore-next-line */
        $passwordShare = PasswordShare::findOrFail($id);

        if ($passwordShare->isExpired()) {
            abort(Response::HTTP_FORBIDDEN, 'This link has expired or reached its maximum uses');
        }

        $passwordShare->decrementRemainingUses();

        return view('password.show')->with([
            'password' => decrypt($passwordShare->password),
            'isLastUse' => $passwordShare->remaining_uses <= 0,
        ]);
    }
}
