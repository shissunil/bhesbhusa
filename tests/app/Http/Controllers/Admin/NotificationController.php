<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\DeliveryAssociate;
use App\Models\Notification;
use App\Models\ReviewAndRating;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function create()
    {
        // $offers = Offer::where('deleted_at','')->orderBy('id','DESC')->get();
        $userList = User::where('deleted_at','')->select('id','first_name','last_name')->where('status','1')->get();
        return view('admin.notification.create',compact('userList'));
    }
    public function send(Request $request)
    {
        $request->validate([
            'notification_message'=> 'required',
        ]);
        $adminId = auth()->user()->id;
        $notificationTo = $request->notification_to;
        $userId = $request->user_id;
        if ($notificationTo == '2')
        {
           return redirect()->route('admin.notification.create')->with('error','please select notification send to'); 
        }
        else
        {
            $notificationMessage = $request->notification_message;
            $notificationType = '';
            $categoryName = '';
            if ($notificationTo == '0')
            {
                $categoryType = $request->customer_category_type;
                if ($categoryType == '1')
                {
                    $notificationType = 'Order';
                    $categoryName = 'Order';
                }
                if ($categoryType == '2')
                {
                    $notificationType = 'Offer';
                    $categoryName = 'Promotion';
                }
                if ($userId)
                {
                    foreach($userId as $id)
                    {
                        $userData = User::where('deleted_at','')->where('status','1')->find($id);
                        if ($userData)
                        {
                            $notification = new Notification;
                            $notification->receiver_id = $userData->id;
                            $notification->receiver_type = 'user';
                            $notification->sender_id = $adminId;
                            $notification->sender_type = 'Admin';
                            $notification->category_type = $categoryName;
                            $notification->notification_type = $notificationType;
                            $notification->type_id = '';
                            $notification->message = $notificationMessage;
                            $notification->deleted_at = '';
                            $notification->save();
                            $token = $userData->device_token;
                            sendCustomerNotification($notificationMessage, $token, $categoryName,$categoryType, $notificationType,'');
                        }
                    }
                }
                else
                {
                    $userList = User::where('deleted_at','')->where('status','1')->get();
                    if (!empty($userList->toArray()))
                    {
                        foreach($userList as $user)
                        {
                            $notification = new Notification;
                            $notification->receiver_id = $user->id;
                            $notification->receiver_type = 'user';
                            $notification->sender_id = $adminId;
                            $notification->sender_type = 'Admin';
                            $notification->category_type = $categoryName;
                            $notification->notification_type = $notificationType;
                            $notification->type_id = '';
                            $notification->message = $notificationMessage;
                            $notification->deleted_at = '';
                            $notification->save();
                            $token = $user->device_token;
                            sendCustomerNotification($notificationMessage, $token, $categoryName,$categoryType, $notificationType,'');
                        }
                    }
                }
            }
            if ($notificationTo == '1')
            {
                $categoryType = $request->delivery_category_type;
                if ($categoryType == '3')
                {
                    $notificationType = 'Order';
                    $categoryName = 'Order Assignee';
                }
                if ($categoryType == '4')
                {
                    $notificationType = 'Order';
                    $categoryName = 'Order Delivered';
                }
                $deliveryBoy = DeliveryAssociate::where('deleted_at','')->where('status','1')->get();
                if (!empty($deliveryBoy->toArray()))
                {
                    foreach($deliveryBoy as $value)
                    {
                        $notification = new Notification;
                        $notification->receiver_id = $value->id;
                        $notification->receiver_type = 'delivery';
                        $notification->sender_id = $adminId;
                        $notification->sender_type = 'Admin';
                        $notification->category_type = $categoryName;
                        $notification->notification_type = $notificationType;
                        $notification->type_id = '';
                        $notification->message = $notificationMessage;
                        $notification->deleted_at = '';
                        $notification->save();

                        $token = $value->device_token;
                        sendDriverNotification($notificationMessage, $token, $categoryName,$categoryType, $notificationType,'');
                    }
                }
            }
            return redirect()->route('admin.notification.create')->with('success','notification send successfully');
        }
    }
}
