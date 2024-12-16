<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends BaseController
{
    public function sliders()
    {
        $sliders =  Slider::orderBy('display_order')->get();
        return $this->sendResponse(SliderResource::collection($sliders), 'Sliders retrieved successfully.');
    }
}
