<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\SocialiteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
        Route::post('password/email', [AuthController::class, 'resetPasswordSendEmail']);
        Route::get('brands', [BrandController::class, 'brands']);
        Route::get('brands/{brand}', [BrandController::class, 'details']);
        Route::get('categories', [CategoryController::class, 'categories']);
        Route::get('categories/{category}', [CategoryController::class, 'details']);

        //Product list for brand only
        Route::get('products/brands/{brand}/{paginate?}', [ProductController::class, 'getProductByBrand']);
        Route::get('products/get_categories_for_brand/{brand}', [ProductController::class, 'getCategoriesForBrandFilter']);
        Route::get('products/get_price_range_for_brand/{brand}', [ProductController::class, 'getPriceRangeForBrandFilter']);
        Route::get('products/get_rating_range_for_brand/{brand}', [ProductController::class, 'getRatingRangeForBrandFilter']);

        //Product list for category only
        Route::get('products/categories/{category}/{paginate?}', [ProductController::class, 'getProductByCategory']);
        Route::get('products/get_brands_for_category/{category}', [ProductController::class, 'getBrandsForCategoryFilter']);
        Route::get('products/get_price_range_for_category/{category}', [ProductController::class, 'getPriceRangeForCategoryFilter']);
        Route::get('products/get_rating_range_for_category/{category}', [ProductController::class, 'getRatingRangeForCategoryFilter']);

        //Product list for brand and category
        Route::get('products/brand_categories/{brand}/{category}/{paginate?}', [ProductController::class, 'getProductByBrandAndCategory']);
        Route::get('products/get_brands_for_category/{category}', [ProductController::class, 'getBrandsForCategoryFilter']);
        Route::get('products/get_price_range_for_category/{category}', [ProductController::class, 'getPriceRangeForCategoryFilter']);
        Route::get('products/get_rating_range_for_category/{category}', [ProductController::class, 'getRatingRangeForCategoryFilter']);

        //Product list for search
        Route::get('products/search/{paginate?}', [ProductController::class, 'searchProducts']);
        Route::get('products/get_brands_for_search', [ProductController::class, 'getBrandsForSearch']);
        Route::get('products/get_category_for_search', [ProductController::class, 'getCategoriesForSearch']);
        Route::get('products/get_price_range_for_search', [ProductController::class, 'getPriceRangeForSearch']);
        Route::get('products/get_rating_list_for_search', [ProductController::class, 'getRatingRangeForSearch']);

        //Product Detail
        Route::get('product/test/{product}',[ProductController::class,'productDetails']);
        Route::get('product_detail/{product}', [ProductController::class, 'productDetails']);
        Route::get('product_specifications/{product}', [ProductController::class, 'productSpecifications']);
        Route::get('product_features/{product}', [ProductController::class, 'productFeatures']);
        Route::get('product_reviews/{product}', [ProductController::class, 'productReviews']);
        Route::get('product_reviews_summary/{product}', [ProductController::class, 'productReviewsSummary']);
        Route::get('related_products/{product}/{count?}', [ProductController::class, 'relatedProducts']);
        Route::get('questions_and_answers/{product}/{count?}', [ProductController::class, 'questionsAndAnswers']);

        //Social Logins
        Route::get('social-login-data/{provider?}', [SocialiteController::class, 'loginSocial']);
        Route::get('auth/{provider}/callback', [SocialiteController::class, 'callbackSocial']);

        //Home Page Contents
        Route::get('/content/{content_type?}/{items_per_page?}', [ContentController::class, 'getProductList']);

        //Place Order
        Route::post('orders/place', [OrderController::class, 'create']);
   
        //Address
        Route::get('address/get-provinces', [OrderController::class, 'getProvinces']);
        Route::get('address/get-cities/{province_id?}', [OrderController::class, 'getCities']);
        Route::get('address/get-areas/{city_id?}', [OrderController::class, 'getAreas']);
        Route::get('address/get-shipping-price/{area}', [OrderController::class, 'getShippingPrice']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('profile', [UserController::class, 'profile']);
        Route::get('addresses', [UserController::class, 'addresses']);

    });
});