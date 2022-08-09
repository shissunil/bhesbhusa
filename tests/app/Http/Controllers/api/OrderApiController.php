<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiBaseController as ApiBaseController;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnOrders;
use App\Models\Color;
use App\Models\Size;
use App\Models\Pincode;
use App\Models\City;
use App\Models\TicketReason;
use App\Models\WishList;
use App\Models\Brand;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\ReviewAndRating;
use App\Models\Offer;
use URL;
use stdClass;
use DB;
use PDF;
// use Storage;
class OrderApiController extends ApiBaseController
{
    public function addToCart(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            // $userId = $request->user_id;
            $productId = $request->product_id;
            $colorId =$request->color;
            $sizeId =$request->size;
            $qtyData =(int)$request->qty;
            $pincode = $request->pincode;
            if ($pincode)
            {
                $checkPincode = Pincode::where('deleted_at','')->where('pincode',$pincode)->where('status','1')->first();
                if ($checkPincode)
                {
                    $sizeData =Size::where('deleted_at','')->find($sizeId);
                    if ($sizeData) 
                    {
                        $colorData =Color::where('deleted_at','')->find($colorId);
                        if ($colorData)
                        {
                            $productData = Product::where('deleted_at','')->find($productId);
                            if ($productData)
                            {
                                $productPrice = (int)$productData->price;
                                $discount = 0;
                                $discountPrice = '';    
                                if ($productData->discount != '')
                                {
                                    $discount = (int)$productData->discount;
                                    $discount = ((int)$productPrice * $discount / 100) * $qtyData;
                                }
                                $shippingCharge = 0;
                                $cityData = City::where('deleted_at','')->where('status','1')->find($checkPincode->city_id);
                                if ($cityData)
                                {
                                    if ($cityData->is_free != '1')
                                    {
                                        $shippingCharge = $cityData->shipping_charge;
                                    }
                                }
                                $checkCart = Cart::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productId)->where('color',$colorId)->where('size',$sizeId)->first();
                                if ($checkCart)
                                {
                                    $qty = (int)$checkCart->qty + $qtyData;
                                    $total = $productPrice * $qty;
                                    $discountPrice = (int)$checkCart->discount_price + $discount;
                                    $total = $total - $discountPrice;
                                    // $total = $total + $shippingCharge;
                                    $totalPrice = (int)$checkCart->total_price;

                                    $checkCart->qty = $qty;
                                    $checkCart->shipping_charge = $shippingCharge;
                                    $checkCart->discount_price = $discountPrice;
                                    $checkCart->total_price = $total;
                                    $checkCart->color = $request->color;
                                    $checkCart->size = $request->size;
                                    $checkCart->brand = $productData->brand_id;
                                    $checkCart->pincode = $request->pincode;
                                    // $checkCart->coupon_id = $request->coupon_id;
                                    $checkCart->save();
                                    $checkCart->product_price = (int)$checkCart->product_price;
                                    $data = $checkCart;
                                }
                                else
                                {
                                    $total = ($productPrice * $qtyData) - $discount; 
                                    // $total = $total + $shippingCharge; 
                                    $cart = new Cart;
                                    $cart->user_id = $userId;
                                    $cart->product_id = $request->product_id;
                                    // $cart->coupon_id = $request->coupon_id;
                                    $cart->pincode = $request->pincode;
                                    $cart->qty = $qtyData;
                                    $cart->color = $request->color;
                                    $cart->size = $request->size;
                                    $cart->brand = $productData->brand_id;
                                    $cart->product_price = $productPrice;
                                    $cart->discount_price = $discount;
                                    $cart->shipping_charge = $shippingCharge;
                                    $cart->total_price = $total;
                                    $cart->deleted_at = '';
                                    $cart->save();
                                    $data = $cart;
                                }
                                return $this->sendResponse(200, $data,'Product added successfully');
                            }
                            else
                            {
                                $productData = new stdClass;
                                return $this->sendResponse(201, $productData, 'product not found');
                            }
                        }
                        else
                        {
                            $colorData = new stdClass;
                            return $this->sendResponse(201, $colorData, 'Product Not Available for this color'); 
                        }
                    }
                    else
                    {
                        $sizeData = new stdClass;
                        return $this->sendResponse(201, $sizeData, 'Product Not Available for this Size'); 
                    }  
                }
                else
                {
                    $checkPincode = new stdClass;
                    return $this->sendResponse(201,$checkPincode,'Product not available for this pincode');
                }
            }
            else
            {
                $pincode = new stdClass;
                return $this->sendResponse(201,$pincode,'Please Enter Pincode');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function cartDetail(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
            if (!empty($cartDetail->toArray()))
            {
                $cartTotal = 0;
                $productDiscount = 0;
                $shippingCharge = 0;
                $payableAmount = 0;
                foreach($cartDetail as $cart)
                {
                    $couponId = $cart->coupon_id;
                    $colorData = Color::find($cart->color);
                    $cart->color_id = $colorData->id;
                    $cart->color = $colorData->color;
                    $cart->colorData = $colorData;

                    $sizeData = Size::find($cart->size);
                    $cart->size_id = $sizeData->id;
                    $cart->size = $sizeData->size;
                    $cart->sizeData = $sizeData;
                    $productData = Product::where('deleted_at','')->find($cart->product_id);
                    $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';

                    $productData->discount_price = '';
                    $price = $productData->price;
                    
                    $productData->favorite = '0';
                    // dd($userId);
                    $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productData->id)->first();
                    if ($checkFav)
                    {
                        $productData->favorite = '1';
                    }
                    $brand = Brand::find($productData->brand_id);
                    if ($brand)
                    {
                        $productData->brand_name = $brand->name;
                    }
                    
                    $productData->price = $productData->price . ' NR';
                    if ($productData->discount != '')
                    {
                        $discount = $productData->discount;
                        $productData->discount = $discount . ' % off';
                        $productData->discount_price = $price * $discount / 100;
                        $productData->discount_price = round($productData->discount_price);
                        $productData->discount_price = (string)($price - $productData->discount_price);
                        $productData->discount_price = $productData->discount_price . ' NR';
                    }
                    $pinCodeData = Pincode::where('pincode',$cart->pincode)->first();
                    $cityData = City::find($pinCodeData->city_id);
                    $date = date('Y-m-d');
                    $deliveryDays = date('dS M', strtotime($date. ' + '.$cityData->delivery_days.' days')); 
                    $cart->delivery_days = $deliveryDays;
                    // unset($productData->quantity);

                    $colorIds = $productData->color_id;
                    $colorIds = explode(',',$colorIds);
                    $productData->colorData = [];
                    if ($colorIds)
                    {
                        $colorData = Color::where('deleted_at','')->whereIn('id',$colorIds)->get();
                        $productData->colorData = $colorData;
                    }
                    $sizeIds = $productData->size_id;
                    $sizeIds = explode(',',$sizeIds);
                    $productData->sizeData = [];
                    if ($sizeIds)
                    {
                        $sizeData = Size::where('deleted_at','')->whereIn('id',$sizeIds)->get();
                        $productData->sizeData = $sizeData;
                    }

                    $cart->productData = $productData;
                    // $qty = (int)$cart->qty;
                    // $productPrice = (int)$cart->product_price;
                    // $cartTotal += $qty * $productPrice;
                    // $cartTotal += $qty * $productPrice;
                    // $productDiscount += $cart->discount_price;
                    // $payableAmount += $cartTotal - $productDiscount;
                    $couponDiscount = (int)$cart->coupon_discount;
                    $shippingCharge = (int)$cart->shipping_charge;
                    $productDiscount += $cart->discount_price;
                    $payableAmount += $cart->total_price;
                    $cartTotal += $cart->total_price + $cart->discount_price;
                }
                $couponData = new stdClass;
                $discountPrice = '0';
                if ($couponId != '0')
                {
                    $couponData = Offer::find($couponId);
                    $discount = $couponData->offer_discount;
                    $discountPrice = $payableAmount * $discount / 100;
                    $couponData->saved_amount = '-'.$discountPrice . ' NR';

                    unset($couponData->created_at);
                    unset($couponData->updated_at);
                    unset($couponData->deleted_at);
                }
                $payableAmount = $payableAmount - $couponDiscount;
                $payableAmount = $payableAmount + $shippingCharge;
                // $cartTotal = $cartTotal + $shippingCharge;
                if ($shippingCharge == 0)
                {
                    $shippingCharge = 'Free';
                }
                else
                {
                    $shippingCharge = $shippingCharge. ' NR';
                }
                if ($discountPrice != '0')
                {
                    $discountPrice = '-'.$discountPrice. ' NR';
                }
                else
                {
                    $discountPrice = $discountPrice. ' NR';
                }
                $summaryArray = array(
                    'cart_total' => $cartTotal. ' NR',
                    'product_discount' => '-'.$productDiscount. ' NR',
                    'coupon_discount' => $discountPrice,
                    'shipping_charge' => $shippingCharge,
                    'payable_amount' => $payableAmount. ' NR',
                );
                $data['cartDetail'] = $cartDetail;
                $data['coupon_data'] = $couponData;
                $data['cart_summary'] = $summaryArray;
                // $cartDetail->summary = ['cartTotal'=>'123'];
                // $cartDetail['test']= '124';
                return $this->sendResponse(200, $data,'Cart Detail');
            }
            else
            {
                // $data['cartDetail'] = [];
                $cartDetail = new stdClass;
                return $this->sendResponse(201,$cartDetail,'cart is empty');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }
    public function removeCart(Request $request)
    {
        try
        {
            $cartId = $request->cart_id;
            $userData = $request->user();
            $userId = $userData->id;
            if (isset($cartId))
            {
                $cart = Cart::where('deleted_at','')->where('user_id',$userId)->find($cartId);
                if ($cart)
                {
                    $cart->deleted_at = config('constant.CURRENT_DATETIME');
                    $cart->save();
                    // $data['cartDetail'] = $cart;
                    $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
                    if (!empty($cartDetail->toArray()))
                    {
                        $couponId = '0';
                        $cartTotal = 0;
                        $subCategoryId = '';
                        foreach($cartDetail as $cart)
                        {
                            $couponId = $cart->coupon_id;
                            $cartTotal += $cart->total_price;
                            $productId = $cart->product_id;
                            $productData = Product::where('deleted_at','')->select('sub_category_id')->find($productId);
                            if ($productData)
                            {
                                $subCategoryId = $subCategoryId != '' ? $subCategoryId . ','. $productData->sub_category_id. ',' : $productData->sub_category_id;
                            }
                        }
                        if ($couponId != '0')
                        {
                            $checkCoupon = Offer::where('deleted_at','')->find($couponId);
                            if ($checkCoupon)
                            {
                                $validAmountForCoupon = $checkCoupon->total_amount;
                                $discount = $checkCoupon->offer_discount;
                                $isGlobal = $checkCoupon->is_global;
                                if ($isGlobal == '1')
                                {
                                    if ($validAmountForCoupon <= $cartTotal)
                                    {
                                        $discountPrice = $cartTotal * $discount / 100;
                                        foreach($cartDetail as $value)
                                        {
                                            $value->coupon_id = $checkCoupon->id;
                                            $value->coupon_discount = $discountPrice;
                                            $value->save();
                                        }
                                    }
                                    else
                                    {
                                        $discountPrice = 0;
                                        foreach($cartDetail as $value)
                                        {
                                            $value->coupon_id = '0';
                                            $value->coupon_discount = $discountPrice;
                                            $value->save();
                                        }
                                    }
                                }
                                else
                                {
                                    $subCategoryId = explode(',',$subCategoryId);
                                    $checkCoupon = Offer::where('deleted_at','')->where('id',$couponId);
                                    foreach($subCategoryId as $value)
                                    {

                                        $checkCoupon->whereRaw("find_in_set('".$value."',sub_category_id)");
                                    }
                                    $checkCoupon = $checkCoupon->first();
                                    if ($checkCoupon)
                                    {
                                        $discountPrice = $cartTotal * $discount / 100;
                                        foreach($cartDetail as $value)
                                        {
                                            $value->coupon_id = $couponId;
                                            $value->coupon_discount = $discountPrice;
                                            $value->save();
                                        }
                                    }
                                    else
                                    {
                                        $discountPrice = 0;
                                        foreach($cartDetail as $value)
                                        {
                                            $value->coupon_id = '0';
                                            $value->coupon_discount = $discountPrice;
                                            $value->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return $this->sendResponse(200, $cart,'Cart Item Removed');
                }
                else
                {
                    $cart = new stdClass;
                    return $this->sendResponse(201,$cart,'record not found');
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Please Enter cart id');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }
    public function chekPincode(Request $request)
    {
        try
        {
            $pincode = $request->pincode;
            if (isset($pincode))
            {
                $pincode = Pincode::where('deleted_at','')->where('pincode',$pincode)->where('status','1')->first();
                if ($pincode)
                {
                    $cityData = City::where('deleted_at','')->where('status','1')->find($pincode->city_id);
                    if ($cityData)
                    {
                        $date = date('Y-m-d'); 
                        $deliveryDays = date('l d M', strtotime($date. ' + '.$cityData->delivery_days.' days')); 
                        $pincode->delivery_days = $deliveryDays;
                        return $this->sendResponse(200,$pincode,'Product Available for this pincode');
                    }
                    else
                    {
                        $pincode = new stdClass;
                        return $this->sendResponse(201,$pincode,'Product not available for this pincode');
                    }
                }
                else
                {
                    $pincode = new stdClass;
                    return $this->sendResponse(201,$pincode,'Product not available for this pincode');
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Please Enter Pincode');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage()); 
        }
    }
    public function onlineCheckout(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $token = $request->bearerToken(); 
            $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
            if (!empty($cartDetail->toArray()))
            {
                $paymentUrl = array(
                    'main_url' => URL::to('/onlinePay?Utoken='.$token),
                    'success_url' => route('payment_success'),
                    'failed_url' => route('payment_failure')
                );
                return $this->sendResponse(200, $paymentUrl,'payment url');
            }
            else
            {
                $paymentUrl = new stdClass;    
                return $this->sendResponse(201, $paymentUrl,'cart is empty');
            }
            // dd($request->bearerToken());
            // $token = $chekUser->createToken('apiToken')->plainTextToken;
            // $chekUser->token = $token;
            // dd(URL::to('/home'));
        }
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage()); 
        }
    }
    public function onlinePay(Request $request)
    {
        try
        {
            // $deviceType = $request->device_type;
            // $deviceType = $_GET['device_type'];
            // dd($deviceType);
            // if ($deviceType != '')
            // {
            // }
            // else
            // {
            // }
            $user_id = '';
            $token = $_GET['Utoken'];

            if (isset($token)) {
                [$id, $user_token] = explode('|', $token, 2);
                $token_data = DB::table('personal_access_tokens')->where('token', hash('sha256', $user_token))->first();
                $user_id = $token_data->tokenable_id; // !!!THIS ID WE CAN USE TO GET DATA OF YOUR USER!!!
            }
            // dd($user_id);
            // $userData = auth('sanctum')->user();
            $userData = User::where('deleted_at','')->find($user_id);
            if ($userData != null)
            {

                $userId = $userData->id;
                $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
                $productName = '';
                $payableAmount = '';                
                
                if (!empty($cartDetail->toArray()))
                {
                    $cartTotal = 0;
                    $productDiscount = 0;
                    $shippingCharge = 0;
                    $payableAmount = 0;
                    $couponDiscount = 0;
                    foreach($cartDetail as $cart)
                    {
                        $userAddressId = $cart->user_address_id;
                        $couponId = $cart->coupon_id;
                        $couponDiscount = (int)$cart->coupon_discount;
                        $shippingCharge = (int)$cart->shipping_charge;
                        $productDiscount += $cart->discount_price;
                        $payableAmount += $cart->total_price;
                        $cartTotal += $cart->total_price + $cart->discount_price + $shippingCharge;
                        $productData = Product::where('deleted_at','')->select('product_name')->find($cart->product_id);
                        $productName = ($productName != '') ? ','.$productData->product_name : $productData->product_name;
                    }
                    // if ($couponId != '0')
                    // {
                    //     // product discount plus coupon discount
                    //     // payable amount minus coupon discount
                    // }
                    $payableAmount = $payableAmount - $couponDiscount;
                    $payableAmount = $payableAmount + $shippingCharge;
                    $data = array(
                        'productId' => time(),
                        'productName' => $productName,
                        'productUrl' => URL::to('/'),
                        'payableAmount'=>$payableAmount,
                    );
                    // dd($payableAmount);
                    return view('khalti.onlinePayment',compact('data'));
                }
                else
                {
                    return redirect()->route('payment_failure');
                    // $cartDetail = new stdClass;
                    // return $this->sendResponse(201,$cartDetail,'cart detail not found');
                }
            }
            else
            {
                return response()->json([
                    'status' => 201,
                    'data' => new  \stdClass,
                    'message' => 'Not authenticated'
                ], 401);
            }
            
        }
        catch (\Exception $e) 
        {
            return $this->sendError(201,$e->getMessage()); 
        }
    }
    public function saveOnlineOrder(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
            if (!empty($cartDetail->toArray()))
            {
                $cartTotal = 0;
                $productDiscount = 0;
                $shippingCharge = 0;
                $payableAmount = 0;
                $couponDiscount = 0;
                foreach($cartDetail as $cart)
                {
                    $userAddressId = $cart->user_address_id;
                    $couponId = $cart->coupon_id;
                    $couponDiscount = (int)$cart->coupon_discount;
                    $shippingCharge = (int)$cart->shipping_charge;
                    $productDiscount += $cart->discount_price;
                    $payableAmount += $cart->total_price;
                    $cartTotal += $cart->total_price + $cart->discount_price;
                }
                if ($couponId != '0')
                {
                    // product discount plus coupon discount
                    // payable amount minus coupon discount
                }
                $productDiscount = $productDiscount + $couponDiscount;
                $payableAmount = $payableAmount - $couponDiscount;
                $payableAmount = $payableAmount + $shippingCharge;
                if ($userAddressId != '0')
                {
                    $deliveryDays = '0';
                    $addressData = UserAddress::find($userAddressId);
                    if ($addressData)
                    {
                        $pincode = Pincode::where('pincode',$addressData->pincode)->first();
                        if ($pincode)
                        {
                            $cityData = City::find($pincode->city_id);
                            if ($cityData)
                            {
                                $date = date('Y-m-d'); 
                                $deliveryDays = $cityData->delivery_days;
                            }
                        }
                    }

                    $masterData = Setting::where('deleted_at','')->select('order_id')->orderBy('id','desc')->first();
                    $OID = $masterData->order_id + 1;
                    $orderNo = '#'.date('dmY').time().$OID;

                    $order = new Order;
                    $order->order_no = $orderNo;
                    $order->user_id = $userId;
                    $order->user_address_id = $userAddressId;
                    $order->estimate_delivery_days = $deliveryDays;
                    $order->coupon_id = $couponId;
                    $order->payment_type = '2';
                    $order->coupon_discount = $couponDiscount;
                    $order->total_discount = $couponDiscount;
                    $order->total_discount = $productDiscount;
                    $order->shipping_charge = $shippingCharge;
                    $order->total_amount = $payableAmount;
                    $order->cancel_description = '';
                    $order->invoice = '';
                    $order->deleted_at = '';
                    $order->save();
                    $totalDiscount = $productDiscount + $couponDiscount . ' NR';

                    $productDataArray = array();
                    foreach($cartDetail as $cart)
                    {
                        $orderItem = new OrderItem;
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $cart->product_id;
                        $orderItem->color_id = $cart->color;
                        $orderItem->size_id = $cart->size;
                        $orderItem->brand_id = $cart->brand;
                        $orderItem->quantity = $cart->qty;
                        $orderItem->product_price = $cart->product_price;
                        $orderItem->total_price = $cart->total_price;
                        $orderItem->product_discount = $cart->discount_price;
                        $orderItem->total_amount = $cart->total_price;
                        $orderItem->order_status = '1';
                        $orderItem->deleted_at = '';
                        $orderItem->save();

                        $productData = Product::where('deleted_at','')->select('product_image')->find($cart->product_id);
                        $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                        $productDataArray [] = $productData;


                        $updateProductQty = Product::where('deleted_at', '')->find($cart->product_id);
                        if ($updateProductQty)
                        {
                            $oldQuantity = (int)$updateProductQty->quantity;
                            $oldUsedQty = (int)$updateProductQty->used_quantity;
                            $newUsedQuantity = $oldUsedQty + (int)$cart->qty;
                            $updateProductQty->used_quantity = $newUsedQuantity;
                            $updateProductQty->save();

                            if ($oldQuantity == $newUsedQuantity)
                            {
                                $updateProductQty->product_status = '0';
                                $updateProductQty->save();
                            }
                        }
                        // $updateProductQty = 
                    }
                    $data['total_saved'] = $totalDiscount;
                    $data['productImage'] = $productDataArray;
                    $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
                    if ($masterData)
                    {
                        $masterData->order_id = $order->id;
                        $masterData->save();
                    }
                    $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->update(['deleted_at'=>config('constant.CURRENT_DATETIME')]);

                    $message = "Your order has been successfully placed checkout more details here";
                    $notification = new Notification;
                    $notification->receiver_id = $userId;
                    $notification->receiver_type = 'user';
                    $notification->sender_id = '';
                    $notification->sender_type = '';
                    $notification->category_type = 'Order';
                    $notification->notification_type = 'Order';
                    $notification->type_id = $order->id;
                    $notification->order_status = '1';
                    $notification->message = $message;
                    $notification->deleted_at = '';
                    $notification->save();
                    $userData = User::where('notification','1')->find($userId);
                    if ($userData)
                    {
                        $token = $userData->device_token;
                        sendCustomerNotification($message, $token, 'Order','1', 'Order',$order->id);
                    }
                    genratePdf($userId,$order->id);
                    $message = "You have new Order!";
                    $notification = new Notification;
                    $notification->receiver_id = '';
                    $notification->receiver_type = 'admin';
                    $notification->sender_id = $userId;
                    $notification->sender_type = '';
                    $notification->category_type = 'Order';
                    $notification->notification_type = 'Order';
                    $notification->type_id = $order->id;
                    $notification->order_status = '1';
                    $notification->message = $message;
                    $notification->deleted_at = '';
                    $notification->save();
                    return $this->sendResponse(200,$data,'order placed successfully');
                }
                else
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data, 'Please Enter Address');
                }
            }
            else
            {
                $cartDetail = new stdClass;
                return $this->sendResponse(201,$cartDetail,'cart data not found');
            }
        }
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage()); 
        }
    }
    public function checkout(Request $request)
    {
        try 
        {
            $userData = $request->user();
            $userId = $userData->id;
            // $paymentType = $request->payment_type;
            // if (isset($paymentType))
            // {
                
            $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
            if (!empty($cartDetail->toArray()))
            {
                $cartTotal = 0;
                $productDiscount = 0;
                $shippingCharge = 0;
                $payableAmount = 0;
                $couponDiscount = 0;
                foreach($cartDetail as $cart)
                {
                    $userAddressId = $cart->user_address_id;
                    $couponId = $cart->coupon_id;
                    $shippingCharge = (int)$cart->shipping_charge;
                    $couponDiscount = (int)$cart->coupon_discount;
                    $productDiscount += $cart->discount_price;
                    $payableAmount += $cart->total_price;
                    $cartTotal += $cart->total_price + $cart->discount_price;
                }
                if ($couponId != '0')
                {
                    // product discount plus coupon discount
                    // payable amount minus coupon discount
                }
                $payableAmount = $payableAmount - $couponDiscount;
                $payableAmount = $payableAmount + $shippingCharge;
                if ($userAddressId != '0')
                {
                    $deliveryDays = '0';
                    $addressData = UserAddress::find($userAddressId);
                    if ($addressData)
                    {
                        $pincode = Pincode::where('pincode',$addressData->pincode)->first();
                        if ($pincode)
                        {
                            $cityData = City::find($pincode->city_id);
                            if ($cityData)
                            {
                                $date = date('Y-m-d'); 
                                $deliveryDays = $cityData->delivery_days;
                            }
                        }
                    }
                    $masterData = Setting::where('deleted_at','')->select('order_id')->orderBy('id','desc')->first();
                    $OID = $masterData->order_id + 1;
                    $orderNo = '#'.date('dmY').time().$OID;
                    $order = new Order;
                    $order->order_no = $orderNo;
                    $order->user_id = $userId;
                    $order->user_address_id = $userAddressId;
                    $order->estimate_delivery_days = $deliveryDays;
                    $order->coupon_id = $couponId;
                    $order->payment_type = '1';
                    $order->coupon_discount = $couponDiscount;
                    $order->total_discount = $productDiscount;
                    $order->shipping_charge = $shippingCharge;
                    $order->total_amount = $payableAmount;
                    $order->cancel_description = '';
                    $order->invoice = '';
                    $order->deleted_at = '';
                    $order->save();
                    $totalDiscount = $productDiscount + $couponDiscount . ' NR';

                    $productDataArray = array();
                    foreach($cartDetail as $cart)
                    {
                        $orderItem = new OrderItem;
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $cart->product_id;
                        $orderItem->color_id = $cart->color;
                        $orderItem->size_id = $cart->size;
                        $orderItem->brand_id = $cart->brand;
                        $orderItem->quantity = $cart->qty;
                        $orderItem->product_price = $cart->product_price;
                        $orderItem->total_price = $cart->total_price;
                        $orderItem->product_discount = $cart->discount_price;
                        $orderItem->total_amount = $cart->total_price;
                        $orderItem->order_status = '1';
                        $orderItem->deleted_at = '';
                        $orderItem->save();

                        $productData = Product::where('deleted_at','')->select('product_image')->find($cart->product_id);
                        $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                        $productDataArray [] = $productData;

                        $updateProductQty = Product::where('deleted_at', '')->find($cart->product_id);
                        if ($updateProductQty)
                        {
                            $oldQuantity = (int)$updateProductQty->quantity;
                            $oldUsedQty = (int)$updateProductQty->used_quantity;
                            $newUsedQuantity = $oldUsedQty + (int)$cart->qty;
                            $updateProductQty->used_quantity = $newUsedQuantity;
                            $updateProductQty->save();

                            if ($oldQuantity == $newUsedQuantity)
                            {
                                $updateProductQty->product_status = '0';
                                $updateProductQty->save();
                            }
                        }

                    }
                    $data['total_saved'] = $totalDiscount;
                    $data['productImage'] = $productDataArray;
                    $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
                    if ($masterData)
                    {
                        $masterData->order_id = $order->id;
                        $masterData->save();
                    }
                    $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->update(['deleted_at'=>config('constant.CURRENT_DATETIME')]);
                    
                    $message = "Your order has been successfully placed checkout more details here";
                    $notification = new Notification;
                    $notification->receiver_id = $userId;
                    $notification->receiver_type = 'user';
                    $notification->sender_id = '';
                    $notification->sender_type = '';
                    $notification->category_type = 'Order';
                    $notification->notification_type = 'Order';
                    $notification->type_id = $order->id;
                    $notification->order_status = '1';
                    $notification->message = $message;
                    $notification->deleted_at = '';
                    $notification->save();
                    $userData = User::where('notification','1')->find($userId);
                    if ($userData)
                    {
                        $token = $userData->device_token;
                        sendCustomerNotification($message, $token, 'Order','1', 'Order',$order->id);
                    }

                    // $pdf = PDF::loadView('invoice.invoice', $data);
                    genratePdf($userId,$order->id);
                    // genratePdf($userId,$order->id);
                    $message = "You have new Order!";
                    $notification = new Notification;
                    $notification->receiver_id = '';
                    $notification->receiver_type = 'admin';
                    $notification->sender_id = $userId;
                    $notification->sender_type = '';
                    $notification->category_type = 'Order';
                    $notification->notification_type = 'Order';
                    $notification->type_id = $order->id;
                    $notification->order_status = '1';
                    $notification->message = $message;
                    $notification->deleted_at = '';
                    $notification->save();
                    return $this->sendResponse(200,$data,'order placed successfully');
                }
                else
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data, 'Please Enter Address');
                }
            }
            else
            {
                $cartDetail = new stdClass;
                return $this->sendResponse(201,$cartDetail,'cart data not found');
            }
            // }
            // else
            // {
            //     $data = new stdClass;
            //     return $this->sendResponse(201,$data, 'fill all the required field');
            // }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage()); 
        }
    }
    //download invoice
    public function invoice(Request $request,$filename)
    {
        $file_path = storage_path() . "/app/invoice/" . $filename;
        
        // dd($file_path);
        // dd($file_path);
        $headers = array(
            'Content-Type: pdf',
            'Content-Disposition: attachment; filename='.$filename,
        );
        if ( file_exists( $file_path ) ) {
            // Send Download
            return \Response::download( $file_path, $filename, $headers );
        } 
        else
        {
            // Error
            $data = new stdClass;
            return $this->sendResponse(201,$data,'Requested file does not exist on our server!');
            // exit( 'Requested file does not exist on our server!' );
        }
    }
    // public function invoice($id)
    // {
    //     $userId = $id;
    //     $orderId = 21;

    // //     // genratePdf($userId,$orderId);
    //     $orderDetail = Order::where('deleted_at','')->where('user_id',$userId)->find($orderId);
    //     if ($orderDetail)
    //     {
    //         $customerName = '';
    //         $userData = User::find($orderDetail->user_id);
    //         if ($userData)
    //         {
    //             $customerName = $userData->first_name. ' '.$userData->last_name;
    //         }
    //         $date = date('d M Y',strtotime($orderDetail->created_at));
    //         $time = date('H i ',strtotime($orderDetail->created_at));
    //         $orderDetail->order_date = $date;
    //         $orderDetail->order_time = $time;
    //         $estimateDate = date('d M Y', strtotime($date. ' + '.$orderDetail->estimate_delivery_days.' days'));
    //         $orderDetail->estimate_date = $estimateDate;
    //         $total = $orderDetail->total_amount;
    //         $cashOnDelivery = $orderDetail->shipping_charge;
    //         $totalDiscount = $orderDetail->total_discount;
    //         $MRP = $total + $cashOnDelivery +$totalDiscount;
    //         $orderDetail->mrp = $MRP . ' NR';
    //         $orderDetail->item_discount = $totalDiscount . ' NR';
    //         $orderDetail->cash_on_delivery = $cashOnDelivery . ' NR';
    //         $orderDetail->total = $total . ' NR';
    //         $orderDetail->invoice = '';
    //         $paymentType = $orderDetail->payment_type;

    //         $orderDetail->is_cancel = '1';
    //         if ($orderDetail->order_status == '1' || $orderDetail->order_status == '2')
    //         {
    //             $orderDetail->is_cancel = '0';
    //         }
    //         $orderDetail->is_return = '1';
    //         if ($orderDetail->order_status == '4')
    //         {
    //             $orderDeliveredDate = date('Y-m-d', strtotime($orderDetail->delivered_date));
    //             $currentDate = date('Y-m-d');
    //             $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
    //             if ($masterData)
    //             {
    //                 $returnDay = $masterData->return_day;
    //                 $returnDate = date('Y-m-d', strtotime($orderDeliveredDate. ' + '.$returnDay.' days'));

    //                 if ($currentDate < $returnDate)
    //                 {
    //                     $orderDetail->is_return = '0';
    //                 }
    //             }

    //         }
    //         if ($paymentType == '1')
    //         {
    //             $orderDetail->payment_type = 'COD';
    //         }
    //         if ($paymentType == '2')
    //         {
    //             $orderDetail->payment_type = 'Online';
    //         }

    //         $orderStatus = $orderDetail->order_status;
    //         if ($orderStatus == '1')
    //         {
    //             $orderDetail->order_status = 'Pending';
    //         }
    //         elseif($orderStatus == '2')
    //         {
    //             $orderDetail->order_status = 'Confirmed';
    //         }
    //         elseif($orderStatus == '3')
    //         {
    //             $orderDetail->order_status = 'Canceled';
    //         }
    //         elseif($orderStatus == '4')
    //         {
    //             $orderDetail->order_status = 'Delivered';
    //         }
    //         elseif($orderStatus == '5')
    //         {
    //             $orderDetail->order_status = 'Returned';
    //         }
    //         elseif($orderStatus == '6')
    //         {
    //             $orderDetail->order_status = 'Out For Service';
    //         }
    //         $refundStatus = $orderDetail->refund_status;
    //         if ($refundStatus == '1')
    //         {
    //             $orderDetail->refund_status = 'Refund Has Been Intitaion';
    //         }
    //         if ($refundStatus == '2')
    //         {
    //             $orderDetail->refund_status = 'Refund Has Been Done';
    //         }
    //         $orderItem = OrderItem::where('deleted_at','')->where('order_status',$orderStatus)->where('order_id',$orderDetail->id)->get();
    //         if (!empty($orderItem->toArray()))
    //         {
    //             foreach($orderItem as $value)
    //             {
    //                 $sizeData = Size::find($value->size_id);
    //                 $value->size_name = '';
    //                 if ($sizeData)
    //                 {
    //                     $value->size_name = $sizeData->size;
    //                 }
    //                 $colorData = Color::find($value->color_id);
    //                 $value->color_name = '';
    //                 if ($colorData)
    //                 {
    //                     $value->color_name = $colorData->color;
    //                 }
    //                 $brand = Brand::find($value->brand_id);
    //                 $value->brand_name = '';
    //                 if ($brand)
    //                 {
    //                     $value->brand_name = $brand->name;
    //                 }
    //                 $productPrice = $value->product_price;
    //                 $discountPrice = $value->total_amount;
    //                 $savedAmount = $value->product_discount;
    //                 $value->product_price = $productPrice; 
    //                 $value->discount_price = $discountPrice; 
    //                 $value->saved_amount = $savedAmount;
    //                 $value->price = $productPrice * $value->quantity;
    //                 $value->is_rate = '0';
    //                 $chekAlreadyInserted = ReviewAndRating::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->where('order_item_id',$value->id)->first();
    //                 if ($chekAlreadyInserted)
    //                 {
    //                     $value->is_rate = '1';
    //                 }
    //                 $productData = Product::where('deleted_at','')->find($value->product_id);
    //                 if ($productData) 
    //                 {
    //                    $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
    //                    $productData->favorite = '0';
    //                     $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productData->id)->first();
    //                     if ($checkFav)
    //                     {
    //                         $productData->favorite = '1';
    //                     }
    //                     $productData->discount_price = '';
    //                     $price = $productData->price;
    //                     $productData->price = $productData->price . ' NR';
    //                     if ($productData->discount != '')
    //                     {
    //                         $discount = $productData->discount;
    //                         $productData->discount = $discount . ' % off';
    //                         $productData->discount_price = $price * $discount / 100;
    //                         $productData->discount_price = round($productData->discount_price);
    //                         $productData->discount_price = (string)($price - $productData->discount_price);
    //                         $productData->discount_price = $productData->discount_price . ' NR';
    //                     }
    //                     $brand = Brand::find($productData->brand_id);
    //                     if ($brand)
    //                     {
    //                         $productData->brand_name = $brand->name;
    //                     }
    //                     else
    //                     {
    //                         $productData->brand_name = '';
    //                     }
    //                 }
    //                 $value->productData = $productData;
    //             }
    //             // dd($orderDetail->productData);
    //         }
    //         $orderDetail->orderItem = $orderItem;
    //         $address = UserAddress::find($orderDetail->user_address_id);
    //         $data['orderDetail'] = $orderDetail;
    //         $data['delivery_address'] = $address;
    //         unset($orderDetail->created_at);
    //         unset($orderDetail->updated_at);
    //         unset($orderDetail->deleted_at);
    //         // dd("test");
    //         $pdf = PDF::loadView('invoice.invoice', $data);
    //         // return view('invoice.invoice',compact('data'));
    //         // var_dump($pdf);
    //         // dd($pdf);
    //         // return $pdf->download('demoPdf.pdf');
    //         // dd($pdf->download());
    //         $customerName = $customerName. '_'.$orderId.'.pdf';
    //         $content = $pdf->download()->getOriginalContent();
    //         Storage::put('invoice/'.$customerName,$content);

    //         // $source = file_get_contents($pdf->download());
    //         // file_put_contents("uploads/invoice/".$customerName, $content);

    //         // return $this->sendResponse(200, $data, 'Order Detail');
    //     }
    // }
    public function orderList(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $pageNumber = isset($request->page_number) ? $request->page_number : 1;
            $pageSize = isset($request->page_size) ? $request->page_size : 10; 

            $firstName = $userData->first_name;
            $lastName = $userData->last_name;
            $filterStatus = $request->filter_status;
            $orderListCollection = Order::where('deleted_at','')->where('user_id',$userId)->orderBy('id','desc');
            if (isset($filterStatus))
            {
                $orderListCollection->where('order_status',$filterStatus);
            }
            $orderListCollection = $orderListCollection->paginate($pageSize,'*','page',$pageNumber);
            $orderList = $orderListCollection->items();

            $totalPages = $orderListCollection->lastPage();
            $totalCount = $orderListCollection->total();
            $pageNumber = $orderListCollection->currentPage();
            $nextPage = $orderListCollection->nextPageUrl()?true:false;
            $prevPage = $orderListCollection->previousPageUrl()?true:false;

            if (!empty($orderList))
            {
                foreach($orderList as $value)
                {
                    $value->user_name = $firstName . ' '.$lastName;
                    $orderStatus = $value->order_status;
                    $value->is_cancel = '1';
                    if ($value->order_status == '1' || $value->order_status == '2')
                    {
                        $value->is_cancel = '0';
                    }
                    $value->is_return = '1';
                    if ($value->order_status == '4')
                    {
                        $orderDeliveredDate = date('Y-m-d', strtotime($value->delivered_date));
                        $currentDate = date('Y-m-d');
                        $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
                        if ($masterData)
                        {
                            $returnDay = $masterData->return_day;
                            $returnDate = date('Y-m-d', strtotime($orderDeliveredDate. ' + '.$returnDay.' days'));

                            if ($currentDate < $returnDate)
                            {
                                $value->is_return = '0';
                            }
                        }

                    }
                    if ($orderStatus == '1')
                    {
                        $value->order_status = 'Pending';
                    }
                    elseif($orderStatus == '2')
                    {
                        $value->order_status = 'Confirmed';
                    }
                    elseif($orderStatus == '3')
                    {
                        $value->order_status = 'Canceled';
                    }
                    elseif($orderStatus == '4')
                    {
                        $value->order_status = 'Delivered';
                    }
                    elseif($orderStatus == '5')
                    {
                        $value->order_status = 'Returned';
                    }
                    elseif($orderStatus == '6')
                    {
                        $value->order_status = 'Out For Service';
                    }
                    $value->order_date = date('d M Y',strtotime($value->created_at));
                    $value->total_amount = $value->total_amount . ' NR';
                    $paymentType = $value->payment_type;
                    if ($paymentType == '1')
                    {
                        $value->payment_type = 'COD';
                    }
                    if ($paymentType == '2')
                    {
                        $value->payment_type = 'Online';
                    }
                    // $value->created_at = date('d M Y',strtotime($value->created_at));
                    // $sizeData = Size::where('deleted_at','')->find($value->size_id);
                    // $colorData = Color::where('deleted_at','')->find($value->color_id);
                    // $value->size_id = $sizeData->size;
                    // $value->color_id = $colorData->color;
                    // $productData = Product::where('deleted_at','')->find($value->product_id);
                    // $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                    // $value->productData = $productData;
                }
                // return $this->sendResponse(200, $orderList, 'Order List');
                return $this->paginatResponse(200, $orderList , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, 'Order List');
            }
            else
            {
                // $orderList = new stdClass;
                return $this->sendResponse(201,$orderList,'Order not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function orderDetail(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
        	// $userId = $request->user_id;
            $orderId = $request->order_id;
            $orderDetail = Order::where('deleted_at','')->where('user_id',$userId)->find($orderId);
            if ($orderDetail)
            {
                $customerName = '';
                $userData = User::find($orderDetail->user_id);
                if ($userData)
                {
                    $customerName = $userData->first_name. ' '.$userData->last_name;
                }
                $date = date('d M Y',strtotime($orderDetail->created_at));
                $orderDetail->order_date = $date;
                $estimateDate = date('d M Y', strtotime($date. ' + '.$orderDetail->estimate_delivery_days.' days'));
                $orderDetail->estimate_date = $estimateDate;
                $total = $orderDetail->total_amount;
                $cashOnDelivery = $orderDetail->shipping_charge;
                $totalDiscount = $orderDetail->total_discount;
                $MRP = $total + $cashOnDelivery +$totalDiscount;
                $orderDetail->mrp = $MRP . ' NR';
                $orderDetail->item_discount = $totalDiscount . ' NR';
                $orderDetail->cash_on_delivery = $cashOnDelivery . ' NR';
                $orderDetail->total = $total . ' NR';
                $customerName = $customerName. '_'.$orderId.'.pdf';
                // $orderDetail->invoice = asset('uploads/invoice/'.$customerName);
                $invoiceLink = $orderDetail->invoice;
                $orderDetail->invoice = '';
                if ($invoiceLink)
                {
                    $orderDetail->invoice = route('invoice',$invoiceLink);
                }
                $paymentType = $orderDetail->payment_type;

                $orderDetail->is_cancel = '1';
                if ($orderDetail->order_status == '1' || $orderDetail->order_status == '2')
                {
                    $orderDetail->is_cancel = '0';
                }
                $orderDetail->is_return = '1';
                if ($orderDetail->order_status == '4')
                {
                    $orderDeliveredDate = date('Y-m-d', strtotime($orderDetail->delivered_date));
                    $currentDate = date('Y-m-d');
                    $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
                    if ($masterData)
                    {
                        $returnDay = $masterData->return_day;
                        $returnDate = date('Y-m-d', strtotime($orderDeliveredDate. ' + '.$returnDay.' days'));

                        if ($currentDate < $returnDate)
                        {
                            $orderDetail->is_return = '0';
                        }
                    }

                }
                if ($paymentType == '1')
                {
                    $orderDetail->payment_type = 'COD';
                }
                if ($paymentType == '2')
                {
                    $orderDetail->payment_type = 'Online';
                }

                $orderStatus = $orderDetail->order_status;
                if ($orderStatus == '1')
                {
                    $orderDetail->order_status = 'Pending';
                }
                elseif($orderStatus == '2')
                {
                    $orderDetail->order_status = 'Confirmed';
                }
                elseif($orderStatus == '3')
                {
                    $orderDetail->order_status = 'Canceled';
                }
                elseif($orderStatus == '4')
                {
                    $orderDetail->order_status = 'Delivered';
                }
                elseif($orderStatus == '5')
                {
                    $orderDetail->order_status = 'Returned';
                }
                elseif($orderStatus == '6')
                {
                    $orderDetail->order_status = 'Out For Service';
                }
                $refundStatus = $orderDetail->refund_status;
                if ($refundStatus == '1')
                {
                    $orderDetail->refund_status = 'Refund Has Been Intitaion';
                }
                if ($refundStatus == '2')
                {
                    $orderDetail->refund_status = 'Refund Has Been Done';
                }
                $orderItem = OrderItem::where('deleted_at','')->where('order_status',$orderStatus)->where('order_id',$orderDetail->id)->get();
                if (!empty($orderItem->toArray()))
                {
                    foreach($orderItem as $value)
                    {
                        $sizeData = Size::find($value->size_id);
                        $value->size_name = '';
                        if ($sizeData)
                        {
                            $value->size_name = $sizeData->size;
                        }
                        $colorData = Color::find($value->color_id);
                        $value->color_name = '';
                        if ($colorData)
                        {
                            $value->color_name = $colorData->color;
                        }
                        $brand = Brand::find($value->brand_id);
                        $value->brand_name = '';
                        if ($brand)
                        {
                            $value->brand_name = $brand->name;
                        }
                        $productPrice = $value->product_price. ' NR';
                        $discountPrice = $value->total_amount. ' NR';
                        $savedAmount = $value->product_discount. ' NR';
                        $value->saved_amount = $savedAmount;
                        $value->product_price = $productPrice; 
                        // $value->discount_price = $discountPrice; 



                        $value->discount_price = '';
                        if ($value->product_discount != '0')
                        {
                            $value->discount_price = $discountPrice;
                        } 
                        // $value->is_rate = '0';
                        // $chekAlreadyInserted = ReviewAndRating::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->where('order_item_id',$value->id)->first();
                        // if ($chekAlreadyInserted)
                        // {
                        //     $value->is_rate = '1';
                        // }
                        $value->is_rate = '1';
                        if ($orderStatus == '4')
                        {
                            $value->is_rate = '0';
                            $chekAlreadyInserted = ReviewAndRating::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->where('order_item_id',$value->id)->first();
                            if ($chekAlreadyInserted)
                            {
                                $value->is_rate = '1';
                            }
                        }
                        $productData = Product::where('deleted_at','')->find($value->product_id);
                        if ($productData) 
                        {
                           $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                           $productData->favorite = '0';
                            $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productData->id)->first();
                            if ($checkFav)
                            {
                                $productData->favorite = '1';
                            }
                            $productData->discount_price = '';
                            $price = $productData->price;
                            $productData->price = $productData->price . ' NR';
                            if ($productData->discount != '')
                            {
                                $discount = $productData->discount;
                                $productData->discount = $discount . ' % off';
                                $productData->discount_price = $price * $discount / 100;
                                $productData->discount_price = round($productData->discount_price);
                                $productData->discount_price = (string)($price - $productData->discount_price);
                                $productData->discount_price = $productData->discount_price . ' NR';
                            }
                            $brand = Brand::find($productData->brand_id);
                            if ($brand)
                            {
                                $productData->brand_name = $brand->name;
                            }
                            else
                            {
                                $productData->brand_name = '';
                            }
                            $colorIds = $productData->color_id;
                            $colorIds = explode(',',$colorIds);
                            $productData->colorData = [];
                            if ($colorIds)
                            {
                                $colorData = Color::whereIn('id',$colorIds)->get();
                                $productData->colorData = $colorData;
                            }
                            $sizeIds = $productData->size_id;
                            $sizeIds = explode(',',$sizeIds);
                            $productData->sizeData = [];
                            if ($sizeIds)
                            {
                                $sizeData = Size::whereIn('id',$sizeIds)->get();
                                $productData->sizeData = $sizeData;
                            }

                        }
                        $value->productData = $productData;

                        $colorData = Color::find($value->color_id);
                        $value->colorData = $colorData;
                        $sizeData = Size::find($value->size_id);
                        $value->sizeData = $sizeData;

                    }
                    // dd($orderDetail->productData);
                }
                $orderDetail->orderItem = $orderItem;
                $address = UserAddress::find($orderDetail->user_address_id);
                $data['orderDetail'] = $orderDetail;
                $data['delivery_address'] = $address;
                unset($orderDetail->created_at);
                unset($orderDetail->updated_at);
                unset($orderDetail->deleted_at);
                return $this->sendResponse(200, $data, 'Order Detail');
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Order not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function cancelOrder(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            // $userId = $request->user_id;
            $orderId = $request->order_id;
            $cancelId = $request->cancel_id;
            $description = $request->description;
            if (isset($orderId) && isset($cancelId) && isset($description))
            {
                $ticketReason = TicketReason::where('deleted_at','')->where('reason_for','1')->where('status','1')->find($cancelId);
                if ($ticketReason)
                {
                    $orderData = Order::where('deleted_at','')->where('user_id',$userId)->whereIn('order_status',[1,2])->find($orderId);
                    if ($orderData)
                    {
                        // $chekReturned = ReturnOrders::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->update(['deleted_at'=>config('constant.CURRENT_DATETIME')]);
                        $updateOrderItem = OrderItem::where('order_id',$orderId)->update(['order_status'=>'3']);
                        $orderData->cancel_id = $cancelId;
                        $orderData->cancel_description = $description;
                        $orderData->return_date = date('d M Y');
                        $orderData->refund_status = '1';
                        $orderData->order_status = '3';
                        $orderData->save();
                        $orderData = new stdClass;

                        $orderItem = OrderItem::where('deleted_at','')->where('order_id',$orderId)->get();
                        if (!empty($orderItem->toArray()))
                        {
                            foreach($orderItem as $value)
                            {
                                $sizeData = Size::find($value->size_id);
                                $value->size_name = '';
                                if ($sizeData)
                                {
                                    $value->size_name = $sizeData->size;
                                }
                                $colorData = Color::find($value->color_id);
                                $value->sizeData = $sizeData;
                                $value->colorData = $colorData;
                                $value->color_name = '';
                                if ($colorData)
                                {
                                    $value->color_name = $colorData->color;
                                }
                                $brand = Brand::find($value->brand_id);
                                $value->brand_name = '';
                                if ($brand)
                                {
                                    $value->brand_name = $brand->name;
                                }
                                $productPrice = $value->product_price. ' NR';
                                $discountPrice = $value->total_amount. ' NR';
                                $savedAmount = $value->product_discount. ' NR';
                                $value->product_price = $productPrice; 
                                $value->discount_price = $discountPrice; 
                                $value->saved_amount = $savedAmount;

                                $value->is_rate = '0';
                                $chekAlreadyInserted = ReviewAndRating::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->where('order_item_id',$value->id)->first();
                                if ($chekAlreadyInserted)
                                {
                                    $value->is_rate = '1';
                                }
                                $productData = Product::where('deleted_at','')->find($value->product_id);
                                if ($productData) 
                                {
                                   $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                                   $productData->favorite = '0';
                                    $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productData->id)->first();
                                    if ($checkFav)
                                    {
                                        $productData->favorite = '1';
                                    }
                                    $productData->discount_price = '';
                                    $price = $productData->price;
                                    $productData->price = $productData->price . ' NR';
                                    if ($productData->discount != '')
                                    {
                                        $discount = $productData->discount;
                                        $productData->discount = $discount . ' % off';
                                        $productData->discount_price = $price * $discount / 100;
                                        $productData->discount_price = round($productData->discount_price);
                                        $productData->discount_price = (string)($price - $productData->discount_price);
                                        $productData->discount_price = $productData->discount_price . ' NR';
                                    }
                                    $brand = Brand::find($productData->brand_id);
                                    if ($brand)
                                    {
                                        $productData->brand_name = $brand->name;
                                    }
                                    else
                                    {
                                        $productData->brand_name = '';
                                    }
                                    $colorIds = $productData->color_id;
                                    $colorIds = explode(',',$colorIds);
                                    $productData->colorData = [];
                                    if ($colorIds)
                                    {
                                        $colorData = Color::whereIn('id',$colorIds)->get();
                                        $productData->colorData = $colorData;
                                    }
                                    $sizeIds = $productData->size_id;
                                    $sizeIds = explode(',',$sizeIds);
                                    $productData->sizeData = [];
                                    if ($sizeIds)
                                    {
                                        $sizeData = Size::whereIn('id',$sizeIds)->get();
                                        $productData->sizeData = $sizeData;
                                    }
                                }
                                $value->productData = $productData;
                            }
                        }
                        $message = "Customer Canceled Order!";
                        $notification = new Notification;
                        $notification->receiver_id = '';
                        $notification->receiver_type = 'admin';
                        $notification->sender_id = $userId;
                        $notification->sender_type = '';
                        $notification->category_type = 'Order';
                        $notification->notification_type = 'Order';
                        $notification->type_id = $orderId;
                        $notification->order_status = '3';
                        $notification->message = $message;
                        $notification->deleted_at = '';
                        $notification->save();


                        $getOrderItem = OrderItem::where('order_id',$orderId)->get();
                        if (!empty($getOrderItem->toArray()))
                        {
                            foreach($orderItem as $item)
                            {
                                $productId = $item->product_id;
                                $quantity = $item->quantity;
                                $updateProductQty = Product::where('deleted_at', '')->find($productId);
                                if ($updateProductQty)
                                {
                                    $oldUsedQty = (int)$updateProductQty->used_quantity;
                                    $updateProductQty->used_quantity = $oldUsedQty - (int)$quantity;
                                    $updateProductQty->save();
                                }
                            }
                        }
                        return $this->sendResponse(200, $orderItem, 'order canceled');
                    }
                    else
                    {
                        $orderData = [];
                        return $this->sendResponse(201,$orderData, 'Order not found');
                    }
                }
                else
                {
                    $data = [];
                    return $this->sendResponse(201,$data,'cancel reason record not found');
                }
            }
            else
            {
                $data = [];
                return $this->sendResponse(201, $data, 'Please fill all the required field');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }
    }
    public function returnOrder(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $orderId = $request->order_id;

            $cancelId = $request->cancel_id;
            $description = $request->description;
            $addressId = $request->address_id;

            $ifscCode = $request->ifsc_code;
            $accountNumber = $request->account_number;
            $aHolderName = $request->account_holder_name;
            if (isset($orderId) && isset($ifscCode) && isset($accountNumber) && isset($aHolderName) && isset($cancelId) && isset($description) && isset($addressId))
            {
                $ticketReason = TicketReason::where('deleted_at','')->where('reason_for','2')->where('status','1')->find($cancelId);
                if ($ticketReason)
                {
                    $userAddress =UserAddress::where('deleted_at','')->where('user_id',$userId)->find($addressId);
                    if ($userAddress)
                    {
                        $orderData = Order::where('deleted_at','')->where('order_status','4')->where('user_id',$userId)->find($orderId);
                        if ($orderData)
                        {
                            $checkPincode = Pincode::where('deleted_at','')->where('pincode',$userAddress->pincode)->where('status','1')->first();
                            if ($checkPincode)
                            {
                                $cityData = City::where('deleted_at','')->where('status','1')->find($checkPincode->city_id);
                                if ($cityData)
                                {
                                    
                                    // $orderData->save();
                                    // return $this->sendResponse(200,$orderData,'address changed successfully');
                                    $chekReturned = ReturnOrders::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->first();
                                    if ($chekReturned)
                                    {
                                        $data = new stdClass;
                                        return $this->sendResponse(201, $data, 'order already returned');
                                    }
                                    else
                                    {
                                        $orderDeliveredDate = date('Y-m-d', strtotime($orderData->delivered_date));
                                        $currentDate = date('Y-m-d');
                                        $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
                                        if ($masterData)
                                        {
                                            $returnDay = $masterData->return_day;
                                            $returnDate = date('Y-m-d', strtotime($orderDeliveredDate. ' + '.$returnDay.' days'));

                                            if ($currentDate < $returnDate)
                                            {
                                                $updateOrderItem = OrderItem::where('order_id',$orderId)->update(['order_status'=>'5']);
                                                $orderData->order_status = '5';
                                                $orderData->cancel_id = $cancelId;
                                                $orderData->return_date = date('d M Y',strtotime($returnDate));
                                                $orderData->cancel_description = $description;
                                                $orderData->refund_status = '1';
                                                $orderData->save();
                                                $returnOrder = new ReturnOrders;
                                                $returnOrder->user_id = $userId;
                                                $returnOrder->order_id = $orderId;
                                                $returnOrder->address_id = $addressId;
                                                $returnOrder->ifsc_code = $ifscCode;
                                                $returnOrder->account_number = $accountNumber;
                                                $returnOrder->account_holder_name = $aHolderName;
                                                $returnOrder->deleted_at = '';
                                                $returnOrder->save();

                                                $orderItem = OrderItem::where('deleted_at','')->where('order_id',$orderId)->get();
                                                if (!empty($orderItem->toArray()))
                                                {
                                                    foreach($orderItem as $value)
                                                    {
                                                        $sizeData = Size::find($value->size_id);
                                                        $value->size_name = '';
                                                        if ($sizeData)
                                                        {
                                                            $value->size_name = $sizeData->size;
                                                        }
                                                        $colorData = Color::find($value->color_id);
                                                        $value->color_name = '';
                                                        if ($colorData)
                                                        {
                                                            $value->color_name = $colorData->color;
                                                        }
                                                        $brand = Brand::find($value->brand_id);
                                                        $value->brand_name = '';
                                                        if ($brand)
                                                        {
                                                            $value->brand_name = $brand->name;
                                                        }
                                                        $productPrice = $value->product_price. ' NR';
                                                        $discountPrice = $value->total_amount. ' NR';
                                                        $savedAmount = $value->product_discount. ' NR';
                                                        $value->product_price = $productPrice; 
                                                        $value->discount_price = $discountPrice; 
                                                        $value->saved_amount = $savedAmount;

                                                        $value->is_rate = '0';
                                                        $chekAlreadyInserted = ReviewAndRating::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->where('order_item_id',$value->id)->first();
                                                        if ($chekAlreadyInserted)
                                                        {
                                                            $value->is_rate = '1';
                                                        }
                                                        $productData = Product::where('deleted_at','')->find($value->product_id);
                                                        if ($productData) 
                                                        {
                                                           $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                                                           $productData->favorite = '0';
                                                            $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productData->id)->first();
                                                            if ($checkFav)
                                                            {
                                                                $productData->favorite = '1';
                                                            }
                                                            $productData->discount_price = '';
                                                            $price = $productData->price;
                                                            $productData->price = $productData->price . ' NR';
                                                            if ($productData->discount != '')
                                                            {
                                                                $discount = $productData->discount;
                                                                $productData->discount = $discount . ' % off';
                                                                $productData->discount_price = $price * $discount / 100;
                                                                $productData->discount_price = round($productData->discount_price);
                                                                $productData->discount_price = (string)($price - $productData->discount_price);
                                                                $productData->discount_price = $productData->discount_price . ' NR';
                                                            }
                                                            $brand = Brand::find($productData->brand_id);
                                                            if ($brand)
                                                            {
                                                                $productData->brand_name = $brand->name;
                                                            }
                                                            else
                                                            {
                                                                $productData->brand_name = '';
                                                            }
                                                        }
                                                        $value->productData = $productData;
                                                    }
                                                }
                                                return $this->sendResponse(200,$orderItem,'order Returned');  
                                            }
                                            else
                                            {
                                                $data = [];
                                                return $this->sendResponse(201,$data,'notable to return this order');
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $data = [];
                                    return $this->sendResponse(201, $data, 'our service not available for this area');
                                }
                            }
                            else
                            {
                                $data = [];
                                return $this->sendResponse(201,$data,'our service not available for this area');
                            }
                        }
                        else
                        {
                            $data = [];
                            return $this->sendResponse(201,$data,'order not found');
                        }
                    }
                    else
                    {
                        $data = [];
                        return $this->sendResponse(201,$data,'address not found');
                    }
                }
                else
                {
                    $data = [];
                    return $this->sendResponse(201,$data,'return reason data not found');
                }
            }
            else
            {
                $data = [];
                return $this->sendResponse(201, $data, 'Please fill all the required field');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }
    }
    public function changeAddress(Request $request)
    {
        try
        {
            $addressId = $request->address_id;
            $orderId = $request->order_id;
            $userData = $request->user();
            $userId = $userData->id;
            if (isset($addressId) && isset($orderId))
            {
                $userAddress =UserAddress::where('deleted_at','')->where('user_id',$userId)->find($addressId);
                if ($userAddress)
                {
                    $orderData = Order::where('deleted_at','')->where('user_id',$userId)->find($orderId);
                    if ($orderData)
                    {
                        $checkPincode = Pincode::where('deleted_at','')->where('pincode',$userAddress->pincode)->where('status','1')->first();
                        if ($checkPincode)
                        {
                            $cityData = City::where('deleted_at','')->where('status','1')->find($checkPincode->city_id);
                            if ($cityData)
                            {
                                $orderData->user_address_id = $addressId;
                                $orderData->save();
                                return $this->sendResponse(200,$orderData,'address changed successfully');
                            }
                            else
                            {
                                $data = new stdClass;
                                return $this->sendResponse(201, $date, 'our service not available for this area');
                            }
                        }
                        else
                        {
                            $data = new stdClass;
                            return $this->sendResponse(201,$data,'our service not available for this area');
                        }
                    }
                    else
                    {
                        $data = new stdClass;
                        return $this->sendResponse(201,$data,'order not found');
                    }
                }
                else
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'address not found');
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Please fill all the required field');
            }

        }
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }

    }
    public function proceedToCheckout(Request $request)
    {
        try 
        {
            $userData = $request->user();
            $userId = $userData->id;
            $addressId = $request->address_id;
            $isDeliverable = false;
            if (isset($addressId))
            {
                $userAddress =UserAddress::where('deleted_at','')->where('user_id',$userId)->find($addressId);
            }
            else
            {
                $userAddress =UserAddress::where('deleted_at','')->where('is_default','1')->where('user_id',$userId)->first();
            }
            $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
            $deliveryDays = '';
            $productArray = array();
            if ($userAddress)
            {
                if ($userAddress->save_as == '0')
                {
                    $userAddress->save_as = 'Home';
                }
                if ($userAddress->save_as == '1')
                {
                    $userAddress->save_as = 'Work';
                }
                $pincode = $userAddress->pincode;
                $pincode = Pincode::where('deleted_at','')->where('pincode',$pincode)->where('status','1')->first();
                if ($pincode)
                {
                    $cityData = City::where('deleted_at','')->where('status','1')->find($pincode->city_id);
                    if ($cityData)
                    {
                        $date = date('Y-m-d'); 
                        $deliveryDays = date('d M Y', strtotime($date. ' + '.$cityData->delivery_days.' days')); 
                        // $userAddress->delivery_days = $deliveryDays;
                        if (!empty($cartDetail->toArray()))
                        {
                            foreach($cartDetail as $cart)
                            {
                                $productData = Product::select('product_image')->where('deleted_at','')->find($cart->product_id);
                                $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                                $productData->delivery_days = $deliveryDays;
                                $productArray []= $productData;
                            }
                            $isDeliverable = true;
                        }
                    }
                    else
                    {
                        $isDeliverable = false;
                        $data['userAddress'] = $userAddress;
                        $data['productData'] = $productArray;
                        $data['isDeliverable'] = $isDeliverable;
                        return $this->sendResponse(201,$data,'delivery not available for this city');
                    }
                }
                else
                {
                    if (!empty($cartDetail->toArray()))
                    {
                        foreach($cartDetail as $cart)
                        {
                            $productData = Product::select('product_image')->where('deleted_at','')->find($cart->product_id);
                            $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                            $productData->delivery_days = $deliveryDays;
                            $productArray []= $productData;
                        }
                    }
                    // $userAddress = new stdClass;
                    $isDeliverable = false;
                    $data['userAddress'] = $userAddress;
                    $data['productData'] = $productArray;
                    $data['isDeliverable'] = $isDeliverable;
                    return $this->sendResponse(201,$data,'delivery not available for this city');
                }
                $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->update(['user_address_id'=>$userAddress->id]);
            }
            else
            {
                $userAddress = new stdClass;
                $isDeliverable = false;
                $cart = Cart::where('deleted_at','')->where('user_id',$userId)->first();
                $pinCodeData = Pincode::where('pincode',$cart->pincode)->first();
                $cityData = City::find($pinCodeData->city_id);
                $date = date('Y-m-d');
                $deliveryDays = date('d M Y', strtotime($date. ' + '.$cityData->delivery_days.' days')); 
                // dd($cityData);
                // $userAddress->delivery_days = $deliveryDays;
                if (!empty($cartDetail->toArray()))
                {
                    foreach($cartDetail as $cart)
                    {
                        $productData = Product::select('product_image')->where('deleted_at','')->find($cart->product_id);
                        $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                        $productData->delivery_days = $deliveryDays;
                        $productArray []= $productData;
                    }
                }
                // $userAddress = new stdClass;
            }
            $data['userAddress'] = $userAddress;
            $data['productData'] = $productArray;
            $data['isDeliverable'] = $isDeliverable;
            return $this->sendResponse(200,$data,'User Address Detail.');
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage()); 
        }
    }
    public function checkoutContinue(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
            if (!empty($cartDetail->toArray()))
            {
                $cartTotal = 0;
                $productDiscount = 0;
                $shippingCharge = 0;
                $payableAmount = 0;
                $couponDiscount = 0;
                foreach($cartDetail as $cart)
                {
                    $couponDiscount = (int)$cart->coupon_discount;
                    $shippingCharge = (int)$cart->shipping_charge;
                    $productDiscount += $cart->discount_price;
                    $payableAmount += $cart->total_price;
                    $cartTotal += $cart->total_price + $cart->discount_price;
                }
                $payableAmount = $payableAmount - $couponDiscount;
                $payableAmount = $payableAmount + $shippingCharge;
                if ($shippingCharge == 0)
                {
                    $shippingCharge = 'Free';
                }
                if ($couponDiscount != 0)
                {
                    $couponDiscount = '-'.$couponDiscount. ' NR';
                }
                else
                {
                    $couponDiscount = $couponDiscount. ' NR';
                }
                $summaryArray = array(
                    'cart_total' => $cartTotal. ' NR',
                    'product_discount' => '-'.$productDiscount. ' NR',
                    'coupon_discount' => $couponDiscount,
                    'shipping_charge' => (string)$shippingCharge,
                    'payable_amount' => $payableAmount. ' NR',
                );
                $data['cart_summary'] = $summaryArray;
                return $this->sendResponse(200, $summaryArray,'Cart Detail');
            }
            else
            {
                $summaryArray = new stdClass;
                return $this->sendResponse(201,$summaryArray,'record not found');
            }
        }
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage()); 
        }
    }
}
