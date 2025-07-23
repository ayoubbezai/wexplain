<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'phone_number' => 'required|string|max:20',
        'second_phone_number' => 'nullable|string|max:20',
        'parent_phone_number' => 'nullable|string|max:20',
        'preferred_contact_method' => 'nullable|in:phone,email,whatsapp',
        'year_of_study' => 'nullable|string|max:100',
        'date_of_birth' => 'nullable|date',
        'address' => 'nullable|string',
        ];
    }
}
