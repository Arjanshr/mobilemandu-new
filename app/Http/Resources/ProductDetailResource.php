<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Prepare image URLs
        $image_urls = [];
        if ($this->getMedia() && $this->getMedia()->count() > 0) {
            foreach ($this->getMedia() as $image) {
                $image_urls[] = $image->getUrl();
            }
        }

        // Prepare rating summary
        $rating_summary = [
            'Five' => $this->reviews()->where('rating', 5)->count(),
            'Four' => $this->reviews()->where('rating', 4)->count(),
            'Three' => $this->reviews()->where('rating', 3)->count(),
            'Two' => $this->reviews()->where('rating', 2)->count(),
            'One' => $this->reviews()->where('rating', 1)->count(),
        ];

        // Prepare variants
        $variants = $this->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->price,
                'stock_quantity' => $variant->stock_quantity,
                'options' => $variant->variant_options->map(function ($option) {
                    return [
                        'specification_name' => $option->specification->name,
                        'value' => $option->value,
                    ];
                }),
            ];
        });
        $user = $request->user();

        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "slug" => $this->slug,
            "average_rating" => $this->getAverageRating(),
            "discounted_amount" => $this->discounted_price,
            "original_amount" => $this->price,
            "added_to_cart" => false,
            "added_to_wishlist" => $user
                ? $user->hasInWishlist($this->id)
                : false,
            "offer" => null,
            "status" => $this->status,
            "images" => $image_urls,
            "total_reviews" => $this->reviews()->count(),
            "rating_summary" => $rating_summary,
            "category_id" => $this->categories()->first()?->id,
            "alt_text" => $this->alt_text,
            "tags" => [
                "new" => $this->isNew(),
                "popular" => $this->isPopular(),
                "campaign" => $this->isCampaignProduct()->first()?->name,
            ],
            "variants" => $variants, // Include variants here
        ];
    }
}
