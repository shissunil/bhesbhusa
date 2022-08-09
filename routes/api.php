<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\HomeApiController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\OrderApiController;
use App\Http\Controllers\api\ReviewAndRatingController;
use App\Http\Controllers\api\DeliveryController;


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

Route::post('/register', [UserController::class, 'register'])->name('api.register');
Route::post('/login', [UserController::class, 'login'])->name('api.login');
Route::post('verifyMobileNo', [UserController::class, 'verifyMobileNo'])->name('api.verifyMobileNo');

// Route::post('verifyOtp', [UserController::class, 'verifyOtp'])->name('verifyOtp');
Route::post('saveDliveryAssociates', [DeliveryController::class, 'saveDliveryAssociates'])->name('saveDliveryAssociates');

//Set all authenticated route in this group
Route::get('user', [UserController::class, 'user'])->name('api.user');
Route::middleware('auth:sanctum')->group(function () {

    Route::post('verifyChangeMobileOtp', [UserController::class, 'verifyChangeMobileOtp'])->name('api.verifyChangeMobileOtp');
    
    Route::get('/category_list', [CategoryController::class, 'category_list'])->name('api.category_list');
    Route::post('/category_details', [CategoryController::class, 'category_details'])->name('api.category_details');  
    Route::post('saveAddress', [UserController::class, 'saveAddress'])->name('saveAddress');
    Route::post('updateAddress', [UserController::class, 'updateAddress'])->name('updateAddress');
    Route::post('removeAddress', [UserController::class, 'removeAddress'])->name('removeAddress');
    Route::post('updateUserProfile', [UserController::class, 'updateUserProfile'])->name('updateUserProfile');
    Route::get('bannerList', [HomeApiController::class, 'bannerList'])->name('bannerList');

    //wishList
    Route::post('saveWish', [HomeApiController::class, 'saveWish'])->name('saveWish');
    Route::post('removeWish', [HomeApiController::class, 'removeWish'])->name('removeWish');
    Route::post('wishList', [HomeApiController::class, 'wishList'])->name('wishList');

    //addToCart
    Route::post('addToCart', [OrderApiController::class, 'addToCart'])->name('addToCart');
    Route::post('cartDetail', [OrderApiController::class, 'cartDetail'])->name('cartDetail');
    Route::post('removeCart', [OrderApiController::class, 'removeCart'])->name('removeCart');
    Route::post('proceedToCheckout',[OrderApiController::class,'proceedToCheckout'])->name('proceedToCheckout');
    Route::post('checkoutContinue',[OrderApiController::class,'checkoutContinue'])->name('checkoutContinue');
    Route::post('addressList', [UserController::class, 'addressList'])->name('addressList');
    Route::post('checkout', [OrderApiController::class, 'checkout'])->name('checkout');
    Route::post('saveOnlineOrder', [OrderApiController::class, 'saveOnlineOrder'])->name('saveOnlineOrder');
    Route::post('onlineCheckout', [OrderApiController::class, 'onlineCheckout'])->name('onlineCheckout');
    Route::post('saveOnlineOrder', [OrderApiController::class, 'saveOnlineOrder'])->name('saveOnlineOrder');
    
    // Route::post('onlinePay',function(){
    //     $userData = auth('sanctum')->user();
    //     $userId = $userData->id;
    //     return view('khalti.onlinePayment');
    // });

    Route::post('orderList',[OrderApiController::class,'orderList'])->name('orderList');
    Route::post('orderDetail',[OrderApiController::class,'orderDetail'])->name('orderDetail');
    Route::post('cancelOrder',[OrderApiController::class,'cancelOrder'])->name('cancelOrder');
    Route::post('returnOrder',[OrderApiController::class,'returnOrder'])->name('returnOrder');
    

    //RwviewAndRating
    Route::post('saveReviewAndRating',[ReviewAndRatingController::class,'saveReviewAndRating'])->name('saveReviewAndRating');

    //Delivery
    Route::post('deliveryHomeData',[DeliveryController::class,'deliveryHomeData'])->name('deliveryHomeData');
    Route::post('updateDeliveryProfilePhoto',[DeliveryController::class,'updateDeliveryProfilePhoto'])->name('updateDeliveryProfilePhoto');
    Route::post('profileDetail',[DeliveryController::class,'profileDetail'])->name('profileDetail');
    Route::post('bookingList',[DeliveryController::class,'bookingList'])->name('bookingList');
    Route::post('deliveryOrderDetail',[DeliveryController::class,'deliveryOrderDetail'])->name('deliveryOrderDetail');
    Route::post('changeOrderStatus',[DeliveryController::class,'changeOrderStatus'])->name('changeOrderStatus');


    Route::post('changeMobileNo',[UserController::class,'changeMobileNo'])->name('changeMobileNo');

    //change order address
    Route::post('changeAddress',[OrderApiController::class,'changeAddress'])->name('changeAddress');
    Route::post('ticketReasonList', [HomeApiController::class, 'ticketReasonList'])->name('ticketReasonList');

    Route::post('deliverLogout',[DeliveryController::class, 'deliverLogout'])->name('deliverLogout');

    //userNotificationList
    Route::post('userNotificationList',[HomeApiController::class, 'userNotificationList'])->name('userNotificationList');
    Route::post('deleteNotification',[HomeApiController::class, 'deleteNotification'])->name('deleteNotification');
    Route::post('clearAllNotification',[HomeApiController::class, 'clearAllNotification'])->name('clearAllNotification');
    
    Route::post('deliveryNotificationList',[HomeApiController::class, 'deliveryNotificationList'])->name('deliveryNotificationList');
    Route::post('deleteDeliveryNotification',[HomeApiController::class, 'deleteDeliveryNotification'])->name('deleteDeliveryNotification');
    Route::post('clearAllDelivereyNotification',[HomeApiController::class, 'clearAllDelivereyNotification'])->name('clearAllDelivereyNotification');
    Route::post('couponList',[HomeApiController::class, 'couponList'])->name('couponList');
    Route::post('applyCoupon',[HomeApiController::class, 'applyCoupon'])->name('applyCoupon');
    Route::post('removeCoupon',[HomeApiController::class, 'removeCoupon'])->name('removeCoupon');

    Route::get('updateNotificationSetting',[HomeApiController::class, 'updateNotificationSetting'])->name('updateNotificationSetting');
});

// Route::get('onlinePay',function(){
//         // $userData = auth('sanctum')->user();
//         // $userId = $userData->id;
//         return view('khalti.onlinePayment');
//     });
Route::post('productDetail', [ProductController::class,'productDetail'])->name('productDetail');

Route::post('subCategoryWiseProductList', [ProductController::class, 'subCategoryWiseProductList'])->name('subCategoryWiseProductList');
Route::post('searchFilterProductList', [ProductController::class, 'searchFilterProductList'])->name('searchFilterProductList');
Route::post('searchProduct', [ProductController::class, 'searchProduct'])->name('searchProduct');

Route::post('resendOtp', [UserController::class, 'resendOtp'])->name('resendOtp');
Route::post('superCategoryList', [CategoryController::class, 'superCategoryList'])->name('superCategoryList');  
Route::post('superSubCategory', [CategoryController::class, 'superSubCategory'])->name('superSubCategory');  
Route::post('cmsList', [HomeApiController::class, 'cmsList'])->name('cmsList');

Route::post('chekPincode', [OrderApiController::class, 'chekPincode'])->name('chekPincode');

//Home
Route::get('homeData',[HomeApiController::class, 'homeData'])->name('homeData');
Route::get('colorList',[HomeApiController::class, 'colorList'])->name('colorList');
Route::get('brandList',[HomeApiController::class, 'brandList'])->name('brandList');
Route::get('subCategoryList',[HomeApiController::class, 'subCategoryList'])->name('subCategoryList');
Route::get('sizeList',[HomeApiController::class, 'sizeList'])->name('sizeList');
Route::any('filterList',[HomeApiController::class, 'filterList'])->name('filterList');


//Deliveryapp
Route::post('deliverLogin',[DeliveryController::class, 'deliverLogin'])->name('deliverLogin');
// Route::post('deliverLogin_',[DeliveryController::class, 'deliverLogin_'])->name('deliverLogin_');

//productReview
Route::post('productReview',[ReviewAndRatingController::class, 'productReview'])->name('productReview');

//test sms
Route::get('testSms',[HomeApiController::class, 'testSms'])->name('testSms');
Route::post('testPostSms',[HomeApiController::class, 'testPostSms'])->name('testPostSms');

Route::post('testNotfication',[HomeApiController::class, 'testNotfication'])->name('testNotfication');

