<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
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
    Route::get('auth/callback/{provider}', [SocialiteController::class, 'callbackSocial'])
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
        Route::delete('/products/specifications/delete/{product_specification}', [ProductController::class, 'deleteSpecifications'])->name('product.specification.delete');
        Route::delete('/products/specifications/delete_all/{product}', [ProductController::class, 'deleteAllSpecifications'])->name('product.specification.delete.all');

        Route::get('/products/features/{product}', [ProductController::class, 'manageFeatures'])->name('product.features');
        Route::get('/products/features/create/{product}', [ProductController::class, 'createFeatures'])->name('product.feature.create');
        Route::post('/products/features/insert/{product}', [ProductController::class, 'insertFeatures'])->name('product.feature.insert');
        Route::get('/products/features/edit/{feature}', [ProductController::class, 'editFeatures'])->name('product.feature.edit');
        Route::patch('/products/features/edit/{feature}', [ProductController::class, 'updateFeatures'])->name('product.feature.update');
        Route::delete('/products/features/delete/{feature}', [ProductController::class, 'deleteFeatures'])->name('product.feature.delete');

        Route::get('/products/images/link-images', [ProductController::class, 'linkImages']);
        Route::get('/products/images/{product}', [ProductController::class, 'manageImages'])->name('product.images');
        Route::post('/products/images/insert/{product}', [ProductController::class, 'insertImages'])->name('product.image.insert');
        Route::patch('/products/images/edit/{product}', [ProductController::class, 'updateImages'])->name('product.image.update');
        Route::delete('/products/images/delete/{product}', [ProductController::class, 'deleteImages'])->name('product.image.delete');
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
    Route::get('/campaigns', [CampaignsController::class,'index'])->name('campaigns')->middleware('can:browse-campaigns');
	Route::get('/campaigns/create', [CampaignsController::class,'create'])->name('campaigns.create')->middleware('can:add-campaigns');
	Route::post('/campaigns/create', [CampaignsController::class,'insert'])->name('campaigns.insert')->middleware('can:add-campaigns');
	Route::get('/campaigns/edit/{campaign}', [CampaignsController::class,'edit'])->name('campaigns.edit')->middleware('can:edit-campaigns');
    Route::patch('/campaigns/edit/{campaign}', [CampaignsController::class, 'update'])->name('campaigns.update')->middleware('can:edit-campaigns');
    Route::get('/campaigns/products/{campaign}', [CampaignsController::class,'products'])->name('campaigns.products')->middleware('can:read-campaigns');
	Route::post('/campaigns/products/{campaign}', [CampaignsController::class,'productsAction'])->name('campaigns.products.action')->middleware('can:read-campaigns');
	Route::get('/campaigns/products/{campaign}/delete/{product}', [CampaignsController::class,'productDelete'])->name('campaigns.products.delete')->middleware('can:delete-campaigns');
	Route::delete('/campaigns/delete/{campaign}', [CampaignsController::class,'delete'])->name('campaigns.delete')->middleware('can:delete-campaigns');
    Route::post('/update-discount',[CampaignsController::class,'updateDiscount']);


});
