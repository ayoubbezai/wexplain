<?php
// app/Http/Requests/Auth/RegisterStudentRequest.php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneRule;
use App\Rules\StrongPasswordRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiHelper;

class RegisterStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            // Email: lowercase and trim
            'email' => strtolower(trim($this->email ?? '')),

            // Names: trim, capitalize first letter
            'first_name' => ucfirst(trim($this->first_name ?? '')),
            'last_name' => ucfirst(trim($this->last_name ?? '')),

            // Phone numbers: remove spaces, dashes, parentheses
            'phone_number' => preg_replace('/[\s\-\(\)]/', '', $this->phone_number ?? ''),
            'second_phone_number' => $this->second_phone_number ? preg_replace('/[\s\-\(\)]/', '', $this->second_phone_number) : null,
            'parent_phone_number' => $this->parent_phone_number ? preg_replace('/[\s\-\(\)]/', '', $this->parent_phone_number) : null,

            // Address: trim
            'address' => trim($this->address ?? ''),

            // Year of study: trim
            'year_of_study' => trim($this->year_of_study ?? ''),
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'min:' . config('students.validation.password_min_length'), 'confirmed', new StrongPasswordRule],
            'first_name' => 'required|string|max:' . config('students.validation.name_max_length'),
            'last_name' => 'required|string|max:' . config('students.validation.name_max_length'),
            'phone_number' => ['required', 'string', 'max:' . config('students.validation.phone_max_length'), new PhoneRule],
            'second_phone_number' => ['nullable', 'string', 'max:' . config('students.validation.phone_max_length'), new PhoneRule],
            'parent_phone_number' => ['nullable', 'string', 'max:' . config('students.validation.phone_max_length'), new PhoneRule],
            'preferred_contact_method' => 'nullable|in:' . implode(',', config('students.contact_methods')),
            'year_of_study' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'validation.email.required',
            'email.email' => 'validation.email.invalid',
            'email.unique' => 'validation.email.taken',

            'password.required' => 'validation.password.required',
            'password.min' => 'validation.password.min',
            'password.confirmed' => 'validation.password.confirmed',

            'first_name.required' => 'validation.first_name.required',
            'last_name.required' => 'validation.last_name.required',

            'phone_number.required' => 'validation.phone_number.required',
            'phone_number.max' => 'validation.phone_number.max',

            'preferred_contact_method.in' => 'validation.preferred_contact_method.invalid',
            'date_of_birth.date' => 'validation.date_of_birth.invalid',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        // Get only the first error for each field
        $cleanErrors = [];
        foreach ($errors as $field => $messages) {
            $cleanErrors[$field] = [$messages[0]]; // Take only first error
        }

        throw new HttpResponseException(
            ApiHelper::apiError('validation.failed', $cleanErrors, 422)
        );
    }
}
