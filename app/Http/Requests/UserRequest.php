<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        $userId = $this->user ? $this->user->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'nullable', // Allow null, since phone can be required
                'email',
                'unique:users,email,' . $userId,
                'required_without_all:phone', // Require email if phone is missing
            ],
            'phone' => [
                'nullable', // Allow null, since email can be required
                'string',
                'max:15',
                'unique:users,phone,' . $userId,
                'required_without_all:email', // Require phone if email is missing
            ],
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
            'role' => 'required|array',
            'role.*' => 'exists:roles,name',
        ];
    }
}
