<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            "number" => $this->phone,
            "gender" => $this->gender,
            "email" => $this->email,
            "birthday" => $this->dob,
            "profile_image_path" => $this->avatar??$this->profile_photo_path?url('storage',$this->profile_photo_path):asset('images/default.png')
        ];
    }
}
