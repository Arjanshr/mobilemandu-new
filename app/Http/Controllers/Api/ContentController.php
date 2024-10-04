<?php

namespace App\Http\Controllers\Api;

use App\Models\FeaturedProduct;
use App\Models\NewArraival;
use App\Models\PopularProduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ContentController extends BaseController
{
    public function getProductList($content_type, $items_per_page = 6)
    {
        switch ($content_type) {
            case 'featured-products':
                $products = FeaturedProduct::whereRelation('product', 'status', 'publish')
                ->orderByDesc('display_order')
                ->paginate($items_per_page);
                break;
            case 'popular-products':
                $products = PopularProduct::whereRelation('product', '.status', 'publish')
                ->orderByDesc('display_order')
                ->paginate($items_per_page);
                break;
            case $content_type == 'new-arriavals':
                $products = NewArraival::whereRelation('product', 'status', 'publish')
                ->orderByDesc('display_order')
                ->paginate($items_per_page);
                break;
            default:
                $products = Product::where('status', 'publish')
                ->orderByDesc('display_order')
                ->paginate($items_per_page);
        }
        return $this->sendResponse(ContentResource::collection($products)->resource, ucfirst(str_replace('-', ' ', $content_type)) . ' retrieved successfully.');
    }
}
