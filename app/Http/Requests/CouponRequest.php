<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to true if all users are authorized to make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string|unique:coupons,code,' . $this->route('coupon'),
            'type' => 'required|in:fixed,percentage',
            'discount' => 'required|numeric|min:0', // Ensure discount is a positive value
            'max_uses' => 'nullable|integer|min:0',
            'uses' => 'nullable|integer|min:0',
            'expires_at' => 'nullable|date|after_or_equal:today', // Ensure the expiration date is valid and not in the past
            'is_user_specific' => 'nullable|boolean',
            'status' => 'required|in:0,1', // Active (1) or Inactive (0)
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'The coupon code is required.',
            'code.unique' => 'The coupon code must be unique.',
            'type.required' => 'Please select the coupon type.',
            'type.in' => 'The coupon type must be either fixed or percentage.',
            'discount.required' => 'Please provide a discount value.',
            'discount.numeric' => 'The discount must be a valid number.',
            'discount.min' => 'The discount must be at least 0.',
            'max_uses.integer' => 'Max uses must be an integer.',
            'max_uses.min' => 'Max uses must be at least 0.',
            'uses.integer' => 'Uses must be an integer.',
            'uses.min' => 'Uses must be at least 0.',
            'expires_at.date' => 'The expiration date must be a valid date.',
            'expires_at.after_or_equal' => 'The expiration date cannot be in the past.',
            'is_user_specific.boolean' => 'The user-specific flag must be true or false.',
            'status.required' => 'Please select the status of the coupon.',
            'status.in' => 'The status must be either active or inactive.',
        ];
    }

    /**
     * Configure the validator to exclude the coupon itself from unique validation (for edit scenario).
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->route('coupon')) {
            // Include the coupon ID to exclude it from the unique validation rule when updating
            $this->merge([
                'coupon_id' => $this->route('coupon'),
            ]);
        }
    }
}
