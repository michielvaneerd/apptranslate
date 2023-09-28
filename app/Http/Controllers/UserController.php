<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\UserCreatedVerifyEmail;
use App\Models\User;
use App\Rules\EmailUnique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display list of users.
     */
    public function index(Request $request): View
    {
        $users = User::all();
        $table = [
            'items' => $users,
            'columns' => ['id', 'role', 'email'],
        ];

        return view('index', [
            'title' => __('app.users'),
            'titleButton' => [
                'title' => __('app.create'),
                'route' => route('users.create'),
            ],
            'table' => $table,
        ]);
    }

    /**
     * Display form to create new user.
     */
    public function create(Request $request): View
    {
        $fields = [
            [
                'type' => 'email',
                'name' => 'email',
                'required' => true,
                'autofocus' => true,
                'placeholder' => __('app.email'),
                'label' => __('app.email'),
            ],
            [
                'type' => 'select',
                'name' => 'role',
                'required' => true,
                'label' => __('app.role'),
                'options' => ['root', 'admin', 'editor'],
            ],
        ];
        $form = [
            'fields' => $fields,
            'method' => 'post',
        ];

        return view('create-edit', [
            'title' => __('app.create'),
            'form' => $form,
        ]);
    }

    /**
     * Stores a new user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                new EmailUnique(),
            ],
            'role' => [
                'required',
                Rule::in(User::ROLES),
            ],
        ]);
        $user = User::create([
            'email' => $validated['email'],
            'role' => $validated['role'],
            'tfa_secret' => User::generateSecretTfaKey(),
            'email_token' => User::createToken(),
            'email_token_created_at' => now(),
        ]);
        Mail::to($user->email)->send(new UserCreatedVerifyEmail(user: $user, route: route('email.verify', ['token' => $user->email_token])));

        return redirect()->route('users.index');
    }

    /**
     * Display form to edit a user.
     */
    public function edit(Request $request, int $id)
    {
    }

    /**
     * View user.
     */
    public function view(Request $request, int $id)
    {
    }

    /**
     * Updates user.
     */
    public function update(Request $request, int $id)
    {
    }

    /**
     * Show delete confirmation.
     */
    public function delete(Request $request, int $id)
    {
    }

    /**
     * Deletes a user.
     */
    public function destroy(Request $request, int $id)
    {
    }
}
