<?php

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SuperCategory;
use App\Models\SuperSubCategory;
use App\Models\Order;
use App\Models\User;
use App\Models\Setting;
use App\Models\OrderItem;
use App\Models\Size;
use App\Models\Color;
use App\Models\Brand;
use App\Models\ReviewAndRating;
use App\Models\Product;
use App\Models\WishList;
use App\Models\UserAddress;
use App\Models\Notification;
use App\Models\Cart;
use App\Models\WebCmsMaster;
use App\Mail\OrderMail;
// use Storage;

function getCategoryData(){
    $superCategoryList = SuperCategory::where('deleted_at','')->where('supercategory_status','1')->limit(5)->get();
    
    if(!empty($superCategoryList)){
        foreach($superCategoryList as $key => $superCategory){
            $superCategory->supercategory_image = (!empty($superCategory->supercategory_image)) ? asset('uploads/super_category/'.$superCategory->supercategory_image) : '';
            $superSubCategoryList = SuperSubCategory::where('deleted_at','')->where('supersub_status','1')->where('super_category_id',$superCategory->id)->get();
            $superCategory->superSubCategoryList = [];
            if (!empty($superSubCategoryList->toArray()))
            {
                foreach($superSubCategoryList as $superSubCategory)
                {
                    $categoryList = Category::where('deleted_at','')->where('status','1')->where('supersub_cat_id',$superSubCategory->id)->get();
                    if (!empty($categoryList->toArray())) 
                    {                            
                        foreach ($categoryList as $value) 
                        {                                
                            $subCategoryList = SubCategory::where(['deleted_at' =>'','category_id' => $value->id,'status' => '1'])->get();                                
                            $value->subCategoryList = $subCategoryList;
                        }
                    }

                    $superSubCategory->categoryList = $categoryList;
                }
                $superCategory->superSubCategoryList = $superSubCategoryList;
            }
        }
    }
    return $superCategoryList;
}

function genratePdf($userId,$orderId)
{
    $orderDetail = Order::where('deleted_at','')->where('user_id',$userId)->find($orderId);
    if ($orderDetail)
    {
        $orderNo = $orderDetail->order_no;
        $customerName = '';
        $userData = User::find($orderDetail->user_id);
        if ($userData)
        {
            $customerName = $userData->first_name. ' '.$userData->last_name;
        }
        $date = date('d M Y',strtotime($orderDetail->created_at));
        $time = date('H i ',strtotime($orderDetail->created_at));
        $orderDetail->order_date = $date;
        $orderDetail->order_time = $time;
        $estimateDate = date('d M Y', strtotime($date. ' + '.$orderDetail->estimate_delivery_days.' days'));
        $orderDetail->estimate_date = $estimateDate;
        $total = $orderDetail->total_amount;
        $cashOnDelivery = $orderDetail->shipping_charge;
        $totalDiscount = $orderDetail->total_discount;
        $MRP = $total + $cashOnDelivery +$totalDiscount;
        $orderDetail->mrp = $MRP . ' NR';
        $orderDetail->item_discount = $totalDiscount . ' NR';
        $orderDetail->cash_on_delivery = $cashOnDelivery . ' NR';
        $orderDetail->total = $total . ' NR';
        $orderDetail->invoice = '';
        $paymentType = $orderDetail->payment_type;

        $orderDetail->is_cancel = '1';
        if ($orderDetail->order_status == '1' || $orderDetail->order_status == '2')
        {
            $orderDetail->is_cancel = '0';
        }
        $orderDetail->is_return = '1';
        if ($orderDetail->order_status == '4')
        {
            $orderDeliveredDate = date('Y-m-d', strtotime($orderDetail->delivered_date));
            $currentDate = date('Y-m-d');
            $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
            if ($masterData)
            {
                $returnDay = $masterData->return_day;
                $returnDate = date('Y-m-d', strtotime($orderDeliveredDate. ' + '.$returnDay.' days'));

                if ($currentDate < $returnDate)
                {
                    $orderDetail->is_return = '0';
                }
            }

        }
        if ($paymentType == '1')
        {
            $orderDetail->payment_type = 'COD';
        }
        if ($paymentType == '2')
        {
            $orderDetail->payment_type = 'Online';
        }

        $orderStatus = $orderDetail->order_status;
        if ($orderStatus == '1')
        {
            $orderDetail->order_status = 'Pending';
        }
        elseif($orderStatus == '2')
        {
            $orderDetail->order_status = 'Confirmed';
        }
        elseif($orderStatus == '3')
        {
            $orderDetail->order_status = 'Canceled';
        }
        elseif($orderStatus == '4')
        {
            $orderDetail->order_status = 'Delivered';
        }
        elseif($orderStatus == '5')
        {
            $orderDetail->order_status = 'Returned';
        }
        elseif($orderStatus == '6')
        {
            $orderDetail->order_status = 'Out For Service';
        }
        $refundStatus = $orderDetail->refund_status;
        if ($refundStatus == '1')
        {
            $orderDetail->refund_status = 'Refund Has Been Intitaion';
        }
        if ($refundStatus == '2')
        {
            $orderDetail->refund_status = 'Refund Has Been Done';
        }
        $orderItem = OrderItem::where('deleted_at','')->where('order_status',$orderStatus)->where('order_id',$orderDetail->id)->get();
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
                $productPrice = $value->product_price;
                $discountPrice = $value->total_amount;
                $savedAmount = $value->product_discount;
                $value->product_price = $productPrice; 
                $value->discount_price = $discountPrice; 
                $value->saved_amount = $savedAmount;
                $value->price = $productPrice * $value->quantity;
                $value->is_rate = '0';
                $chekAlreadyInserted = ReviewAndRating::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->where('order_item_id',$value->id)->first();
                if ($chekAlreadyInserted)
                {
                    $value->is_rate = '1';
                }
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
            // dd($orderDetail->productData);
        }
        $orderDetail->orderItem = $orderItem;
        $address = UserAddress::find($orderDetail->user_address_id);
        $data['orderDetail'] = $orderDetail;
        $data['delivery_address'] = $address;
        unset($orderDetail->created_at);
        unset($orderDetail->updated_at);
        unset($orderDetail->deleted_at);
        // dd("test");
        $pdf = PDF::loadView('invoice.invoice', $data);
        // return view('invoice.invoice',compact('data'));
        // var_dump($pdf);
        // dd($pdf);
        // return $pdf->download('demoPdf.pdf');
        // dd($pdf->download());
        $pdfName = $orderId.'.pdf';
        $content = $pdf->download()->getOriginalContent();
        // app/invoice/ 
        \Storage::put('invoice/'.$pdfName,$content);
        $updateOrder = Order::where('deleted_at','')->where('user_id',$userId)->find($orderId);
        if ($updateOrder)
        {
           $updateOrder->invoice = $pdfName;
           $updateOrder->save();
        }
        // $source = file_get_contents($pdf->download());
        // file_put_contents("uploads/invoice/".$customerName, $content);

        // return $this->sendResponse(200, $data, 'Order Detail');
    }
}

function sendCustomerNotification($message,$token,$categoryType,$categoryTypeId,$notificationType,$typeId)
{
    $title = 'BheshBhusha';
    $senderId = configuration('user_notification_sender_id');
    $push_notification_key = configuration('user_notification_key');
    $url = "https://fcm.googleapis.com/fcm/send";
    
    $header = array("authorization: key=" . $push_notification_key . "",
        "content-type: application/json"
    );
    $postdata = '{
        "to" : "' . $token . '",
            "notification" : {
                "title":"' . $message . '",
                "text" : "' . $message . '"
            },
        "data" : {
            "id" : "'.$senderId.'",
            "category_type" : "'.$categoryType.'",
            "category_type_id" : "'.$categoryTypeId.'",
            "notification_type" : "'.$notificationType.'",
            "type_id" : "'.$typeId.'",
            "id" : "'.$senderId.'",
            "title":"' . $title . '",
            "description" : "' . $message . '",
            "text" : "' . $message . '",
            "is_read": 0
          }
    }';
    $ch = curl_init();
    $timeout = 120;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // Get URL content
    $result = curl_exec($ch);    
    // close handle to release resources
    curl_close($ch);
}
function sendDriverNotification($message,$token,$categoryType,$categoryTypeId,$notificationType,$typeId)
{
    $title = 'BheshBhusha';
    $senderId = configuration('driver_notification_sender_id');
    $push_notification_key = configuration('driver_notification_key');
    $url = "https://fcm.googleapis.com/fcm/send";
    
    $header = array("authorization: key=" . $push_notification_key . "",
        "content-type: application/json"
    );
    $postdata = '{
        "to" : "' . $token . '",
            "notification" : {
                "title":"' . $message . '",
                "text" : "' . $message . '"
            },
        "data" : {
            "id" : "'.$senderId.'",
            "category_type" : "'.$categoryType.'",
            "category_type_id" : "'.$categoryTypeId.'",
            "notification_type" : "'.$notificationType.'",
            "type_id" : "'.$typeId.'",
            "id" : "'.$senderId.'",
            "title":"' . $title . '",
            "description" : "' . $message . '",
            "text" : "' . $message . '",
            "is_read": 0
          }
    }';

    $ch = curl_init();
    $timeout = 120;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // Get URL content
    $result = curl_exec($ch);    
    // close handle to release resources
    curl_close($ch);
}
function configuration($key)
{
    $configurationData = Setting::select($key)->first();
    if ($configurationData)
    {
        return $configurationData->$key;
    }
    return '';
}
function adminNotificationList($limit = 0)
{
    $notificationList = Notification::where('deleted_at','')->where('receiver_type','admin')->where('is_read','0')->orderBy('id','DESC');
    if ($limit != 0)
    {
        $notificationList->limit($limit);
    }
    $notificationList = $notificationList->get();
    return $notificationList;
}
function cartCount($customerId)
{
    return $count = Cart::where('deleted_at','')->where('user_id',$customerId)->count();
}

function webCmsMaster()
{
    $cmsList = WebCmsMaster::where('deleted_at', '')->first();
    return $cmsList;
}
function sendMail($email = '',$message)
{
    $masterData = Setting::where('deleted_at','')->orderBy('id','desc')->first();
    if ($masterData)
    {
        config(['mail.mailers.smtp.transport'=> $masterData->mail_mailer]);
        config(['mail.mailers.smtp.host'=> $masterData->mail_host]);
        config(['mail.mailers.smtp.port'=> $masterData->mail_port]);
        config(['mail.mailers.smtp.encryption'=> $masterData->mail_encryption]);
        config(['mail.mailers.smtp.username'=> $masterData->mail_username]);
        config(['mail.mailers.smtp.password'=> $masterData->mail_password]);
        config(['mail.from.address'=> $masterData->mail_from_address]);
    }
    Mail::to($email)->send(new OrderMail($email,$message));
}
function sendSms($mobileNo = '',$otp = '1234')
{
    $message = 'Your OTP for login '.$otp;

    $args = http_build_query(array(
    'token' => 'v2_T7tHFZwTUO8sso3QNv8vCMZmAA1.tHyQ',
    'from'  => 'BhesBhusa',
    'to'    => $mobileNo,
    'text'  => $message));

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
}