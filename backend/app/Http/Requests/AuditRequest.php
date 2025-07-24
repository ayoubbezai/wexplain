<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuditRequest extends FormRequest
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
        return  [
            'user_id'     => 'nullable|uuid',
            'action'      => 'required|string|max:255',
            'table_name'  => 'nullable|string|max:255',
            'old_value'   => 'nullable|string',
            'new_value'   => 'nullable|string',
            'ip_address'  => 'nullable|ip',
            'record_id'   => 'nullable|string|max:255',
            'method'      => 'nullable|in:create,update,delete',
        ];
    }
}
