<?php
// app/Rules/StrongPasswordRule.php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPasswordRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Must have at least 8 chars, 1 uppercase, 1 number
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $value)) {
            $fail('validation.password.weak');
        }
    }
}
