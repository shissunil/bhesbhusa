<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\api\OrderApiController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController as FrontUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController as FrontOrderController;
use App\Http\Controllers\NotificationController;
/*
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('index');
// Route::get('/clear-cache', function(){
    // Artisan::call('config:clear');
    // Artisan::call('cache:clear');
    // Artisan::call('dump-autoload');
    // Artisan::call('view:clear');
    // Artisan::call('route:clear');
    // echo env('MAIL_HOST');exit;
// });
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'about_us'])->name('about-us');
Route::get('/terms-and-conditions', [HomeController::class, 'terms_and_conditions'])->name('terms_and_conditions');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/shop', [ShopController::class, 'shop'])->name('shop');

Route::post('filterCategoryData', [HomeController::class, 'filterCategoryData'])->name('filterCategoryData');

Route::middleware('preventBackHistory')->group(function () {
//Shop
Route::get('/categoryProductList/{super_categoy_id}', [ShopController::class, 'categoryProductList'])->name('categoryProductList');
Route::get('/productList/{sub_categoy_id}', [ShopController::class, 'productList'])->name('productList');
Route::get('/product/{product_id}', [ShopController::class, 'productDetails'])->name('productDetails');
Route::post('/check-pincode', [ShopController::class, 'checkPincode'])->name('checkPincode');
Route::post('/filter-products', [ShopController::class, 'searchFilterProductList'])->name('searchFilterProductList');
Route::get('/bannerProductList/{banner_id}', [ShopController::class, 'bannerProductList'])->name('bannerProductList');
Route::get('/search', [ShopController::class, 'searchProduct'])->name('searchProduct');
Route::post('/colorWiseProduct', [ShopController::class, 'colorWiseProduct'])->name('colorWiseProduct');

});

Route::middleware(['guest', 'preventBackHistory'])->group(function () {
    Route::get('/login', [FrontUserController::class, 'login'])->name('login');
    Route::get('/auth/login', [FrontUserController::class, 'loginForm'])->name('auth.login');
    Route::post('/auth/login', [FrontUserController::class, 'loginUser'])->name('login.submit');
    Route::get('/auth/google/redirect', [FrontUserController::class, 'handleGoogleRedirect'])->name('login.google.redirect');
    Route::get('/auth/google/callback', [FrontUserController::class, 'handleGoogleCallback'])->name('login.google.callback');
    Route::get('/otp', [FrontUserController::class, 'otp'])->name('otp');
    Route::post('/otp', [FrontUserController::class, 'verifyOTP'])->name('otp.verify');
    Route::get('/your-self', [FrontUserController::class, 'your_self'])->name('your_self');
    Route::post('/your-self', [FrontUserController::class, 'saveAboutYourSelf'])->name('your_self.submit');
    Route::get('/auth/facebook/redirect', [FrontUserController::class, 'handleFacebookRedirect'])->name('login.facebook.redirect');
    Route::get('/auth/facebook/callback', [FrontUserController::class, 'handleFacebookCallback'])->name('login.facebook.callback');
});

Route::middleware(['auth:web', 'preventBackHistory'])->group(function () {
    //User
    Route::get('/profile', [FrontUserController::class, 'profile'])->name('profile');
    Route::post('/profile', [FrontUserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile-pic-update', [FrontUserController::class, 'updateProfilePicture'])->name('profile_pic_post');
    
    Route::get('/my-address', [FrontUserController::class, 'my_address'])->name('my_address');
    Route::post('/save-address', [FrontUserController::class, 'saveAddress'])->name('address.save');
    Route::post('/update-address', [FrontUserController::class, 'updateAddress'])->name('address.update');
    Route::post('/remove-address', [FrontUserController::class, 'removeAddress'])->name('address.remove');
    Route::get('/logout', [FrontUserController::class, 'logout'])->name('logout');
    
    //Orders
    Route::get('/my-orders', [FrontOrderController::class, 'orderList'])->name('my_orders');

});

//Khalti Payment Page
Route::get('/make-payment', [PaymentController::class, 'make_payment'])->name('make_payment');
Route::post('/verify-payment', [PaymentController::class, 'verify_payment'])->name('verify_payment');

Route::middleware('preventBackHistory')->group(function () {
    Route::post('/payment/success', [PaymentController::class, 'payment_success'])->name('payment_success');
    Route::get('/payment/failed', [PaymentController::class, 'payment_failure'])->name('payment_failure');
});
//Invoice
Route::get('invoice/{id}', [OrderApiController::class, 'invoice'])->name('invoice');



Route::prefix('admin')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::get('/forgot-password', [AdminLoginController::class, 'showForgotPasswordForm'])->name('admin.forgot-password');

    //Admin Roles & Permissions Route
    Route::middleware('role')->group(function () {

        //Category
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.category.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('admin.category.create');
            Route::post('/create', [CategoryController::class, 'store'])->name('admin.category.store');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
            // Route::post('/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
        });

        //Sub Category
        Route::prefix('sub-category')->group(function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('admin.sub-category.index');
            Route::get('/create', [SubCategoryController::class, 'create'])->name('admin.sub-category.create');
            Route::post('/create', [SubCategoryController::class, 'store'])->name('admin.sub-category.store');
            Route::get('/{id}/edit', [SubCategoryController::class, 'edit'])->name('admin.sub-category.edit');
            Route::put('/{id}', [SubCategoryController::class, 'update'])->name('admin.sub-category.update');
            // Route::post('/{id}', [SubCategoryController::class, 'destroy'])->name('admin.sub-category.destroy');
        });

        //Tax
        // Route::prefix('tax')->group(function () {
        //     Route::get('/', [TaxController::class, 'index'])->name('admin.tax.index');
        //     Route::get('/create', [TaxController::class, 'create'])->name('admin.tax.create');
        //     Route::post('/create', [TaxController::class, 'store'])->name('admin.tax.store');
        //     Route::get('/{id}/edit', [TaxController::class, 'edit'])->name('admin.tax.edit');
        //     Route::put('/{id}', [TaxController::class, 'update'])->name('admin.tax.update');
        //     // Route::post('/{id}', [TaxController::class, 'destroy'])->name('admin.tax.destroy');
        // });

        //State
        Route::prefix('state')->group(function () {
            Route::get('/', [StateController::class, 'index'])->name('admin.state.index');
            Route::get('/create', [StateController::class, 'create'])->name('admin.state.create');
            Route::post('/create', [StateController::class, 'store'])->name('admin.state.store');
            Route::get('/{id}/edit', [StateController::class, 'edit'])->name('admin.state.edit');
            Route::put('/{id}', [StateController::class, 'update'])->name('admin.state.update');
            // Route::post('/{id}', [StateController::class, 'destroy'])->name('admin.state.destroy');
        });

        //Role
        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('admin.role.index');
            Route::get('/create', [RoleController::class, 'create'])->name('admin.role.create');
            Route::post('/create', [RoleController::class, 'store'])->name('admin.role.store');
            Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('admin.role.edit');
            Route::put('/{id}', [RoleController::class, 'update'])->name('admin.role.update');
            // Route::post('/{id}', [StateController::class, 'destroy'])->name('admin.state.destroy');
        });

        //City
        Route::prefix('city')->group(function () {
            Route::get('/', [CityController::class, 'index'])->name('admin.city.index');
            Route::get('/create', [CityController::class, 'create'])->name('admin.city.create');
            Route::post('/create', [CityController::class, 'store'])->name('admin.city.store');
            Route::get('/{id}/edit', [CityController::class, 'edit'])->name('admin.city.edit');
            Route::put('/{id}', [CityController::class, 'update'])->name('admin.city.update');
            // Route::post('/{id}', [CityController::class, 'destroy'])->name('admin.city.destroy');
        });

    });

    Route::post('/getCityFromState', [StateController::class, 'getCityFromState'])->name('admin.get_city');
    Route::post('/getPincodeFromCity', [StateController::class, 'getPincodeFromCity'])->name('admin.get_pincode');

    Route::post('/getSuperSubCategory', [CategoryController::class, 'getSuperSubCategory'])->name('admin.get_super_sub_category');
    Route::post('/getCategory', [CategoryController::class, 'getCategory'])->name('admin.get_category');
    Route::post('/getSubCategory', [CategoryController::class, 'getSubCategory'])->name('admin.get_sub_category');
    Route::post('/getProduct', [ProductController::class, 'getProduct'])->name('admin.get_product');

});
