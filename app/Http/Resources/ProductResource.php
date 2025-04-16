<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "brand" => $this->brand?$this->brand->name:null,
            "link" => 'https://www.mobilemandu.com/products/'.$this->slug,
            "description" => $this->description,
            "slug" => $this->slug,
            "rating" => $this->getAverageRating(),
            "discounted_amount" => $this->discounted_price,
            "original_amount" => $this->price,
            "added_to_cart" => false,
            "added_to_wishlist" => false,
            "image_link" => $this->getFirstMedia() ? $this->getFirstMedia()->getUrl() : null,
            "offer" => null,
            "alt_text" => $this->alt_text,
            "status" => $this->status,
            "tags"=>[
                "new"=> $this->isNew(),
                "popular"=> $this->isPopular(),
                "campaign"=> $this->isCampaignProduct()->first()?$this->isCampaignProduct()->first()->name:false,
            ]
        ];
    }
}
