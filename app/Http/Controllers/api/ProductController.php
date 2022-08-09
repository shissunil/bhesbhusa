<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiBaseController as ApiBaseController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ReviewAndRating;
use App\Models\WishList;
use App\Models\SearchHistory;
use App\Models\Brand;
use App\Models\UserAddress;
use App\Models\Pincode;
use App\Models\City;
use App\Models\Color;
use App\Models\Size;
use App\Models\Banner;
use DB;
use URL;
use stdClass;
class ProductController extends ApiBaseController
{
    public function subCategoryWiseProductList_(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $sort = $request->sort;
            $subCatId = $request->sub_category_id;
            if (isset($sort))
            {
                if ($sort == '1')
                {
                    $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->orderBy('id','DESC')->get();
                }
                elseif($sort == '2')
                {
                    $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->orderBy('price','DESC')->get();
                }
                elseif($sort == '3')
                {
                    $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->get();
                }
                elseif($sort == '4')
                {
                    $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->get();
                }
                elseif($sort == '5')
                {
                    $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->orderBy('price','ASC')->get();
                }
                elseif($sort == '6')
                {
                    $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->get();
                }
            }
            else
            {
                $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->get();
            }
            if (!empty($productList->toArray()))
            {
                foreach($productList as $value)
                {
                    $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/'.$value->product_image) : '';
                    $value->favorite = '0';
                    $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$value->id)->first();
                    if ($checkFav)
                    {
                        $value->favorite = '1';
                    }
                    $value->discount_price = '';
                    $value->discount_percentage = '';

                    $rating = ReviewAndRating::where('deleted_at','')->where('product_id',$value->id)->sum('rate');
                    $count = ReviewAndRating::where('deleted_at','')->where('product_id',$value->id)->count();
                    if ($count != 0)
                    {
                        $rating = $rating / $count;
                    }
                    $value->rating = sprintf("%.1f",$rating);
                }
                // $data['productList'] = $productList;
                return $this->sendResponse(200,$productList,'Product List');
            }
            else
            {
                // $data['productList'] = [];
                return $this->sendResponse(201,$productList,'Product not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }
    
    public function subCategoryWiseProductList(Request $request)
    {
        try
        {
            $userData = auth('sanctum')->user();
            $userId = $userData->id??0;
            $subCatId = $request->sub_category_id;
            
            $pageNumber = isset($request->page_number) ? $request->page_number : 1;
            $pageSize = isset($request->page_size) ? $request->page_size : 10; 
            
            $productListCollection = Product::where('deleted_at','')->where('product_status','1')->whereRaw('quantity > 0')
            ->where('sub_category_id',$subCatId)
            ->paginate($pageSize,'*','page',$pageNumber);
            
            $productList = $productListCollection->items(); 
            
            $totalPages = $productListCollection->lastPage();
            $totalCount = $productListCollection->total();
            $pageNumber = $productListCollection->currentPage();
            $nextPage = $productListCollection->nextPageUrl()?true:false;
            $prevPage = $productListCollection->previousPageUrl()?true:false;
        
            // return $productList;
            
            // if ($pageNumber == '1')
            // {
            //     $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->limit($pageSize)->get();
            // }
            // else
            // {
            //     $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->offset($pageSize)->limit($pageSize)->get();
            // }
            
            if (!empty($productList))
            {
                // $totalPage = 
                foreach($productList as $value)
                {
                    $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/'.$value->product_image) : '';
                    $value->favorite = '0';
                    $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$value->id)->first();
                    if ($checkFav)
                    {
                        $value->favorite = '1';
                    }
                    // $value->discount_percentage = '';
                    $value->rating = sprintf("%.1f",$value->rating);

                    $value->discount_price = '';
                    $price = $value->price;
                    $value->price = $value->price . ' NR';
                    if ($value->discount != '')
                    {
                        $discount = $value->discount;
                        $value->discount = $discount . ' % off';
                        $value->discount_price = $price * $discount / 100;
                        $value->discount_price = round($value->discount_price);
                        $value->discount_price = (string)($price - $value->discount_price);
                        $value->discount_price = $value->discount_price . ' NR';
                    }

                    $colorIds = $value->color_id;
                    $colorIds = explode(',',$colorIds);
                    $value->colorData = [];
                    if ($colorIds)
                    {
                        $colorData = Color::whereIn('id',$colorIds)->get();
                    }
                    $sizeIds = $value->size_id;
                    $sizeIds = explode(',',$sizeIds);
                    $value->sizeData = [];
                    if ($sizeIds)
                    {
                        $sizeData = Size::whereIn('id',$sizeIds)->get();
                    }

                    $value->sizeData = $sizeData;
                    $value->colorData = $colorData;
                }
                return $this->paginatResponse(200, $productList , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, 'Product List');
            }
            else
            {
                // $data['productList'] = [];
                return $this->sendResponse(201,$productList,'Product not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }
    
    public function productDetail(Request $request)
    {
        try
        {
            // $userData = $request->user();
            $userData = auth('sanctum')->user();
            $userId = $userData->id??0;

            $productId = $request->product_id;
            $productDetail = Product::where('deleted_at','')->find($productId);
            if ($productDetail)
            {
                $quantity = (int)$productDetail->quantity;
                $usedQuantity = (int)$productDetail->used_quantity;
                $productDetail->quantity = (string)($quantity - $usedQuantity);
                $productDetail->favorite = '0';
                $productDetail->delivery_days = '';
                $brand = Brand::find($productDetail->brand_id);
                if ($brand)
                {
                    $productDetail->brand_name = $brand->name;
                }
                else
                {
                    $productDetail->brand_name = '';
                }
                if ($userData)
                {
                    $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productDetail->id)->first();
                    if ($checkFav)
                    {
                        $productDetail->favorite = '1';
                    }

                    $chekDefaultAddress = UserAddress::where('deleted_at','')->where('user_id',$userId)->where('is_default','1')->first();
                    if ($chekDefaultAddress)
                    {
                        $pincode = Pincode::where('deleted_at','')->where('pincode',$chekDefaultAddress->pincode)->where('status','1')->first();
                        if ($pincode)
                        {
                            $cityData = City::where('deleted_at','')->where('status','1')->find($pincode->city_id);
                            if ($cityData)
                            {
                                $date = date('Y-m-d'); 
                                $deliveryDays = date('l d M', strtotime($date. ' + '.$cityData->delivery_days.' days')); 
                                
                                $productDetail->delivery_days = $deliveryDays;
                            }
                        }
                    }
                }
                // $productDetail->discount_price = '';
                // $productDetail->discount_percentage = '';
                $productDetail->rating = sprintf("%.1f",$productDetail->rating);
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
                if ($productDetail->discount != '')
                {
                    $discount = $productDetail->discount;
                    $productDetail->discount = $discount . ' % off';
                    $productDetail->discount_price = $price * $discount / 100;
                    $productDetail->discount_price = round($productDetail->discount_price);
                    $productDetail->discount_price = (string)($price - $productDetail->discount_price);
                    $productDetail->discount_price = $productDetail->discount_price . ' NR';
                }
                $productDetail->product_image = (!empty($productDetail->product_image)) ? asset('uploads/product/'.$productDetail->product_image) : '';
                $productImage = ProductImage::where('deleted_at','')->where('product_id',$productDetail->id)->get();
                if (!empty($productImage->toArray()))
                {
                    foreach($productImage as $value)
                    {
                        $colorData = Color::find($value->color_id);
                        $sizeData = Size::find($value->size_id);
                        $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/'.$value->product_image) : '';
                        $value->colorData = new stdClass;
                        if ($colorData)
                        {
                            $value->colorData = $colorData;
                        }
                        $value->sizeData = new stdClass;
                        if ($sizeData)
                        {
                            $value->sizeData = $sizeData;
                        }
                    }
                }
                $productDetail->share_link = URL::to('/api/productDetail');
                $productDetail->product_images = $productImage;
                $colorIds = $productDetail->color_id;
                $colorIds = explode(',',$colorIds);
                $productDetail->colorData = [];
                if ($colorIds)
                {
                    $colorData = Color::whereIn('id',$colorIds)->get();
                }
                $sizeIds = $productDetail->size_id;
                $sizeIds = explode(',',$sizeIds);
                $productDetail->sizeData = [];
                if ($sizeIds)
                {
                    $sizeData = Size::whereIn('id',$sizeIds)->get();
                }

                $productDetail->sizeData = $sizeData;
                $productDetail->colorData = $colorData;
                // $data['productDetail'] = $productDetail;
                return $this->sendResponse(200,$productDetail,'product Detail');
            }
            else
            {
                return $this->sendError(201, 'product not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    
    public function searchFilterProductList(Request $request)
    {
        try
        {
            $bannerId = $request->banner_id ?? '';
            if ($bannerId == 'null' || $bannerId == null)
            {
                $bannerId = '';
            }
            $pageNumber = isset($request->page_number) ? $request->page_number : 1;
            $pageSize = isset($request->page_size) ? $request->page_size : 10;
            
            $userData = auth('sanctum')->user();
            $userId = $userData->id??0;
            
            //Sub Category
            $subCatId = array_filter(explode(",",$request->sub_category_id));
            $productId = '';
            if (!empty($bannerId))
            {
                $selectBanner = Banner::where('deleted_at','')->where('status','1')->find($bannerId);
                if ($selectBanner)
                {
                    $productId = $selectBanner->product_id;
                }

            }
            $productId = explode(',',$productId);
            $query = Product::select('*')->where('deleted_at','')->where('product_status','1')->whereRaw('quantity > 0');
            if (count($subCatId) > 0)
            {
                $query->whereIn('sub_category_id',$subCatId);
            }
            if ($bannerId)
            {
                $query->whereIn('id',$productId);
            }
            //Sort
            $sort = trim($request->sort);
            if(!empty($sort)){
                //What's new
                if($sort==1){
                    $query->orderBy('id','desc');
                }
                //Price - high to low
                if($sort==2){
                    $query->orderByRaw('CAST(price as DECIMAL(10,2)) DESC');
                }
                //Popularity
                if($sort==3){
                    $query->orderBy('popularity','desc');
                }
                //Price - low to high
                if($sort==4){
                    $query->orderByRaw('CAST(price as DECIMAL(10,2)) ASC');
                }
                //Discount
                if($sort==5){
                    // $query->orderBy('price','desc');
                }
               
                //Customer Rating
                if($sort==6){
                    $query->orderBy('rating','desc');
                }
            }
            
            //Size
            $size_id = trim($request->size_id);
            if(!empty($size_id)){
                $query->where('size_id', 'LIKE', "%{$size_id}%");
            }
            
            //Color
            $color_id = trim($request->color_id);
            if(!empty($color_id)){
                $query->where('color_id', 'LIKE', "%{$color_id}%");
            }
            
            //Brand
            $brand_id = array_filter(explode(",",$request->brand_id));
            if(!empty($brand_id)){
                $query->whereIn('brand_id',$brand_id);
            }
            
            //Price
            $min_price = trim($request->min_price);
            $max_price = trim($request->max_price);
            if($min_price!=''){
                $query->whereRaw('CAST(price as DECIMAL(10,2)) >= '.$min_price);
            }
            if ($max_price != '')
            {
                $query->whereRaw('CAST(price as DECIMAL(10,2)) <= '.$max_price);
            }
            
            //Rating
            $rating = trim($request->rating);
            if(!empty($rating)){
                $ratingFloor = sprintf("%.1f",floor($rating));
                $query->where('rating', '>=',$ratingFloor);
                $rating = sprintf("%.1f",$rating);
                $query->where('rating', '<=',$rating);
            }
            
            //Search
            $keyword = trim($request->keyword);
            if ($keyword == 'null' || $keyword == null)
            {
                $keyword = '';
            }
            if(!empty($keyword)){
                
                $query->where(function ($query) use($keyword){
                    
                    $query->where('product_name', 'LIKE', "%{$keyword}%");
                    $query->orWhere('description', 'LIKE', "%{$keyword}%");
                    
                    $query->orWhereHas('super_category', function( $query ) use ( $keyword ){
                      $query->where('supercategory_name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('super_sub_category', function( $query ) use ( $keyword ){
                      $query->where('supersub_cat_name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('category', function( $query ) use ( $keyword ){
                      $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('sub_category', function( $query ) use ( $keyword ){
                      $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('brand', function( $query ) use ( $keyword ){
                      $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                
                });
                
                if(!empty($keyword) && $userId!=0){
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
            
            $productListCollection = $query->paginate($pageSize,'*','page',$pageNumber);
            
            $productList = $productListCollection->items(); 

            // return $productList;
            
            $totalPages = $productListCollection->lastPage();
            $totalCount = $productListCollection->total();
            $pageNumber = $productListCollection->currentPage();
            $nextPage = $productListCollection->nextPageUrl()?true:false;
            $prevPage = $productListCollection->previousPageUrl()?true:false;
        
            // return $productList;
            
            // if ($pageNumber == '1')
            // {
            //     $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->limit($pageSize)->get();
            // }
            // else
            // {
            //     $productList = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCatId)->offset($pageSize)->limit($pageSize)->get();
            // }
            
            if (!empty($productList))
            {
                // $totalPage = 
                foreach($productList as $value)
                {
                    $brand = Brand::find($value->brand_id);
                    if ($brand)
                    {
                        $value->brand_name = $brand->name;
                    }
                    else
                    {
                        $value->brand_name = '';
                    }
                    $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/'.$value->product_image) : '';
                    $value->favorite = '0';
                    // dd($userId);
                    $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$value->id)->first();
                    if ($checkFav)
                    {
                        $value->favorite = '1';
                    }
                    // $value->discount_price = '';
                    // $value->discount_percentage = '';

                    // $rating = ReviewAndRating::where('deleted_at','')->where('product_id',$value->id)->sum('rate');
                    // $count = ReviewAndRating::where('deleted_at','')->where('product_id',$value->id)->count();
                    // if ($count != 0)
                    // {
                    //     $rating = $rating / $count;
                    // }
                    $value->rating = sprintf("%.1f",$value->rating);
                    
                    $value->discount_price = '';
                    $price = $value->price;
                    $value->price = $value->price . ' NR';
                    if ($value->discount != '')
                    {
                        $discount = $value->discount;
                        $value->discount = $discount . ' % off';
                        $value->discount_price = $price * $discount / 100;
                        $value->discount_price = round($value->discount_price);
                        $value->discount_price = (string)($price - $value->discount_price);
                        $value->discount_price = $value->discount_price . ' NR';
                    }


                    $colorIds = $value->color_id;
                    $colorIds = explode(',',$colorIds);
                    $value->colorData = [];
                    if ($colorIds)
                    {
                        $colorData = Color::whereIn('id',$colorIds)->get();
                    }
                    $sizeIds = $value->size_id;
                    $sizeIds = explode(',',$sizeIds);
                    $value->sizeData = [];
                    if ($sizeIds)
                    {
                        $sizeData = Size::whereIn('id',$sizeIds)->get();
                    }

                    $value->sizeData = $sizeData;
                    $value->colorData = $colorData;
                }
                
                // return $this->sendResponse(200,$productList,'Product List');
                
                return $this->paginatResponse(200, $productList , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, 'Product List');
            }
            else
            {
                // $data['productList'] = [];
                return $this->sendResponse(201,$productList,'Product not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }
    public function searchProduct(Request $request)
    {
        try
        {
            $pageNumber = isset($request->page_number) ? $request->page_number : 1;
            $pageSize = isset($request->page_size) ? $request->page_size : 10;

            $userData = auth('sanctum')->user();
            $userId = $userData->id??0;

            $query = Product::select('*')->where('deleted_at','')->where('product_status','1')->whereRaw('quantity > 0');
            $keyword = trim($request->keyword);
            $searchData = SearchHistory::where('deleted_at','')->where('user_id',$userId)->orderBy('id','DESC')->limit(5)->get();
            // dd($searchHistory);
            if(!empty($keyword))
            {
                
                $query->where(function ($query) use($keyword){
                    
                    $query->where('product_name', 'LIKE', "%{$keyword}%");
                    $query->orWhere('description', 'LIKE', "%{$keyword}%");
                    
                    $query->orWhereHas('super_category', function( $query ) use ( $keyword ){
                      $query->where('supercategory_name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('super_sub_category', function( $query ) use ( $keyword ){
                      $query->where('supersub_cat_name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('category', function( $query ) use ( $keyword ){
                      $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('sub_category', function( $query ) use ( $keyword ){
                      $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                    $query->orWhereHas('brand', function( $query ) use ( $keyword ){
                      $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                
                });
                
                if(!empty($keyword) && $userId!=0){

                    $searchHistory = new SearchHistory;
                    $searchHistory->user_id = $userId;
                    $searchHistory->keyword = $keyword;
                    $searchHistory->deleted_at = '';
                    $searchHistory->save();
                }

                $productListCollection = $query->paginate($pageSize,'*','page',$pageNumber);
            
                $productList = $productListCollection->items(); 

                // return $productList;
                
                $totalPages = $productListCollection->lastPage();
                $totalCount = $productListCollection->total();
                $pageNumber = $productListCollection->currentPage();
                $nextPage = $productListCollection->nextPageUrl()?true:false;
                $prevPage = $productListCollection->previousPageUrl()?true:false;

                if (!empty($productList))
                {
                    foreach($productList as $value)
                    {
                        $brand = Brand::find($value->brand_id);
                        if ($brand)
                        {
                            $value->brand_name = $brand->name;
                        }
                        else
                        {
                            $value->brand_name = '';
                        }
                        $value->product_image = (!empty($value->product_image)) ? asset('uploads/product/'.$value->product_image) : '';
                        $value->favorite = '0';
                        $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$value->id)->first();
                        if ($checkFav)
                        {
                            $value->favorite = '1';
                        }
                        $value->rating = sprintf("%.1f",$value->rating);
                        $value->discount_price = '';
                        $price = $value->price;
                        $value->price = $value->price . ' NR';
                        if ($value->discount != '')
                        {
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
                    return $this->paginatResponse(200, $data , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, 'Product List');
                }
                else
                {
                    $data['searchData'] = $searchData;
                    $data['productData'] = [];
                    return $this->sendResponse(200,$data,'product data not found');
                    // return $this->sendResponse(201,$productList,'Product not found');
                }
            }
            else
            {
                $data['searchData'] = $searchData;
                $data['productData'] = [];
                return $this->sendResponse(200,$data,'search data');
            }

        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }
}
