<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\BestDeal;
use App\Models\NewArrival;
use App\Models\ExclusiveProduct;
use App\Models\TrendingMen;
use App\Models\TrendingWomen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Image;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // $banners = Banner::whereHas('offer', function ($query) {
        //     $query->where('deleted_at','');
        // })->where('deleted_at', '')->orderBy('id', 'DESC')->get();

        $banners = Banner::where('deleted_at', '')->where('banner_type','1')->orderBy('id', 'DESC')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.banners.create',compact('offers','categorylist','brandList','colorList','productList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required'
        ]);
        $productId = $request->product_id;
        $input = $request->all();
        if ($image = $request->file('image')) {

            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $input['image'] = time().'.'.$image->extension();  
            $destinationPath = 'uploads/banners/';
            $img = Image::make($image->path());
            $img->resize(1920, 768)->save($destinationPath.'/'.$bannerImage);
            // dd($bannerImage);
            
            // $image->move($destinationPath, $bannerImage);
            
            // dd($bannerImage);

            $input['image'] = "$bannerImage";
        }

        $input['banner_location'] = '1';
        $input['offer_id'] = 0;
        $input['product_id'] = implode(',',$productId);
        $input['status'] = $input['status'] ?? 0;
        $input['banner_type'] = 1;

        Banner::create($input);

        return redirect()->route('admin.banners.index')->with('success', 'Banner added successfully.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.banners.edit', compact('banner','offers','categorylist','brandList','colorList','productList'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required',
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/banners/';
            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            $img = Image::make($image->path());
            $img->resize(1920, 768)->save($destinationPath.'/'.$bannerImage);
            // $image->move($destinationPath, $bannerImage);
            if ($banner->image != '' && file_exists("uploads/banners/" . $banner->image)) {
                unlink("uploads/banners/" . $banner->image);
            }
            $input['image'] = "$bannerImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;
        $input['banner_location'] = '1';
        $input['offer_id'] = 0;
        $input['product_id'] = implode(',',$productId);
        $banner->update($input);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Banner deleted successfully.');
    }

    public function bestDeals()
    {
        $bestDeals = Banner::where('deleted_at', '')->where('banner_type','2')->orderBy('id', 'DESC')->get();
        return view('admin.bestDeals.index', compact('bestDeals'));
    }
    public function bestDealCreate()
    {
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.bestDeals.create',compact('categorylist','brandList','colorList','productList'));
    }
    public function bestDealStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required'
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {

            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $input['image'] = time().'.'.$image->extension();
            
            $destinationPath = 'uploads/banners/';
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(360, 360)->save($destinationPath.'/'.$bannerImage);
            
            
            // dd($bannerImage);

            $input['image'] = "$bannerImage";
        }
        $input['product_id'] = implode(',',$productId);
        $input['status'] = $input['status'] ?? 0;
        $input['offer_id'] = 0;
        $input['banner_type'] = 2;
        $input['banner_location'] = '';

        Banner::create($input);

        return redirect()->route('admin.bestdeals.index')->with('success', 'Banner added successfully.');
    }
    public function bestDealEdit($id)
    {
        $bestDeal = Banner::findOrFail($id);
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.bestDeals.edit', compact('bestDeal','offers','categorylist','brandList','colorList','productList'));
    }
    public function bestDealUpdate(Request $request,$id)
    {
        $bestDeal = Banner::findOrFail($id);

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required',
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/banners/';
            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(360, 360)->save($destinationPath.'/'.$bannerImage);
            if ($bestDeal->image != '' && file_exists("uploads/banners/" . $bestDeal->image)) {
                unlink("uploads/banners/" . $bestDeal->image);
            }
            $input['image'] = "$bannerImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;
        $input['product_id'] = implode(',',$productId);
        $bestDeal->update($input);

        return redirect()->route('admin.bestdeals.index')->with('success', 'Banner updated successfully.');
    }
    public function newArrival()
    {
        $newArrival = Banner::where('deleted_at', '')->where('banner_type','3')->orderBy('id', 'DESC')->get();
        return view('admin.newArrivals.index', compact('newArrival'));
    }
    public function newArrivalCreate()
    {
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.newArrivals.create',compact('categorylist','brandList','colorList','productList'));
    }
    public function newArrivalStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required'
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {

            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $input['image'] = time().'.'.$image->extension();
            
            $destinationPath = 'uploads/banners/';
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(468, 468)->save($destinationPath.'/'.$bannerImage);
            
            
            // dd($bannerImage);

            $input['image'] = "$bannerImage";
        }
        $input['product_id'] = implode(',',$productId);
        $input['status'] = $input['status'] ?? 0;
        $input['offer_id'] = 0;
        $input['banner_type'] = 3;
        $input['banner_location'] = '';

        Banner::create($input);

        return redirect()->route('admin.newArrivals.index')->with('success', 'Banner added successfully.');
    }
    public function newArrivalEdit($id)
    {
        $newArrival = Banner::findOrFail($id);
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.newArrivals.edit', compact('newArrival','offers','categorylist','brandList','colorList','productList'));
    }
    public function newArrivalUpdate(Request $request,$id)
    {
        $newArrival = Banner::findOrFail($id);

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required',
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/banners/';
            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(468, 468)->save($destinationPath.'/'.$bannerImage);
            if ($newArrival->image != '' && file_exists("uploads/banners/" . $newArrival->image)) {
                unlink("uploads/banners/" . $newArrival->image);
            }
            $input['image'] = "$bannerImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;
        $input['product_id'] = implode(',',$productId);
        $newArrival->update($input);

        return redirect()->route('admin.newArrivals.index')->with('success', 'Banner updated successfully.');
    }
    public function bbExclusive(Request $request)
    {
        $bbExclusiveList = Banner::where('deleted_at', '')->where('banner_type','4')->orderBy('id', 'DESC')->get();
        return view('admin.bbExclusive.index', compact('bbExclusiveList'));
    }
    public function bbExclusiveCreate(Request $request)
    {
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.bbExclusive.create',compact('categorylist','brandList','colorList','productList'));
    }
    public function bbExclusiveStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required'
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {

            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $input['image'] = time().'.'.$image->extension();
            
            $destinationPath = 'uploads/banners/';
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(468, 468)->save($destinationPath.'/'.$bannerImage);
            
            
            // dd($bannerImage);

            $input['image'] = "$bannerImage";
        }
        $input['product_id'] = implode(',',$productId);
        $input['status'] = $input['status'] ?? 0;
        $input['offer_id'] = 0;
        $input['banner_type'] = 4;
        $input['banner_location'] = '';

        Banner::create($input);

        return redirect()->route('admin.exclusive.index')->with('success', 'Banner added successfully.');
    }
    public function bbExclusiveEdit($id)
    {
        $exclusiveProduct = Banner::findOrFail($id);
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.bbExclusive.edit', compact('exclusiveProduct','offers','categorylist','brandList','colorList','productList'));
    }
    public function bbExclusiveUpdate(Request $request,$id)
    {
        $exclusiveProduct = Banner::findOrFail($id);

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required',
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/banners/';
            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(468, 468)->save($destinationPath.'/'.$bannerImage);
            if ($exclusiveProduct->image != '' && file_exists("uploads/banners/" . $exclusiveProduct->image)) {
                unlink("uploads/banners/" . $exclusiveProduct->image);
            }
            $input['image'] = "$bannerImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;
        $input['product_id'] = implode(',',$productId);
        $exclusiveProduct->update($input);

        return redirect()->route('admin.exclusive.index')->with('success', 'Banner updated successfully.');
    }

    public function trendingInMen()
    {
        $trendingMenList = Banner::where('deleted_at', '')->where('banner_type','5')->orderBy('id', 'DESC')->get();
        return view('admin.trendingMen.index', compact('trendingMenList'));
    }
    public function trendingInMenCreate()
    {
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.trendingMen.create',compact('categorylist','brandList','colorList','productList'));
    }
    public function trendingInMenStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required'
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {

            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $input['image'] = time().'.'.$image->extension();
            
            $destinationPath = 'uploads/banners/';
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(468, 468)->save($destinationPath.'/'.$bannerImage);
            
            
            // dd($bannerImage);

            $input['image'] = "$bannerImage";
        }
        $input['product_id'] = implode(',',$productId);
        $input['status'] = $input['status'] ?? 0;$input['offer_id'] = 0;
        $input['banner_type'] = 5;
        $input['banner_location'] = '';

        Banner::create($input);

        return redirect()->route('admin.trendingInMen.index')->with('success', 'Banner added successfully.');
    }
    public function trendingInMenEdit($id)
    {
        $trendingMen = Banner::findOrFail($id);
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.trendingMen.edit', compact('trendingMen','offers','categorylist','brandList','colorList','productList'));
    }
    public function trendingInMenUpdate(Request $request,$id)
    {
        $trendingMen = Banner::findOrFail($id);

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required',
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/banners/';
            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(468, 468)->save($destinationPath.'/'.$bannerImage);
            if ($trendingMen->image != '' && file_exists("uploads/banners/" . $trendingMen->image)) {
                unlink("uploads/banners/" . $trendingMen->image);
            }
            $input['image'] = "$bannerImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;
        $input['product_id'] = implode(',',$productId);
        $trendingMen->update($input);

        return redirect()->route('admin.trendingInMen.index')->with('success', 'Banner updated successfully.');
    }
    public function trendingInWomen()
    {
        $trendingInWomen = Banner::where('deleted_at', '')->where('banner_type','6')->orderBy('id', 'DESC')->get();
        return view('admin.trendingWomen.index', compact('trendingInWomen'));
    }
    public function trendingInWomenCreate()
    {
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.trendingWomen.create',compact('categorylist','brandList','colorList','productList'));
    }
    public function trendingInWomenStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required'
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {

            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $input['image'] = time().'.'.$image->extension();
            
            $destinationPath = 'uploads/banners/';
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(468, 468)->save($destinationPath.'/'.$bannerImage);
            
            
            // dd($bannerImage);

            $input['image'] = "$bannerImage";
        }
        $input['product_id'] = implode(',',$productId);
        $input['status'] = $input['status'] ?? 0;
        $input['offer_id'] = 0;
        $input['banner_type'] = 6;
        $input['banner_location'] = '';

        Banner::create($input);

        return redirect()->route('admin.trendingInWomen.index')->with('success', 'Banner added successfully.');
    }
    public function trendingInWomenEdit($id)
    {
        $trendingInWomen = Banner::findOrFail($id);
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $categorylist = Category::where('deleted_at','')->where('status','1')->orderBy('id', 'DESC')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $productList = Product::where('deleted_at','')->where('product_status','1')->get();
        return view('admin.trendingWomen.edit', compact('trendingInWomen','offers','categorylist','brandList','colorList','productList'));
    }
    public function trendingInWomenUpdate(Request $request,$id)
    {
        $trendingInWomen = Banner::findOrFail($id);

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required',
        ]);
        $productId = $request->product_id;
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/banners/';
            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            // $image->move($destinationPath, $bannerImage);
            $img = Image::make($image->path());
            $img->resize(468, 468)->save($destinationPath.'/'.$bannerImage);
            if ($trendingInWomen->image != '' && file_exists("uploads/banners/" . $trendingInWomen->image)) {
                unlink("uploads/banners/" . $trendingInWomen->image);
            }
            $input['image'] = "$bannerImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;
        $input['product_id'] = implode(',',$productId);
        $trendingInWomen->update($input);

        return redirect()->route('admin.trendingInWomen.index')->with('success', 'Banner updated successfully.');
    }
}
