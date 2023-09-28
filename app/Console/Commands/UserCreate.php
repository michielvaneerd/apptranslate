<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->ask('Email?');
        $password = $this->ask('Password?');
        $role = $this->askWithCompletion('Role?', User::ROLES);

        $user = User::create([
            'email' => $email,
            'password' => $password,
            'email_verified_at' => now(),
            'role' => $role,
            'tfa_secret' => User::generateSecretTfaKey(),
        ]);

        $this->line('User #'.$user->id.' created successfully.');
    }
}
