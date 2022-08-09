<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\City;
use App\Models\Size;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Pincode;
use App\Models\Product;
use App\Models\Setting;
use App\Models\WishList;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cart()
    {
        $userId = Auth::user()->id;
        $data = [];
        $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
        $userAddressList = UserAddress::where('deleted_at', '')->where('user_id', $userId)->get();

        $masterData = Setting::where('deleted_at', '')->select('support_number')->first();

        if (!empty($cartDetail->toArray())) {
            $cartTotal = 0;
            $productDiscount = 0;
            $shippingCharge =  0;
            $payableAmount = 0;
            foreach ($cartDetail as $cart) {
                $couponId = $cart->coupon_id;
                $colorData = Color::find($cart->color);
                $cart->color = $colorData->color;
                $sizeData = Size::find($cart->size);
                $cart->size = $sizeData->size;
                $productData = Product::where('deleted_at', '')->find($cart->product_id);
                $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/' . $productData->product_image) : '';
                $productData->discount_price = '';
                $price = $productData->price;
                $productData->favorite = '0';
                $checkFav = WishList::where('deleted_at', '')->where('user_id', $userId)->where('product_id', $productData->id)->first();
                if ($checkFav) {
                    $productData->favorite = '1';
                }
                $brand = Brand::find($productData->brand_id);
                if ($brand) {
                    $productData->brand_name = $brand->name;
                }

                $productData->price = $productData->price . ' NR';
                if ($productData->discount != '') {
                    $discount = $productData->discount;
                    $productData->discount = $discount . ' % off';
                    $productData->discount_price = $price * $discount / 100;
                    $productData->discount_price = round($productData->discount_price);
                    $productData->discount_price = (string)($price - $productData->discount_price);
                    $productData->discount_price = $productData->discount_price . ' NR';
                }
                $pinCodeData = Pincode::where('pincode', $cart->pincode)->first();
                $cityData = City::find($pinCodeData->city_id);
                $date = date('Y-m-d');
                $deliveryDays = date('dS M', strtotime($date . ' + ' . $cityData->delivery_days . ' days'));
                $cart->delivery_days = $deliveryDays;
                $cart->productData = $productData;
                $couponDiscount = (int)$cart->coupon_discount;
                $shippingCharge = (int)$cart->shipping_charge;
                $productDiscount += $cart->discount_price;
                $payableAmount += $cart->total_price;
                $cartTotal += $cart->total_price + $cart->discount_price;
            }
            $couponData = null;
            $discountPrice = 0;
            if ($couponId != '0') {
                $couponData = Offer::find($couponId);
                $discount = $couponData->offer_discount;
                $discountPrice = $payableAmount * $discount / 100;
                $couponData->saved_amount = '-' . $discountPrice . ' NR';
                unset($couponData->created_at);
                unset($couponData->updated_at);
                unset($couponData->deleted_at);
            }
            $payableAmount = $payableAmount - $couponDiscount;
            $payableAmount = $payableAmount + $shippingCharge;
            if ($shippingCharge == 0) {
                $shippingCharge = 'Free';
            } else {
                $shippingCharge = $shippingCharge . ' NR';
            }
            $summaryArray = array(
                'cart_total' => $cartTotal . ' NR',
                'product_discount' => '-' . $productDiscount . ' NR',
                'coupon_discount' => '-' . $discountPrice . ' NR',
                'shipping_charge' => $shippingCharge,
                'payable_amount' => $payableAmount . ' NR',
            );
            $data['cartDetail'] = $cartDetail;
            $data['coupon_data'] = $couponData;
            $data['cart_summary'] = $summaryArray;

            // dd($couponData);
        }

        $couponList = $this->couponList();

        // dd($couponList);

        return view('front.cart', compact('data', 'userAddressList', 'couponList','masterData'));
    }

    public function removeFromCart(Request $request)
    {
        $cartId = $request->cart_id;
        // dd($cartId);
        $userId = auth()->user()->id;
        if (isset($cartId)) {
            $cartId = array_filter(explode(",", $cartId));
            if (!empty($cartId)) {
                foreach ($cartId as $cId) {
                    $cart = Cart::where('deleted_at', '')->where('user_id', $userId)->find($cId);
                    if ($cart) {
                        $cart->deleted_at = config('constant.CURRENT_DATETIME');
                        $cart->save();
                    }
                }
            }
            return redirect()->back()->with('success', 'Cart item removed');
        } else {
            return redirect()->back()->with('error', 'Cart item not selected');
        }
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'color' => 'required',
            'size' => 'required',
            'qty' => 'required',
            'pincode' => 'required|digits:6',
        ]);

        $userId = Auth::user()->id;
        $productId = $request->product_id;
        $colorId = $request->color;
        $sizeId = $request->size;
        $qtyData = (int)$request->qty;
        $pincode = $request->pincode;

        if ($pincode) {
            $checkPincode = Pincode::where('deleted_at', '')->where('pincode', $pincode)->where('status', '1')->first();
            if ($checkPincode) {
                $sizeData = Size::where('deleted_at', '')->find($sizeId);
                if ($sizeData) {
                    $colorData = Color::where('deleted_at', '')->find($colorId);
                    if ($colorData) {
                        $productData = Product::where('deleted_at', '')->find($productId);
                        if ($productData) {
                            $productPrice = (int)$productData->price;
                            $discount = 0;
                            $discountPrice = '';
                            if ($productData->discount != '') {
                                $discount = (int)$productData->discount;
                                $discount = ((int)$productPrice * $discount / 100) * $qtyData;
                            }
                            $shippingCharge = 0;
                            $cityData = City::where('deleted_at', '')->where('status', '1')->find($checkPincode->city_id);
                            if ($cityData) {
                                if ($cityData->is_free != '1') {
                                    $shippingCharge = $cityData->shipping_charge;
                                }
                            }
                            $checkCart = Cart::where('deleted_at', '')->where('user_id', $userId)->where('product_id', $productId)->where('color', $colorId)->where('size', $sizeId)->first();
                            if ($checkCart) {
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
                            } else {
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

                            return redirect()->intended(route('cart'))->with('success', 'Product added to cart successfully.');
                        } else {
                            return redirect()->back()->with('error', 'Product not found.');
                        }
                    } else {
                        return redirect()->back()->with('error', 'Product not available for this color.');
                    }
                } else {
                    return redirect()->back()->with('error', 'Product not available for this size.');
                }
            } else {
                return redirect()->back()->with('error', 'Product not available for this pincode.');
            }
        } else {
            return redirect()->back()->with('error', 'Product enter pincode.');
        }
    }

    //coupon

    public function couponList()
    {
        $userId = Auth::user()->id;
        $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
        if (!empty($cartDetail->toArray())) {
            $cartTotal = 0;
            $subCategoryId = '';
            $productDiscount = 0;
            $shippingCharge = 0;
            $payableAmount = 0;
            $total = 0;
            foreach ($cartDetail as $cart) {
                $cartTotal += $cart->total_price;
                $productId = $cart->product_id;
                $productData = Product::where('deleted_at', '')->select('sub_category_id')->find($productId);
                if ($productData) {
                    $subCategoryId = $subCategoryId != '' ? $subCategoryId . ',' . $productData->sub_category_id . ',' : $productData->sub_category_id;
                }
                $couponId = $cart->coupon_id;
                $couponDiscount = (int)$cart->coupon_discount;
                $shippingCharge = (int)$cart->shipping_charge;
                $productDiscount += $cart->discount_price;
                $payableAmount += $cart->total_price;
                $total += $cart->total_price + $cart->discount_price;
            }
            $currentDate = date('Y-m-d');
            $couponList = array();

            $globalCouponList = Offer::where('deleted_at', '')->where('offer_status', '1')->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->where('is_global', '1')->where('total_amount', '<=', $cartTotal)->whereRaw('total_used < total_use')->get();
            $subCategoryId = explode(',', $subCategoryId);
            $sCategoryCouponList = Offer::where('deleted_at', '')->where('offer_status', '1')->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->where('is_global', '0')->whereRaw('total_used < total_use');
            foreach ($subCategoryId as $value) {
                $sCategoryCouponList->whereRaw("find_in_set('" . $value . "',sub_category_id)");
            }
            $sCategoryCouponList = $sCategoryCouponList->get();
            $couponList = $globalCouponList->merge($sCategoryCouponList);
            foreach ($couponList as $value) {
                $discount = $value->offer_discount;
                $discountPrice = $cartTotal * $discount / 100;
                $value->saved_amount = 'Save ' . $discountPrice . ' NR';
                $endDate = $value->end_date;
                $endDate = date('d M Y h:i a', strtotime($endDate));
                $value->end_date = $endDate;
            }

            $couponData = '';
            $discountPrice = 0;
            if ($couponId != '0') {
                $couponData = Offer::find($couponId);
                $discount = $couponData->offer_discount;
                $discountPrice = $cartTotal * $discount / 100;
                $couponData->saved_amount = '-' . $discountPrice . ' NR';
                unset($couponData->created_at);
                unset($couponData->updated_at);
                unset($couponData->deleted_at);
            }
            $summaryArray = array(
                'MRP' => $total . ' NR',
                'coupon_discount' => $discountPrice . ' NR',
                'discount_price' => '-' . $productDiscount . ' NR',
                'total_paid' => $payableAmount . ' NR',
            );
            $data['coupon_list'] = $couponList;
            $data['view_detail'] = $summaryArray;
            return $data;
        } else {
            return [];
        }
    }

    public function applyCoupon(Request $request)
    {
        $userId = auth()->user()->id;
        $couponCode = $request->couponCode;
        if (isset($couponCode)) {
            $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
            if (!empty($cartDetail->toArray())) {
                $checkCoupon = Offer::where('deleted_at', '')->where('offer_code', $couponCode)->whereRaw('total_used < total_use')->first();
                if ($checkCoupon) {
                    $updateCart = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
                    $cartTotal = 0;
                    $discount = $checkCoupon->offer_discount;
                    $rowCount = $updateCart->count();
                    foreach ($updateCart as $cart) {
                        $cartTotal += $cart->total_price;
                    }
                    $discountPrice = $cartTotal * $discount / 100;
                    $cartTotal = 0;
                    $productDiscount = 0;
                    $shippingCharge = 0;
                    $payableAmount = 0;
                    foreach ($updateCart as $value) {
                        $value->coupon_id = $checkCoupon->id;
                        $value->coupon_discount = $discountPrice;
                        $value->save();
                        $couponDiscount = (int)$value->coupon_discount;
                        $shippingCharge = (int)$value->shipping_charge;
                        $productDiscount += $value->discount_price;
                        $payableAmount += $value->total_price;
                        $cartTotal += $value->total_price + $value->discount_price;
                    }
                    $payableAmount = $payableAmount - $couponDiscount;
                    $payableAmount = $payableAmount + $shippingCharge;
                    if ($shippingCharge == 0) {
                        $shippingCharge = 'Free';
                    } else {
                        $shippingCharge = $shippingCharge . ' NR';
                    }
                    $summaryArray = array(
                        'cart_total' => $cartTotal . ' NR',
                        'product_discount' => '-' . $productDiscount . ' NR',
                        'shipping_charge' => $shippingCharge,
                        'payable_amount' => $payableAmount . ' NR',
                    );
                    $checkCoupon->saved_amount = '-' . $discountPrice . ' NR';
                    unset($checkCoupon->created_at);
                    unset($checkCoupon->updated_at);
                    unset($checkCoupon->deleted_at);
                    $data['coupon_data'] = $checkCoupon;
                    $data['summary_data'] = $summaryArray;
                    $updateOfferCount = Offer::where('deleted_at', '')->where('offer_code', $couponCode)->first();
                    if ($updateOfferCount) {
                        $totalUsed = $updateOfferCount->total_used;
                        $totalUsed = $totalUsed + 1;
                        $updateOfferCount->total_used = $totalUsed;
                        $updateOfferCount->save();
                    }
                    return redirect()->back()->with('success', 'Coupon applied successfully');
                } else {
                    return redirect()->back()->with('error', 'Coupon code not found');
                }
            } else {
                return redirect()->back()->with('error', 'Cart is empty');
            }
        } else {
            return redirect()->back()->with('error', 'Coupon code required');
        }
    }

    public function removeCoupon()
    {
        $userId = auth()->user()->id;

        $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->get();

        if (!empty($cartDetail->toArray())) {
            $couponId = '0';
            foreach ($cartDetail as $cart) {
                $couponId = $cart->coupon_id;
            }
            $removeCoupon = Cart::where('deleted_at', '')->where('user_id', $userId)->update(['coupon_id'=>'0','coupon_discount'=>'0']);
            $updateCart = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
            $cartTotal = 0;
            $productDiscount = 0;
            $shippingCharge = 0;
            $payableAmount = 0;
            foreach ($updateCart as $value) {
                // $total = (int)$value->total_price;
                // $value->total_price = $total - $perProductDiscount
                $couponDiscount = (int)$value->coupon_discount;
                $shippingCharge = (int)$value->shipping_charge;
                $productDiscount += $value->discount_price;
                $payableAmount += $value->total_price;
                $cartTotal += $value->total_price + $value->discount_price;
            }
            $payableAmount = $payableAmount - $couponDiscount;
            $payableAmount = $payableAmount + $shippingCharge;
            if ($shippingCharge == 0) {
                $shippingCharge = 'Free';
            } else {
                $shippingCharge = $shippingCharge. ' NR';
            }
            $summaryArray = array(
                    'cart_total' => $cartTotal. ' NR',
                    'product_discount' => '-'.$productDiscount. ' NR',
                    'shipping_charge' => $shippingCharge,
                    'payable_amount' => $payableAmount. ' NR',
                );
            $updateOfferCount = Offer::where('deleted_at', '')->where('id', $couponId)->first();
            if ($updateOfferCount) {
                $totalUsed = $updateOfferCount->total_used;
                $totalUsed = $totalUsed - 1;
                $updateOfferCount->total_used = $totalUsed;
                $updateOfferCount->save();
            }
            return redirect()->back()->with('success', 'Coupon removed');
        } else {
            return redirect()->back()->with('error', 'Cart is empty.');
        }
    }

    public function proceedToCheckout(Request $request)
    {
        try {
            $userData = $request->user();
            $userId = auth()->user()->id;
            $addressId = $request->address_id;
            // dd($addressId);
            $isDeliverable = false;
            if (isset($addressId)) {
                $userAddress = UserAddress::where('deleted_at', '')->where('user_id', $userId)->find($addressId);
            } else {
                $userAddress = UserAddress::where('deleted_at', '')->where('is_default', '1')->where('user_id', $userId)->first();
            }
            $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
            $deliveryDays = '';
            $productArray = array();
            if ($userAddress) {
                if ($userAddress->save_as == '0') {
                    $userAddress->save_as = 'Home';
                }
                if ($userAddress->save_as == '1') {
                    $userAddress->save_as = 'Work';
                }
                $pincode = $userAddress->pincode;
                $pincode = Pincode::where('deleted_at', '')->where('pincode', $pincode)->where('status', '1')->first();
                if ($pincode) {
                    $cityData = City::where('deleted_at', '')->where('status', '1')->find($pincode->city_id);
                    if ($cityData) {
                        $date = date('Y-m-d');
                        $deliveryDays = date('d M Y', strtotime($date . ' + ' . $cityData->delivery_days . ' days'));
                        // $userAddress->delivery_days = $deliveryDays;
                        if (!empty($cartDetail->toArray())) {
                            foreach ($cartDetail as $cart) {
                                $productData = Product::select('product_image')->where('deleted_at', '')->find($cart->product_id);
                                $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/' . $productData->product_image) : '';
                                $productData->delivery_days = $deliveryDays;
                                $productArray[] = $productData;
                            }
                            $isDeliverable = true;
                        }
                    } else {
                        $isDeliverable = false;
                        $data['userAddress'] = $userAddress;
                        $data['productData'] = $productArray;
                        $data['isDeliverable'] = $isDeliverable;
                        return ['status' => $isDeliverable, 'message' => 'delivery not available for this city'];
                    }
                } else {
                    if (!empty($cartDetail->toArray())) {
                        foreach ($cartDetail as $cart) {
                            $productData = Product::select('product_image')->where('deleted_at', '')->find($cart->product_id);
                            $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/' . $productData->product_image) : '';
                            $productData->delivery_days = $deliveryDays;
                            $productArray[] = $productData;
                        }
                    }
                    // $userAddress = new stdClass;
                    $isDeliverable = false;
                    $data['userAddress'] = $userAddress;
                    $data['productData'] = $productArray;
                    $data['isDeliverable'] = $isDeliverable;
                    return ['status' => $isDeliverable, 'message' => 'delivery not available for this city'];
                }
                $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->update(['user_address_id' => $userAddress->id]);
            } else {
                $userAddress = '';
                $isDeliverable = false;
                $cart = Cart::where('deleted_at', '')->where('user_id', $userId)->first();
                $pinCodeData = Pincode::where('pincode', $cart->pincode)->first();
                $cityData = City::find($pinCodeData->city_id);
                $date = date('Y-m-d');
                $deliveryDays = date('d M Y', strtotime($date . ' + ' . $cityData->delivery_days . ' days'));
                // dd($cityData);
                // $userAddress->delivery_days = $deliveryDays;
                if (!empty($cartDetail->toArray())) {
                    foreach ($cartDetail as $cart) {
                        $productData = Product::select('product_image')->where('deleted_at', '')->find($cart->product_id);
                        $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/' . $productData->product_image) : '';
                        $productData->delivery_days = $deliveryDays;
                        $productArray[] = $productData;
                    }
                }
                // $userAddress = new stdClass;
            }
            $data['userAddress'] = $userAddress;
            $data['productData'] = $productArray;
            $data['isDeliverable'] = $isDeliverable;
            return ['status' => $isDeliverable, 'message' => 'delivery available for this city'];
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function placeOrder(Request $request)
    {
        $userId = auth()->user()->id;
        $payment_type = $request->payment_type; //1 - Online, 2 - COD
        if ($payment_type == 1) {
            return redirect(route('payment'));
        } elseif ($payment_type == 2) {
            // dd("Pay COD");
            $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
            if (!empty($cartDetail->toArray())) {
                $cartTotal = 0;
                $productDiscount = 0;
                $shippingCharge = 0;
                $payableAmount = 0;
                $couponDiscount = 0;
                foreach ($cartDetail as $cart) {
                    $userAddressId = $cart->user_address_id;
                    $couponId = $cart->coupon_id;
                    $shippingCharge = (int)$cart->shipping_charge;
                    $couponDiscount = (int)$cart->coupon_discount;
                    $productDiscount += $cart->discount_price;
                    $payableAmount += $cart->total_price;
                    $cartTotal += $cart->total_price + $cart->discount_price;
                }
                if ($couponId != '0') {
                    // product discount plus coupon discount
                    // payable amount minus coupon discount
                }
                $payableAmount = $payableAmount - $couponDiscount;
                $payableAmount = $payableAmount + $shippingCharge;
                if ($userAddressId != '0') {
                    $deliveryDays = '0';
                    $addressData = UserAddress::find($userAddressId);
                    if ($addressData) {
                        $pincode = Pincode::where('pincode', $addressData->pincode)->first();
                        if ($pincode) {
                            $cityData = City::find($pincode->city_id);
                            if ($cityData) {
                                $date = date('Y-m-d');
                                $deliveryDays = $cityData->delivery_days;
                            }
                        }
                    }
                    $masterData = Setting::where('deleted_at', '')->select('order_id')->orderBy('id', 'desc')->first();
                    $OID = $masterData->order_id + 1;
                    $orderNo = '#' . date('dmY') . time() . $OID;
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
                    $totalDiscount = $productDiscount . ' NR';

                    $productDataArray = array();
                    foreach ($cartDetail as $cart) {
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

                        $productData = Product::where('deleted_at', '')->select('product_image')->find($cart->product_id);
                        $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/' . $productData->product_image) : '';
                        $productDataArray[] = $productData;

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
                    $masterData = Setting::where('deleted_at', '')->orderBy('id', 'desc')->first();
                    if ($masterData) {
                        $masterData->order_id = $order->id;
                        $masterData->save();
                    }
                    $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->update(['deleted_at' => config('constant.CURRENT_DATETIME')]);

                    $message = "Your order has been successfully placed checkout more details here";
                    $notification = new Notification;
                    $notification->receiver_id = $userId;
                    $notification->receiver_type = 'user';
                    $notification->sender_id = '';
                    $notification->sender_type = '';
                    $notification->category_type = 'Order';
                    $notification->notification_type = 'Order';
                    $notification->type_id = $order->id;
                    $notification->message = $message;
                    $notification->deleted_at = '';
                    $notification->save();

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
                    return redirect(route('thank_you'))->with(['success' => 'Order placed successfully.', 'data' => $data]);
                // dd($data);
                } else {
                    return redirect()->back()->with('error', 'Please select address.');
                }
            } else {
                return redirect()->back()->with('error', 'cart data not found.');
            }
        } else {
            return redirect()->back()->with('error', 'Somthing went wrong.');
        }
    }

    public function payment()
    {
        $userId = auth()->user()->id;
        $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
        $productName = '';
        $payableAmount = '';

        if (!empty($cartDetail->toArray())) {
            $cartTotal = 0;
            $productDiscount = 0;
            $shippingCharge = 0;
            $payableAmount = 0;
            $couponDiscount = 0;
            foreach ($cartDetail as $cart) {
                $userAddressId = $cart->user_address_id;
                $couponId = $cart->coupon_id;
                $couponDiscount = (int)$cart->coupon_discount;
                $shippingCharge = (int)$cart->shipping_charge;
                $productDiscount += $cart->discount_price;
                $payableAmount += $cart->total_price;
                $cartTotal += $cart->total_price + $cart->discount_price + $shippingCharge;
                $productData = Product::where('deleted_at', '')->select('product_name')->find($cart->product_id);
                $productName = ($productName != '') ? ',' . $productData->product_name : $productData->product_name;
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
                'payableAmount' => $payableAmount,
            );
            // dd($payableAmount);
            return view('khalti.paymentOnline', compact('data'));
        } else {
            return redirect(route('cart'))->with('error', 'Your cart is empty.');
        }
    }

    public function saveOnlineOrder(Request $request)
    {
        $userId = auth()->user()->id;
        $transaction_id = $request->transaction_id;
        $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->get();
        if (!empty($cartDetail->toArray())) {
            $cartTotal = 0;
            $productDiscount = 0;
            $shippingCharge = 0;
            $payableAmount = 0;
            $couponDiscount = 0;
            foreach ($cartDetail as $cart) {
                $userAddressId = $cart->user_address_id;
                $couponId = $cart->coupon_id;
                $couponDiscount = (int)$cart->coupon_discount;
                $shippingCharge = (int)$cart->shipping_charge;
                $productDiscount += $cart->discount_price;
                $payableAmount += $cart->total_price;
                $cartTotal += $cart->total_price + $cart->discount_price;
            }
            if ($couponId != '0') {
                // product discount plus coupon discount
                // payable amount minus coupon discount
            }
            $productDiscount = $productDiscount + $couponDiscount;
            $payableAmount = $payableAmount - $couponDiscount;
            $payableAmount = $payableAmount + $shippingCharge;
            if ($userAddressId != '0') {
                $deliveryDays = '0';
                $addressData = UserAddress::find($userAddressId);
                if ($addressData) {
                    $pincode = Pincode::where('pincode', $addressData->pincode)->first();
                    if ($pincode) {
                        $cityData = City::find($pincode->city_id);
                        if ($cityData) {
                            $date = date('Y-m-d');
                            $deliveryDays = $cityData->delivery_days;
                        }
                    }
                }

                $masterData = Setting::where('deleted_at', '')->select('order_id')->orderBy('id', 'desc')->first();
                $OID = $masterData->order_id + 1;
                $orderNo = '#' . date('dmY') . time() . $OID;

                $order = new Order;
                $order->order_no = $orderNo;
                $order->user_id = $userId;
                $order->transaction_id = $transaction_id;
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
                $totalDiscount = $productDiscount . ' NR';

                $productDataArray = array();
                foreach ($cartDetail as $cart) {
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

                    $productData = Product::where('deleted_at', '')->select('product_image')->find($cart->product_id);
                    $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/' . $productData->product_image) : '';
                    $productDataArray[] = $productData;

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
                $masterData = Setting::where('deleted_at', '')->orderBy('id', 'desc')->first();
                if ($masterData) {
                    $masterData->order_id = $order->id;
                    $masterData->save();
                }
                $cartDetail = Cart::where('deleted_at', '')->where('user_id', $userId)->update(['deleted_at' => config('constant.CURRENT_DATETIME')]);

                $message = "Your order has been successfully placed checkout more details here";
                $notification = new Notification;
                $notification->receiver_id = $userId;
                $notification->receiver_type = 'user';
                $notification->sender_id = '';
                $notification->sender_type = '';
                $notification->category_type = 'Order';
                $notification->notification_type = 'Order';
                $notification->type_id = $order->id;
                $notification->message = $message;
                $notification->deleted_at = '';
                $notification->save();
                genratePdf($userId,$order->id);
                return ['status' => true, 'message' => 'Order placed successfully.'];
            }
        }
    }
}
