<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image_urls = [];
        if ($this->getMedia()->count() > 0) {
            foreach ($this->getMedia() as $image) {
                $image_urls[] = $image->getUrl();
            }
        }
        $rating_summary['Five'] = $this->reviews()->where('rating',5)->count();
        $rating_summary['Four'] = $this->reviews()->where('rating',4)->count();;
        $rating_summary['Three'] = $this->reviews()->where('rating',3)->count();;
        $rating_summary['Two'] = $this->reviews()->where('rating',2)->count();;
        $rating_summary['One'] = $this->reviews()->where('rating',1)->count();;
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "slug" => $this->slug,
            "average_rating" => $this->getAverageRating(),
            "discounted_amount" => $this->price,
            "original_amount" => $this->price,
            "added_to_cart" => false,
            "added_to_wishlist" => false,
            "offer" => null,
            "status" => $this->status,
            "images" => $image_urls,
            "total_reviews" => $this->reviews()->count(),
            "rating_summary" =>  $rating_summary,
        ];
    }
}
