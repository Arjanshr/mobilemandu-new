<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function categories()
    {
        $categories =  Category::where('status', 'active')
            ->where('parent_id', null)
            ->with('products')
            ->with('children.products.brand')
            ->get();
        return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
    }

    public function details(Category $category)
    {
        return $this->sendResponse(CategoryResource::make($category), 'Category detail retrieved successfully.');
    }
}
