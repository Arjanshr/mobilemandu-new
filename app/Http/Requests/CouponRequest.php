<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change if needed
    }

    public function rules()
    {
        $couponId = $this->route('coupon') ? $this->route('coupon')->id : null;

        return [
            'code' => 'required|string|unique:coupons,code,' . $couponId,
            'type' => 'required|in:fixed,percentage',
            'discount' => 'required|numeric|min:1',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_user_specific' => 'nullable', // Allow null instead of boolean
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'is_category_specific' => 'nullable', // Allow null instead of boolean
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'status' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Coupon code is required.',
            'code.unique' => 'This coupon code already exists.',
            'discount.required' => 'Discount value is required.',
            'type.required' => 'Discount type is required.',
            'status.required' => 'Coupon status is required.',
            'user_ids.*.exists' => 'Selected user does not exist.',
            'category_ids.*.exists' => 'Selected category does not exist.',
        ];
    }
}
