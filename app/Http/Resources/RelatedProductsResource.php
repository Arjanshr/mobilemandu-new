<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RelatedProductsResource extends JsonResource
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
            "name" => $this->name,
            "slug" => $this->slug,
            "rating" => 4.3,
            "discounted_amount" => $this->price,
            "original_amount" => $this->price,
            "added_to_cart" => false,
            "added_to_wishlist" => false,
            "image_link" => $this->getFirstMedia() ? $this->getFirstMedia()->getUrl() : null,
            "offer" => null,
            "status" => $this->status
        ];
    }
}