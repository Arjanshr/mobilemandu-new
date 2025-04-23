<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            "id" => $this->product->id,
            "name" => $this->product->name,
            "slug" => $this->product->slug,
            "rating" => $this->product->getAverageRating(),
            "discounted_amount" => $this->product->discounted_price,
            "original_amount" => $this->product->price,
            "added_to_cart" => false,
            "added_to_wishlist" => $user
                ? $user->hasInWishlist($this->product->id)
                : false,
            "image_link" => $this->product->getFirstMedia()?->getUrl(),
            "offer" => null,
            "alt_text" => $this->alt_text,
            "status" => $this->product->status,
            "tags" => [
                "new" => $this->product->isNew(),
                "popular" => $this->product->isPopular(),
                "campaign" => optional($this->product->isCampaignProduct()->first())->name ?? false,
            ]
        ];
    }
}
