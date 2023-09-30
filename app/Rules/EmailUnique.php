<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailUnique implements ValidationRule
{
    private ?User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = User::where('email_hash', User::hashProperty($value));
        if (! empty($this->user)) {
            $query->where('id', '<>', $this->user->id);
        }
        if ($query->exists()) {
            $fail(__('error.exists', ['attribute' => $attribute]));
        }
    }
}
