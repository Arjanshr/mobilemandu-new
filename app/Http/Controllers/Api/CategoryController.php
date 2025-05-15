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
    $categories = Category::where('status', 'active')
        ->where('parent_id', null)
        ->with(['products.brand', 'children.products.brand'])
        ->get()
        ->filter(function ($category) {
            $category->children = $category->children->filter(function ($subcat) {
                return $subcat->products->isNotEmpty();
            })->values();
            return $category->products->isNotEmpty() || $category->children->isNotEmpty();
        })
        ->values();

    return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
}


    public function details($category_id_or_slug)
    {
        $category = Category::where('id', $category_id_or_slug)
            ->orWhere('slug', $category_id_or_slug)->firstOrFail();
        return $this->sendResponse(CategoryResource::make($category), 'Category detail retrieved successfully.');
    }
}
