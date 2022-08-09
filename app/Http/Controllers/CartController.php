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
            'pincode' => 'required|digits:5',
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
                $value->save = $discountPrice . ' NR';
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
}
