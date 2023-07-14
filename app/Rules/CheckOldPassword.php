<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class CheckOldPassword implements ValidationRule
{
    public function __construct(private readonly User $user){}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value)){
            if (!Hash::check($value, $this->user->password))
                $fail("old password not match with the current");
        }
    }
}
