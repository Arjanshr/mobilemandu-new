<?php

namespace App\Http\Requests;

use App\Enums\AddressType;
use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use App\Models\Area;
use App\Models\Coupon;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        // return auth()->user()->can('add-orders');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_date' => ['nullable', 'date'],
            'customer_id' => ['nullable', 'numeric', 'exists:users,id'],
            'address' => ['nullable', 'numeric', 'exists:addresses,id'],
            'reciever_name' => ['required', 'string'],
            'address_type' => ['required', new Enum(AddressType::class)],
            'province_id' => ['required', 'string'],
            'city_id' => ['required', 'string'],
            'area_id' => ['required', 'numeric', 'exists:areas,id'],
            'location' => ['required', 'string'],
            'email' => ['nullable', 'string'],
            'phone_number' => ['required', 'string'],
            'items' => ['array'],
            'total_quantity' => ['numeric', 'min:1'],
            'total_amount' => ['numeric', 'min:0'],
            'total_discount' => ['numeric', 'min:0'],
            'shipping_price' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $area_id = $this->input('area_id');
                    $coupon_code = $this->input('coupon_code'); // Assuming coupon code is sent in request

                    if (!$area_id || !is_numeric($area_id)) {
                        return $fail('Invalid area selected.');
                    }

                    $area = Area::find($area_id);
                    if (!$area) {
                        return $fail('Invalid area selected.');
                    }

                    // Check if a free shipping coupon is applied
                    $coupon = Coupon::where('code', $coupon_code)
                        ->where('specific_type', 'free_shipping') // Assuming type column stores coupon type
                        ->where('status', 1) // Ensure it's active
                        ->first();

                    if ($coupon) {
                        // If free shipping coupon is applied, allow 0 as valid shipping price
                        if (floatval($value) != 0) {
                            return $fail('The shipping price should be 0 when using a Free Shipping coupon.');
                        }
                    } else {
                        // Otherwise, enforce the normal shipping price
                        if (floatval($value) != floatval($area->shipping_price)) {
                            return $fail('The shipping price does not match the actual shipping price for the selected area.');
                        }
                    }
                },
            ],
            'grand_total' => ['numeric', 'min:0'],
            'payment_type' => ['required', new Enum(PaymentType::class)]
        ];
    }
}
