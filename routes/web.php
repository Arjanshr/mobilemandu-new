<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('front.home');
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'storage:linked';
});
Route::get('/front-test', [FrontController::class, 'test'])->name('front.test');
Route::middleware('guest')->group(function () {
    Route::get('auth/{provider}/redirect', [SocialiteController::class, 'loginSocial'])
        ->name('socialite.auth');
    Route::get('auth/{provider}/callback', [SocialiteController::class, 'callbackSocial'])
        ->name('socialite.callback');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'is_admin'
])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/test', [DashboardController::class, 'test'])->name('admin.test');

    //Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Users routes
    Route::get('/users', [UserController::class, 'index'])->name('users')->middleware('can:browse-users');
    Route::middleware('can:add-users')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/users/insert', [UserController::class, 'insert'])->name('user.insert');
    });
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show')->middleware('can:read-users');
    Route::middleware('can:edit-users')->group(function () {
        Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/users/edit/{user}', [UserController::class, 'update'])->name('user.update');
    });
    Route::delete('/users/delete/{user}', [UserController::class, 'delete'])->name('user.delete')->middleware('can:delete-users');
    Route::get('/users/activities/{user}', [UserController::class, 'activities'])->name('user.activity')->middleware('can:browse-activities');
    Route::get('/users/activities/view/{activity}', [UserController::class, 'showActivity'])->name('user.activity.show')->middleware('can:read-activities');

    //Roles routes
    Route::get('/roles', [RoleController::class, 'index'])->name('roles')->middleware('can:browse-roles');
    Route::middleware('can:add-roles')->group(function () {
        Route::get('/roles/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('/roles/insert', [RoleController::class, 'insert'])->name('role.insert');
    });
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('role.show')->middleware('can:read-roles');
    Route::middleware('can:edit-roles')->group(function () {
        Route::get('/roles/edit/{role}', [RoleController::class, 'edit'])->name('role.edit');
        Route::patch('/roles/edit/{role}', [RoleController::class, 'update'])->name('role.update');
    });
    Route::delete('/roles/delete/{role}', [RoleController::class, 'delete'])->name('role.delete')->middleware('can:delete-roles');


    //Permission routes
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions')->middleware('can:browse-permissions');
    Route::middleware('can:add-permissions')->group(function () {
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permission.create');
        Route::post('/permissions/insert', [PermissionController::class, 'insert'])->name('permission.insert');
    });
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permission.show')->middleware('can:read-permissions');
    Route::middleware('can:edit-permissions')->group(function () {
        Route::get('/permissions/edit/{permission}', [PermissionController::class, 'edit'])->name('permission.edit');
        Route::patch('/permissions/edit/{permission}', [PermissionController::class, 'update'])->name('permission.update');
    });
    Route::delete('/permissions/delete/{permission}', [PermissionController::class, 'delete'])->name('permission.delete')->middleware('can:delete-permissions');

    // Settings routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings')->middleware('can:browse-settings');
    Route::middleware('can:edit-general-settings')->group(function () {
        Route::get('/settings/general', [SettingController::class, 'generalSettings'])->name('setting.general.edit');
        Route::patch('/settings/general', [SettingController::class, 'updateGeneralSettings'])->name('setting.general.update');
    });
    Route::middleware('can:add-settings')->group(function () {
        Route::get('/settings/create', [SettingController::class, 'create'])->name('setting.create');
        Route::post('/settings/insert', [SettingController::class, 'insert'])->name('setting.insert');
    });
    Route::get('/settings/{setting}', [SettingController::class, 'show'])->name('setting.show')->middleware('can:read-settings');
    Route::middleware('can:edit-settings')->group(function () {
        Route::get('/settings/edit/{setting}', [SettingController::class, 'edit'])->name('setting.edit');
        Route::patch('/settings/edit/{setting}', [SettingController::class, 'update'])->name('setting.update');
    });
    Route::delete('/settings/delete/{setting}', [SettingController::class, 'delete'])->name('setting.delete')->middleware('can:delete-settings');

    //Categories routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories')->middleware('can:browse-categories');
    Route::middleware('can:add-categories')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/categories/insert', [CategoryController::class, 'insert'])->name('category.insert');
    });
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('category.show')->middleware('can:read-categories');
    Route::middleware('can:edit-categories')->group(function () {
        Route::get('/categories/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::patch('/categories/edit/{category}', [CategoryController::class, 'update'])->name('category.update');
    });
    Route::delete('/categories/delete/{category}', [CategoryController::class, 'delete'])->name('category.delete')->middleware('can:delete-categories');

    //Category Specifications routes
    Route::get('/category-specifications/{category}', [CategoryController::class, 'categorySpecifications'])->name('category-specifications')->middleware('can:browse-category-specifications');
    Route::middleware('can:add-category-specifications')->group(function () {
        Route::get('/category-specifications/create/{category}', [CategoryController::class, 'createCategorySpecifications'])->name('category-specification.create');
        Route::post('/category-specifications/insert/{category}', [CategoryController::class, 'insertCategorySpecifications'])->name('category-specification.insert');
    });
    Route::middleware('can:edit-category-specifications')->group(function () {
        Route::get('/category-specifications/edit/{category}/{category_specification}', [CategoryController::class, 'editCategorySpecifications'])->name('category-specification.edit');
        Route::patch('/category-specifications/edit/{category}/{category_specification}', [CategoryController::class, 'updateCategorySpecifications'])->name('category-specification.update');
    });
    Route::delete('/category-specifications/delete/{category}/{category_specification_id}', [CategoryController::class, 'deleteCategorySpecifications'])->name('category-specification.delete')->middleware('can:delete-category-specifications');

    //Brand routes
    Route::get('/brands', [BrandController::class, 'index'])->name('brands')->middleware('can:browse-brands');
    Route::middleware('can:add-brands')->group(function () {
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/brands/insert', [BrandController::class, 'insert'])->name('brand.insert');
    });
    Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brand.show')->middleware('can:read-brands');
    Route::middleware('can:edit-brands')->group(function () {
        Route::get('/brands/edit/{brand}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::patch('/brands/edit/{brand}', [BrandController::class, 'update'])->name('brand.update');
    });
    Route::delete('/brands/delete/{brand}', [BrandController::class, 'delete'])->name('brand.delete')->middleware('can:delete-brands');

    //Products routes
    Route::get('/products', [ProductController::class, 'index'])->name('products')->middleware('can:browse-products');
    Route::middleware('can:add-products')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/products/import', [ProductController::class, 'import'])->name('product.import');
        Route::post('/products/insert', [ProductController::class, 'insert'])->name('product.insert');
    });
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show')->middleware('can:read-products');
    Route::middleware('can:edit-products')->group(function () {
        Route::get('/products/edit/{product}', [ProductController::class, 'edit'])->name('product.edit');
        Route::patch('/products/edit/{product}', [ProductController::class, 'update'])->name('product.update');

        Route::get('/products/specifications/{product}', [ProductController::class, 'manageSpecifications'])->name('product.specifications');
        Route::get('/products/specifications/create/{product}', [ProductController::class, 'createSpecifications'])->name('product.specification.create');
        Route::post('/products/specifications/insert/{product}', [ProductController::class, 'insertSpecifications'])->name('product.specification.insert');
        Route::get('/products/specifications/edit/{product_specification}', [ProductController::class, 'editSpecifications'])->name('product.specification.edit');
        Route::patch('/products/specifications/edit/{product_specification}', [ProductController::class, 'updateSpecifications'])->name('product.specification.update');
        Route::delete('/products/specifications/delete/{product}/{specification}', [ProductController::class, 'deleteSpecifications'])->name('product.specification.delete');
        Route::delete('/products/specifications/delete_all/{product}', [ProductController::class, 'deleteAllSpecifications'])->name('product.specification.delete.all');

        Route::get('/products/features/{product}', [ProductController::class, 'manageFeatures'])->name('product.features');
        Route::get('/products/features/create/{product}', [ProductController::class, 'createFeatures'])->name('product.feature.create');
        Route::post('/products/features/insert/{product}', [ProductController::class, 'insertFeatures'])->name('product.feature.insert');
        Route::get('/products/features/edit/{feature}', [ProductController::class, 'editFeatures'])->name('product.feature.edit');
        Route::patch('/products/features/edit/{feature}', [ProductController::class, 'updateFeatures'])->name('product.feature.update');
        Route::delete('/products/features/delete/{feature}', [ProductController::class, 'deleteFeatures'])->name('product.feature.delete');
        Route::delete('/products/features/delete_all/{product}', [ProductController::class, 'deleteAllFeatures'])->name('product.features.delete.all');

        Route::get('/products/images/link-images', [ProductController::class, 'linkImages']);
        Route::get('/products/images/{product}', [ProductController::class, 'manageImages'])->name('product.images');
        Route::post('/products/images/insert/{product}', [ProductController::class, 'insertImages'])->name('product.image.insert');
        Route::patch('/products/images/edit/{product}', [ProductController::class, 'updateImages'])->name('product.image.update');
        Route::delete('/products/images/delete/{product}', [ProductController::class, 'deleteImages'])->name('product.image.delete');

        Route::get('/products/variants/{product}', [ProductController::class, 'manageVariants'])->name('product.variants');
        Route::get('/products/variants/create/{product}', [ProductController::class, 'createVariants'])->name('product.variant.create');
        Route::post('/products/variants/insert/{product}', [ProductController::class, 'insertVariants'])->name('product.variant.insert');
        // Edit Variant
        Route::get('/products/{product}/variants/{variant}/edit', [ProductController::class, 'editVariants'])->name('product.variant.edit');

        // Update Variant
        Route::put('/products/{product}/variants/{variant}', [ProductController::class, 'updateVariants'])->name('product.variant.update');

        // Delete Variant
        Route::delete('/products/{product}/variants/{variant}', [ProductController::class, 'deleteVariants'])->name('product.variant.delete');
        Route::delete('/products/variants/delete_all/{product}', [ProductController::class, 'deleteAllVariants'])->name('product.variant.delete.all');
    });
    Route::delete('/products/delete/{product}', [ProductController::class, 'delete'])->name('product.delete')->middleware('can:delete-products');

    //Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders')->middleware('can:browse-orders');
    Route::middleware('can:add-orders')->group(function () {
        Route::get('/orders/create', [OrderController::class, 'create'])->name('order.create');
        Route::post('/orders/insert', [OrderController::class, 'insert'])->name('order.insert');
    });
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('order.show')->middleware('can:read-orders');
    Route::middleware('can:edit-orders')->group(function () {
        Route::get('/orders/edit/{order}', [OrderController::class, 'edit'])->name('order.edit');
        Route::patch('/orders/edit/{order}', [OrderController::class, 'update'])->name('order.update');
    });
    Route::delete('/orders/delete/{order}', [OrderController::class, 'delete'])->name('order.delete')->middleware('can:delete-orders');

    //Homepage content routes
    Route::get('/contents/{content_type}', [ContentController::class, 'index'])->name('contents')->middleware('can:browse-contents');
    Route::post('/contents/{content_type}/insert', [ContentController::class, 'insert'])->name('contents.insert')->middleware('can:add-contents');
    Route::delete('/contents/{content_type}/delete/{content_id}', [ContentController::class, 'delete'])->name('content.delete')->middleware('can:delete-contents');

    //Campaign Routes
    Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns')->middleware('can:browse-campaigns');
    Route::get('/campaigns/create', [CampaignsController::class, 'create'])->name('campaigns.create')->middleware('can:add-campaigns');
    Route::post('/campaigns/create', [CampaignsController::class, 'insert'])->name('campaigns.insert')->middleware('can:add-campaigns');
    Route::get('/campaigns/edit/{campaign}', [CampaignsController::class, 'edit'])->name('campaigns.edit')->middleware('can:edit-campaigns');
    Route::patch('/campaigns/edit/{campaign}', [CampaignsController::class, 'update'])->name('campaigns.update')->middleware('can:edit-campaigns');
    Route::get('/campaigns/products/{campaign}', [CampaignsController::class, 'products'])->name('campaigns.products')->middleware('can:read-campaigns');
    Route::post('/campaigns/products/{campaign}', [CampaignsController::class, 'productsAction'])->name('campaigns.products.action')->middleware('can:read-campaigns');
    Route::get('/campaigns/products/{campaign}/delete/{product}', [CampaignsController::class, 'productDelete'])->name('campaigns.products.delete')->middleware('can:delete-campaigns');
    Route::delete('/campaigns/delete/{campaign}', [CampaignsController::class, 'delete'])->name('campaigns.delete')->middleware('can:delete-campaigns');
    Route::post('/update-discount', [CampaignsController::class, 'updateDiscount']);

    //Sliders routes
    Route::get('/sliders', [SliderController::class, 'index'])->name('sliders')->middleware('can:browse-sliders');
    Route::middleware('can:add-sliders')->group(function () {
        Route::get('/sliders/create', [SliderController::class, 'create'])->name('slider.create');
        Route::post('/sliders/insert', [SliderController::class, 'insert'])->name('slider.insert');
    });
    Route::get('/sliders/{slider}', [SliderController::class, 'show'])->name('slider.show')->middleware('can:read-sliders');
    Route::middleware('can:edit-sliders')->group(function () {
        Route::get('/sliders/edit/{slider}', [SliderController::class, 'edit'])->name('slider.edit');
        Route::patch('/sliders/edit/{slider}', [SliderController::class, 'update'])->name('slider.update');
    });
    Route::delete('/sliders/delete/{slider}', [SliderController::class, 'delete'])->name('slider.delete')->middleware('can:delete-sliders');

    //Blogs routes
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs')->middleware('can:browse-blogs');
    Route::middleware('can:add-blogs')->group(function () {
        Route::get('/blogs/create', [BlogController::class, 'create'])->name('blog.create');
        Route::post('/blogs/insert', [BlogController::class, 'insert'])->name('blog.insert');
    });
    Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blog.show')->middleware('can:read-blogs');
    Route::middleware('can:edit-blogs')->group(function () {
        Route::get('/blogs/edit/{blog}', [BlogController::class, 'edit'])->name('blog.edit');
        Route::patch('/blogs/edit/{blog}', [BlogController::class, 'update'])->name('blog.update');
    });
    Route::delete('/blogs/delete/{blog}', [BlogController::class, 'delete'])->name('blog.delete')->middleware('can:delete-blogs');

    // Coupons routes
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons')->middleware('can:browse-coupons');
    Route::middleware('can:add-coupons')->group(function () {
        Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
        Route::post('/coupons/insert', [CouponController::class, 'insert'])->name('coupons.insert');
    });
    Route::middleware('can:edit-coupons')->group(function () {
        Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
        Route::patch('/coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    });
    Route::delete('/coupons/{coupon}', [CouponController::class, 'delete'])->name('coupons.delete')->middleware('can:delete-coupons');
});
