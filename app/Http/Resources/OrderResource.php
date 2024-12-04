<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "total_price" => $this->total_price,
            "discount" => $this->discount,
            "shipping_price" => $this->shipping_price,
            "grand_total" => $this->grand_total,
            "coupon_code" => $this->coupon_code,
            "payment_type" => $this->payment_type,
            "payment_status" => $this->payment_status,
            "status" => $this->status,
            "address_id" => $this->address_id,
            "shipping_address" => $this->shipping_address,
            "area_id" => $this->area_id,
            "cancellable" => $this->status == 'pending' ? true : false,
        ];
    }
}
