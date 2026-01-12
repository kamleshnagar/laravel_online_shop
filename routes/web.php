<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/register', [AuthController::class, 'register'])->name('account.register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('account.processRegister');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('account.loginProcess');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/profile/change-password', [AuthController::class, 'changePassword'])->name('account.change.password');
    Route::post('/profile/update-password', [AuthController::class, 'updatePassword'])->name('account.password.update');
        

    Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
    Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');
    Route::get('/thank-you', function () {
        return view('front.thankyou');
    })->name('front.thankyou');
    Route::get('/get-shipping', [CartController::class, 'getShipping'])->name('front.getShipping');
    Route::get('/order-summery', [CartController::class, 'orderSummery'])->name('front.orderSummery');
});

Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/delete-cart-product', [CartController::class, 'deletCartItem'])->name('front.deletCartItem');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('front.updateCart');




Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Category Routes
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

        // temp images
        Route::post('/upload-tempImage', [TempImagesController::class, 'create'])->name('temp-images.create');

        // Sub Categories Route
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        // Brands
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [BrandController::class, 'create'])->name('create.brand');
        Route::post('/brands', [BrandController::class, 'store'])->name('store.brand');
        Route::get('/brands/{brand}', [BrandController::class, 'edit'])->name('edit.brand');
        Route::post('/brands/{brand}', [BrandController::class, 'update'])->name('update.brand');
        Route::get('/brands/{category}/delete', [BrandController::class, 'destroy'])->name('delete.brand');


        //Products Route
        Route::get('/products', [ProductController::class, 'index'])->name('index.products');
        Route::get('/products/create/', [ProductController::class, 'create'])->name('create.product');
        Route::post('/products', [ProductController::class, 'store'])->name('store.product');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('edit.product');
        Route::post('/products/{product}', [ProductController::class, 'update'])->name('update.product');

        Route::get('/shippings', [ShippingController::class, 'index'])->name('shipping.index');
        Route::post('/shippings', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/delete/{id}', [ShippingController::class, 'destroy'])->name('shipping.destroy');

        Route::get('/products-subcategories', [ProductSubCategoryController::class, 'index'])->name('index.product-subcategories');

        Route::post('/products-update/update', [ProductImageController::class, 'update'])->name('update.product-image');
        Route::delete('/product-delete', [ProductImageController::class, 'destroy'])->name('destroy.product-image');
        Route::get('/product-delete/{product_id}', [ProductController::class, 'destroy'])->name('destroy.product');

        Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');

        Route::resource('/roles', RoleController::class);
        Route::resource('/users', UserController::class);

        Route::get('/getSlug', function (Request $request) {

            $slug = '';

            if (! empty($request->title)) {
                $slug = Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug,
            ]);
        })->name('getSlug');
    });
});
