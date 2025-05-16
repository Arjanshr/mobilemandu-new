<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesForFilterResource;
use App\Http\Resources\PriceAndRatingRangeForFilterResource;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductFeaturesResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductReviewsResource;
use App\Http\Resources\ProductSpecificationsResource;
use App\Http\Resources\QuestionsAndAnswersResource;
use App\Http\Resources\RelatedProductsResource;
use App\Http\Resources\ReviewsSummaryResource;
use App\Http\Resources\VariantDetailResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\QuestionsAndAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends BaseController
{
    public function getProductByBrand($brand_id_or_slug, $paginate = 8, Request $request)
    {
        $brand  = Brand::find($brand_id_or_slug);
        if (!$brand) {
            $brand = Brand::where('slug', $brand_id_or_slug)->first();
        }
        if ($brand) {
            $products = Product::where('brand_id', $brand->id)
                ->where('status', 'publish');
            if (isset($request->categories) && is_array($request->categories) && count($request->categories) > 0) {
                $category_ids = $request->categories;
                $products = $products->whereHas('categories', function ($query) use ($category_ids) {
                    $query->whereIn('categories.id', $category_ids);
                });
            }
            if (isset($request->min_price) && $request->min_price > 0) {
                $products = $products->where('price', '>=', $request->min_price);
            }
            if (isset($request->max_price) && $request->max_price > 0) {
                $products = $products->where('price', '<=', $request->max_price);
            }
            if (isset($request->min_rating) && $request->min_rating > 0) {
                // $products = $products->where('rating', '>=', $request->min_rating);
            }
            if (isset($request->max_rating) && $request->max_rating > 0) {
                // $products = $products->where('rating', '<=', $request->max_rating);
            }
        } else {
            $products = Product::where('status', 'publish');
        }
        $products = $products->orderBy('id', 'DESC')->paginate($paginate);
        return $this->sendResponse(ProductResource::collection($products)->resource, 'Products retrieved successfully.');
    }

    public function getCategoriesForBrandFilter(Brand $brand)
    {
        $products = Product::where('brand_id', $brand->id)
            ->where('status', 'publish')
            ->with('categories')
            ->get();
        $category_ids = [];
        foreach ($products as $product) {
            foreach ($product->categories as $category) {
                $category_ids[] = $category->id;
            }
        }
        $categories = Category::whereIn('id', $category_ids)->get();
        return $this->sendResponse(CategoriesForFilterResource::collection($categories)->resource, 'All available categories for selected brand');
    }

    public function getPriceRangeForBrandFilter(Brand $brand)
    {
        $products = Product::select(DB::raw("MIN(price) AS min_price, MAX(price) AS max_price"))
            ->where('brand_id', $brand->id)
            ->where('status', 'publish')
            ->get();
        return $this->sendResponse(PriceAndRatingRangeForFilterResource::collection($products)->resource, 'Price range for selected brand');
    }
    public function getRatingRangeForBrandFilter(Brand $brand)
    {
        $data = ['min_rating' => 0, 'max_rating' => 5];

        return $this->sendResponse($data, 'Ratinge range for selected brand');
    }

    public function getProductByCategory($category_id_or_slug, $paginate = 8, Request $request)
    {
        $category = Category::where('slug', $category_id_or_slug)->first();
        if (!$category) {
            $category  = Category::find($category_id_or_slug);
        }
        $cat_ids = [];
        if ($category || $category != null)
            $cat_ids = $category->getAllChildrenIds()->toArray();
        array_push($cat_ids, $category->id);
        $products = Product::whereHas('categories', function ($q) use ($cat_ids) {
            $q->whereIn('id', $cat_ids);
        })->where('status', 'publish');
        if (isset($request->brands) && is_array($request->brands) && count($request->brands) > 0) {
            $brand_ids = $request->brands;
            $products = $products->whereIn('brand_id', $brand_ids);
        }
        if (isset($request->min_price) && $request->min_price > 0) {
            $products = $products->where('price', '>=', $request->min_price);
        }
        if (isset($request->max_price) && $request->max_price > 0) {
            $products = $products->where('price', '<=', $request->max_price);
        }
        if (isset($request->min_rating) && $request->min_rating > 0) {
            // $products = $products->where('rating', '>=', $request->min_rating);
        }
        if (isset($request->max_rating) && $request->max_rating > 0) {
            // $products = $products->where('rating', '<=', $request->max_rating);
        }
        $products = $products->orderBy('id', 'DESC')->paginate($paginate);
        return $this->sendResponse(ProductResource::collection($products)->resource, 'Products retrieved successfully.');
    }

    public function getBrandsForCategoryFilter(Category $category)
    {
        $cat_ids = [];
        if ($category || $category != null)
            $cat_ids = $category->getAllChildrenIds()->toArray();
        array_push($cat_ids, $category->id);
        $products = Product::whereHas('categories', function ($q) use ($cat_ids) {
            $q->whereIn('id', $cat_ids);
        })->where('status', 'publish');
        $brand_ids = $products->select('brand_id')->get()->pluck('brand_id');
        $brands = Brand::whereIn('id', $brand_ids)->get();
        return $this->sendResponse(CategoriesForFilterResource::collection($brands)->resource, 'All available brands for selected category');
    }

    public function getPriceRangeForCategoryFilter(Category $category)
    {
        $products = $category->products()
            ->where('status', 'publish')
            ->select('price')
            ->pluck('price');

        $data['min_price'] = $products->min();
        $data['max_price'] = $products->max();

        return $this->sendResponse($data, 'Price range for selected category');
    }
    public function getRatingRangeForCategoryFilter(Category $category)
    {
        $data = ['min_rating' => 0, 'max_rating' => 5];

        return $this->sendResponse($data, 'Ratinge range for selected category');
    }

    public function getProductByBrandAndCategory(Brand $brand, Category $category, $paginate = 8)
    {
        $products = $category->products()->where('brand_id', $brand->id)
            ->where('status', 'publish')
            ->paginate($paginate);
        return $this->sendResponse(ProductResource::collection($products)->resource, 'Products retrieved successfully.');
    }



    public function searchProducts(Request $request, $paginate = 8)
    {
        $use_old_fallback = true;
        if ($request->query('use_old_fallback') && $request->query('use_old_fallback') == false) {
            $use_old_fallback = false;
        }
        $searchQuery = $request->query('query', '');
        $filters = $this->buildFilters($request);

        try {
            $products = $this->performMeilisearch($searchQuery, $filters, $paginate);
        } catch (\Throwable $e) {
            if (!$request->query($use_old_fallback, false)) {
                $products = $this->oldSearch($request, $searchQuery, $paginate);
            } else {
                $products = $this->performFallbackSearch($request, $searchQuery, $paginate);
            }
        }

        return $this->sendResponse(ProductResource::collection($products)->resource, 'Products retrieved successfully.');
    }

    private function buildFilters(Request $request)
    {
        $filters = [];

        if (!empty($request->categories) && is_array($request->categories)) {
            $filters[] = 'categories.id IN [' . implode(',', $request->categories) . ']';
        }

        if (!empty($request->brand) && is_array($request->brand)) {
            $filters[] = 'brand_id IN [' . implode(',', $request->brand) . ']';
        }

        if (!empty($request->min_price)) {
            $filters[] = 'price >= ' . $request->min_price;
        }

        if (!empty($request->max_price)) {
            $filters[] = 'price <= ' . $request->max_price;
        }

        if (isset($request->min_rating) || isset($request->max_rating)) {
            $min_rating = $request->min_rating ?? 0;
            $max_rating = $request->max_rating ?? 5;
            $filters[] = "rating >= $min_rating AND rating <= $max_rating";
        }

        return "status = 'publish'" . (empty($filters) ? '' : " AND " . implode(' AND ', $filters));
    }

    private function performMeilisearch($searchQuery, $filterString, $paginate)
    {
        $meilisearch = new \Meilisearch\Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));
        $index = $meilisearch->index('products');
        $index->updateFilterableAttributes(['price', 'rating', 'categories.id', 'brand_id', 'status']);

        return Product::search($searchQuery, function ($meilisearch, $query, $options) use ($filterString) {
            $options['filter'] = $filterString;
            return $meilisearch->search($query, $options);
        })->paginate($paginate);
    }

    private function oldSearch(Request $request, $searchQuery, $paginate)
    {
        $products = Product::query()->where('status', 'publish');

        $filters = $this->buildFilters($request);
        if (!empty($filters)) {
            $products->whereRaw($filters);
        }

        if (!empty($searchQuery)) {
            $pluralQuery = \Illuminate\Support\Str::plural($searchQuery);
            $singularQuery = \Illuminate\Support\Str::singular($searchQuery);

            $category_ids = Category::where('name', 'like', '%' . $searchQuery . '%')
                ->orWhere('name', 'like', '%' . $pluralQuery . '%')
                ->orWhere('name', 'like', '%' . $singularQuery . '%')
                ->pluck('id');

            $products->where(function ($query) use ($searchQuery, $pluralQuery, $singularQuery, $category_ids) {
                $query->whereHas('categories', function ($q) use ($category_ids) {
                    $q->whereIn('categories.id', $category_ids);
                })
                    ->orWhere('name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('name', 'like', '%' . $pluralQuery . '%')
                    ->orWhere('name', 'like', '%' . $singularQuery . '%')
                    ->orWhere('keywords', 'like', '%' . $searchQuery . '%')
                    ->orWhere('keywords', 'like', '%' . $pluralQuery . '%')
                    ->orWhere('keywords', 'like', '%' . $singularQuery . '%');
            });

            $tempQuery = clone $products;
            if ($tempQuery->count() <= 5) {
                $products->orWhere('description', 'like', '%' . $searchQuery . '%')
                    ->orWhere('description', 'like', '%' . $pluralQuery . '%')
                    ->orWhere('description', 'like', '%' . $singularQuery . '%');
            }
        }

        // Ensure the query is executed and paginated before returning the response
        $products = $products->orderBy('id', 'DESC')->paginate($paginate);

        return $products; // Return the paginated query result, not a JsonResponse
    }

    private function performFallbackSearch(Request $request, $searchQuery, $paginate)
    {
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        $min_rating = $request->min_rating ?? 0;
        $max_rating = $request->max_rating ?? 5;

        $query = Product::query()
            ->with(['brand', 'categories']) // eager load to reduce N+1 queries
            ->withAvg('reviews', 'rating') // calculate average rating
            ->where('status', 'publish');

        // Fulltext search across products, brands, and categories
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->whereRaw("MATCH(name, description, keywords) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchQuery])
                    ->orWhereHas('brand', function ($b) use ($searchQuery) {
                        $b->whereRaw("MATCH(name, description) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchQuery]);
                    })
                    ->orWhereHas('categories', function ($c) use ($searchQuery) {
                        $c->whereRaw("MATCH(name, description) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchQuery]);
                    });
            });
        }

        // Category filter
        if (!empty($request->categories) && is_array($request->categories)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }

        // Brand filter
        if (!empty($request->brand) && is_array($request->brand)) {
            $query->whereIn('brand_id', $request->brand);
        }

        // Price filters
        if (!empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        if (!empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Rating filter on the average rating from reviews
        $query->having('reviews_avg_rating', '>=', $min_rating)
            ->having('reviews_avg_rating', '<=', $max_rating);

        return $query->paginate($paginate);
    }

    public function getBrandsForSearch(Request $request)
    {
        $products = Product::where('status', 'publish');
        if (isset($request->query) && $request->query != '') {
            if (isset($request->query) && $request->query != '') {
                $category_ids = Category::where('name', 'like', '%' . $request->get('query') . '%')->pluck('id');

                $products = $products->whereHas('categories', function ($query) use ($category_ids) {
                    $query->whereIn('id', $category_ids);
                });

                $products = $products->orWhere(function ($query) use ($request) {
                    $query->orWhere('name', 'like', '%' . $request->get('query') . '%');
                });
                if ($products->count() <= 0) {
                    $products = $products->orWhere('description', 'like', '%' . $request->get('query') . '%');
                }
            }
        }

        $brand_ids = $products->select('brand_id')->get()->pluck('brand_id');
        $brands = Brand::whereIn('id', $brand_ids)->get();
        return $this->sendResponse(CategoriesForFilterResource::collection($brands)->resource, 'All available brands for product list');
    }

    public function getCategoriesForSearch(Request $request)
    {
        $products = Product::where('status', 'publish');
        if (isset($request->query) && $request->query != '') {
            if (isset($request->query) && $request->query != '') {
                $category_ids = Category::where('name', 'like', '%' . $request->get('query') . '%')->pluck('id');

                $products = $products->whereHas('categories', function ($query) use ($category_ids) {
                    $query->whereIn('id', $category_ids);
                });

                $products = $products->orWhere(function ($query) use ($request) {
                    $query->orWhere('name', 'like', '%' . $request->get('query') . '%');
                });
                if ($products->count() <= 0) {
                    $products = $products->orWhere('description', 'like', '%' . $request->get('query') . '%');
                }
            }
        }
        $products = $products->get();
        $category_ids = [];
        foreach ($products as $product) {
            foreach ($product->categories as $category) {
                $category_ids[] = $category->id;
            }
        }
        $categories = Category::whereIn('id', $category_ids)->get();
        return $this->sendResponse(CategoriesForFilterResource::collection($categories)->resource, 'All available categories for product list');
    }

    public function getPriceRangeForSearch(Request $request)
    {
        $products = Product::where('status', 'publish');
        if (isset($request->query) && $request->query != '') {
            if (isset($request->query) && $request->query != '') {
                $category_ids = Category::where('name', 'like', '%' . $request->get('query') . '%')->pluck('id');

                $products = $products->whereHas('categories', function ($query) use ($category_ids) {
                    $query->whereIn('id', $category_ids);
                });

                $products = $products->orWhere(function ($query) use ($request) {
                    $query->orWhere('name', 'like', '%' . $request->get('query') . '%');
                });
                if ($products->count() <= 0) {
                    $products = $products->orWhere('description', 'like', '%' . $request->get('query') . '%');
                }
            }
        }
        $products = $products->select(DB::raw("MIN(price) AS min_price, MAX(price) AS max_price"))->get();

        return $this->sendResponse(PriceAndRatingRangeForFilterResource::collection($products)->resource, 'Price range for product list');
    }
    public function getRatingRangeForSearch(Request $request)
    {
        $data = ['min_rating' => 0, 'max_rating' => 5];

        return $this->sendResponse($data, 'Ratinge range for product list');
    }

    public function productDetails($product_id_or_slug)
    {
        $product = Product::where('status', 'publish')->where('slug', $product_id_or_slug)->first();
        if (!$product) {
            $product  = Product::find($product_id_or_slug);
        }
        if ($product)
            return $this->sendResponse(ProductDetailResource::make($product), 'Product detail retrieved successfully.');
        return $this->sendError('No such product');
    }

    public function productFeatures(Product $product)
    {
        return $this->sendResponse(ProductFeaturesResource::collection($product->features), 'Product features retrieved successfully.');
    }

    public function productSpecifications(Product $product)
    {
        // $specifications = $product->specifications()
        //     ->get(['specifications.*']); // Select only columns from the specifications table
        $specifications = $product->categories()
            ->first()
            ->specifications()
            ->withPivot('display_order') // Ensure pivot field is loaded
            ->orderBy('category_specification.display_order') // Correct table and column name
            ->get();
        $product_specifications = [];
        foreach ($specifications as $specification) {
            $specification_data = $product->specifications()->where('specification_id', $specification->id)->first();
            if ($specification_data !== null) { // Remove null values
                $product_specifications[] = $specification_data;
            }
        }
        return $this->sendResponse(ProductSpecificationsResource::collection($product_specifications), 'Product specifications retrieved successfully.');
    }

    public function productReviews(Product $product)
    {
        return $this->sendResponse(ProductReviewsResource::collection($product->reviews), 'Product reviews retrieved successfully.');
    }
    public function productReviewsSummary(Product $product)
    {
        return $this->sendResponse(ReviewsSummaryResource::make($product->reviews), 'Product reviews retrieved successfully.');
    }

    public function relatedProducts(Product $product, $count = 8)
    {
        $category_ids = $product->categories()->pluck('id');
        $related_products = Product::where('status', 'publish')
            ->where('brand_id', $product->brand_id)
            ->whereHas('categories', function ($query) use ($category_ids) {
                $query->whereIn('categories.id', $category_ids);
            })
            ->get()
            ->take($count);
        if ($related_products->count() == 0) {
            $related_products = Product::where('status', 'publish')
                ->whereHas('categories', function ($query) use ($category_ids) {
                    $query->whereIn('categories.id', $category_ids);
                })
                ->get()
                ->take($count);
        }
        return $this->sendResponse(RelatedProductsResource::collection($related_products), 'Related products retrieved successfully.');
    }

    public function questionsAndAnswers(Product $product, $count = 8)
    {
        $q_n_a = QuestionsAndAnswer::where('product_id', $product->id)->get()->take($count);
        return $this->sendResponse(QuestionsAndAnswersResource::collection($q_n_a), 'Q and A retrieved successfully.');
    }

    public function specCompare(Request $request)
    {
        $product_ids = $request->product_id;
        $products = Product::with(['specifications'])->whereIn('id', $product_ids)->get();
        $comparison_data = [];

        // Loop through products and their specifications
        foreach ($products as $product) {
            foreach ($product->specifications as $specification) {
                // Initialize the specification array if it doesn't exist
                if (!isset($comparison_data[$specification->name])) {
                    $comparison_data[$specification->name] = [];
                }

                // Add the product's specification value
                $comparison_data[$specification->name][$product->id] = $specification->pivot->value;
            }
        }

        // Ensure all products are listed even if some lack specific specifications
        foreach ($comparison_data as $specification_name => &$values) {
            foreach ($product_ids as $product_id) {
                $values[$product_id] = $values[$product_id] ?? 'N/A'; // Default to 'N/A' if value is missing
            }
        }


        return $this->sendResponse($comparison_data, "Comparision data fetched successfully");
    }

    public function getVariantDetails($id)
    {
        $variant = ProductVariant::with(['product', 'variant_options.specification'])->find($id);
        if (!$variant) {
            return $this->sendError('Variant not found.', [], 404);
        }

        return $this->sendResponse(new VariantDetailResource($variant), 'Variant details retrieved successfully.');
    }
}
