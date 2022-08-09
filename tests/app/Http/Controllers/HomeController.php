<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Banner;
use App\Models\CmsMaster;
use App\Models\Product;
use App\Models\WishList;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $headerBannerList = Banner::where('deleted_at', '')->where('status', '1')->where('banner_location', '1')->get();
        $footerBannerList = Banner::where('deleted_at', '')->where('status', '2')->where('banner_location', '2')->get();
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
        $newArrivals = $this->newArrivals();
        return view('front.home', compact('headerBannerList', 'footerBannerList', 'newArrivals'));
    }

    public function about_us()
    {
        $aboutUs = CmsMaster::where('cms_page', 1)->where('app_type', 1)->first();
        return view('front.about_us', ['aboutUs' => $aboutUs]);
    }

    public function terms_and_conditions()
    {
        $termsOfUse = CmsMaster::where('cms_page', 2)->where('app_type', 1)->first();
        return view('front.terms_and_conditions', ['termsOfUse' => $termsOfUse]);
    }

    public function privacy_policy()
    {
        $privacyPolicy = CmsMaster::where('cms_page', 3)->where('app_type', 1)->first();
        return view('front.privacy_policy', ['privacyPolicy' => $privacyPolicy]);
    }

    public function faq()
    {
        $FAQs = CmsMaster::where('cms_page', 0)->where('app_type', 1)->first();
        return view('front.faq', ['FAQs' => $FAQs]);
    }

    public function newArrivals()
    {
        $userId = auth()->user()->id ?? 0;

        $productList = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity')
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
}
