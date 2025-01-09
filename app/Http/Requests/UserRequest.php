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
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable','string' ],
            'gender' => ['nullable', 'in:male,female'],
            'dob' => ['nullable', 'date'],
            'password'=>['nullable','min:6'],
            'photo' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,ico,pdf', 'max:2048'],
        ];
    }
}
