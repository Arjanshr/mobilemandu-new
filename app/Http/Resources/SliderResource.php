<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'link_url' => $this->link_url,
            "display_order" => $this->display_order,
            "imageLink" => $this->image?asset('storage/sliders/' . $this->image):asset('images/default.png'),
        ];
    }
}