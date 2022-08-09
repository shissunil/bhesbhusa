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

class WishlistController extends Controller
{
    public function wishlist(Request $request)
    {
        $userId = Auth::user()->id;

        $pageNumber = isset($request->page_number) ? $request->page_number : 1;
        $pageSize = isset($request->page_size) ? $request->page_size : 10;

        $wishList = WishList::where('deleted_at', '')->where('user_id', $userId)->paginate(10);
        // dd($wishListCollection);
        // $wishList = $wishListCollection->items();

        // $totalPages = $wishListCollection->lastPage();
        // $totalCount = $wishListCollection->total();
        // $pageNumber = $wishListCollection->currentPage();
        // $nextPage = $wishListCollection->nextPageUrl() ? true : false;
        // $prevPage = $wishListCollection->previousPageUrl() ? true : false;
        if (!empty($wishList)) {
            foreach ($wishList as $value) {
                $productData = Product::where('deleted_at', '')->find($value->product_id);
                if ($productData) {
                    $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/' . $productData->product_image) : '';
                    $productData->favorite = '0';
                    $checkFav = WishList::where('deleted_at', '')->where('user_id', $userId)->where('product_id', $productData->id)->first();
                    if ($checkFav) {
                        $productData->favorite = '1';
                    }
                    $productData->discount_price = '';
                    $price = $productData->price;
                    $productData->price = $productData->price . ' NR';
                    if ($productData->discount != '') {
                        $discount = $productData->discount;
                        $productData->discount = $discount . ' % off';
                        $productData->discount_price = $price * $discount / 100;
                        $productData->discount_price = round($productData->discount_price);
                        $productData->discount_price = (string)($price - $productData->discount_price);
                        $productData->discount_price = $productData->discount_price . ' NR';
                    }

                    $brand = Brand::find($productData->brand_id);
                    if ($brand) {
                        $productData->brand_name = $brand->name;
                    } else {
                        $productData->brand_name = '';
                    }
                }

                $value->productData = $productData;
            }
        }

        // dd($wishList);

        return view('front.wishlist', compact('wishList'));
    }

    public function addRemoveWishlist(Request $request)
    {
        $userId = auth()->user()->id;
        $productId = $request->product_id;
        $isFavroite = $request->is_favorite;
        if (isset($productId) && isset($isFavroite)) {
            $chekProduct = Product::where('deleted_at', '')->find($productId);
            if ($chekProduct) {
                $checkFav = WishList::where('user_id', $userId)->where('product_id', $productId)->first();
                if ($checkFav) {
                    if ($isFavroite == '0') {
                        if ($checkFav->deleted_at == '') {
                            $checkFav->deleted_at = config('constant.CURRENT_DATETIME');
                            $checkFav->save();
                            return redirect()->back()->with('success', 'Product removed from wishlist successfully.');
                        }
                    } elseif ($isFavroite == '1') {
                        $checkFav->deleted_at = '';
                        $checkFav->save();
                        return redirect()->back()->with('success', 'Product added to wishlist successfully.');
                    }
                } else {
                    if ($isFavroite == '1') {
                        $saveWishList = new WishList;
                        $saveWishList->user_id = $userId;
                        $saveWishList->product_id = $request->product_id;
                        $saveWishList->deleted_at = '';
                        $saveWishList->save();
                        return redirect()->back()->with('success', 'Product added to wishlist successfully.');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Product not found.');
            }
        }
    }

    public function moveToWishlist(Request $request)
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
                        $productId = $cart->product_id;
                        $isFavroite = 1;
                        if (isset($productId) && isset($isFavroite)) {
                            $chekProduct = Product::where('deleted_at', '')->find($productId);
                            if ($chekProduct) {
                                $checkFav = WishList::where('user_id', $userId)->where('product_id', $productId)->first();
                                if ($checkFav) {
                                    $checkFav->deleted_at = '';
                                    $checkFav->save();
                                } else {
                                    $saveWishList = new WishList;
                                    $saveWishList->user_id = $userId;
                                    $saveWishList->product_id = $productId;
                                    $saveWishList->deleted_at = '';
                                    $saveWishList->save();
                                }
                            }
                        }
                    }
                }
            }
            return redirect()->back()->with('success', 'Moved to wishlist sucessfully.');
        } else {
            return redirect()->back()->with('error', 'Cart item not selected');
        }
    }
}
