<?php
// app/Rules/PhoneRule.php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Simple phone validation
        if (!preg_match('/^[+]?[0-9\s\-\(\)]+$/', $value)) {
            $fail('validation.phone_number.invalid');
        }
    }
}
