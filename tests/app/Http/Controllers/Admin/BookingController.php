<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryAssociate;
use App\Models\Notification;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ReviewAndRating;
use App\Models\UserAddress;
use App\Models\ReturnOrders;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function allBooking()
    {
        $ongoingBooking = Order::where('deleted_at', '')->orderBy('id', 'DESC')->get();
        if($ongoingBooking){
            foreach($ongoingBooking as $booking){
                $userData = User::where('id',$booking->user_id)->first();
                $booking->full_name = $userData->full_name??'';

                $noOfUnit = OrderItem::where('deleted_at','')->where('order_id',$booking->id)->count();
                $booking->no_of_unit = $noOfUnit;
            }
        }
        return view('admin.orders.allBooking', compact('ongoingBooking'));
    }
    public function ongoingBooking()
    {
        $ongoingBooking = Order::where('deleted_at', '')->whereIn('order_status',[2,6])->orderBy('id', 'DESC')->get();
        if($ongoingBooking){
            foreach($ongoingBooking as $booking){
                $userData = User::where('id',$booking->user_id)->first();
                $booking->full_name = $userData->full_name??'';

                $noOfUnit = OrderItem::where('deleted_at','')->where('order_id',$booking->id)->count();
                $booking->no_of_unit = $noOfUnit;
            }
        }
        return view('admin.orders.ongoing', compact('ongoingBooking'));
    }

    public function pastBooking()
    {
        $pastBooking = Order::where('deleted_at', '')->whereDate('created_at', '<', Carbon::today())->get();
        if($pastBooking){
            foreach($pastBooking as $booking){
                $userData = User::where('id',$booking->user_id)->first();
                $booking->full_name = $userData->full_name??'';

                $noOfUnit = OrderItem::where('deleted_at','')->where('order_id',$booking->id)->count();
                $booking->no_of_unit = $noOfUnit;
            }
        }
        return view('admin.orders.past', compact('pastBooking'));
    }

    public function upcomingBooking()
    {
        $upcomingBooking = Order::where('deleted_at', '')->where('order_status','1')->orderBy('id', 'DESC')->get();
        if($upcomingBooking){
            foreach($upcomingBooking as $booking)
            {
                $userData = User::where('id',$booking->user_id)->first();
                $booking->full_name = $userData->full_name??'';

                $noOfUnit = OrderItem::where('deleted_at','')->where('order_id',$booking->id)->count();
                $booking->no_of_unit = $noOfUnit;
            }
        }
        return view('admin.orders.upcoming', compact('upcomingBooking'));
    }
    public function returnOrders(Request $request)
    {
        $returnOrders = Order::where('deleted_at', '')->where('order_status','5')->orderBy('id', 'DESC')->get();
        if($returnOrders){
            foreach($returnOrders as $booking)
            {
                $userData = User::where('id',$booking->user_id)->first();
                $booking->full_name = $userData->full_name??'';

                $noOfUnit = OrderItem::where('deleted_at','')->where('order_id',$booking->id)->count();
                $booking->no_of_unit = $noOfUnit;
            }
        }
        return view('admin.orders.returnOrders', compact('returnOrders'));
    }
    public function orderDetail($id)
    {
        $orderData = Order::where('deleted_at','')->findOrFail($id);
        // dd($orderData);
        $orderItem = OrderItem::where('deleted_at','')->where('order_id',$id)->get();
        $deliveryAssociates = DeliveryAssociate::where('deleted_at','')->where('status','1')->get();
        $reviewData = ReviewAndRating::where('deleted_at','')->where('order_id',$id)->where('user_id',$orderData->user_id)->get();
        $avarageRate = 0;
        if (!empty($reviewData->toArray()))
        {
            $avarageRate = $reviewData->sum('rate');
            if ($reviewData->count() != 0)
            {
                $avarageRate = $reviewData->sum('rate') / $reviewData->count();
            }
        }
        $orderData->avarage_rate = $avarageRate;

        $deliveryAddress = UserAddress::select('address','pincode', 'locality', 'city', 'state')->find($orderData->user_address_id);
        $orderData->deliveryAddress = $deliveryAddress;
        $orderData->returnOrderData = '';
        if ($orderData->order_status == '5')
        {
            $returnOrderData = ReturnOrders::where('order_id',$orderData->id)->first();
            $orderData->returnOrderData = $returnOrderData;
        }
        if($orderItem){
            foreach($orderItem as $item)
            {
                // $productData = PR
                $productData = Product::where('deleted_at','')->find($item->product_id);
                $brand = Brand::select('name')->find($item->brand_id);
                $item->brand = $brand->name;
                $item->sold_by = $productData->sold_by;
            }
        }
        return view('admin.orders.orderDetail',compact('orderData','orderItem', 'deliveryAssociates'));
    }
    public function assignOrder(Request $request,$id)
    {
        $request->validate([
            'delivery_associate_id'=>'required',
        ]);
        $deliveryAssociateId = $request->delivery_associate_id;
        $order = Order::where('deleted_at','')->find($id);
        if ($order)
        {
            $orderStatus = $order->order_status;
            if ($orderStatus == '1' || $orderStatus == '2')
            {
                $order->order_status = '2';
                $order->delivery_associates_id = $deliveryAssociateId;
                $order->assign_date = date('Y-m-d');
                $order->save();
                $orderItem = OrderItem::where('deleted_at','')->where('order_id',$id)->update(['order_status'=>'2']);
                
                $message = "Your Order has been Assignee Successfully.";
                $notification = new Notification;
                $notification->receiver_id = $deliveryAssociateId;
                $notification->receiver_type = 'delivery';
                $notification->sender_id = '';
                $notification->sender_type = '';
                $notification->category_type = 'Order Assignee';
                $notification->notification_type = 'Order';
                $notification->type_id = $order->id;
                $notification->message = $message;
                $notification->deleted_at = '';
                $notification->save();
                $deliveryData = DeliveryAssociate::find($deliveryAssociateId);
                if ($deliveryData)
                {
                    $token = $deliveryData->device_token;
                    sendDriverNotification($message, $token, 'Order Assignee','4', 'Order',$order->id);
                }
                $message = "Your Order has been Confirm Successfully.";
                $notification = new Notification;
                $notification->receiver_id = $order->user_id;
                $notification->receiver_type = 'user';
                $notification->sender_id = $deliveryAssociateId;
                $notification->sender_type = 'delivery';
                $notification->category_type = 'Order';
                $notification->notification_type = 'Order';
                $notification->type_id = $order->id;
                $notification->message = $message;
                $notification->deleted_at = '';
                $notification->save();
                $userData = User::where('notification','1')->find($order->user_id);
                if ($userData)
                {
                    $token = $userData->device_token;
                    sendCustomerNotification($message, $token, 'Order','1', 'Order',$order->id);
                }
                $message = "Order Assignee Successfully...!";
                $notification = new Notification;
                $notification->receiver_id = '';
                $notification->receiver_type = 'admin';
                $notification->sender_id = $deliveryAssociateId;
                $notification->sender_type = '';
                $notification->category_type = 'Order';
                $notification->notification_type = 'Order';
                $notification->type_id = $order->id;
                $notification->order_status = '2';
                $notification->message = $message;
                $notification->deleted_at = '';
                $notification->save();

                return redirect()->route('admin.booking.list')->with('success','order Assign Successfully');
            }
            else
            {
                return redirect()->route('admin.booking.list')->with('error','order not Assign Successfully ');
            }
        }
        else
        {
            return redirect()->route('admin.booking.list')->with('error','order not found');
        }
    }
    public function totalSales()
    {
        $totalSales = Order::where('deleted_at', '')->where('order_status','4')->whereRaw('MONTH(created_at) = ?',[Carbon::now()->month()])->orderBy('id', 'DESC')->get();
        if($totalSales){
            foreach($totalSales as $booking){
                $userData = User::where('id',$booking->user_id)->first();
                $booking->full_name = $userData->full_name??'';
            }
        }
        return view('admin.accountReport.totalSales', compact('totalSales'));
    }
    public function refundPayment()
    {
        $refundPayments = Order::where('deleted_at', '')->where('order_status','5')->where('pickup_done','1')->orderBy('id', 'DESC')->get();
        if($refundPayments){
            foreach($refundPayments as $booking){
                $userData = User::where('id',$booking->user_id)->first();
                $booking->full_name = $userData->full_name??'';
            }
        }
        return view('admin.accountReport.refundPayment', compact('refundPayments'));
    }
}
