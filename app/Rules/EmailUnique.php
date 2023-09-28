<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailUnique implements ValidationRule
{
    private ?int $userId;

    public function __construct(int $userId = null)
    {
        $this->userId = $userId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = User::where('email_hash', User::hashProperty($value));
        if (! empty($this->userId)) {
            $query->where('id', '<>', $this->userId);
        }
        if ($query->exists()) {
            $fail(__('error.exists', ['attribute' => $attribute]));
        }
    }
}
