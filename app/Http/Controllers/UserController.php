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
            'columns' => ['id', 'role', 'email', 'email_verified_at', 'tfa_secret_verified_at'],
            'links' => [
                'email' => function(User $user) {
                    return route('users.edit', compact('user'));
                }
            ]
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

    private function getCreateEdit(Request $request, ?User $user = null)
    {
        $fields = [
            [
                'type' => 'email',
                'name' => 'email',
                'required' => true,
                'autofocus' => true,
                'placeholder' => __('app.email'),
                'label' => __('app.email'),
                'value' => optional($user)->email
            ],
            [
                'type' => 'select',
                'name' => 'role',
                'required' => true,
                'label' => __('app.role'),
                'options' => ['root', 'admin', 'editor'],
                'value' => optional($user)->role
            ],
        ];
        $form = [
            'fields' => $fields,
            'method' => 'post',
        ];

        return view('create-edit', [
            'title' => empty($user) ? __('app.create') : __('app.edit'),
            'form' => $form,
        ]);
    }

    /**
     * Display form to create new user.
     */
    public function create(Request $request): View
    {
        return $this->getCreateEdit($request);
    }

    private function validateStoreUpdate(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'email' => [
                'required',
                'email',
                new EmailUnique($user),
            ],
            'role' => [
                'required',
                Rule::in(User::ROLES),
            ],
        ]);
    }

    /**
     * Stores a new user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateStoreUpdate($request);
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
    public function edit(Request $request, User $user)
    {
        return $this->getCreateEdit($request, $user);
    }

    /**
     * View user.
     */
    public function view(Request $request, User $user)
    {
    }

    /**
     * Updates user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $this->validateStoreUpdate($request, $user);
        // TODO: validate email if changed
        // and check for role change.
        $user->update($validated);
        return redirect()->route('users.index');
    }

    /**
     * Show delete confirmation.
     */
    public function delete(Request $request, User $user)
    {
    }

    /**
     * Deletes a user.
     */
    public function destroy(Request $request, User $user)
    {
    }
}
