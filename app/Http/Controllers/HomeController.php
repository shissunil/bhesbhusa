<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Banner;
use App\Models\CmsMaster;
use App\Models\Product;
use App\Models\WishList;
use App\Models\SuperSubCategory;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $headerBannerList = Banner::where('deleted_at', '')->where('banner_type','1')->where('status', '1')->where('banner_location', '1')->get();
        $footerBannerList = Banner::where('deleted_at', '')->where('status', '2')->where('banner_location', '2')->get();
        $bannerList = Banner::where('deleted_at','')->where('status','1')->where('banner_type','!=','1')->get();
        if (!empty($headerBannerList)) {
            foreach ($headerBannerList as $banner) {
                $banner->banner_image = (!empty($banner->image)) ? asset('uploads/banners/'.$banner->image) : '';
            }
        }
        if (!empty($footerBannerList)) {
            foreach ($footerBannerList as $banner) {
                $banner->banner_image = (!empty($banner->image)) ? asset('uploads/banners/'.$banner->image) : '';
            }
        }
        if (count($bannerList) > 0)
        {
            foreach($bannerList as $banner)
            {
                $banner->banner_image = (!empty($banner->image)) ? asset('uploads/banners/'.$banner->image) : '';
            }
        }
        $newArrivals = $this->newArrivals();
        return view('front.home', compact('headerBannerList', 'footerBannerList', 'newArrivals','bannerList'));
    }

    public function about_us()
    {
        $aboutUs = CmsMaster::where('cms_page', 1)->where('app_type', 1)->first();
        return view('front.about_us', compact('aboutUs'));
    }

    public function terms_and_conditions()
    {
        $termsOfUse = CmsMaster::where('cms_page', 2)->where('app_type', 1)->first();
        return view('front.terms_and_conditions', compact('termsOfUse'));
    }

    public function privacy_policy()
    {
        $privacyPolicy = CmsMaster::where('cms_page', 3)->where('app_type', 1)->first();
        return view('front.privacy_policy', compact('privacyPolicy'));
    }

    public function faq()
    {
        $FAQs = CmsMaster::where('cms_page', 0)->where('app_type', 1)->first();
        return view('front.faq', compact('FAQs'));
    }

    public function newArrivals()
    {
        $userId = auth()->user()->id ?? 0;

        $productList = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0')
            ->take(16)
            ->orderBy('id', 'DESC')
            ->get();

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

        return $productList;
    }

    public function helpCenter()
    {
        $helpCenter = CmsMaster::where('cms_page', 5)->where('app_type', 1)->first();
        return view('front.helpCenter', ['helpCenter' => $helpCenter]);
    }

    public function returnPolicy()
    {
        $returnPolicy = CmsMaster::where('cms_page', 4)->where('app_type', 1)->first();
        return view('front.returnPolicy', ['returnPolicy' => $returnPolicy]);
    }
    public function filterCategoryData(Request $request)
    {
        $SuperCategoryId = $request->super_cat_id;
        // $SuperCategoryId = $input['super_category_id'];
        $superSubCategory = SuperSubCategory::where('deleted_at','')->where('super_category_id',$SuperCategoryId)->where('supersub_status','1')->get();
        if (!empty($superSubCategory->toArray()))
        {
            foreach ($superSubCategory as $key => $SubCategory) 
            {
                $SubCategory->supersub_cat_image = (!empty($SubCategory->supersub_cat_image)) ? asset('uploads/super_sub_category/'.$SubCategory->supersub_cat_image) : '';

                $category_list = Category::where('deleted_at','')->where('status','1')->where('supersub_cat_id',$SubCategory->id)->get();
                if (!empty($category_list->toArray())) 
                {
                    // $category_list->image = (!empty($category_list->image)) ? asset('uploads/category/'.$category_list->image) : '';
                    foreach ($category_list as $value) 
                    {
                        $value->image = (!empty($value->image)) ? asset('uploads/category/'.$value->image) : '';
                        $subCategory_list = SubCategory::where(['deleted_at' =>'','category_id' => $value->id,'status' => '1'])->get();
                        // $subCategory_list->image = 'uploads/category/'.$subCategory_list->image;
                        if (!empty($subCategory_list->toArray()))
                        {
                            foreach($subCategory_list as $sCValue)
                            {
                                $sCValue->image = (!empty($sCValue->image)) ? asset('uploads/sub_category/'.$sCValue->image) : '';
                            }
                        }
                        $value->subCategory_list = $subCategory_list;
                    }
                }
                $SubCategory->category_list = $category_list;
            }
            // $data['superSubCategory'] = $superSubCategory;
        }
        // dd($superSubCategory);
        return view('front.filterCategory',compact('superSubCategory'));
        // return $this->sendResponse(200,$superSubCategory,'Super Category List...!');
    }
}
