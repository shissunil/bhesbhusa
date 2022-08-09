<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiBaseController as ApiBaseController;
use Illuminate\Http\Request;
use App\Models\CmsMaster;
use App\Models\Banner;
use App\Models\WishList;
use App\Models\SuperCategory;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\TicketReason;
use App\Models\Color;
use App\Models\Brand;
use App\Models\Size;
use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Cart;
use App\Models\User;
use stdClass;


class HomeApiController extends ApiBaseController
{
    public function cmsList(Request $request)
    {
        try
        {
            $pageId = $request->page_id;
            $appType = $request->app_type;
            if (isset($appType))
            {
                $supportData = '';
                $cmsMaster = CmsMaster::where('deleted_at','')->where('status','1');
                if ($appType == '1')
                {
                    $cmsMaster->where('app_type','1')->whereIn('cms_page',[0,1,2,3,4,5]);
                }
                if ($appType == '2')
                {
                    $cmsMaster->where('app_type','2')->whereIn('cms_page',[6,7,8]);
                    $supportData = Setting::where('deleted_at','')->select('support_number')->first();
                }
                if ($appType != '1' && $appType != '2')
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'Please Enter Valid data');
                }
                $cmsMaster = $cmsMaster->get();
                if (!empty($cmsMaster->toArray()))
                {
                    if ($appType == '1')
                    {
                        return $this->sendResponse(200, $cmsMaster,'Cms Data');
                    }
                    if ($appType == '2')
                    {
                        $data['cmsMaster'] = $cmsMaster;
                        $data['supportData'] = $supportData;
                        return $this->sendResponse(200,$data,'Cms Data');
                    }
                }
                else
                {
                    // $cmsMaster = new stdClass;
                    $data['cmsMaster'] = $cmsMaster;
                    $data['supportData'] = $supportData;
                    return $this->sendResponse(201, $data,'record not found');
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Please fill the required field');
            }
        }
        catch(\Exception $e)
        {
       
            return $this->sendError(201, $e->getMessage());
        }
    } 
	public function bannerList(Request $request)
	{
		try 
		{
			$bannerList = Banner::where('deleted_at','')->get();
			if(!empty($bannerList))
			{
	            foreach($bannerList as $banner)
	            {
	                $banner->banner_image = (!empty($banner->banner_image)) ? asset('uploads/banners/'.$banner->banner_image) : '';

	            }
                $data['bannerList'] = $bannerList;
                return $this->sendResponse(200, $bannerList,'Banner List...');
        	}
        	else
        	{
        		$data['bannerList'] = [];
                return $this->sendResponse(200, $bannerList,'Data Not Found...!');
        	}
		} 
		catch (Exception $e) 
		{
            return $this->sendError(201,$e->getMessage());
		}
	}
    public function saveWish(Request $request)
    {
        try 
        {
            $userData = $request->user();
            $userId = $userData->id;
            $productId = $request->product_id;
            $isFavroite = $request->is_favroite;
            if (isset($productId) && isset($isFavroite))
            {
                $chekProduct = Product::where('deleted_at','')->find($productId);
                if ($chekProduct)
                {
                    $checkFav = WishList::where('user_id',$userId)->where('product_id',$productId)->first();
                    if ($checkFav)
                    {
                        if ($isFavroite == '0')
                        {
                            if ($checkFav->deleted_at == '')
                            {
                                $checkFav->deleted_at = config('constant.CURRENT_DATETIME');
                                $checkFav->save();
                                $checkFav = new stdClass;
                                return $this->sendResponse(200,$checkFav, "Remove Wishlist Successfully...!");
                            }
                            else
                            {
                                $checkFav = new stdClass;
                                return $this->sendResponse(201,$checkFav,'Favroite item not found');
                            }
                        }
                        elseif ($isFavroite == '1')
                        {
                            if ($checkFav->deleted_at == '')
                            {
                                $checkFav = new stdClass;
                                return $this->sendResponse(201, $checkFav, 'prodcut already added');
                            }
                            else
                            {
                                $checkFav->deleted_at = '';
                                $checkFav->save();
                                return $this->sendResponse(200,$checkFav, "Save Wishlist Successfully...!");
                            }
                        }
                    }
                    else
                    {
                        if ($isFavroite == '0')
                        {
                            $checkFav = new stdClass;
                            return $this->sendResponse(201,$checkFav,'Favroite item not found');
                        }
                        if ($isFavroite == '1')
                        {
                            $saveWishList = new WishList;
                            $saveWishList->user_id = $userId;
                            $saveWishList->product_id = $request->product_id;
                            $saveWishList->deleted_at = '';
                           
                            $saveWishList->save();
                            return $this->sendResponse(200,$saveWishList, "Save Wishlist Successfully...!");
                        }
                    }
                    // if ($isFavroite == '0')
                    // {
                    //     $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productId)->first();
                    //     if ($checkFav)
                    //     {
                    //         $checkFav->deleted_at = config('constant.CURRENT_DATETIME');
                    //         $checkFav->save();
                    //     }
                    //     else
                    //     {
                    //         $checkFav = new stdClass;
                    //         return $this->sendResponse(201,$checkFav,'Favroite item not found');
                    //     }
                    //     $checkFav = new stdClass;
                    //     return $this->sendResponse(200,$checkFav, "Remove Wishlist Successfully...!");
                    // }
                    // elseif ($isFavroite == '1')
                    // {
                    //     $checkFav = WishList::where('deleted_at','')->where('user_id',$userId)->where('product_id',$productId)->first();
                    //     if ($checkFav)
                    //     {
                    //         $checkFav = new stdClass;
                    //         return $this->sendResponse(201, $checkFav, 'prodcut already added');
                    //     }
                    //     else
                    //     {
                    //         $saveWishList = new WishList;
                    //         $saveWishList->user_id = $userId;
                    //         $saveWishList->product_id = $request->product_id;
                    //         $saveWishList->deleted_at = '';
                           
                    //         $saveWishList->save();
                    //         return $this->sendResponse(200,$saveWishList, "Save Wishlist Successfully...!");
                    //     }
                    // }
                }
                else
                {
                    $chekProduct = new stdClass;
                    return $this->sendResponse(201,$chekProduct,'product not found');
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'fill all the required field');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());  
        }
    }
    public function removeWish(Request $request)
    {
        try
        {
            $wishListId = $request->wishlist_id;
            $wishList = WishList::where('deleted_at','')->find($wishListId);
            if ($wishList)
            {
                $wishList->deleted_at = config('constant.CURRENT_DATETIME');
                $wishList->save();

                return $this->sendResponse(200,$wishList,'Item Removed Successfully');
            }
            else
            {
                $wishList = new stdClass;
                return $this->sendResponse(201, $wishList, 'Data not found');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());  
        }
    }
    public function wishList(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $pageNumber = isset($request->page_number) ? $request->page_number : 1;
            $pageSize = isset($request->page_size) ? $request->page_size : 10;

            $wishListCollection = WishList::where('deleted_at','')->where('user_id',$userId)->paginate($pageSize,'*','page',$pageNumber);
            $wishList = $wishListCollection->items();

            $totalPages = $wishListCollection->lastPage();
            $totalCount = $wishListCollection->total();
            $pageNumber = $wishListCollection->currentPage();
            $nextPage = $wishListCollection->nextPageUrl()?true:false;
            $prevPage = $wishListCollection->previousPageUrl()?true:false;
            if (!empty($wishList))
            {
                foreach($wishList as $value)
                {
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
                // return $this->sendResponse(200, $wishList, 'Wish List');
                return $this->paginatResponse(200, $wishList , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, 'Wish List');
            }
            else
            {
                $wishList = new stdClass;
                return $this->sendResponse(201, $wishList, 'record not found');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());  
        }
    }
    public function homeData()
    {
        try 
        {
            $userData = auth('sanctum')->user();
            $userId = $userData->id??0;
            $superCategoryList = SuperCategory::where('deleted_at','')->where('supercategory_status','1')->get();
            $headerBannerList = Banner::where('banner_location','1')->where('banner_type','1')->where('status','1')->where('deleted_at','')->get();
            $footerBannerList = Banner::where('banner_location','2')->where('banner_type','1')->where('status','1')->where('deleted_at','')->get();
            $notificationCount = Notification::where('deleted_at','')->where('is_read','0')->where('receiver_type','user')->where('receiver_id',$userId)->where('created_at', '>=', now()->subDays(30)->endOfDay())->count();

            $bestDeals = Banner::where('deleted_at','')->where('banner_type','2')->where('status','1')->get();
            $newArrivals = Banner::where('deleted_at','')->where('banner_type','3')->where('status','1')->get();
            $bbExclusive = Banner::where('deleted_at','')->where('banner_type','4')->where('status','1')->get();
            $trendingInMenList = Banner::where('deleted_at','')->where('banner_type','5')->where('status','1')->get();
            $trendingInWomenList = Banner::where('deleted_at','')->where('banner_type','6')->where('status','1')->get();
            
            foreach($superCategoryList as $superCategory)
            {
                $superCategory->supercategory_image = (!empty($superCategory->supercategory_image)) ? asset('uploads/super_category/'.$superCategory->supercategory_image) : '';
            }

            foreach($headerBannerList as $headerBanner)
            {
                $headerBanner->banner_image = (!empty($headerBanner->image)) ? asset('uploads/banners/'.$headerBanner->image) : '';
            }

            foreach($footerBannerList as $footerBanner)
            {
                $footerBanner->banner_image = (!empty($footerBanner->image)) ? asset('uploads/banners/'.$footerBanner->image) : '';
            }

            foreach($bestDeals as $bestDeal)
            {
                $bestDeal->banner_image = (!empty($bestDeal->image)) ? asset('uploads/banners/'.$bestDeal->image) : '';
            }
            foreach($newArrivals as $newArrival)
            {
                $newArrival->banner_image = (!empty($newArrival->image)) ? asset('uploads/banners/'.$newArrival->image) : '';
            }
            foreach($bbExclusive as $exclusive)
            {
                $exclusive->banner_image = (!empty($exclusive->image)) ? asset('uploads/banners/'.$exclusive->image) : '';
            }
            foreach($trendingInMenList as $trendingInMen)
            {
                $trendingInMen->banner_image = (!empty($trendingInMen->image)) ? asset('uploads/banners/'.$trendingInMen->image) : '';
            }
            foreach($trendingInWomenList as $trendingInWomen)
            {
                $trendingInWomen->banner_image = (!empty($trendingInWomen->image)) ? asset('uploads/banners/'.$trendingInWomen->image) : '';
            }
            $data['superCategoryList'] = $superCategoryList;
            $data['headerBannerList'] = $headerBannerList;
            $data['footerBannerList'] = $footerBannerList;

            $data['bestDeals'] = $bestDeals;
            $data['newArrivals'] = $newArrivals;
            $data['bbExclusive'] = $bbExclusive;
            $data['trendingInMenList'] = $trendingInMenList;
            $data['trendingInWomenList'] = $trendingInWomenList;

            $data['notificationCount'] = $notificationCount;
            return $this->sendResponse(200, $data, 'Home Data ');
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }   
    }
    public function userNotificationList(Request $request)
    {
        try
        {
            // dd(now()->subDays(30)->endOfDay());
            $userData = $request->user();
            $userId = $userData->id;
            $notificationList = Notification::where('deleted_at','')->where('is_read','0')->where('receiver_type','user')->where('receiver_id',$userId)->where('created_at', '>', now()->subDays(30)->endOfDay())->orderBy('id','desc')->get();
            if (!empty($notificationList->toArray()))
            {
                // $notificationList->created_at->diffForHumans();
                foreach($notificationList as $notification)
                {
                    $orderStatus = $notification->order_status;
                    if ($orderStatus == '1')
                    {
                        $notification->order_status = 'Pending';
                    }
                    elseif($orderStatus == '2')
                    {
                        $notification->order_status = 'Assigned';
                    }
                    elseif($orderStatus == '3')
                    {
                        $notification->order_status = 'Cancelled';
                    }
                    elseif($orderStatus == '4')
                    {
                        $notification->order_status = 'Delivered';
                    }
                    elseif($orderStatus == '5')
                    {
                        $notification->order_status = 'Returned';
                    }
                    elseif($orderStatus == '6')
                    {
                        $notification->order_status = 'Out For Service';
                    }
                    $notification->date = $notification->created_at->diffForHumans();
                }
                $checkNotification = Notification::where('deleted_at','')->where('receiver_type','user')->where('receiver_id',$userId)->where('is_read','0')->get();
                if (!empty($checkNotification->toArray()))
                {
                    // $deleteNotification = Notification::where('deleted_at','')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('is_read','0')->update(['is_read'=>'1']);
                    foreach($checkNotification as $value)
                    {
                        $value->is_read = '1';
                        $value->save();
                    }
                }
                return $this->sendResponse(200,$notificationList,'notification list');
            }
            else
            {
                $data = [];
                return $this->sendResponse(201,$data,'record not found');
            }
        }
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }
    }
    public function deleteNotification(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $notificationId = $request->notification_id;
            if (isset($notificationId))
            {
                $chekNotification = Notification::where('deleted_at','')->where('receiver_type','user')->where('receiver_id',$userId)->where('is_read','0')->find($notificationId);
                if ($chekNotification)
                {
                    $chekNotification->is_read = '1';
                    $chekNotification->save();
                    return $this->sendResponse(200,$chekNotification,'notification deleted');
                }
                else
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'data not found');
                }
            }
            else
            {
                $checkNotification = Notification::where('deleted_at','')->where('receiver_type','user')->where('receiver_id',$userId)->where('is_read','0')->get();
                if (!empty($checkNotification->toArray()))
                {
                    $deleteNotification = Notification::where('deleted_at','')->where('receiver_type','user')->where('receiver_id',$userId)->where('is_read','0')->update(['is_read'=>'1']);

                    return $this->sendResponse(200,$checkNotification,'notification cleared');
                }
                else
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'record not found');
                }
                // $data = new stdClass;
                // return $this->sendResponse(201,$data,'Please fill the required field');
            }
        }   
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }
    }
    // public function clearAllNotification(Request $request)
    // {
    //     try
    //     {
    //         $userData = $request->user();
    //         $userId = $userData->id;
    //         $checkNotification = Notification::where('deleted_at','')->where('receiver_type','user')->where('receiver_id',$userId)->where('is_read','0')->get();
    //         if (!empty($checkNotification->toArray()))
    //         {
    //             $deleteNotification = Notification::where('deleted_at','')->where('receiver_type','user')->where('receiver_id',$userId)->where('is_read','0')->update(['is_read'=>'1']);
    //             return $this->sendResponse(200,$deleteNotification,'notification cleared');
    //         }
    //         else
    //         {
    //             $data = new stdClass;
    //             return $this->sendResponse(201,$data,'record not found');
    //         }
    //     }
    //     catch (Exception $e) 
    //     {
    //         return $this->sendError(201, $e->getMessage());  
    //     }
    // }
    public function deliveryNotificationList(Request $request)
    {
        try
        {
            $deliveryData = auth('sanctum')->user();
            $deliveryId = $deliveryData->id;
            // $status = $request->status;
            $notificationList = Notification::where('deleted_at','')->where('is_read','0')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('created_at', '>', now()->subDays(30)->endOfDay())->orderBy('id','desc')->get();
            // $notificationList = $notificationList->get();
            if (!empty($notificationList->toArray()))
            {
                // $notificationList->created_at->diffForHumans();
                foreach($notificationList as $notification)
                {
                    $categoryType = '';
                    if ($notification->category_type == 'Order Assignee')
                    {
                        $categoryType = '3';
                    }
                    if ($notification->category_type == 'Order Delivered')
                    {
                        $categoryType = '4';
                    }
                    $notification->category_type_id = $categoryType;
                    $notification->date = $notification->created_at->diffForHumans();
                }
                $checkNotification = Notification::where('deleted_at','')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('is_read','0')->get();
                if (!empty($checkNotification->toArray()))
                {
                    // $deleteNotification = Notification::where('deleted_at','')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('is_read','0')->update(['is_read'=>'1']);
                    foreach($checkNotification as $value)
                    {
                        $value->is_read = '1';
                        $value->save();
                    }
                }
                return $this->sendResponse(200,$notificationList,'notification list');
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'record not found');
            }
        }
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }
    }
    public function deleteDeliveryNotification(Request $request)
    {
        try
        {
            $deliveryData = auth('sanctum')->user();
            $deliveryId = $deliveryData->id;

            $notificationId = $request->notification_id;
            if (isset($notificationId))
            {
                $chekNotification = Notification::where('deleted_at','')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('is_read','0')->find($notificationId);
                if ($chekNotification)
                {
                    $chekNotification->is_read = '1';
                    $chekNotification->save();
                    return $this->sendResponse(200,$chekNotification,'notification deleted');
                }
                else
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'data not found');
                }
            }
            else
            {
                // $data = new stdClass;
                // return $this->sendResponse(201,$data,'Please fill the required field');
                $checkNotification = Notification::where('deleted_at','')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('is_read','0')->get();
                if (!empty($checkNotification->toArray()))
                {
                    // $deleteNotification = Notification::where('deleted_at','')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('is_read','0')->update(['is_read'=>'1']);
                    foreach($checkNotification as $value)
                    {
                        $value->is_read = '1';
                        $value->save();
                    }
                    return $this->sendResponse(200,$deleteNotification,'notification cleared');
                }
                else
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'record not found');
                }
            }
        }   
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }
    }
    // public function clearAllDelivereyNotification(Request $request)
    // {
    //     try
    //     {
    //         $deliveryData = auth('sanctum')->user();
    //         $deliveryId = $deliveryData->id;
            
    //         $checkNotification = Notification::where('deleted_at','')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('is_read','0')->get();
    //         if (!empty($checkNotification->toArray()))
    //         {
    //             $deleteNotification = Notification::where('deleted_at','')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('is_read','0')->update(['is_read'=>'1']);
    //             return $this->sendResponse(200,$deleteNotification,'notification cleared');
    //         }
    //         else
    //         {
    //             $data = new stdClass;
    //             return $this->sendResponse(201,$data,'record not found');
    //         }
    //     }
    //     catch (Exception $e) 
    //     {
    //         return $this->sendError(201, $e->getMessage());  
    //     }
    // }
    public function ticketReasonList(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $type = $request->type; 
            $orderId = $request->order_id;
            if (isset($orderId) && isset($type))
            {
                $reasonList = TicketReason::where('deleted_at','')->where('reason_for',$type)->where('status','1')->get();
                if (!empty($reasonList->toArray()))
                {
                    $chekOrder = Order::where('deleted_at','')->where('user_id',$userId)->find($orderId);
                    if ($chekOrder)
                    {
                        $orderItem = OrderItem::where('deleted_at','')->where('order_id',$chekOrder->id)->get();
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
                            $chekOrder->order_item = $orderItem;                   
                        }
                        $refundAmount = $chekOrder->total_amount;
                        $data['orderData'] = $chekOrder;
                        $data['reasonList'] = $reasonList;
                        $data['refund_detail'] = $refundAmount . ' NR';
                        return $this->sendResponse(200, $data, 'ticket reason list');
                    }
                    else
                    {
                        $data = new stdClass;
                        return $this->sendResponse(201, $data, 'order not found');
                    }
                }
                else
                {
                    $reasonList = new stdClass;
                    return $this->sendResponse(201, $reasonList, 'record not found');
                }
                
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data, 'Please fill all the required field');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function filterList(Request $request)
    {
        try
        {
            $bannerId = $request->banner_id ?? '';
            $colorIds = '';
            $brandIds = '';
            $subCategoryIds = '';
            $sizeIds = '';
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
            $selectProduct = Product::where('deleted_at','')->where('product_status','1')->whereRaw('quantity > 0')->whereIn('id',$productId)->get();
            if (count($selectProduct) > 0)
            {
                foreach($selectProduct as $product)
                {
                    $colorIds .= $product->color_id.',';
                    $brandIds .= $product->brand_id.',';
                    $subCategoryIds .= $product->sub_category_id.',';
                    $sizeIds .= $product->size_id.',';
                }
            }
            $colorIds = explode(',',$colorIds);
            $brandIds = explode(',',$brandIds);
            $subCategoryIds = explode(',',$subCategoryIds);
            $sizeIds = explode(',',$sizeIds);
            $colorList = Color::where('deleted_at','')->where('status','1');
            if ($bannerId)
            {
                $colorList->whereIn('id',$colorIds);
            }
            $colorList = $colorList->get();
            if ($colorList)
            {
                foreach($colorList as $color)
                {
                    $colorId = $color->id;
                    // $productCount = Product::where('deleted_at','')->where('product_status','1')->whereRaw('FIND_IN_SET('.$colorId.',color_id)')->count();
                    // $color->count = $productCount;
                    unset($color->status);
                    unset($color->created_at);
                    unset($color->updated_at);
                    unset($color->deleted_at);
                }
                // return $this->sendResponse(200,$colorList,'color list');
            }
            $brandList = Brand::where('deleted_at','')->where('status','1');
            if ($bannerId)
            {
                $brandList->whereIn('id',$brandIds);
            }
            $brandList = $brandList->get();
            if ($brandList)
            {
                foreach($brandList as $brand)
                {
                    $brandId = $brand->id;
                    // $productCount = Product::where('deleted_at','')->where('product_status','1')->where('brand_id',$brandId)->count();
                    // $brand->count = $productCount;
                    $brand->image = (!empty($brand->image)) ? asset('uploads/brand/'.$brand->image) : '';
                    unset($brand->status);
                    unset($brand->created_at);
                    unset($brand->updated_at);
                    unset($brand->deleted_at);
                }
                // return $this->sendResponse(200,$brandList,'brand list');
            }
            $subCategoryList = SubCategory::where('deleted_at','')->where('status','1');
            if ($bannerId)
            {
                $subCategoryList->whereIn('id',$subCategoryIds);
            }
            $subCategoryList = $subCategoryList->get();
            if ($subCategoryList)
            {
                foreach($subCategoryList as $value)
                {
                    $subCategoryId = $value->id;
                    // $productCount = Product::where('deleted_at','')->where('product_status','1')->where('sub_category_id',$subCategoryId)->count();
                    // $value->count = $productCount;
                    $value->image = (!empty($value->image)) ? asset('uploads/sub_category/'.$value->image) : '';
                    unset($value->category_id);
                    unset($value->status);
                    unset($value->created_at);
                    unset($value->updated_at);
                    unset($value->deleted_at);
                }
                // return $this->sendResponse(200, $subCategoryList, 'sub category list');
            }
            $sizeList = Size::where('deleted_at','')->where('status','1');
            if ($bannerId)
            {
                $sizeList->whereIn('id',$sizeIds);
            }
            $sizeList = $sizeList->get();
            if ($sizeList)
            {
                foreach($sizeList as $size)
                {
                    $sizeId = $size->id;
                    // $productCount = Product::where('deleted_at','')->where('product_status','1')->whereRaw('FIND_IN_SET('.$sizeId.',size_id)')->count();
                    // $size->count = $productCount;

                    unset($size->status);
                    unset($size->created_at);
                    unset($size->updated_at);
                    unset($size->deleted_at);
                }
                // return $this->sendResponse(200,$sizeList,'size list');
            }
            // $minPrice = Product::where('deleted_at','')->where('product_status','1')->min('price');
            // $maxPrice = Product::where('deleted_at','')->where('product_status','1')->max('price');
            $minPrice = Product::selectRaw('MIN(CAST(price as DECIMAL(10))) as minPrice')->where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0');
            if ($bannerId)
            {
                $minPrice->whereIn('id',$productId);
            }
            $minPrice = $minPrice->first();
            $maxPrice = Product::selectRaw('MAX(CAST(price as DECIMAL(10))) as maxPrice')->where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0');
            if ($bannerId)
            {
                $maxPrice->whereIn('id',$productId);
            }
            $maxPrice = $maxPrice->first();
            // $productCount = Product::where('deleted_at','')->where('product_status','1')->count();
            // dd($priceRange);
            $priceRange  = array(
                "minPrice" => $minPrice->minPrice ?? '0',
                "maxPrice" => $maxPrice->maxPrice ?? '0',
                // "productCount" => (string)$productCount,
            );
            $data['colorList'] = $colorList;
            $data['brandList'] = $brandList;
            $data['subCategoryList'] = $subCategoryList;
            $data['priceRange'] = $priceRange;
            // $data['sizeList'] = $sizeList;
            return $this->sendResponse(200, $data, 'filter data list');
        }
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());  
        }
    }
    public function couponList(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
            if (!empty($cartDetail->toArray()))
            {
                $cartTotal = 0;
                $subCategoryId = '';
                $productDiscount = 0;
                $shippingCharge = 0;
                $payableAmount = 0;
                $total = 0;
                foreach($cartDetail as $cart)
                {
                    $cartTotal += $cart->total_price;
                    $productId = $cart->product_id;
                    $productData = Product::where('deleted_at','')->select('sub_category_id')->find($productId);
                    if ($productData)
                    {
                        $subCategoryId = $subCategoryId != '' ? $subCategoryId . ','. $productData->sub_category_id. ',' : $productData->sub_category_id;
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

                $globalCouponList = Offer::where('deleted_at','')->where('offer_status','1')->where('start_date','<=',$currentDate)->where('end_date','>=',$currentDate)->where('is_global','1')->where('total_amount','<=',$cartTotal)->whereRaw('total_used < total_use')->get();
                $subCategoryId = explode(',',$subCategoryId);
                // dd($subCategoryId);
                // $sCategoryCouponList = Offer::where('deleted_at','')->where('offer_status','1')->where('start_date','<=',$currentDate)->where('end_date','>=',$currentDate)->where('is_global','0')->whereRaw("find_in_set('".$subCategoryId."',sub_category_id)")->get()->toArray();
                $sCategoryCouponList = Offer::where('deleted_at','')->where('offer_status','1')->where('start_date','<=',$currentDate)->where('end_date','>=',$currentDate)->where('is_global','0')->whereRaw('total_used < total_use');
                // dd($sCategoryCouponList->toSql());
                foreach($subCategoryId as $value)
                {
                    $sCategoryCouponList->whereRaw("find_in_set('".$value."',sub_category_id)");
                }
                $sCategoryCouponList = $sCategoryCouponList->get();
                // Print Query
                // return vsprintf(str_replace('?', '%s', $sCategoryCouponList->toSql()), collect($sCategoryCouponList->getBindings())->map(function ($binding) {
                //     return is_numeric($binding) ? $binding : "'{$binding}'";
                // })->toArray());
                $couponList = $globalCouponList->merge($sCategoryCouponList);
                // $couponList = array_merge($globalCouponList,$sCategoryCouponList);
                // $couponList = collect([ (object) $couponList ]);
                // $couponList = $couponList->sortBy('id');
                // $couponList = $couponList->values()->all();
                foreach($couponList as $value)
                {
                    // dd($value->id);
                    // $couponId = $value['id'];
                    // $couponData = Offer::find($couponId);
                    $discount = $value->offer_discount;
                    $discountPrice = $cartTotal * $discount / 100;
                    $value->saved_amount = 'Save '.$discountPrice . ' NR';
                    $endDate = $value->end_date;

                    $endDate = date('d M Y h:i a',strtotime($endDate));
                    // dd($endDate);
                    $value->end_date = $endDate;
                }

                $couponData = new stdClass;
                $discountPrice = 0;
                if ($couponId != '0')
                {
                    $couponData = Offer::find($couponId);
                    $discount = $couponData->offer_discount;
                    $discountPrice = $cartTotal * $discount / 100;
                    $couponData->saved_amount = '-'.$discountPrice . ' NR';

                    unset($couponData->created_at);
                    unset($couponData->updated_at);
                    unset($couponData->deleted_at);
                }
                $summaryArray = array(
                    'MRP' => $total. ' NR',
                    'coupon_discount' => $discountPrice. ' NR',
                    'discount_price' => '-'.$productDiscount. ' NR',
                    'total_paid' => $payableAmount. ' NR',
                );
                $data['coupon_list'] = $couponList;
                $data['view_detail'] = $summaryArray;
                // $couponList = $sCategoryCouponList;
                return $this->sendResponse(200,$data,'coupon list');
            }
            else
            {
                $cartDetail = new stdClass;
                return $this->sendResponse(201,$cartDetail,'cart is empty');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function applyCoupon(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $couponCode = $request->couponCode;
            if (isset($couponCode))
            {
                $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
                if (!empty($cartDetail->toArray()))
                {
                    $checkCoupon = Offer::where('deleted_at','')->where('offer_code',$couponCode)->whereRaw('total_used < total_use')->first();
                    if ($checkCoupon)
                    {
                        if ($checkCoupon->is_global == '1')
                        {
                            $cartTotal = $cartDetail->sum('total_price');
                            if ($cartTotal < (int)$checkCoupon->total_amount)
                            {
                                $data = new stdClass;
                                return $this->sendResponse(201,$data,'coupon not found');
                            }
                        }
                        else
                        {
                            $subCategoryId = '';
                            foreach ($cartDetail as $cart)
                            {
                                $productId = $cart->product_id;
                                $productData = Product::where('deleted_at', '')->select('sub_category_id')->find($productId);
                                if ($productData)
                                {
                                    $subCategoryId = $subCategoryId != '' ? $subCategoryId . ',' . $productData->sub_category_id . ',' : $productData->sub_category_id;
                                }
                                $subCategoryId = explode(',', $subCategoryId);
                                $checkCategoryCoupon = Offer::where('deleted_at', '')->where('offer_code', $couponCode)->whereRaw('total_used < total_use');
                                foreach ($subCategoryId as $value)
                                {
                                    $checkCategoryCoupon->whereRaw("find_in_set('" . $value . "',sub_category_id)");
                                }
                                $checkCategoryCoupon = $checkCategoryCoupon->first();
                                if (!$checkCategoryCoupon)
                                {
                                    $data = new stdClass;
                                    return $this->sendResponse(201,$data,'coupon not found');
                                }
                            }

                        }

                        // $updateCart = Cart::where('deleted_at','')->where('user_id',$userId)->update(['coupon_id'=>$checkCoupon->id]);
                        $updateCart = Cart::where('deleted_at','')->where('user_id',$userId)->get();
                        $cartTotal = 0;
                        $discount = $checkCoupon->offer_discount;
                        $rowCount = $updateCart->count();
                        foreach($updateCart as $cart)
                        {
                            $cartTotal += $cart->total_price;
                        }
                        $discountPrice = $cartTotal * $discount / 100;
                        // $perProductDiscount = $discountPrice / $rowCount;
                        $cartTotal = 0;
                        $productDiscount = 0;
                        $shippingCharge = 0;
                        $payableAmount = 0;
                        foreach($updateCart as $value)
                        {
                            // $total = (int)$value->total_price;
                            // $value->total_price = $total - $perProductDiscount
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
                        // $productDiscount = $productDiscount + $couponDiscount;
                        if ($shippingCharge == 0)
                        {
                            $shippingCharge = 'Free';
                        }
                        else
                        {
                            $shippingCharge = $shippingCharge. ' NR';
                        }
                        $summaryArray = array(
                            'cart_total' => $cartTotal. ' NR',
                            'product_discount' => '-'.$productDiscount. ' NR',
                            'shipping_charge' => $shippingCharge,
                            'payable_amount' => $payableAmount. ' NR',
                        );
                        $checkCoupon->saved_amount = '-'.$discountPrice . ' NR';
                        unset($checkCoupon->created_at);
                        unset($checkCoupon->updated_at);
                        unset($checkCoupon->deleted_at);
                        $data['coupon_data'] = $checkCoupon;
                        $data['summary_data'] = $summaryArray;
                        $updateOfferCount = Offer::where('deleted_at','')->where('offer_code',$couponCode)->first();
                        if ($updateOfferCount)
                        {
                            $totalUsed = $updateOfferCount->total_used;
                            $totalUsed = $totalUsed + 1;
                            $updateOfferCount->total_used = $totalUsed;
                            $updateOfferCount->save();
                        }
                        return $this->sendResponse(200,$data,'coupon applied');
                    }
                    else
                    {
                        $data = new stdClass;
                        return $this->sendResponse(201,$data,'coupon not found');
                    }
                }
                else
                {
                    $cartDetail = new stdClass;
                    return $this->sendResponse(201,$cartDetail,'cart is empty');
                }
            } 
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'please insert data in coupon field');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function removeCoupon(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $cartDetail = Cart::where('deleted_at','')->where('user_id',$userId)->get();
            if (!empty($cartDetail->toArray()))
            {
                $couponId = '0';
                foreach($cartDetail as $cart)
                {
                    $couponId = $cart->coupon_id;
                }
                $removeCoupon = Cart::where('deleted_at','')->where('user_id',$userId)->update(['coupon_id'=>'0','coupon_discount'=>'0']);
                $updateCart = Cart::where('deleted_at','')->where('user_id',$userId)->get();
                $cartTotal = 0;
                $productDiscount = 0;
                $shippingCharge = 0;
                $payableAmount = 0;
                foreach($updateCart as $value)
                {
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
                if ($shippingCharge == 0)
                {
                    $shippingCharge = 'Free';
                }
                else
                {
                    $shippingCharge = $shippingCharge. ' NR';
                }
                $summaryArray = array(
                    'cart_total' => $cartTotal. ' NR',
                    'product_discount' => '-'.$productDiscount. ' NR',
                    'shipping_charge' => $shippingCharge,
                    'payable_amount' => $payableAmount. ' NR',
                );
                $updateOfferCount = Offer::where('deleted_at','')->where('id',$couponId)->first();
                if ($updateOfferCount)
                {
                    $totalUsed = $updateOfferCount->total_used;
                    $totalUsed = $totalUsed - 1;
                    $updateOfferCount->total_used = $totalUsed;
                    $updateOfferCount->save();
                }
                return $this->sendResponse(200,$summaryArray,'coupon removed');
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'cart data not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function updateNotificationSetting(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $userData = User::where('deleted_at','')->find($userId);
            if ($userData)
            {
                $notification = (int)$userData->notification;
                if ($notification == 0)
                {
                    $userData->notification = '1';
                }
                else
                {
                    $userData->notification = '0';
                }
                $userData->save();
                unset($userData->otp);
                unset($userData->mobile_number);
                unset($userData->profile_pic);
                unset($userData->otp_verified);
                unset($userData->created_at);
                unset($userData->updated_at);
                unset($userData->deleted_at);

                // $chekUser = Auth::user();
                // $token = $chekUser->createToken('apiToken')->plainTextToken;
                // $chekUser->token = $token;
                $userData->profile_pic = (!empty($userData->profile_pic)) ? asset('uploads/user/'.$userData->profile_pic) : '';
                return $this->sendResponse(200,$userData,'notification setting updated');
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'user data not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function testSms()
    {
        try
        {
            $args = http_build_query(array(
            'token' => 'v2_T7tHFZwTUO8sso3QNv8vCMZmAA1.tHyQ',
            'from'  => 'BhesBhusa',
            'to'    => '9812007234',
            'text'  => 'SMS Message to be sent'));

                $url = "http://api.sparrowsms.com/v2/sms/";

            # Make the call using API.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // Response
            $response = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            echo $response;exit;
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function testPostSms(Request $request)
    {
        // $message = 'Hey sunil, Your booking request has been sent to the TEST. Please, wait while TEST confirm your booking.';
        
        // sendMail('shissunil@gmail.com',$message);

        // echo "TSET";exit;
        $args = http_build_query(array(
            'token' => 'v2_T7tHFZwTUO8sso3QNv8vCMZmAA1.tHyQ',
            'from'  => 'BhesBhusa',
            'to'    => '6354700638',
            'text'  => 'SMS Message to be sent'));

        $url = "http://api.sparrowsms.com/v2/sms/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo $response;exit;
    }

    public function testNotfication(Request $request)
    {
        try
        {   $message = "Your order has been successfully placed checkout more details here";
            $token = $request->token;
            // $token = "eZaZRfdWRx2hqiDtO71BPM:APA91bEnSMH5CR6EZ3TYYVHgi3k8ndS0NObcVgVBl0eTtFX4kHcrJ389IEz_x-SGtVM2xpJSVVSsclvx04t8K4H2gHpeVT5DwiDmpo97zIfkVwqITH3qUNLhPBZg97yD3ph0oFJ1CwbW";
            // $token = "cZJF-GA7oExqmC2xk4vOst:APA91bGXCamIbpSRENFHcejPgF1d06vnCcURFNMyQ5elZDinda-V6QO3M3LVPvFk1MlO04csqeYVp6kn9EnGs-l69mfaLdxOh_BPCja3kHgxkHclIdsRrp_awTw6sJtSEG9r8sszzJne";
            sendCustomerNotification($message, $token, 'Order','1', 'Order','1');
            sendDriverNotification($message, $token, 'Order Assignee','4', 'Order','1');
            echo "success";
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
}
