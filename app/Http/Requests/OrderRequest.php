<?php

namespace App\Http\Requests;

use App\Enums\AddressType;
use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'order_date' => ['nullable','date'],
            'customer_id' => ['nullable','numeric','exists:users,id'],
            'address' => ['nullable','numeric','exists:addresses,id'],
            'reciever_name' => ['required','string'],
            'address_type'=>['required', new Enum(AddressType::class)],
            'province_id'=>['required','string'],
            'city_id'=>['required','string'],
            'area_id'=>['required','string'],
            'location'=>['required','string'],
            'email'=>['nullable','string'],
            'phone_number'=>['required','string'],
            'items'=>['array'],
            'total_quantity'=>['numeric','min:1'],
            'total_amount'=>['numeric','min:0'],
            'total_discount'=>['numeric','min:0'],
            'shipping_price'=>['numeric','min:0'],
            'grand_total'=>['numeric','min:0'],
            'payment_type'=>['required', new Enum(PaymentType::class)]
        ];
    }
}
