<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends BaseController
{
    public function brands()
    {
        $brands =  Brand::get();
        return $this->sendResponse(BrandResource::collection($brands), 'Brands retrieved successfully.');
    }
    public function details(Brand $brand)
    {
        return $this->sendResponse(BrandResource::make($brand), 'Brand detail retrieved successfully.');
    }
}