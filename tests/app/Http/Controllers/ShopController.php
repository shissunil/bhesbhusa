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

            $productList = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity')
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

                $productList = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity')
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

    public function productDetails($product_id)
    {
        $userData = auth()->user();
        $userId = $userData->id ?? 0;
        $product_id = Crypt::decrypt($product_id);

        $productDetail = Product::where('deleted_at', '')->find($product_id);
        if ($productDetail) {
            $quantity = (int)$productDetail->quantity;
            $usedQuantity = (int)$productDetail->used_quantity;
            $productDetail->quantity = $quantity - $usedQuantity;
            $bradcumbData = $this->getParentData($productDetail->sub_category_id);
            $similiarProducts = $this->similiarProducts($productDetail->sub_category_id, $product_id);

            $productDetail->favorite = '0';
            $productDetail->delivery_days = '';
            $brand = Brand::find($productDetail->brand_id);
            if ($brand) {
                $productDetail->brand_name = $brand->name;
            } else {
                $productDetail->brand_name = '';
            }
            if ($userData) {
                $checkFav = WishList::where('deleted_at', '')->where('user_id', $userId)->where('product_id', $productDetail->id)->first();
                if ($checkFav) {
                    $productDetail->favorite = '1';
                }

                $chekDefaultAddress = UserAddress::where('deleted_at', '')->where('user_id', $userId)->where('is_default', '1')->first();
                if ($chekDefaultAddress) {
                    $pincode = Pincode::where('deleted_at', '')->where('pincode', $chekDefaultAddress->pincode)->where('status', '1')->first();
                    if ($pincode) {
                        $cityData = City::where('deleted_at', '')->where('status', '1')->find($pincode->city_id);
                        if ($cityData) {
                            $date = date('Y-m-d');
                            $deliveryDays = date('l d M', strtotime($date . ' + ' . $cityData->delivery_days . ' days'));

                            $productDetail->delivery_days = $deliveryDays;
                        }
                    }
                }
            }
            // $productDetail->discount_price = '';
            // $productDetail->discount_percentage = '';
            $productDetail->rating = sprintf("%.1f", $productDetail->rating);
            // $rating = ReviewAndRating::where('deleted_at','')->where('product_id',$productId)->sum('rate');
            // $productDetail->rating = '0';
            // if ($rating != 0)
            // {
            //     $count = ReviewAndRating::where('deleted_at','')->where('product_id',$productId)->count();
            //     $productDetail->rating = sprintf("%.2f",$rating / $count);
            // }
            $productDetail->discount_price = '';
            $price = $productDetail->price;
            $productDetail->price = $productDetail->price . ' NR';
            if ($productDetail->discount != '') {
                $discount = $productDetail->discount;
                $productDetail->discount = $discount . ' % off';
                $productDetail->discount_price = $price * $discount / 100;
                $productDetail->discount_price = round($productDetail->discount_price);
                $productDetail->discount_price = (string)($price - $productDetail->discount_price);
                $productDetail->discount_price = $productDetail->discount_price . ' NR';
            }
            $productDetail->product_image = (!empty($productDetail->product_image)) ? asset('uploads/product/' . $productDetail->product_image) : '';
            $productImage = ProductImage::where('deleted_at', '')->where('product_id', $productDetail->id)->get();
            if (!empty($productImage->toArray())) {
                foreach ($productImage as $value) {
                    $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/' . $value->product_image) : '';
                }
            }
            $productDetail->product_images = $productImage;

            $color_id = $productDetail->color_id;
            if ($color_id) {
                $color_id = array_filter(explode(",", $color_id));
                $colorList = Color::where('deleted_at', '')->where('status', '1')->whereIn('id', $color_id)->get();
                $productDetail->colorList = $colorList;
            }

            $size_id = $productDetail->size_id;
            if ($size_id) {
                $size_id = array_filter(explode(",", $size_id));
                $sizeList = Size::where('deleted_at', '')->where('status', '1')->whereIn('id', $size_id)->get();
                $productDetail->sizeList = $sizeList;
            }

            $userAddress = UserAddress::where('deleted_at', '')->where('is_default', '1')->where('user_id', $userId)->first();

            // dd($userAddress);

            // $productId = Crypt::decrypt($product_id);
            $reviewCollection = ReviewAndRating::where('deleted_at','')->where('product_id',$product_id)->paginate(5);
            // $$reviewCollection = $reviewCollection;
            // $reviewData = $reviewCollection->items();

            // $totalPages = $reviewCollection->lastPage();
            // $totalCount = $reviewCollection->total();
            // $pageNumber = $reviewCollection->currentPage();
            // $nextPage = $reviewCollection->nextPageUrl()?true:false;
            // $prevPage = $reviewCollection->previousPageUrl()?true:false;
            // $reviewDataArr = $reviewData->toArray();
            $reviewArray = array();
            if (!empty($reviewCollection->toArray()))
            {
                foreach($reviewCollection as $value)
                {
                    $userData = User::find($value->user_id);
                    $value->customer_name = $userData->first_name. ' '. $userData->last_name;
                    $value->review_date = date('d M, Y',strtotime($value->created_at));

                    $reviewReply = ReviewReply::where('deleted_at','')->where('review_id',$value->id)->get();
                    $value->review_reply = $reviewReply;
                }
                $reviewDetail = ReviewAndRating::where('deleted_at','')->where('product_id',$product_id)->paginate(5);
                $count = $reviewDetail->count();
                $sumOfRate = $reviewDetail->sum('rate');
                $avarageRate = $reviewDetail;

                $oneStar = $reviewDetail->where('rate','<=','1')->count();
                $twoStar = $reviewDetail->where('rate','<=','2')->count();
                $threeStar = $reviewDetail->where('rate','<=','3')->count();
                $fourStar = $reviewDetail->where('rate','<=','4')->count();
                $fiveStar = $reviewDetail->where('rate','<=','5')->count();

                $oneStar = 100 * $oneStar;
                $twoStar = 100 * $twoStar;
                $threeStar = 100 * $threeStar;
                $fourStar = 100 * $fourStar;
                $fiveStar = 100 * $fiveStar;
                if ($count != 0)
                {
                    $avarageRate = $sumOfRate / $count;

                    $oneStar = $oneStar / $count;
                    $twoStar = $twoStar / $count;
                    $threeStar = $threeStar / $count;
                    $fourStar = $fourStar / $count;
                    $fiveStar = $fiveStar / $count;
                }
                // $value->review_reply = [];
                $reviewArray = array(
                    'total_count' => (string)$count,
                    'avarage_rate' => (string)$avarageRate,
                    'one_star' => (int)$oneStar,
                    'two_star' => (int)$twoStar,
                    'three_star' => (int)$threeStar,
                    'four_star' => (int)$fourStar,
                    'five_star' => (int)$fiveStar,
                    'review_detail' => $reviewCollection
                );
                // $reviewArray->reviewDetail = $reviewData;
                // return $this->sendResponse(200,$reviewArray,'product review detail');
                // return $this->paginatResponse(200, $reviewArray , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, 'product review detail');
            }

            // dd($reviewArray);
            return view('front.productDetails', compact('productDetail', 'bradcumbData', 'similiarProducts', 'userAddress','reviewArray'));
        } else {
            return redirect()->route('home')->with('error', 'Product not found.');
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
        $minPrice = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity')->min('price');
        $maxPrice = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity')->max('price');
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

    public function similiarProducts($subCategoryId, $productId)
    {
        $userId = auth()->user()->id ?? 0;

        $productList = Product::where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity')
            ->where('sub_category_id', $subCategoryId)
            ->where('id', '!=', $productId)
            ->take(10)
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

    public function checkPincode(Request $request)
    {
        // $request->validate([
        //     'pincode' => 'required|digits:6',
        // ]);
        $pincode = $request->pincode;
        $product_color_id = $request->product_color_id ?? 0;
        $product_size_id = $request->product_size_id ?? 0;
        $qty = $request->qty ?? 1;
        if (isset($pincode)) {
            $pincode_data = Pincode::where('deleted_at', '')->where('pincode', $pincode)->where('status', '1')->first();
            if ($pincode_data) {
                $cityData = City::where('deleted_at', '')->where('status', '1')->find($pincode_data->city_id);
                if ($cityData) {
                    $date = date('Y-m-d');
                    $deliveryDays = date('l d M', strtotime($date . ' + ' . $cityData->delivery_days . ' days'));
                    $pincode_data->delivery_days = $deliveryDays;
                    Session::flash('success', 'Product Available for this Pincode');
                    return 1;
                    // return redirect()->back()->with([
                    //     'pincode_data' => $pincode_data, 
                    //     'pincode' => $pincode,
                    //     'product_color_id' => $product_color_id,
                    //     'product_size_id' => $product_size_id,
                    //     'qty' => $qty,
                    //     'success' => 'Product Available for this City'
                    // ]);
                } else {
                    Session::flash('error', 'Product not available for this Pincode');
                    return 0;
                    // return redirect()->back()->with([
                    //     'pincode' => $pincode,
                    //     'product_color_id' => $product_color_id,
                    //     'product_size_id' => $product_size_id,
                    //     'qty' => $qty,
                    //     'error' => 'Product not available for this City'
                    // ]);
                }
            } else {
                Session::flash('error', 'Product not available for this Pincode');
                return 0;
                // return redirect()->back()->with([
                //     'pincode' => $pincode,
                //     'product_color_id' => $product_color_id,
                //     'product_size_id' => $product_size_id,
                //     'qty' => $qty,
                //     'error' => 'Product not available for this City'
                // ]);
            }
        }
        Session::flash('error', 'Please enter pincode');
        return 0;
    }

    public function searchFilterProductList(Request $request)
    {
        $userId = auth()->user()->id ?? 0;

        //Sub Category
        $subCatId = array_filter(explode(",", $request->sub_category_id));

        $query = Product::select('*')->where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity');

        if (!empty($subCatId)) {
            $query->whereIn('sub_category_id', $subCatId);
        }
            
        //Sort
        $sort = trim($request->sort);
        if (!empty($sort)) {
            //What's new
            if ($sort == 1) {
                $query->orderBy('id', 'desc');
            }
            //Price - high to low
            if ($sort == 2) {
                $query->orderByRaw('CAST(price as DECIMAL(10,2)) DESC');
            }
            //Popularity
            if ($sort == 3) {
                $query->orderBy('popularity', 'desc');
            }
            //Price - low to high
            if ($sort == 4) {
                $query->orderByRaw('CAST(price as DECIMAL(10,2)) ASC');
            }
            //Discount
            if ($sort == 5) {
                // $query->orderBy('price','desc');
            }

            //Customer Rating
            if ($sort == 6) {
                $query->orderBy('rating', 'desc');
            }
        }

        //Size
        $size_id = trim($request->size_id);
        if (!empty($size_id)) {
            $query->where('size_id', 'LIKE', "%{$size_id}%");
        }

        //Color
        $color_id = trim($request->color_id);
        if (!empty($color_id)) {
            // $query->where('color_id', 'LIKE', "%{$color_id}%");
            $color_id = array_filter(explode(',', $color_id));
            $query->where(function ($query) use ($color_id) {
                foreach ($color_id as $c_key => $cId) {
                    if ($c_key == 0) {
                        $query->whereRaw("find_in_set('" . $cId . "',color_id)");
                    } else {
                        $query->orWhereRaw("find_in_set('" . $cId . "',color_id)");
                    }
                }
            });
        }

        //Brand
        $brand_id = array_filter(explode(",", $request->brand_id));
        if (!empty($brand_id)) {
            $query->whereIn('brand_id', $brand_id);
        }

        //Price
        $min_price = trim($request->min_price);
        $max_price = trim($request->max_price);
        if ($min_price != '') {
            $query->whereRaw('CAST(price as DECIMAL(10,2)) >= ' . $min_price);
        }
        if ($max_price != '') {
            $query->whereRaw('CAST(price as DECIMAL(10,2)) <= ' . $max_price);
        }

        //Rating
        $rating = trim($request->rating);
        if (!empty($rating)) {
            $ratingFloor = sprintf("%.1f", floor($rating));
            $query->where('rating', '>=', $ratingFloor);
            $rating = sprintf("%.1f", $rating);
            $query->where('rating', '<=', $rating);
        }

        //Search
        $keyword = trim($request->keyword);
        if (!empty($keyword)) {
            $query->where(function ($query) use ($keyword) {
                $query->where('product_name', 'LIKE', "%{$keyword}%");
                $query->orWhere('description', 'LIKE', "%{$keyword}%");

                $query->orWhereHas('super_category', function ($query) use ($keyword) {
                    $query->where('supercategory_name', 'LIKE', "%{$keyword}%");
                });
                $query->orWhereHas('super_sub_category', function ($query) use ($keyword) {
                    $query->where('supersub_cat_name', 'LIKE', "%{$keyword}%");
                });
                $query->orWhereHas('category', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                });
                $query->orWhereHas('sub_category', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                });
                $query->orWhereHas('brand', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                });
            });

            if (!empty($keyword) && $userId != 0) {
                $searchHistory = new SearchHistory;
                $searchHistory->user_id = $userId;
                $searchHistory->keyword = $keyword;
                $searchHistory->deleted_at = '';
                $searchHistory->save();
            }
        }

        // dd($query->toSql());

        //Print Query
        // return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
        //     return is_numeric($binding) ? $binding : "'{$binding}'";
        // })->toArray());

        if (!empty($subCatId)) {

        	$productList = $query->paginate(10);

        }else{

        	$productList = [];
        }

        // return $productList;

        // $productListCollection = $query->paginate(10);
        // $productList = $productListCollection->items();
        // $totalPages = $productListCollection->lastPage();
        // $totalCount = $productListCollection->total();
        // $pageNumber = $productListCollection->currentPage();
        // $nextPage = $productListCollection->nextPageUrl() ? true : false;
        // $prevPage = $productListCollection->previousPageUrl() ? true : false;

        if (!empty($productList)) {
            foreach ($productList as $value) {
                $brand = Brand::find($value->brand_id);
                if ($brand) {
                    $value->brand_name = $brand->name;
                } else {
                    $value->brand_name = '';
                }
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
            }
            
            //Price - low to high
            if ($sort == 4) {
                $sortedResult = $productList->getCollection()->sortBy('discount_price')->values();
                $productList->setCollection($sortedResult);
                // dd($sortedResult);
               
            }
        }

        return view('front.filterProductList', compact('productList'));
    }

    public function searchProduct(Request $request)
    {
        try {
            // $pageNumber = isset($request->page_number) ? $request->page_number : 1;
            // $pageSize = isset($request->page_size) ? $request->page_size : 10;

            $userId = auth()->user()->id ?? 0;

            $query = Product::select('*')->where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity');
            $keyword = trim($request->keyword);
            $searchData = SearchHistory::where('deleted_at', '')->where('user_id', $userId)->orderBy('id', 'DESC')->limit(5)->get();
            // dd($searchHistory);
            if (!empty($keyword)) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('product_name', 'LIKE', "%{$keyword}%");
                    $query->orWhere('description', 'LIKE', "%{$keyword}%");
                    
                    $query->orWhereHas('super_category', function ($query) use ($keyword) {
                        $query->where('supercategory_name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('super_sub_category', function ($query) use ($keyword) {
                        $query->where('supersub_cat_name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('category', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('sub_category', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('brand', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                });
                
                if (!empty($keyword) && $userId!=0) {
                    $searchHistory = new SearchHistory;
                    $searchHistory->user_id = $userId;
                    $searchHistory->keyword = $keyword;
                    $searchHistory->deleted_at = '';
                    $searchHistory->save();
                }

                $productList = $query->paginate(10);

                if (!empty($productList)) {
                    foreach ($productList as $value) {
                        $brand = Brand::find($value->brand_id);
                        if ($brand) {
                            $value->brand_name = $brand->name;
                        } else {
                            $value->brand_name = '';
                        }
                        $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/'.$value->product_image) : '';
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
                    }
                    $data['searchData'] = $searchData;
                    $data['productData'] = $productList;
                    // dd($productList);
                    return view('front.searchProductList', compact('productList'));
                } else {
                    $data['searchData'] = $searchData;
                    $data['productData'] = [];
                    return redirect()->back()->with('error', 'No products found.');
                }
            } else {
                $data['searchData'] = $searchData;
                $data['productData'] = [];
                return redirect()->back()->with('error', 'Please enter keyword.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function colorWiseProduct(Request $request)
    {
        $colorId = $request->color_id ?? '0';
        $productId = $request->product_id ?? '0';

        $productImage = ProductImage::where('deleted_at','')->select('product_image')->where('product_id',$productId)->where('color_id',$colorId)->first();
        $imageUrl = '';
        if ($productImage)
        {
            $imageUrl = $productImage->product_image;
        }
        return response()->json([
            'product_image' => $imageUrl,
        ]);
    }
}
