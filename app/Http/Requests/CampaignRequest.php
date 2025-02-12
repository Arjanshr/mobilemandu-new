<?php

namespace App\Http\Requests;

use App\Enums\CampaignStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CampaignRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', new Enum(CampaignStatus::class)],
            'background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // Max 2MB
            'theme_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'], // Must be a valid hex color
        ];
    }
}
