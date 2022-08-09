<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Crypt;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Size;
use App\Models\Color;
use App\Models\Brand;
use App\Models\ReviewAndRating;
use App\Models\Product;
use App\Models\WishList;
use App\Models\UserAddress;
use App\Models\TicketReason;
use App\Models\ReturnOrders;
use App\Models\Notification;

class OrderController extends Controller
{
    
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function orderList(Request $request)
    {
        // $userId = $userData->id;
        $userData = $request->user();
        $userId = auth()->user()->id;

        $pageNumber = isset($request->page_number) ? $request->page_number : 1;
        $pageSize = isset($request->page_size) ? $request->page_size : 10; 

        $firstName = $userData->first_name;
        $lastName = $userData->last_name;
        // $filterStatus = $request->filter_status;
        $reasonList = TicketReason::where('deleted_at','')->where('reason_for','1')->where('status','1')->get();
        $orderList = Order::where('deleted_at','')->where('user_id',$userId)->orderBy('id','desc')->paginate(10);
        // if (isset($filterStatus))
        // {
        //     $orderList->where('order_status',$filterStatus);
        // }
        // $orderList = $orderList->get();
        // $orderList = $orderList->paginate($pageSize,'*','page',$pageNumber);
        // $orderList = $orderListCollection->items();

        // $totalPages = $orderListCollection->lastPage();
        // $totalCount = $orderListCollection->total();
        // $pageNumber = $orderListCollection->currentPage();
        // $nextPage = $orderListCollection->nextPageUrl()?true:false;
        // $prevPage = $orderListCollection->previousPageUrl()?true:false;

        if (!empty($orderList))
        {
            foreach($orderList as $value)
            {
                $value->user_name = $firstName . ' '.$lastName;
                $orderStatus = $value->order_status;
                $value->is_cancel = '1';
                if ($value->order_status == '1' || $value->order_status == '2')
                {
                    $value->is_cancel = '0';
                }
                $value->is_return = '1';
                if ($value->order_status == '4')
                {
                    $orderDeliveredDate = date('Y-m-d', strtotime($value->delivered_date));
                    $currentDate = date('Y-m-d');
                    $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
                    if ($masterData)
                    {
                        $returnDay = $masterData->return_day;
                        $returnDate = date('Y-m-d', strtotime($orderDeliveredDate. ' + '.$returnDay.' days'));

                        if ($currentDate < $returnDate)
                        {
                            $value->is_return = '0';
                        }
                    }

                }
                $value->status = $orderStatus;
                if ($orderStatus == '1')
                {
                    $value->order_status = 'Pending';
                }
                elseif($orderStatus == '2')
                {
                    $value->order_status = 'Confirmed';
                }
                elseif($orderStatus == '3')
                {
                    $value->order_status = 'Canceled';
                }
                elseif($orderStatus == '4')
                {
                    $value->order_status = 'Delivered';
                }
                elseif($orderStatus == '5')
                {
                    $value->order_status = 'Returned';
                }
                elseif($orderStatus == '6')
                {
                    $value->order_status = 'Out For Service';
                }
                $value->order_date = date('d M Y',strtotime($value->created_at));
                $value->total_amount = $value->total_amount . ' NR';
                $paymentType = $value->payment_type;
                if ($paymentType == '1')
                {
                    $value->payment_type = 'COD';
                }
                if ($paymentType == '2')
                {
                    $value->payment_type = 'Online';
                }
                // $value->created_at = date('d M Y',strtotime($value->created_at));
                // $sizeData = Size::where('deleted_at','')->find($value->size_id);
                // $colorData = Color::where('deleted_at','')->find($value->color_id);
                // $value->size_id = $sizeData->size;
                // $value->color_id = $colorData->color;
                // $productData = Product::where('deleted_at','')->find($value->product_id);
                // $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                // $value->productData = $productData;
            }
        }
        return view('front.my_orders',compact('orderList','reasonList'));
        // $newArrivals = $this->newArrivals();
    }

    public function returnOrder(Request $request,$id)
    {
        $userData = $request->user();
        $userId = auth()->user()->id;
        $orderId = Crypt::decrypt($id);
        $reasonList = TicketReason::where('deleted_at','')->where('reason_for','2')->where('status','1')->get();
        $data = array();
        $userAddressList = UserAddress::where('deleted_at', '')->where('user_id', $userId)->get();
        if (!empty($reasonList->toArray()))
        {
            $chekOrder = Order::where('deleted_at','')->where('user_id',$userId)->find($orderId);
            if ($chekOrder)
            {
                $date = date('d M Y',strtotime($chekOrder->created_at));
                $estimateDate = date('d M Y', strtotime($date. ' + '.$chekOrder->estimate_delivery_days.' days'));
                $chekOrder->estimate_date = $estimateDate;
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
                        }
                        $value->productData = $productData;
                    }
                    $chekOrder->order_item = $orderItem;                   
                }
                $address = UserAddress::find($chekOrder->user_address_id);
                $refundAmount = $chekOrder->total_amount;
                $data['orderData'] = $chekOrder;
                $data['reasonList'] = $reasonList;
                $data['refund_detail'] = $refundAmount . ' NR';
                $data['delivery_address'] = $address;
                // return $this->sendResponse(200, $data, 'ticket reason list');
            }
        }
        return view('front.returnOrder',compact('data','userAddressList'));
    }
    public function cancelOrder(Request $request)
    {
        $userData = $request->user();
        $userId = auth()->user()->id;
        $request->validate([ 
            'order_id' => 'required',
            'reason_id' => 'required',
        ]);
        $orderId = $request->order_id;
        $reasonId = $request->reason_id;
        $cancelDescription = $request->cancel_description;
        $orderData = Order::where('deleted_at','')->where('user_id',$userId)->whereIn('order_status',[1,2])->find($orderId);
        if ($orderData)
        {
            $updateOrderItem = OrderItem::where('order_id',$orderId)->update(['order_status'=>'3']);
            $orderData->cancel_id = $reasonId;
            if ($cancelDescription)
            {
                $orderData->cancel_description = $cancelDescription;
            }
            $orderData->return_date = date('d M Y');
            $orderData->refund_status = '1';
            $orderData->order_status = '3';
            $orderData->save();
            $orderId = Crypt::encrypt($orderId);

            $message = "Customer Canceled Order!";
            $notification = new Notification;
            $notification->receiver_id = '';
            $notification->receiver_type = 'admin';
            $notification->sender_id = $userId;
            $notification->sender_type = '';
            $notification->category_type = 'Order';
            $notification->notification_type = 'Order';
            $notification->type_id = $orderId;
            $notification->order_status = '3';
            $notification->message = $message;
            $notification->deleted_at = '';
            $notification->save();

            $getOrderItem = OrderItem::where('order_id',$orderId)->get();
            if (!empty($getOrderItem->toArray()))
            {
                foreach($orderItem as $item)
                {
                    $productId = $item->product_id;
                    $quantity = $item->quantity;
                    $updateProductQty = Product::where('deleted_at', '')->find($productId);
                    if ($updateProductQty)
                    {
                        // $oldUsedQty = (int)$updateProductQty->used_quantity;
                        // $updateProductQty->used_quantity = $oldUsedQty - (int)$quantity;
                        $oldQty = (int)$updateProductQty->quantity;
                        $updateProductQty->quantity = $oldQty + (int)$quantity;
                        $updateProductQty->save();
                    }
                }
            }

            return redirect()->route('orderDetail',$orderId)->with('success','order cancelled');
        }
        else
        {
           return redirect()->back()->with('error','order not found'); 
        }
    }
    public function filterOrderList(Request $request)
    {
        $orderStatus = $request->order_status;
        $userData = $request->user();
        $userId = auth()->user()->id;

        $pageNumber = isset($request->page_number) ? $request->page_number : 1;
        $pageSize = isset($request->page_size) ? $request->page_size : 10; 

        $firstName = $userData->first_name;
        $lastName = $userData->last_name;
        // $filterStatus = $request->filter_status;
        $reasonList = TicketReason::where('deleted_at','')->where('reason_for','1')->where('status','1')->get();
        if ($orderStatus == '0')
        {
            $orderList = Order::where('deleted_at','')->where('user_id',$userId)->orderBy('id','desc')->paginate(10);
        }
        else
        {
            $orderList = Order::where('deleted_at','')->where('user_id',$userId)->where('order_status',$orderStatus)->orderBy('id','desc')->paginate(10);
        }
        if (!empty($orderList))
        {
            foreach($orderList as $value)
            {
                $value->user_name = $firstName . ' '.$lastName;
                $orderStatus = $value->order_status;
                $value->is_cancel = '1';
                if ($value->order_status == '1' || $value->order_status == '2')
                {
                    $value->is_cancel = '0';
                }
                $value->is_return = '1';
                if ($value->order_status == '4')
                {
                    $orderDeliveredDate = date('Y-m-d', strtotime($value->delivered_date));
                    $currentDate = date('Y-m-d');
                    $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
                    if ($masterData)
                    {
                        $returnDay = $masterData->return_day;
                        $returnDate = date('Y-m-d', strtotime($orderDeliveredDate. ' + '.$returnDay.' days'));

                        if ($currentDate < $returnDate)
                        {
                            $value->is_return = '0';
                        }
                    }

                }
                $value->status = $orderStatus;
                if ($orderStatus == '1')
                {
                    $value->order_status = 'Pending';
                }
                elseif($orderStatus == '2')
                {
                    $value->order_status = 'Confirmed';
                }
                elseif($orderStatus == '3')
                {
                    $value->order_status = 'Cancelled';
                }
                elseif($orderStatus == '4')
                {
                    $value->order_status = 'Delivered';
                }
                elseif($orderStatus == '5')
                {
                    $value->order_status = 'Returned';
                }
                elseif($orderStatus == '6')
                {
                    $value->order_status = 'Out For Service';
                }
                $value->order_date = date('d M Y',strtotime($value->created_at));
                $value->total_amount = $value->total_amount . ' NR';
                $paymentType = $value->payment_type;
                if ($paymentType == '1')
                {
                    $value->payment_type = 'COD';
                }
                if ($paymentType == '2')
                {
                    $value->payment_type = 'Online';
                }
                // $value->created_at = date('d M Y',strtotime($value->created_at));
                // $sizeData = Size::where('deleted_at','')->find($value->size_id);
                // $colorData = Color::where('deleted_at','')->find($value->color_id);
                // $value->size_id = $sizeData->size;
                // $value->color_id = $colorData->color;
                // $productData = Product::where('deleted_at','')->find($value->product_id);
                // $productData->product_image = (!empty($productData->product_image)) ? asset('uploads/product/'.$productData->product_image) : '';
                // $value->productData = $productData;
            }
        }
        return view('front.filterOrderList',compact('orderList','reasonList'));
    }

    public function updateOrderAddress(Request $request)
    {
        $request->validate([
            'contact_name' => 'required',
            'mobile_no' => 'required|digits:10',
            'email' => 'required|email',
            'pincode' => 'required|digits:6',
            'address' => 'required',
            'locality' => 'required',
            'city' => 'required',
            'state' => 'required',
        ]);
        $name = $request->contact_name;
        $mobileNo = $request->mobile_no;
        $email = $request->email;
        $pincode = $request->pincode;
        $address = $request->address;
        $locality = $request->locality;
        $city = $request->city;
        $state = $request->state;
        $saveAs = $request->save_as;
        $isDefault = $request->is_default ?? 0;

        $userId = Auth::user()->id;
        $addressId = $request->address_id;
        $orderId = $request->order_id;

        $updateAddress = UserAddress::where('deleted_at', '')->where('user_id', $userId)->find($addressId);

        if ($updateAddress) {
            if (isset($request->contact_name)) {
                $updateAddress->contact_name = $request->contact_name;
            }
            if (isset($request->mobile_no)) {
                $updateAddress->mobile_no = $request->mobile_no;
            }
            if (isset($request->email)) {
                $updateAddress->email = $request->email;
            }
            if (isset($request->pincode)) {
                $updateAddress->pincode = $request->pincode;
            }
            if (isset($request->address)) {
                $updateAddress->address = $request->address;
            }
            if (isset($request->locality)) {
                $updateAddress->locality = $request->locality;
            }
            if (isset($request->city)) {
                $updateAddress->city = $request->city;
            }
            if (isset($request->state)) {
                $updateAddress->state = $request->state;
            }
            if (isset($request->save_as)) {
                $updateAddress->save_as = $request->save_as;
            }
            $updateAddress->is_default = $isDefault;
            if ($isDefault == '1') {
                $updateDefaultAddress = UserAddress::where('deleted_at', '')->where('user_id', $userId)->update(['is_default' => '0']);
            }
            $updateAddress->deleted_at = '';
            $updateAddress->save();
            $orderId = Crypt::encrypt($orderId);
            return redirect()->intended(route('orderDetail',$orderId))->with('success', 'Address Updated Successfully.');
        } else {
            return redirect()->back()->with('error', 'Address Not Found.');
        }
    }
    public function saveReviewAndRating(Request $request)
    {
        
    }
    public function orderItemData(Request $request)
    {
    }
}
