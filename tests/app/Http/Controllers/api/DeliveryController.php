<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\api\ApiBaseController as ApiBaseController;
use Illuminate\Http\Request;
use App\Models\DeliveryAssociate;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\User;
use App\Models\ReviewAndRating;
use App\Models\Color;
use App\Models\Size;
use App\Models\Brand;
use App\Models\OrderItem;
use App\Models\Notification;
use stdClass;
use Auth;
use Carbon\Carbon;

class DeliveryController extends ApiBaseController
{
   public function updateDeliveryProfilePhoto(Request $request)
   {
        try 
        {
            $deliveryData = auth('sanctum')->user();
            $deliveryId = $deliveryData->id;
            $delivery = DeliveryAssociate::where('deleted_at','')->find($deliveryId);
            if ($delivery)
            {
                // dd($request->hasFile('proﬁle_image'));
                if ($request->file('proﬁle_image'))
                {
                    $file = $request->file('proﬁle_image');
                    $profile_pic = date('y-m-d').'_'.time().'_'.$file->getClientOriginalName();
                    $file->move('uploads/user',$profile_pic);
                    $delivery->profile_pic = $profile_pic;
                    $delivery->deleted_at = '';
                    $delivery->save();
                    $delivery->profile_pic = (!empty($delivery->profile_pic)) ? asset('uploads/delivery_associates/'.$delivery->profile_pic) : '';
                    unset($delivery->password);
                    return $this->sendResponse(200, $delivery, "Profile Update Successfully"); 
                }
                else
                {
                    $delivery = new stdClass;
                    return $this->sendResponse(201, $delivery, 'please Select Profile Image');
                }
            }
            else
            {
                $delivery = new stdClass;
                return $this->sendResponse(201,$delivery,'data not found');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage()); 
        }
        
   }
   // public function deliverLogin(Request $request)
   // {
   //      try
   //      {

   //          $email = $request->email;
   //          $password = $request->password;
   //          $deviceType = $request->device_type;
   //          $deviceToken = $request->device_token;
   //          if (isset($email) && isset($password) && isset($deviceType) && isset($deviceToken))
   //          { 
   //              $deliveryAssociate = DeliveryAssociate::where('deleted_at','')->where('email',$email)->first();
   //              // $Decpassword = Crypt::decryptString($deliveryAssociate->password);
   //              // dd($Decpassword);
   //              if ($deliveryAssociate)
   //              {
   //                  if ($deliveryAssociate->staus == '0')
   //                  {
   //                      $data = new stdClass;
   //                      return $this->sendResponse(201,$data,'Admin temporory blocked you');           
   //                  }
   //                  else
   //                  {
   //                      if ($password == $deliveryAssociate->password)
   //                      {
   //                          $token = $deliveryAssociate->createToken('apiToken')->plainTextToken;
   //                          $deliveryAssociate->token = $token;
   //                          $deliveryAssociate->profile_pic = (isset($deliveryAssociate->profile_pic)) ? asset('uploads/delivery_associates/'.$deliveryAssociate->profile_pic) : '';  
   //                          $deliveryAssociate->vechicle_doc = (!empty($deliveryAssociate->vechicle_doc)) ? asset('uploads/driver_vehicle_doc/'.$deliveryAssociate->vechicle_doc) : '';  
   //                          unset($deliveryAssociate->password);
   //                          return $this->sendResponse(200,$deliveryAssociate, 'login Successfully');
   //                      }
   //                      else
   //                      {
   //                          $data = new stdClass;
   //                          return $this->sendResponse(201,$data, 'Invalid password');
   //                      }
   //                  }
   //              }
   //              else
   //              {
   //                  $data = new stdClass;
   //                  return $this->sendResponse(201,$data, 'Invalid email address');
   //              }
   //          }
   //          else
   //          {
   //              $data = new stdClass;
   //              return $this->sendResponse(201,$data,'Please fill all required field');
   //          }
   //      }
   //      catch (Exception $e) 
   //      {
   //          return $this->sendError(201,$e->getMessage()); 
   //      }
   // }
    public function deliverLogout(Request $request)
    {
        $deliveryData = auth('sanctum')->user();
        $deliveryId = $deliveryData->id;
        $deliveryDetail = DeliveryAssociate::where('deleted_at','')->where('id',$deliveryId)->first();
        if (!empty($deliveryDetail))
        {
            if (Auth::check())
            {
                auth('sanctum')->user()->tokens->each(function($token, $key) {
                    $token->delete();
                });
                // $request->bearerToken()->delete(); 
                // dd(auth('sanctum')->user()->bearerToken());
                // Auth::user()->bearerToken()->delete();
                $data = new stdClass;
                return $this->sendResponse(200,$data,'logout successfully');
            }
        }
        else
        {
            $deliveryDetail = new stdClass;
            return $this->sendResponse(201,$deliveryDetail,'data not found');
        }
    }
   public function deliverLogin(Request $request)
   {
        try
        {

            $email = $request->email;
            $password = $request->password;
            // dd(Crypt::encryptString($password));
            $deviceType = $request->device_type;
            $deviceToken = $request->device_token;
            $passLength = strlen($password);
            if (isset($email) && isset($password) && isset($deviceType) && isset($deviceToken))
            {
                if ($passLength < 8 && $passLength <= 15)
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'Password having length between 8-15');
                } 
                else
                {
                    $deliveryAssociate = DeliveryAssociate::where('deleted_at','')->where('email',$email)->first();
                    // $Decpassword = Crypt::decryptString($deliveryAssociate->password);
                    // dd($Decpassword);
                    if ($deliveryAssociate)
                    {
                        if (Crypt::decryptString($deliveryAssociate->password) == $password)
                        {
                            if ($deliveryAssociate->status == 0)
                            {
                                $data = new stdClass;
                                return $this->sendResponse(201,$data,'Admin temporory blocked you');           
                            }
                            else
                            {
                                if (Crypt::decryptString($deliveryAssociate->password) == $password)
                                {
                                    $token = $deliveryAssociate->createToken('apiToken')->plainTextToken;
                                    $deliveryAssociate->token = $token;
                                    $deliveryAssociate->profile_pic = (isset($deliveryAssociate->profile_pic)) ? asset('uploads/delivery_associates/'.$deliveryAssociate->profile_pic) : ''; 
                                    $deliveryAssociate->vechicle_doc = (!empty($deliveryAssociate->vechicle_doc)) ? asset('uploads/driver_vehicle_doc/'.$deliveryAssociate->vechicle_doc) : '';    
                                    unset($deliveryAssociate->password);
                                    return $this->sendResponse(200,$deliveryAssociate, 'login Successfully');
                                }
                                else
                                {
                                    $data = new stdClass;
                                    return $this->sendResponse(201,$data, 'Invalid password');
                                }
                            }
                        }
                        else
                        {
                            $data = new stdClass;
                            return $this->sendResponse(201,$data, 'Invalid password');
                        }
                    }
                    else
                    {
                        $data = new stdClass;
                        return $this->sendResponse(201,$data, 'Invalid email address');
                    }
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Please fill all required field');
            }
        }
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage()); 
        }
   } 
      
   public function profileDetail(Request $request)
   {
   		try 
   		{
   		    
            $deliveryData = auth('sanctum')->user();
            // dd("test");
            // $deliveryId =$request->id;
            // $deliveryData = $request->DeliveryAssociate();
            $deliveryId = $deliveryData->id;
   			$deliveryDetail = DeliveryAssociate::where('deleted_at','')->where('id',$deliveryId)->first();
            if (!empty($deliveryDetail))
            {
               $deliveryDetail->profile_pic = (!empty($deliveryDetail->profile_pic)) ? asset('uploads/delivery_associates/'.$deliveryDetail->profile_pic) : '';
               $deliveryDetail->vechicle_doc = (!empty($deliveryDetail->vechicle_doc)) ? asset('uploads/driver_vehicle_doc/'.$deliveryDetail->vechicle_doc) : '';
               unset($deliveryDetail->password);
                return $this->sendResponse(200,$deliveryDetail,'Delivery Detail');
            }
            else
            {
                $deliveryDetail = new stdClass;
                return $this->sendResponse(201,$deliveryDetail,'data not found');
            }
   		} 
   		catch (Exception $e) 
   		{
            return $this->sendError(201,$e->getMessage());
   		}
   }
   public function bookingList(Request $request)
   {
        try 
        {   

            $deliveryData = auth('sanctum')->user();
            $deliveryId = $deliveryData->id;
            // dd($deliveryData);
            $pageNumber = isset($request->page_number) ? $request->page_number : 1;
            $pageSize = isset($request->page_size) ? $request->page_size : 10;

            $firstName = $deliveryData->first_name;
            $lastName = $deliveryData->last_name;
            $orderStatus = $request->order_status;
            if($orderStatus)
            {
                if ($orderStatus == '1')
                {
                    $bookingListCollection = Order::where('deleted_at','')->where('delivery_associates_id',$deliveryId)->whereIn('order_status',[3,4])
                    ->orWhere(function($query) use ($deliveryId) {
                        $query
                            ->where('delivery_associates_id',$deliveryId)
                            ->where('deleted_at','')
                            ->where('order_status', '5')
                            ->where('pickup_done', '1');
                    })->orderBy('id','desc')->paginate($pageSize,'*','page',$pageNumber);

                    //Print Query
                    // return vsprintf(str_replace('?', '%s', $bookingListCollection->toSql()), collect($bookingListCollection->getBindings())->map(function ($binding) {
                    //     return is_numeric($binding) ? $binding : "'{$binding}'";
                    // })->toArray());
                }
                elseif ($orderStatus == '2')
                {
                    $bookingListCollection = Order::where('deleted_at','')->whereIn('order_status',[1,2,6])->where('delivery_associates_id',$deliveryId)->orderBy('id','desc')->paginate($pageSize,'*','page',$pageNumber);
                }
                elseif ($orderStatus == '3')
                {
                    $bookingListCollection = Order::where('deleted_at','')->where('order_status','5')->where('pickup_done','0')->where('delivery_associates_id',$deliveryId)->orderBy('id','desc')->paginate($pageSize,'*','page',$pageNumber);
                }
                $bookingList = $bookingListCollection->items();
                // dd("test");

                $totalPages = $bookingListCollection->lastPage();
                $totalCount = $bookingListCollection->total();
                $pageNumber = $bookingListCollection->currentPage();
                $nextPage = $bookingListCollection->nextPageUrl()?true:false;
                $prevPage = $bookingListCollection->previousPageUrl()?true:false;

                if (!empty($bookingList)) 
                {
                    foreach ($bookingList as $value) 
                    {
                        $value->user_name = '';
                        $userData = User::find($value->user_id);
                        if ($userData)
                        {
                            $value->user_name = $userData->first_name. ' '.$userData->last_name;
                        }
                        // $value->user_name = $firstName . ' '.$lastName;
                        $orderStatus = $value->order_status;
                        if ($orderStatus == '1')
                        {
                            $value->order_status = 'Pending';
                        }
                        elseif($orderStatus == '2')
                        {
                            $value->order_status = 'Assigned';
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
                    }
                    // return $this->sendResponse(200,$bookingList,'Booking List...');
                    return $this->paginatResponse(200, $bookingList , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, 'Booking List...');
                }
                else
                {
                    // $bookingList = new stdClass;
                    return $this->sendResponse(201,$bookingList,'No data found');
                }
            }
            else
            {
                $orderStatus = new stdClass;
                return $this->sendResponse(201,$orderStatus,'Please Enter Order status');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());  
        }
   }
   public function saveDliveryAssociates(Request $request)
   {
        try 
        {
            $dliveryAssociates = new DeliveryAssociate;
            $password = $request->password;

            // dd(strlen($password));
            if ($request->first_name)
            {
                $dliveryAssociates->first_name = $request->first_name;
            }
            if ($request->last_name)
            {
                $dliveryAssociates->last_name = $request->last_name;
            }
            if ($request->email)
            {
                $dliveryAssociates->email = $request->email;
            }
            if($request->mobile_number)
            {
                $dliveryAssociates->mobile_number = $request->mobile_number;
            }
            if ($request->password)
            {
                $dliveryAssociates->password = Crypt::encryptString($request->password);
            }
            if ($request->vechicle_number)
            {
                $dliveryAssociates->vechicle_number = $request->vechicle_number;
            }
            if ($request->license_number)
            {
                $dliveryAssociates->license_number = $request->license_number;
            } 
            $dliveryAssociates->device_type = $request->device_type;
            $dliveryAssociates->device_token = $request->device_token;
            if ($image = $request->file('profile_pic')) 
            {
                $destinationPath = 'uploads/delivery_associates/';
                $profilePic = time() . "." . $image->getClientOriginalName();
                $image->move($destinationPath, $profilePic);
                $dliveryAssociates['profile_pic'] = "$profilePic";
            }
            if ($image = $request->file('vechicle_doc')) 
            {
                $vechicleImage = 'uploads/driver_vehicle_doc/';
                $vechicleDoc = time() . "." . $image->getClientOriginalName();
                $image->move($vechicleImage, $vechicleDoc);
                $dliveryAssociates['vechicle_doc'] = "$vechicleDoc";
            }
            $dliveryAssociates->status = '0';
            $dliveryAssociates->deleted_at = '';
            $dliveryAssociates->save();
            $token = $dliveryAssociates->createToken('apiToken')->plainTextToken;
            $dliveryAssociates->token = $token;
            return $this->sendResponse(200,$dliveryAssociates, "delivery Associates Created Successfully...!");
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());  
        }

   }
   public function deliveryOrderDetail(Request $request)
   {
        try
        {
            $deliveryData = auth('sanctum')->user();
            $deliveryId = $deliveryData->id;
            $orderId = $request->order_id;

            $orderDetail = Order::where('deleted_at','')->where('delivery_associates_id',$deliveryId)->find($orderId);
            if ($orderDetail)
            {
                $date = date('d M Y',strtotime($orderDetail->created_at));
                $orderDetail->order_date = $date;
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
                $paymentType = $orderDetail->payment_type;
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
                    $orderDetail->order_status = 'Assigned';
                }
                elseif($orderStatus == '3')
                {
                    $orderDetail->order_status = 'Cancelled';
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
                    // dd($orderDetail->productData);
                }

                $reviewAndRating = ReviewAndRating::where('deleted_at','')->where('order_id',$orderId)->count();
                $rating = ReviewAndRating::where('deleted_at','')->where('order_id',$orderId)->sum('rate');
                if ($reviewAndRating != 0)
                {
                    $rating = $rating / $reviewAndRating;
                }
                $rating = sprintf("%.1f",$rating);
                $orderDetail->rating = $rating;
                $orderDetail->orderItem = $orderItem;
                $address = UserAddress::find($orderDetail->user_address_id);
                $data['orderDetail'] = $orderDetail;
                $data['delivery_address'] = $address;
                unset($orderDetail->created_at);
                unset($orderDetail->updated_at);
                unset($orderDetail->deleted_at);
                return $this->sendResponse(200, $data, 'Order Detail');
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Order not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
   }
   public function deliveryHomeData(Request $request)
   {
        try 
        {
            $deliveryData = auth('sanctum')->user();
            $deliveryId = $deliveryData->id;
            $currentDate = date('Y-m-d');
            $returnDate = date('d M Y');
            // dd($deliveryData);
            $totalCashCollectad = Order::where('deleted_at','')->where('delivery_associates_id',$deliveryId)->where('payment_type','1')->where('delivered_date',date('Y-m-d'))->sum('total_amount');

            //Print Query
            // return vsprintf(str_replace('?', '%s', $totalCashCollectad->toSql()), collect($totalCashCollectad->getBindings())->map(function ($binding) {
            //     return is_numeric($binding) ? $binding : "'{$binding}'";
            // })->toArray());

            $totalAssignceOrder = Order::where('deleted_at','')->whereIn('order_status',[1,2,6])->where('assign_date',$currentDate)->where('delivery_associates_id',$deliveryId)->count();

            $totalOrderDelivered = Order::where('deleted_at','')->where('delivered_date',$currentDate)->where('delivery_associates_id',$deliveryId)->where('order_status','4')->count();
            $totalReturnedOrder = Order::where('deleted_at','')->where('return_date',$returnDate)->where('delivery_associates_id',$deliveryId)->where('order_status','5')->count();
            $totalCancelOrder = Order::where('deleted_at','')->where('return_date',$returnDate)->where('delivery_associates_id',$deliveryId)->where('order_status','3')->count();

            $notificationCount = Notification::where('deleted_at','')->where('is_read','0')->where('receiver_type','delivery')->where('receiver_id',$deliveryId)->where('created_at', '>', now()->subDays(30)->endOfDay())->count();

            $data['totalCashCollectad'] = (string)$totalCashCollectad;
            $data['totalAssigneOrder'] = (string)$totalAssignceOrder;
            $data['totalOrderDelivered'] = (string)$totalOrderDelivered;
            $data['totalReturnedOrder'] = (string)$totalReturnedOrder;
            $data['totalCancelOrder'] = (string)$totalCancelOrder;
            $data['notificationCount'] = $notificationCount;
            
            return $this->sendResponse(200,$data,'Home Dtata Count');
        }
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());
        }
   }
    public function changeOrderStatus(Request $request)
    {
        try
        {
            $deliveryData = auth('sanctum')->user();
            $deliveryId = $deliveryData->id;

            $orderStatus = $request->order_status;
            $orderId = $request->order_id;
            $remark = $request->remark ?? '';

            if (isset($orderStatus) && isset($orderId))
            {
                if ($orderStatus == '1')
                {
                    $orderDetail = Order::where('deleted_at','')->where('order_status','2')->where('delivery_associates_id',$deliveryId)->find($orderId);
                    if ($orderDetail)
                    {
                        $orderDetail->order_status = '4';
                        $orderDetail->delivered_date = date('Y-m-d');
                        $orderDetail->remark = $remark;
                        $orderDetail->save();
                        $orderDetail->order_status_name = "Delivered";
                        $updateOrderItem = OrderItem::where('order_id',$orderId)->update(['order_status'=>'4']);

                        $message = "Your Order has been Successfully Delivered.";
                        $notification = new Notification;
                        $notification->receiver_id = $deliveryId;
                        $notification->receiver_type = 'delivery';
                        $notification->sender_id = '';
                        $notification->sender_type = '';
                        $notification->category_type = 'Order Delivered';
                        $notification->notification_type = 'Order';
                        $notification->type_id = $orderId;
                        $notification->message = $message;
                        $notification->deleted_at = '';
                        $notification->save();

                        $notification = new Notification;
                        $notification->receiver_id = $orderDetail->user_id;
                        $notification->receiver_type = 'user';
                        $notification->sender_id = $deliveryId;
                        $notification->sender_type = 'delivery';
                        $notification->category_type = 'Order';
                        $notification->notification_type = 'Order';
                        $notification->type_id = $orderId;
                        $notification->order_status = '4';
                        $notification->message = $message;
                        $notification->deleted_at = '';
                        $notification->save();

                        $userData = User::where('notification','1')->find($orderDetail->user_id);
                        if ($userData)
                        {
                            $token = $userData->device_token;
                            sendCustomerNotification($message, $token, 'Order','1', 'Order',$orderId);
                        }
                        $deliveryData = DeliveryAssociate::find($deliveryId);
                        if ($deliveryData)
                        {
                            $token = $deliveryData->device_token;
                            sendDriverNotification($message, $token, 'Order Delivered','4', 'Order',$orderId);
                        }
                        $message = "Order Delivered Successfully...!";
                        $notification = new Notification;
                        $notification->receiver_id = '';
                        $notification->receiver_type = 'admin';
                        $notification->sender_id = $deliveryId;
                        $notification->sender_type = '';
                        $notification->category_type = 'Order';
                        $notification->notification_type = 'Order';
                        $notification->type_id = $orderId;
                        $notification->order_status = '4';
                        $notification->message = $message;
                        $notification->deleted_at = '';
                        $notification->save();
                        return $this->sendResponse(200,$orderDetail,'order delivered successfully');
                    }
                    else
                    {
                        $data  = new stdClass;
                        return $this->sendResponse(201,$data,'order not found');
                    }
                }
                elseif($orderStatus == '2')
                {
                    $orderDetail = Order::where('deleted_at','')->where('order_status','5')->where('delivery_associates_id',$deliveryId)->find($orderId);
                    if ($orderDetail)
                    {
                        $orderDetail->refund_status = '2';
                        $orderDetail->pickup_done = '1';
                        $orderDetail->save();
                        $orderDetail->order_status_name = "Returned";
                        unset($orderDetail->created_at);
                        unset($orderDetail->updated_at);
                        unset($orderDetail->deleted_at);
                        // $updateOrderItem = OrderItem::where('order_id',$orderId)->update(['order_status'=>'4']);

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
                                    $oldUsedQty = (int)$updateProductQty->used_quantity;
                                    $updateProductQty->used_quantity = $oldUsedQty - (int)$quantity;
                                    $updateProductQty->save();
                                }
                            }
                        }
                        $userData = User::where('notification','1')->find($orderDetail->user_id);
                        $message = "Your Order has been Successfully Returned.";
                        if ($userData)
                        {
                            $token = $userData->device_token;
                            sendCustomerNotification($message, $token, 'Order','1', 'Order',$orderId);
                        }

                        $notification = new Notification;
                        $notification->receiver_id = $orderDetail->user_id;
                        $notification->receiver_type = 'user';
                        $notification->sender_id = $deliveryId;
                        $notification->sender_type = 'delivery';
                        $notification->category_type = 'Order';
                        $notification->notification_type = 'Order';
                        $notification->type_id = $orderId;
                        $notification->order_status = '5';
                        $notification->message = $message;
                        $notification->deleted_at = '';
                        $notification->save();
                        return $this->sendResponse(200,$orderDetail,'order pick up successfully');
                    }
                    else
                    {
                        $data  = new stdClass;
                        return $this->sendResponse(201,$data,'order not found');
                    }
                }
                elseif($orderStatus == '3')
                {
                    $orderDetail = Order::where('deleted_at','')->where('order_status','2')->where('delivery_associates_id',$deliveryId)->find($orderId);
                    if ($orderDetail)
                    {
                        $orderDetail->order_status = '6';
                        $orderDetail->save();
                        $orderDetail->order_status_name = "Out For Service";
                        unset($orderDetail->created_at);
                        unset($orderDetail->updated_at);
                        unset($orderDetail->deleted_at);
                        // $updateOrderItem = OrderItem::where('order_id',$orderId)->update(['order_status'=>'4']);
                        
                        $message = "Your items have been out for delivery";
                        $notification = new Notification;
                        $notification->receiver_id = $orderDetail->user_id;
                        $notification->receiver_type = 'user';
                        $notification->sender_id = $deliveryId;
                        $notification->sender_type = 'delivery';
                        $notification->category_type = 'Order';
                        $notification->notification_type = 'Order';
                        $notification->type_id = $orderId;
                        $notification->order_status = '6';
                        $notification->message = $message;
                        $notification->deleted_at = '';
                        $notification->save();
                        $userData = User::where('notification','1')->find($orderDetail->user_id);
                        if ($userData)
                        {
                            $token = $userData->device_token;
                            sendCustomerNotification($message, $token, 'Order','1', 'Order',$orderId);
                            
                        }
                        return $this->sendResponse(200,$orderDetail,'order out for delivery');
                    }
                    else
                    {
                        $data  = new stdClass;
                        return $this->sendResponse(201,$data,'order not found');
                    }
                }
                else
                {
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'Invalid input');
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Please fill all required field');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }

}