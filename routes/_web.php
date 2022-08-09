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
use App\Http\Controllers\Admin\PinCodeController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SuperCategoryController;
use App\Http\Controllers\Admin\SuperSubCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\TicketReasonController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\DeliveryAssociatesController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\api\OrderApiController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController as ShopController;
use App\Http\Controllers\UserController as FrontUserController;
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
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'about_us'])->name('about-us');
Route::get('/terms-and-conditions', [HomeController::class, 'terms_and_conditions'])->name('terms_and_conditions');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/shop', [ShopController::class, 'shop'])->name('shop');
Route::get('/product-details', [ShopController::class, 'productDetails'])->name('productDetails');

Route::middleware('guest')->group(function () {

    Route::get('/login', [FrontUserController::class, 'login'])->name('login');
    Route::get('/auth/login', [FrontUserController::class, 'loginForm'])->name('auth.login');
    Route::post('/auth/login', [FrontUserController::class, 'loginUser'])->name('login.submit');
    Route::get('/auth/google/redirect', [FrontUserController::class, 'handleGoogleRedirect'])->name('login.google.redirect');
    Route::get('/auth/google/callback', [FrontUserController::class, 'handleGoogleCallback'])->name('login.google.callback');
    Route::get('/otp', [FrontUserController::class, 'otp'])->name('otp');
    Route::get('/your-self', [FrontUserController::class, 'your_self'])->name('your_self');
    Route::post('/your-self', [FrontUserController::class, 'saveAboutYourSelf'])->name('your_self.submit');

});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [FrontUserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [FrontUserController::class, 'profile'])->name('profile');
    Route::get('/wishlist', [FrontUserController::class, 'wishlist'])->name('wishlist');
    Route::get('/my-address', [FrontUserController::class, 'my_address'])->name('my_address');
    Route::get('/my-orders', [FrontUserController::class, 'my_orders'])->name('my_orders');
    Route::get('/order-details', [FrontUserController::class, 'orderDetails'])->name('orderDetails');
    Route::get('/return-order', [FrontUserController::class, 'returnOrder'])->name('returnOrder');
    Route::get('/notifications', [FrontUserController::class, 'notifications'])->name('notifications');
    Route::get('/cart', [FrontUserController::class, 'cart'])->name('cart');
    Route::get('/thank-you', [FrontUserController::class, 'thank_you'])->name('thank_you');

});


//Khalti Payment Page
Route::get('/make-payment', [PaymentController::class, 'make_payment'])->name('make_payment');
Route::post('/verify-payment', [PaymentController::class, 'verify_payment'])->name('verify_payment');
Route::post('/payment/success', [PaymentController::class, 'payment_success'])->name('payment_success');
Route::get('/payment/failed', [PaymentController::class, 'payment_failure'])->name('payment_failure');
Route::any('onlinePay', [OrderApiController::class, 'onlinePay'])->name('onlinePay');


//Invoice
Route::get('invoice/{id}', [OrderApiController::class, 'invoice'])->name('invoice');


Route::prefix('admin')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::get('/forgot-password', [AdminLoginController::class, 'showForgotPasswordForm'])->name('admin.forgot-password');
    Route::post('/forgot-password', [AdminLoginController::class, 'validateEmail'])->name('admin.forgot-password.submit');
    Route::post('/reset-password', [AdminLoginController::class, 'updatePassword'])->name('admin.reset-password.submit');
    Route::get('/reset-password/{token}', [AdminLoginController::class, 'forgotPasswordValidate'])->name('admin.reset-password');
    Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/profile', [AdminController::class, 'update_profile'])->name('admin.profile.update');
    Route::get('/change-password', [AdminController::class, 'showChangePasswordForm'])->name('admin.change-password');
    Route::post('/change-password', [AdminController::class, 'change_password']);
    // Route::get('/', function(){
    //     // Artisan::call('route:cache');
    //     // Artisan::call('config:cache');
    //     // Artisan::call('cache:clear');
    //     // Artisan::call('view:clear');
    // });

    //Admin Roles & Permissions Route 
    Route::middleware('role')->group(function () {

        //Category
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.category.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('admin.category.create');
            Route::post('/create', [CategoryController::class, 'store'])->name('admin.category.store');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
            Route::post('/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
        });

        //Sub Category
        Route::prefix('sub-category')->group(function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('admin.sub-category.index');
            Route::get('/create', [SubCategoryController::class, 'create'])->name('admin.sub-category.create');
            Route::post('/create', [SubCategoryController::class, 'store'])->name('admin.sub-category.store');
            Route::get('/{id}/edit', [SubCategoryController::class, 'edit'])->name('admin.sub-category.edit');
            Route::put('/{id}', [SubCategoryController::class, 'update'])->name('admin.sub-category.update');
            Route::post('/{id}', [SubCategoryController::class, 'destroy'])->name('admin.sub-category.destroy');
        });

        //Tax
        Route::prefix('tax')->group(function () {
            Route::get('/', [TaxController::class, 'index'])->name('admin.tax.index');
            Route::get('/create', [TaxController::class, 'create'])->name('admin.tax.create');
            Route::post('/create', [TaxController::class, 'store'])->name('admin.tax.store');
            Route::get('/{id}/edit', [TaxController::class, 'edit'])->name('admin.tax.edit');
            Route::put('/{id}', [TaxController::class, 'update'])->name('admin.tax.update');
            Route::post('/{id}', [TaxController::class, 'destroy'])->name('admin.tax.destroy');
        });

        //State
        Route::prefix('state')->group(function () {
            Route::get('/', [StateController::class, 'index'])->name('admin.state.index');
            Route::get('/create', [StateController::class, 'create'])->name('admin.state.create');
            Route::post('/create', [StateController::class, 'store'])->name('admin.state.store');
            Route::get('/{id}/edit', [StateController::class, 'edit'])->name('admin.state.edit');
            Route::put('/{id}', [StateController::class, 'update'])->name('admin.state.update');
            Route::post('/{id}', [StateController::class, 'destroy'])->name('admin.state.destroy');
        });

        //City
        Route::prefix('city')->group(function () {
            Route::get('/', [CityController::class, 'index'])->name('admin.city.index');
            Route::get('/create', [CityController::class, 'create'])->name('admin.city.create');
            Route::post('/create', [CityController::class, 'store'])->name('admin.city.store');
            Route::get('/{id}/edit', [CityController::class, 'edit'])->name('admin.city.edit');
            Route::put('/{id}', [CityController::class, 'update'])->name('admin.city.update');
            Route::post('/{id}', [CityController::class, 'destroy'])->name('admin.city.destroy');
        });

        //Pincode
        Route::prefix('pincode')->group(function () {
            Route::get('/', [PinCodeController::class, 'index'])->name('admin.pincode.index');
            Route::get('/create', [PinCodeController::class, 'create'])->name('admin.pincode.create');
            Route::post('/create', [PinCodeController::class, 'store'])->name('admin.pincode.store');
            Route::get('/{id}/edit', [PinCodeController::class, 'edit'])->name('admin.pincode.edit');
            Route::put('/{id}', [PinCodeController::class, 'update'])->name('admin.pincode.update');
            Route::post('/{id}', [PinCodeController::class, 'destroy'])->name('admin.pincode.destroy');
        });

        //Area
        Route::prefix('area')->group(function () {
            Route::get('/', [AreaController::class, 'index'])->name('admin.area.index');
            Route::get('/create', [AreaController::class, 'create'])->name('admin.area.create');
            Route::post('/create', [AreaController::class, 'store'])->name('admin.area.store');
            Route::get('/{id}/edit', [AreaController::class, 'edit'])->name('admin.area.edit');
            Route::put('/{id}', [AreaController::class, 'update'])->name('admin.area.update');
            Route::post('/{id}', [AreaController::class, 'destroy'])->name('admin.area.destroy');
        });

        Route::prefix('setting')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('admin.setting.index');
            Route::put('/update', [SettingController::class, 'update'])->name('admin.setting.update');
        });

        //Users
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('admin.users.update');
        });

        //Ticket Reason
        Route::prefix('ticket-reasons')->group(function () {
            Route::get('/', [TicketReasonController::class, 'index'])->name('admin.ticket-reasons.index');
            Route::get('/create', [TicketReasonController::class, 'create'])->name('admin.ticket-reasons.create');
            Route::post('/create', [TicketReasonController::class, 'store'])->name('admin.ticket-reasons.store');
            Route::get('/{id}/edit', [TicketReasonController::class, 'edit'])->name('admin.ticket-reasons.edit');
            Route::put('/{id}', [TicketReasonController::class, 'update'])->name('admin.ticket-reasons.update');
            Route::post('/{id}', [TicketReasonController::class, 'destroy'])->name('admin.ticket-reasons.destroy');
        });

        //Offer
        Route::prefix('offers')->group(function () {
            Route::get('/', [OfferController::class, 'index'])->name('admin.offers.index');
            Route::get('/create', [OfferController::class, 'create'])->name('admin.offers.create');
            Route::post('/create', [OfferController::class, 'store'])->name('admin.offers.store');
            Route::get('/{id}/edit', [OfferController::class, 'edit'])->name('admin.offers.edit');
            Route::put('/{id}', [OfferController::class, 'update'])->name('admin.offers.update');
            Route::post('/{id}', [OfferController::class, 'destroy'])->name('admin.offers.destroy');
        });

        Route::prefix('notification')->group(function () {
            Route::get('/', [SettingController::class, 'create'])->name('admin.notification.create');
            Route::post('/send', [SettingController::class, 'send'])->name('admin.notification.send');
            // Route::get('/create', [OfferController::class, 'create'])->name('admin.offers.create');
            // Route::get('/{id}/edit', [OfferController::class, 'edit'])->name('admin.offers.edit');
            // Route::put('/{id}', [OfferController::class, 'update'])->name('admin.offers.update');
            // Route::post('/{id}', [OfferController::class, 'destroy'])->name('admin.offers.destroy');
        });

        //Banner
        Route::prefix('banners')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('admin.banners.index');
            Route::get('/create', [BannerController::class, 'create'])->name('admin.banners.create');
            Route::post('/create', [BannerController::class, 'store'])->name('admin.banners.store');
            Route::get('/{id}/edit', [BannerController::class, 'edit'])->name('admin.banners.edit');
            Route::put('/{id}', [BannerController::class, 'update'])->name('admin.banners.update');
            Route::post('/{id}', [BannerController::class, 'destroy'])->name('admin.banners.destroy');
        });

        //Super Category
        Route::prefix('super-category')->group(function () {
            Route::get('/', [SuperCategoryController::class, 'index'])->name('admin.super-category.index');
            Route::get('/create', [SuperCategoryController::class, 'create'])->name('admin.super-category.create');
            Route::post('/create', [SuperCategoryController::class, 'store'])->name('admin.super-category.store');
            Route::get('/{id}/edit', [SuperCategoryController::class, 'edit'])->name('admin.super-category.edit');
            Route::put('/{id}', [SuperCategoryController::class, 'update'])->name('admin.super-category.update');
            Route::delete('/{id}', [SuperCategoryController::class, 'destroy'])->name('admin.super-category.destroy');
        });

        //Super Sub Category
        Route::prefix('super-sub-category')->group(function () {
            Route::get('/', [SuperSubCategoryController::class, 'index'])->name('admin.super-sub-category.index');
            Route::get('/create', [SuperSubCategoryController::class, 'create'])->name('admin.super-sub-category.create');
            Route::post('/create', [SuperSubCategoryController::class, 'store'])->name('admin.super-sub-category.store');
            Route::get('/{id}/edit', [SuperSubCategoryController::class, 'edit'])->name('admin.super-sub-category.edit');
            Route::put('/{id}', [SuperSubCategoryController::class, 'update'])->name('admin.super-sub-category.update');
            Route::delete('/{id}', [SuperSubCategoryController::class, 'destroy'])->name('admin.super-sub-category.destroy');
        });

        //Brand
        Route::prefix('brand')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('admin.brand.index');
            Route::get('/create', [BrandController::class, 'create'])->name('admin.brand.create');
            Route::post('/create', [BrandController::class, 'store'])->name('admin.brand.store');
            Route::get('/{id}/edit', [BrandController::class, 'edit'])->name('admin.brand.edit');
            Route::put('/{id}', [BrandController::class, 'update'])->name('admin.brand.update');
            Route::post('/{id}', [BrandController::class, 'destroy'])->name('admin.brand.destroy');
        });

        //Delivery Associates
        Route::prefix('delivery-associates')->group(function () {
            Route::get('/', [DeliveryAssociatesController::class, 'index'])->name('admin.delivery_associates.index');
            Route::get('/create', [DeliveryAssociatesController::class, 'create'])->name('admin.delivery_associates.create');
            Route::post('/create', [DeliveryAssociatesController::class, 'store'])->name('admin.delivery_associates.store');
            Route::get('/{id}/edit', [DeliveryAssociatesController::class, 'edit'])->name('admin.delivery_associates.edit');
            Route::put('/{id}', [DeliveryAssociatesController::class, 'update'])->name('admin.delivery_associates.update');
        });

        //Sub Admin
        Route::prefix('sub-admins')->group(function () {
            Route::get('/', [AdminController::class, 'sub_admins'])->name('admin.sub_admins.index');
            Route::get('/create', [AdminController::class, 'create'])->name('admin.sub_admins.create');
            Route::post('/create', [AdminController::class, 'store'])->name('admin.sub_admins.store');
            Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admin.sub_admins.edit');
            Route::put('/{id}', [AdminController::class, 'update'])->name('admin.sub_admins.update');
        });

        //CMS Master
        Route::prefix('cms-master')->group(function () {
            Route::get('/', [CmsController::class, 'index'])->name('admin.cms-master.index');
            Route::get('/create', [CmsController::class, 'create'])->name('admin.cms-master.create');
            Route::post('/create', [CmsController::class, 'store'])->name('admin.cms-master.store');
            Route::get('/{id}/edit', [CmsController::class, 'edit'])->name('admin.cms-master.edit');
            Route::put('/{id}', [CmsController::class, 'update'])->name('admin.cms-master.update');
            Route::delete('/{id}', [CmsController::class, 'destroy'])->name('admin.cms-master.destroy');
        });

        //Product Management
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.product.index');
            Route::get('/create', [ProductController::class, 'create'])->name('admin.product.create');
            Route::post('/create', [ProductController::class, 'store'])->name('admin.product.store');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('admin.product.update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');
        });

        //Brand
        Route::prefix('color')->group(function () {
            Route::get('/', [ColorController::class, 'index'])->name('admin.color.index');
            Route::get('/create', [ColorController::class, 'create'])->name('admin.color.create');
            Route::post('/create', [ColorController::class, 'store'])->name('admin.color.store');
            Route::get('/{id}/edit', [ColorController::class, 'edit'])->name('admin.color.edit');
            Route::put('/{id}', [ColorController::class, 'update'])->name('admin.color.update');
            Route::delete('/{id}', [ColorController::class, 'destroy'])->name('admin.color.destroy');
        });

        //Size
        Route::prefix('size')->group(function () {
            Route::get('/', [SizeController::class, 'index'])->name('admin.sizes.index');
            Route::get('/create', [SizeController::class, 'create'])->name('admin.sizes.create');
            Route::post('/create', [SizeController::class, 'store'])->name('admin.sizes.store');
            Route::get('/{id}/edit', [SizeController::class, 'edit'])->name('admin.sizes.edit');
            Route::put('/{id}', [SizeController::class, 'update'])->name('admin.sizes.update');
            Route::delete('/{id}', [SizeController::class, 'destroy'])->name('admin.sizes.destroy');
        });

        //Booking
        Route::prefix('booking')->group(function () {
            Route::get('/allBooking', [BookingController::class, 'allBooking'])->name('admin.booking.list');
            Route::get('/ongoing', [BookingController::class, 'ongoingBooking'])->name('admin.booking.ongoing');
            Route::get('/past', [BookingController::class, 'pastBooking'])->name('admin.booking.past');
            Route::get('/upcoming', [BookingController::class, 'upcomingBooking'])->name('admin.booking.upcoming');
            Route::get('/orderDetail/{id}', [BookingController::class, 'orderDetail'])->name('admin.booking.detail');
            Route::put('/assignOrder/{id}', [BookingController::class, 'assignOrder'])->name('admin.booking.assign');
        });

    });

    Route::post('/getCityFromState', [StateController::class, 'getCityFromState'])->name('admin.get_city');
    Route::post('/getPincodeFromCity', [StateController::class, 'getPincodeFromCity'])->name('admin.get_pincode');

    //SuperCategory
    // Route::get('/superCategoryList',[SuperCategoryController::class,'superCategoryList'])->name('superCategoryList');
    // Route::get('/addSuperCategoryList',[SuperCategoryController::class,'addSuperCategoryList'])->name('addSuperCategoryList');
    // Route::post('/saveSuperCategoryList',[SuperCategoryController::class,'saveSuperCategoryList'])->name('saveSuperCategoryList');
    // Route::get('/editSuperCategoryList/{id}',[SuperCategoryController::class,'editSuperCategoryList'])->name('editSuperCategoryList');
    // Route::put('/updateSuperCategoryList/{id}',[SuperCategoryController::class,'updateSuperCategoryList'])->name('updateSuperCategoryList');
    // Route::any('/deleteSuperCategoryList/{id}',[SuperCategoryController::class,'deleteSuperCategoryList'])->name('deleteSuperCategoryList');

    //SuperSubCategory
    // Route::get('/superSubCategoryList',[SuperSubCategoryController::class,'superSubCategoryList'])->name('superSubCategoryList');
    // Route::get('/addsuperSubCategory',[SuperSubCategoryController::class,'addsuperSubCategory'])->name('addsuperSubCategory');
    // Route::post('/savesuperSubCategory',[SuperSubCategoryController::class,'savesuperSubCategory'])->name('savesuperSubCategory');
    // Route::get('/editsuperSubCategory/{id}',[SuperSubCategoryController::class,'editsuperSubCategory'])->name('editsuperSubCategory');
    // Route::put('/updatesuperSubCategory/{id}',[SuperSubCategoryController::class,'updatesuperSubCategory'])->name('updatesuperSubCategory');
    // Route::any('/deletesuperSubCategory/{id}',[SuperSubCategoryController::class,'deletesuperSubCategory'])->name('deletesuperSubCategory');

    //Offer
    // Route::get('/offerList',[OfferController::class,'offerList'])->name('offerList');
    // Route::get('/addOffer',[OfferController::class,'addOffer'])->name('addOffer');
    // Route::post('/saveOffer',[OfferController::class,'saveOffer'])->name('saveOffer');
    // Route::get('/editOffer/{id}',[OfferController::class,'editOffer'])->name('editOffer');
    // Route::put('/updateOffer/{id}',[OfferController::class,'updateOffer'])->name('updateOffer');
    // Route::any('/deleteOffer/{id}',[OfferController::class,'deleteOffer'])->name('deleteOffer');

    //CmsManagement
    // Route::get('/cmsList',[CmsController::class,'cmsList'])->name('cmsList');
    // Route::get('/addCms',[CmsController::class,'addCms'])->name('addCms');
    // Route::post('/saveCms',[CmsController::class,'saveCms'])->name('saveCms');
    // Route::get('/editCms/{id}',[CmsController::class,'editCms'])->name('editCms');
    // Route::put('/updateCms/{id}',[CmsController::class,'updateCms'])->name('updateCms');
    // Route::any('/deleteCms/{id}',[CmsController::class,'deleteCms'])->name('deleteCms');

    //customer
    Route::get('/customerList', [CustomerController::class, 'customerList'])->name('customerList');
    Route::get('customerStatusWiseData', [CustomerController::class, 'customerStatusWiseData'])->name('customerStatusWiseData');
    Route::get('changeUserStatus/{id}', [CustomerController::class, 'changeUserStatus'])->name('changeUserStatus');

    // productList
    // Route::get('/productList',[SuperSubCategoryController::class,'productList'])->name('productList');
    // Route::prefix('product')->group(function(){
    //     Route::get('/',[ProductController::class,'productList'])->name('productList');
    //     Route::get('/add',[ProductController::class,'addProduct'])->name('addProduct');
    //     Route::post('/save',[ProductController::class,'saveProduct'])->name('saveProduct');
    //     Route::get('/edit/{id}',[ProductController::class,'editProduct'])->name('editProduct');
    //     Route::put('/update/{id}',[ProductController::class,'updateProduct'])->name('updateProduct');
    //     Route::get('/delete/{id}',[ProductController::class,'deleteProduct'])->name('deleteProduct');
    // });

});
