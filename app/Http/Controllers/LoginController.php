<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    public function login()
    {
        $fields = [
            [
                'type' => 'email',
                'name' => 'email',
                'required' => true,
                'autofocus' => true,
                'placeholder' => __('app.email'),
            ],
            [
                'type' => 'password',
                'name' => 'password',
                'required' => true,
                'placeholder' => __('app.password'),
            ],
        ];

        return view('login', [
            'description' => __('app.login_description'),
            'form' => [
                'fields' => $fields,
                'method' => 'post',
                'button_text' => __('app.login'),
            ],
        ]);
    }

    public function tfaAuthenticate()
    {
        $fields = [
            [
                'type' => 'text',
                'name' => 'tfa',
                'required' => true,
                'autofocus' => true,
                'placeholder' => __('app.code'),
            ],
        ];

        return view('login', [
            'description' => __('app.tfa_authenticate_description'),
            'form' => [
                'fields' => $fields,
                'method' => 'post',
            ],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function tfaAuthenticatePost(Request $request)
    {
        $google2fa = new Google2FA();

        if (! $google2fa->verifyKey($request->user()->tfa_secret, $request->tfa)) {
            return redirect()->back()->withErrors([
                'tfa' => __('error.tfa_code_incorrect'),
            ]);
        }

        $request->user()->tfaAuthenticate();

        return redirect()->route('home');
    }

    public function tfaVerifyPost(Request $request): Redirector|RedirectResponse
    {
        $request->user()->update([
            'tfa_secret_verified_at' => now(),
        ]);

        return redirect()->route('tfa.authenticate');
    }

    public function tfaVerify(Request $request)
    {
        return view('tfa.verify', [
            'tfaQrSvg' => $request->user()->getTfaSecretAsSvg(),
            'form' => [
                'fields' => [],
                'method' => 'post',
            ],
        ]);
    }

    public function loginPost(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email_hash' => User::hashProperty($validated['email']),
            'password' => $validated['password'],
        ])) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => __('error.user_not_found'),
        ])->withInput();
    }

    public function emailVerify(string $token)
    {

    }
}
