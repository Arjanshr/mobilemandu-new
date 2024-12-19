<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            "content" => $this->content,
            "imageLink" => $this->image?asset('storage/blogs/' . $this->image):asset('images/default.png'),
            'date'=> $this->created_at,
            'date_readable' => $this->created_at->diffForHumans()
        ];
    }
}
