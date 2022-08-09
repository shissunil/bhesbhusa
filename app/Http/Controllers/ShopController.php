<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Size;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Pincode;
use App\Models\Product;
use App\Models\Category;
use App\Models\WishList;
use App\Models\SubCategory;
use App\Models\UserAddress;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\SearchHistory;
use App\Models\SuperCategory;
use App\Models\SuperSubCategory;
use App\Models\ReviewAndRating;
use App\Models\ReviewReply;
use App\Models\User;
use App\Models\Banner;
use Illuminate\Support\Facades\Crypt;
use Session;

class ShopController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function shop()
    {
        return view('front.shop');
    }

    public function productList($subCategoryId)
    {
        $subCategoryId = Crypt::decrypt($subCategoryId);

        // dd($subCategoryId);

        try {
            $bradcumbData = $this->getParentData($subCategoryId);

            $userId = auth()->user()->id ?? 0;

            $productList = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0')
                ->where('sub_category_id', $subCategoryId)
                ->paginate(10);

            if (!empty($productList)) {
                foreach ($productList as $value) {
                    $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/' . $value->product_image) : '';
                    $value->favorite = '0';
                    $checkFav = WishList::where('deleted_at', '')->where('user_id', $userId)->where('product_id', $value->id)->first();
                    if ($checkFav) {
                        $value->favorite = '1';
                    }
                    $value->rating = sprintf("%.1f", $value->rating);
                    $value->discount_price = '';
                    $price = $value->price;
                    $value->price = $value->price . ' NR';
                    if ($value->discount != '') {
                        $discount = $value->discount;
                        $value->discount = $discount . ' % off';
                        $value->discount_price = $price * $discount / 100;
                        $value->discount_price = round($value->discount_price);
                        $value->discount_price = (string)($price - $value->discount_price);
                        $value->discount_price = $value->discount_price . ' NR';
                    }
                    $brand = Brand::find($value->brand_id);
                    if ($brand) {
                        $value->brand_name = $brand->name;
                    } else {
                        $value->brand_name = '';
                    }
                }
            }
            // dd($bradcumbData);
            return view('front.productList', compact('productList', 'bradcumbData', 'subCategoryId'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    public function categoryProductList($superCategoryId)
    {
        $superCategoryId = Crypt::decrypt($superCategoryId);
        $subCategoryId = '';

        $superSubCategoryData = SuperSubCategory::where('deleted_at', '')->where('supersub_status', 1)->where('super_category_id', $superCategoryId)->first();
        // dd($superSubCategoryData);

        if ($superSubCategoryData) {
            $superSubCategoryId = $superSubCategoryData->id;
            $categoryData = Category::where('deleted_at', '')->where('status', 1)->where('supersub_cat_id', $superSubCategoryId)->first();
            // dd($categoryData);
            if ($categoryData) {
                $categoryId = $categoryData->id;
                $subCategoryData = SubCategory::where('deleted_at', '')->where('status', 1)->where('category_id', $categoryId)->first();
                // dd($subCategoryData);
                if ($subCategoryData) {
                    $subCategoryId = $subCategoryData->id;
                }
            }
        }

        // dd($subCategoryId);

        try {
            if ($subCategoryId != '') {
                $bradcumbData = $this->getParentData($subCategoryId);

                $userId = auth()->user()->id ?? 0;

                $productList = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0')
                    ->where('sub_category_id', $subCategoryId)
                    ->paginate(10);

                if (!empty($productList)) {
                    foreach ($productList as $value) {
                        $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/' . $value->product_image) : '';
                        $value->favorite = '0';
                        $checkFav = WishList::where('deleted_at', '')->where('user_id', $userId)->where('product_id', $value->id)->first();
                        if ($checkFav) {
                            $value->favorite = '1';
                        }
                        $value->rating = sprintf("%.1f", $value->rating);
                        $value->discount_price = '';
                        $price = $value->price;
                        $value->price = $value->price . ' NR';
                        if ($value->discount != '') {
                            $discount = $value->discount;
                            $value->discount = $discount . ' % off';
                            $value->discount_price = $price * $discount / 100;
                            $value->discount_price = round($value->discount_price);
                            $value->discount_price = (string)($price - $value->discount_price);
                            $value->discount_price = $value->discount_price . ' NR';
                        }
                        $brand = Brand::find($value->brand_id);
                        if ($brand) {
                            $value->brand_name = $brand->name;
                        } else {
                            $value->brand_name = '';
                        }
                    }
                }

                return view('front.productList', compact('productList', 'bradcumbData', 'subCategoryId'));
            } else {
                return redirect()->route('home')->with('error', 'No products found.');
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }


    public function getParentData($subCategoryId)
    {
        $subCategoryData = SubCategory::where('id', $subCategoryId)->first();
        if ($subCategoryData) {
            $categoryData = Category::where('id', $subCategoryData->category_id)->first();
            if ($categoryData) {
                $subCategoryData->category_data = $categoryData;
                $superSubCategoryData = SuperSubCategory::where('id', $categoryData->supersub_cat_id)->first();
                if ($superSubCategoryData) {
                    $subCategoryData->super_sub_category_data = $superSubCategoryData;
                    $superCategoryData = SuperCategory::where('id', $superSubCategoryData->super_category_id)->first();
                    if ($superCategoryData) {
                        $subCategoryData->super_category_data = $superCategoryData;
                    }
                }
            }
        }
        return $subCategoryData;
    }

    public function filterData()
    {
        $colorList = Color::where('deleted_at', '')->where('status', '1')->get();
        $brandList = Brand::where('deleted_at', '')->where('status', '1')->get();
        $subCategoryList = SubCategory::where('deleted_at', '')->where('status', '1')->get();
        // $sizeList = Size::where('deleted_at','')->where('status','1')->get();
        $minPrice = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0')->min('price');
        $maxPrice = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0')->max('price');
        $priceRange  = array(
            "minPrice" => $minPrice,
            "maxPrice" => $maxPrice,
        );
        $data['colorList'] = $colorList;
        $data['brandList'] = $brandList;
        $data['subCategoryList'] = $subCategoryList;
        $data['priceRange'] = $priceRange;
        return view('front.filterSidebar', compact('data'));
    }
}
