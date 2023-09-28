<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TfaAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('myapp.tfa_enabled') && ! $request->session()->has(User::SESSION_KEY_TFA_AUTHENTICATED)) {
            return redirect()->route('tfa.authenticate');
        }

        return $next($request);
    }
}
