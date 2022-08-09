<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\DeliveryAssociate;
use App\Models\Notification;
use App\Models\ReviewAndRating;
use App\Models\ReviewReply;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // $areas = Area::whereHas('pincode', function ($query) {
        //     $query->where('deleted_at','');
        // })->where('deleted_at','')->orderBy('id', 'DESC')->get();
        $settingMaster = Setting::where('deleted_at','')->first();
        return view('admin.setting.index', compact('settingMaster'));
    }
    public function update(Request $request)
    {
        $settingMaster = Setting::where('deleted_at','')->first();
        $input =  $request->validate([
            'return_day' => 'required|numeric|min:1',
            'support_number' => 'required|digits:10',
            'khalti_secret_key' => 'required',
            'khalti_public_key' => 'required',
            'sms_token' => 'required',
            'mail_mailer' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required',
            'user_notification_sender_id' => 'required',
            'user_notification_key' => 'required',
            'driver_notification_sender_id' => 'required',
            'driver_notification_key' => 'required',
        ]);
        if ($settingMaster)
        {
            $settingMaster->return_day = $input['return_day'];
            $settingMaster->support_number = $input['support_number'];
            $settingMaster->khalti_secret_key = $input['khalti_secret_key'];
            $settingMaster->khalti_public_key = $input['khalti_public_key'];
            $settingMaster->sms_token = $input['sms_token'];
            $settingMaster->mail_mailer = $input['mail_mailer'];
            $settingMaster->mail_host = $input['mail_host'];
            $settingMaster->mail_port = $input['mail_port'];
            $settingMaster->mail_username = $input['mail_username'];
            $settingMaster->mail_password = $input['mail_password'];
            $settingMaster->mail_encryption = $input['mail_encryption'];
            $settingMaster->mail_from_address = $input['mail_from_address'];
            $settingMaster->user_notification_sender_id = $input['user_notification_sender_id'];
            $settingMaster->user_notification_key = $input['user_notification_key'];
            $settingMaster->driver_notification_sender_id = $input['driver_notification_sender_id'];
            $settingMaster->driver_notification_key = $input['driver_notification_key'];
            $settingMaster->save();
            return redirect()->back()->with('success','Data Updated successfully');
        }
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

    public function reviewList()
    {
        $reviewList = ReviewAndRating::where('deleted_at','')->get();
        return view('admin.review.reviewList', compact('reviewList'));
    }
    public function reviewReplyForm($id)
    {
        $reviewData = ReviewAndRating::where('deleted_at','')->findOrFail($id);
        return view('admin.review.replyForm',compact('reviewData'));
    }
    public function submitReply(Request $request)
    {
        $request->validate([
            'reply_message' => 'required'
        ]);

        $reviewId = $request->review_id;
        $reviewData = ReviewAndRating::where('deleted_at','')->find($reviewId);
        $adminId = auth()->user()->id;

        $categoryName = 'Promotion';
        $notificationType = 'Offer';

        $replyMessage = $request->reply_message;
        $reviewReply = new ReviewReply;
        $reviewReply->review_id = $reviewId;
        $reviewReply->message = $replyMessage;
        $reviewReply->deleted_at = '';
        $reviewReply->save();

        if ($reviewData)
        {
            $userData = User::where('deleted_at','')->find($reviewData->user_id);
            if ($userData)
            {
                $message = "Hey, ".$userData->first_name." ".$userData->last_name." Admin has give your product review reply";
                $notification = new Notification;
                $notification->receiver_id = $reviewData->user_id;
                $notification->receiver_type = 'user';
                $notification->sender_id = $adminId;
                $notification->sender_type = 'Admin';
                $notification->category_type = $categoryName;
                $notification->notification_type = $notificationType;
                $notification->type_id = '';
                $notification->message = $message;
                $notification->deleted_at = '';
                $notification->save();
                $token = $userData->device_token;
                sendCustomerNotification($message, $token, $categoryName,'2', $notificationType,'');
            }
        }
        return redirect()->route('admin.review.review')->with('success','Review Reply send successfully');
    }
}
