<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "order_id" => $this->order->id,
            "product" => $this->product,
            "primary_image" => $this->product->primary_image,
            "coupon_code" => $this->coupon_code,
            "shipping_price" => $this->shipping_price,
            "quantity" => $this->quantity,
            "price" => $this->price,
            "discount" => $this->discount,
            "review" => $this->review,
        ];
    }
}
