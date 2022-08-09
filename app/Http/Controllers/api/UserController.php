<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiBaseController as ApiBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Notification;
use Auth;
use Storage;
use stdClass;

class UserController extends ApiBaseController
{
    public function user(Request $request)
    {
        try
        {
            $deliveryData = auth('sanctum')->user();
            // $userData = $request->user();
            if ($deliveryData)
            {
                unset($deliveryData->otp);
                $deliveryData->profile_pic = (!empty($deliveryData->profile_pic)) ? asset('uploads/user/'.$deliveryData->profile_pic) : '';
                return $this->sendResponse(200, $deliveryData,'User Data');
            }
            else
            {
                $deliveryData = new stdClass;
                return $this->sendResponse(201,$deliveryData,'user not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function register(Request $request)
    {
        $input = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required',
            'mobile_number' => 'required|unique:users,mobile_number',
            'gender' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);
        $input['mode_of_registration'] = 'Application';
        $user = User::create($input);
        $token = $user->createToken('apiToken')->plainTextToken;
        return response(['user'=>$user,'token'=>$token]);
    }
    public function login_(Request $request)
    {
        $input = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if(Auth::attempt($input)){
            
            $user = Auth::user();
            $token = $user->createToken('apiToken')->plainTextToken;
            return response(['user'=>$user,'token'=>$token]);
        }else{
            return response(['message' => 'Invalid login credentials.']);
        }
        
    }
    public function login(Request $request)
    {
        try
        {
            $mobileNo = $request->mobile_number;
            $socialMedia = $request->social_media;
            $socialToken = $request->social_token;
            $firstName = $request->first_name;
            $lastName = $request->last_name;
            $email = $request->email;
            $gender = $request->gender;
            $deviceType = $request->device_type;
            $deviceToken = $request->device_token;
            $socialImage = $request->social_image;
            if (isset($socialMedia) && isset($deviceType) && isset($deviceToken))
            {
                if (!empty($mobileNo))
                {
                    $chekUser = User::where('mobile_number',$mobileNo)->first();
                    if ($chekUser)
                    {
                        if ($chekUser->status != '1')
                        {
                            // $data['userData'] = $chekUser;
                            unset($chekUser->created_at);
                            unset($chekUser->updated_at);
                            unset($chekUser->deleted_at);
                            // $chekUser = Auth::user();
                            // $token = $chekUser->createToken('apiToken')->plainTextToken;
                            // $chekUser->token = $token;
                            $chekUser->profile_pic = (!empty($chekUser->profile_pic)) ? asset('uploads/user/'.$chekUser->profile_pic) : '';
                            return $this->sendResponse(202,$chekUser,'Admin Temparay bolcked You');
                        }
                        else
                        {
                            if ($socialMedia == $chekUser->social_media)
                            {
                                
                                // $otp = '1234';
                                $otp = (string)mt_rand(1000,9999);
                                sendSms($mobileNo,$otp);
                                // $args = http_build_query(array(
                                //     'token' => configuration('sms_token'),
                                //     'from'  => 'BhesBhusa',
                                //     'to'    =>  $mobileNo,
                                //     'text'  => 'Your OTP for login '.$otp));

                                // $url = "http://api.sparrowsms.com/v2/sms/";

                                // # Make the call using API.
                                // $ch = curl_init();
                                // curl_setopt($ch, CURLOPT_URL, $url);
                                // curl_setopt($ch, CURLOPT_POST, 1);
                                // curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
                                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                                // // Response
                                // $response = curl_exec($ch);
                                // $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                // curl_close($ch);
                                // echo $response;exit;
                                if (isset($firstName))
                                {
                                    $chekUser->first_name = $firstName;
                                }
                                if (isset($lastName))
                                {
                                    $chekUser->last_name = $lastName;
                                }
                                if (isset($gender))
                                {
                                    $chekUser->gender = $gender;
                                }
                                $chekUser->otp = $otp;
                                $chekUser->device_type = '';
                                if (isset($deviceType))
                                {
                                    $chekUser->device_type = $deviceType;
                                }
                                $chekUser->device_token = '';
                                if (isset($deviceToken)) 
                                {
                                    $chekUser->device_token = $deviceToken;
                                }
                                if (isset($socialToken))
                                {
                                    $chekUser->social_token = $socialToken;
                                }
                                if (isset($socialImage))
                                {
                                    $img = date('y-m-d').'_'.time().'_userProfile.png';
                                    $source = file_get_contents($socialImage);
                                    file_put_contents("uploads/user/".$img, $source);
                                    $chekUser->profile_pic = $img;
                                }
                                $chekUser->save();
                                // $data['userData'] = $chekUser;
                                unset($chekUser->otp);
                                unset($chekUser->created_at);
                                unset($chekUser->updated_at);
                                unset($chekUser->deleted_at);
                                // $chekUser = Auth::user();
                                // $token = $chekUser->createToken('apiToken')->plainTextToken;
                                // $chekUser->token = $token;
                                $chekUser->profile_pic = (!empty($chekUser->profile_pic)) ? asset('uploads/user/'.$chekUser->profile_pic) : '';
                                return $this->sendResponse(200, $chekUser, 'Login Successfully');
                            }
                            else
                            {
                                unset($chekUser->otp);
                                unset($chekUser->created_at);
                                unset($chekUser->updated_at);
                                unset($chekUser->deleted_at);
                                // $chekUser = Auth::user();
                                // $token = $chekUser->createToken('apiToken')->plainTextToken;
                                // $chekUser->token = $token;
                                $chekUser->profile_pic = (!empty($chekUser->profile_pic)) ? asset('uploads/user/'.$chekUser->profile_pic) : '';
                                if ($chekUser->social_media == '1')
                                {
                                    return $this->sendResponse(201,$chekUser, 'This Mobile Number Already used With FaceBook Login');
                                }
                                elseif($chekUser->social_media == '2')
                                {
                                    return $this->sendResponse(201,$chekUser, 'This Mobile Number Already used With Google Login');
                                }
                                elseif($chekUser->social_media == '3')
                                {
                                    return $this->sendResponse(201,$chekUser, 'This Mobile Number Already used With Apple Login');
                                }
                                else
                                {
                                    return $this->sendResponse(201,$chekUser, 'This Mobile Number Already used With Normal Login');
                                }
                            }
                        }
                    }
                    else
                    {
                        $chekUser = User::where('social_media',$socialMedia)->where('social_token',$socialToken)->where('email',$email)->first();
                        if ($chekUser)
                        {
                            // $otp = '1234';
                            $otp = (string)mt_rand(1000,9999);
                            sendSms($mobileNo,$otp);
                            $chekUser->otp = $otp;
                            // $args = http_build_query(array(
                            //     'token' => configuration('sms_token'),
                            //     'from'  => 'BhesBhusa',
                            //     'to'    =>  $mobileNo,
                            //     'text'  => 'Your OTP for login '.$otp));

                            // $url = "http://api.sparrowsms.com/v2/sms/";

                            // # Make the call using API.
                            // $ch = curl_init();
                            // curl_setopt($ch, CURLOPT_URL, $url);
                            // curl_setopt($ch, CURLOPT_POST, 1);
                            // curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
                            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                            // // Response
                            // $response = curl_exec($ch);
                            // $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            // curl_close($ch);

                            if (isset($firstName))
                            {
                                $chekUser->first_name = $firstName;
                            }
                            if (isset($lastName))
                            {
                                $chekUser->last_name = $lastName;
                            }
                            if (isset($gender))
                            {
                                $chekUser->gender = $gender;
                            }
                            if (isset($mobileNo))
                            {
                                $chekUser->mobile_number = $mobileNo;
                            }
                            if (isset($socialMedia))
                            {
                                $chekUser->social_media = $socialMedia;
                            }
                            if (isset($deviceType))
                            {
                                $chekUser->device_type = $deviceType;
                            }
                            if (isset($deviceToken)) 
                            {
                                $chekUser->device_token = $deviceToken;
                            }
                            if (isset($socialToken)) 
                            {
                                $chekUser->social_token = $socialToken;
                            }
                            if (isset($socialImage))
                            {
                                $img = date('y-m-d').'_'.time().'_userProfile.png';
                                $source = file_get_contents($socialImage);
                                file_put_contents("uploads/user/".$img, $source);
                                $chekUser->profile_pic = $img;
                            }
                            $chekUser->save();
                            unset($chekUser->otp);
                            unset($chekUser->created_at);
                            unset($chekUser->updated_at);
                            unset($chekUser->deleted_at);
                            $chekUser->profile_pic = (!empty($chekUser->profile_pic)) ? asset('uploads/user/'.$chekUser->profile_pic) : '';
                            return $this->sendResponse(200,$chekUser,'Verify Mobile no');
                        }
                        else
                        {
                            $user = new User;
                            // $otp = '1234';
                            $otp = (string)mt_rand(1000,9999);
                            

                            $user->email = '';
                            if (isset($email))
                            {
                                $user->email = $email;
                            }
                            $user->first_name = '';
                            if (isset($firstName))
                            {
                                $user->first_name = $firstName;
                            }
                            $user->last_name = '';
                            if (isset($lastName))
                            {
                                $user->last_name = $lastName;
                            }
                            $user->full_name = '';
                            $user->gender = '';
                            if (isset($gender))
                            {
                                $user->gender = $gender;
                            }
                            $user->mobile_number = '';
                            if (isset($mobileNo))
                            {
                                $user->mobile_number = $mobileNo;
                            }
                            $user->otp = $otp;
                            $user->mode_of_registration = 'Application';
                            $user->social_media = '';
                            if (isset($socialMedia))
                            {
                                $user->social_media = $socialMedia;
                            }
                            $user->device_type = '';
                            if (isset($deviceType))
                            {
                                $user->device_type = $deviceType;
                            }
                            $user->device_token = '';
                            if (isset($deviceToken)) 
                            {
                                $user->device_token = $deviceToken;
                            }
                            $user->social_token = '';
                            if (isset($socialToken)) 
                            {
                                $user->social_token = $socialToken;
                            }
                            if (isset($socialImage))
                            {
                                $img = date('y-m-d').'_'.time().'_userProfile.png';
                                $source = file_get_contents($socialImage);
                                file_put_contents("uploads/user/".$img, $source);
                                $user->profile_pic = $img;
                            }
                            $user->email_verified_at = '';
                            $user->profile_pic = '';
                            $user->deleted_at = '';
                            // $data['userData'] = $user;
                            $user->save();
                            $user = User::where('deleted_at','')->orderBy('id','desc')->first();
                            unset($user->otp);
                            unset($user->created_at);
                            unset($user->updated_at);
                            unset($user->deleted_at);
                            $user->profile_pic = (!empty($user->profile_pic)) ? asset('uploads/user/'.$user->profile_pic) : '';

                            sendSms($mobileNo,$otp);

                            // $args = http_build_query(array(
                            //     'token' => configuration('sms_token'),
                            //     'from'  => 'BhesBhusa',
                            //     'to'    =>  $mobileNo,
                            //     'text'  => 'Your OTP for login '.$otp));

                            // $url = "http://api.sparrowsms.com/v2/sms/";

                            // # Make the call using API.
                            // $ch = curl_init();
                            // curl_setopt($ch, CURLOPT_URL, $url);
                            // curl_setopt($ch, CURLOPT_POST, 1);
                            // curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
                            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                            // // Response
                            // $response = curl_exec($ch);
                            // $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            // curl_close($ch);
                        }
                        // $user = Auth::user();
                        // $token = $user->createToken('apiToken')->plainTextToken;
                        // $user->token = $token;
                        $message = "New Customer Registred";
                        $notification = new Notification;
                        $notification->receiver_id = '';
                        $notification->receiver_type = 'admin';
                        $notification->sender_id = $user->id;
                        $notification->sender_type = '';
                        $notification->category_type = 'register';
                        $notification->notification_type = '';
                        $notification->type_id = $user->id;
                        $notification->order_status = '';
                        $notification->message = $message;
                        $notification->deleted_at = '';
                        $notification->save();
                        return $this->sendResponse(200,$user,'Login Successfully');
                    }
                }
                else
                {
                    if (isset($email) && isset($socialToken))
                    {
                        $chekUser = User::where('social_media',$socialMedia)->where('social_token',$socialToken)->where('email',$email)->first();
                        if ($chekUser)
                        {
                            if ($chekUser->status != '1')
                            {
                                // $data['userData'] = $chekUser;
                                unset($chekUser->created_at);
                                unset($chekUser->updated_at);
                                unset($chekUser->deleted_at);
                                // $chekUser = Auth::user();
                                // $token = $chekUser->createToken('apiToken')->plainTextToken;
                                // $chekUser->token = $token;
                                $chekUser->profile_pic = (!empty($chekUser->profile_pic)) ? asset('uploads/user/'.$chekUser->profile_pic) : '';
                                return $this->sendResponse(202,$chekUser,'Admin Temparay bolcked You');
                            }
                            else
                            { 
                                if ($chekUser->mobile_number != '' && $chekUser->otp_verified == '1')
                                {
                                    // $otp = '1234';
                                    if (isset($firstName))
                                    {
                                        $chekUser->first_name = $firstName;
                                    }
                                    if (isset($lastName))
                                    {
                                        $chekUser->last_name = $lastName;
                                    }
                                    if (isset($gender))
                                    {
                                        $chekUser->gender = $gender;
                                    }
                                    // $chekUser->otp = $otp;
                                    $chekUser->device_type = '';
                                    if (isset($deviceType))
                                    {
                                        $chekUser->device_type = $deviceType;
                                    }
                                    $chekUser->device_token = '';
                                    if (isset($deviceToken)) 
                                    {
                                        $chekUser->device_token = $deviceToken;
                                    }
                                    if (isset($socialToken))
                                    {
                                        $chekUser->social_token = $socialToken;
                                    }
                                    if (isset($socialImage))
                                    {
                                        $img = date('y-m-d').'_'.time().'_userProfile.png';
                                        $source = file_get_contents($socialImage);
                                        file_put_contents("uploads/user/".$img, $source);
                                        $chekUser->profile_pic = $img;
                                        // echo "File downloaded!"
                                    }
                                    $chekUser->save();
                                    // $data['userData'] = $chekUser;
                                    unset($chekUser->otp);
                                    unset($chekUser->created_at);
                                    unset($chekUser->updated_at);
                                    unset($chekUser->deleted_at);
                                    $token = $chekUser->createToken('apiToken')->plainTextToken;
                                    $chekUser->token = $token;
                                    $chekUser->profile_pic = (!empty($chekUser->profile_pic)) ? asset('uploads/user/'.$chekUser->profile_pic) : '';

                                    return $this->sendResponse(200, $chekUser, 'Login Successfully');
                                }
                                else
                                {
                                    unset($chekUser->otp);
                                    unset($chekUser->created_at);
                                    unset($chekUser->updated_at);
                                    unset($chekUser->deleted_at);
                                    // $data = new stdClass;
                                    return $this->sendResponse(205,$chekUser,'Verify Mobile no');
                                }
                            }
                        }
                        else
                        {
                            $user = new User;
                            // $otp = '1234';
                            $user->email = '';
                            if (isset($email))
                            {
                                $user->email = $email;
                            }
                            $user->first_name = '';
                            if (isset($firstName))
                            {
                                $user->first_name = $firstName;
                            }
                            $user->last_name = '';
                            if (isset($lastName))
                            {
                                $user->last_name = $lastName;
                            }
                            $user->full_name = '';
                            $user->gender = '';
                            if (isset($gender))
                            {
                                $user->gender = $gender;
                            }
                            $user->mobile_number = '';
                            // $user->otp = $otp;
                            $user->otp = '';
                            $user->mode_of_registration = 'Application';
                            $user->social_media = '';
                            if (isset($socialMedia))
                            {
                                $user->social_media = $socialMedia;
                            }
                            $user->device_type = '';
                            if (isset($deviceType))
                            {
                                $user->device_type = $deviceType;
                            }
                            $user->device_token = '';
                            if (isset($deviceToken)) 
                            {
                                $user->device_token = $deviceToken;
                            }
                            $user->social_token = '';
                            if (isset($socialToken)) 
                            {
                                $user->social_token = $socialToken;
                            }
                            if (isset($socialImage))
                            {
                                $img = date('y-m-d').'_'.time().'_userProfile.png';
                                $source = file_get_contents($socialImage);
                                file_put_contents("uploads/user/".$img, $source);
                                $user->profile_pic = $img;
                            }
                            $user->email_verified_at = '';
                            $user->profile_pic = '';
                            $user->deleted_at = '';
                            // $data['userData'] = $user;
                            $user->save();
                            $user = User::where('deleted_at','')->orderBy('id','desc')->first();
                            unset($user->otp);
                            unset($user->created_at);
                            unset($user->updated_at);
                            unset($user->deleted_at);
                            $user->profile_pic = (!empty($user->profile_pic)) ? asset('uploads/user/'.$user->profile_pic) : '';
                            // $user = Auth::user();
                            // $token = $user->createToken('apiToken')->plainTextToken;
                            // $user->token = $token;
                            $message = "New Customer Registred";
                            $notification = new Notification;
                            $notification->receiver_id = '';
                            $notification->receiver_type = 'admin';
                            $notification->sender_id = $user->id;
                            $notification->sender_type = '';
                            $notification->category_type = 'register';
                            $notification->notification_type = '';
                            $notification->type_id = '';
                            $notification->order_status = '';
                            $notification->message = $message;
                            $notification->deleted_at = '';
                            $notification->save();
                            return $this->sendResponse(205,$user,'Verify Mobile no');
                            // $data = new stdClass;
                            // return $this->sendResponse(201,$data,'user not found');
                        }
                    }
                    else
                    {
                        $data = new stdClass;
                        return $this->sendResponse(201,$data,'Fill All required field');
                    }
                }  
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Fill All required field');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }
    public function resendOtp(Request $request)
    {
        try
        {
            $mobileNo = $request->mobile_number;
            $findUser = User::where('mobile_number',$mobileNo)->first();
            if ($findUser)
            {
                // $otp = '1234';
                $otp = (string) mt_rand(1000,9999);
                sendSms($mobileNo,$otp);
                $findUser->otp = $otp;
                $findUser->save();
                // $data['otp'] = $otp;
                unset($findUser->otp);
                unset($findUser->created_at);
                unset($findUser->updated_at);
                unset($findUser->deleted_at);
                $findUser = new stdClass;
                return $this->sendResponse(200, $findUser, 'Otp sent On Registred Mobile Number');
            }
            else
            {
                $findUser = new stdClass;
                return $this->sendResponse(404, $findUser, 'User not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201,$e->getMessage());
        }
    }
    public function updateUserProfile(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;
            $updateUserProfile = User::find($userId);
            if ($updateUserProfile) 
            {
                if ($request->first_name)
                {
                    $updateUserProfile->first_name = $request->first_name;
                }
                if ($request->last_name)
                {
                    $updateUserProfile->last_name = $request->last_name;
                }
                $mobileNo = $request->mobile_number;
                $checkMobile = User::where('deleted_at','')->where('mobile_number',$mobileNo)->where('id','!=',$userId)->first();
                if (!empty($checkMobile))
                {
                    $data = new stdClass;
                    return $this->sendResponse(201, $data, "This Mobile Number Already used");
                }
                if ($request->mobile_number)
                {
                    $updateUserProfile->mobile_number = $request->mobile_number;
                }
                // if ($request->full_name)
                // {
                //     $updateUserProfile->full_name = $request->full_name;
                // }
                if ($request->email)
                {
                    $email = $request->email;
                    $checkEmail = User::where('deleted_at','')->where('email',$email)->where('id','!=',$userId)->first();
                    if (!empty($checkEmail))
                    {
                        $data = new stdClass;
                        return $this->sendResponse(201, $data, "This Email Already used");
                    }
                    $updateUserProfile->email = $request->email;
                }
                if ($request->gender)
                {
                    $updateUserProfile->gender = $request->gender;
                }
                if ($request->date_of_birth) 
                {
                    $updateUserProfile->date_of_birth = $request->date_of_birth;
                }
                if ($request->location)
                {
                    $updateUserProfile->location = $request->location;
                }
                if ($request->file('profile_pic'))
                {
                    $file = $request->file('profile_pic');
                    $profile_pic = date('y-m-d').'_'.time().'_'.$file->getClientOriginalName();
                    $file->move('uploads/user',$profile_pic);
                    $updateUserProfile->profile_pic = $profile_pic;
                }
                $updateUserProfile->save();
                unset($updateUserProfile->otp);
                unset($updateUserProfile->created_at);
                unset($updateUserProfile->updated_at);
                unset($updateUserProfile->deleted_at);
                $updateUserProfile->profile_pic = (!empty($updateUserProfile->profile_pic)) ? asset('uploads/user/'.$updateUserProfile->profile_pic) : '';
                return $this->sendResponse(200, $updateUserProfile, "User Profile Updated SuccessFully"); 

            }
            else
            {
                $updateUserProfile = new stdClass;
                return $this->sendResponse(201, $updateUserProfile, "user data not found");  
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function addressList(Request $request)
    {
        try 
        {
            // $userId = $request->user_id;
            $userData = $request->user();
            $userId = $userData->id;
            $user = UserAddress::where('deleted_at','')->where('user_id',$userId)->get();
            if (!empty($user->toArray())) 
            {
                return $this->sendResponse(200,$user,'Address list');
            }
            else
            {
                // $user = new stdClass;
                return $this->sendResponse(200,$user,'record not found');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage()); 
        }
    }
    public function saveAddress(Request $request)
    {
        try 
        {
            // $input = $request->validate([
            //     'contact_name' => 'required|string|max:255',
            //     'mobile_no' => 'required|string|max:255',
            // ]);
            $name = $request->contact_name;
            $mobileNo = $request->mobile_no;
            $email = $request->email;
            $pincode = $request->pincode;
            $address = $request->address;
            $locality = $request->locality;
            $city = $request->city;
            $state = $request->state;
            $saveAs = $request->save_as;
            $default = $request->is_default;

            $userData = $request->user();
            $userId = $userData->id;
            if (isset($name) && isset($mobileNo) && isset($email) && isset($pincode) && isset($address) && isset($locality) && isset($city) && isset($state) && isset($saveAs) && isset($default))
            {
                $isDefault = $request->is_default;
                $saveAddress = new UserAddress;
                $saveAddress->user_id = $userId;
                $saveAddress->contact_name = $request->contact_name;
                $saveAddress->mobile_no = $request->mobile_no;
                $saveAddress->email = $email;
                $saveAddress->pincode = $request->pincode;
                $saveAddress->address = $request->address;
                $saveAddress->locality = $request->locality;
                $saveAddress->city = $request->city;
                $saveAddress->state = $request->state;
                $saveAddress->save_as = $request->save_as;
                $saveAddress->is_default = '0';
                if ($isDefault == '1')
                {
                    $saveAddress->is_default = $isDefault;
                    $updateDefaultAdd = UserAddress::where('deleted_at','')->where('user_id',$userId)->update(['is_default'=>'0']);
                }
                $saveAddress->deleted_at = '';
               
                $saveAddress->save();
                return $this->sendResponse(200,$saveAddress, "Address Saved Successfully...!");
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data, 'Please Fill Required Field');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());
            
        }
    }
    public function updateAddress(Request $request)
    {
        try 
        {
            $userData = $request->user();
            $userId = $userData->id;

            $addressId = $request->address_id;
            $isDefault = $request->is_default;
            $updateAddress = UserAddress::where('deleted_at','')->where('user_id',$userId)->find($addressId);
            if ($updateAddress)
            {
                if (isset($request->contact_name))
                {
                    $updateAddress->contact_name = $request->contact_name;
                }
                if (isset($request->mobile_no))
                {
                    $updateAddress->mobile_no = $request->mobile_no;
                }
                if (isset($request->email))
                {
                    $updateAddress->email = $request->email;
                }
                if (isset($request->pincode))
                {
                    $updateAddress->pincode = $request->pincode;
                }
                if (isset($request->address))
                {
                    $updateAddress->address = $request->address;
                }
                if (isset($request->locality))
                {
                    $updateAddress->locality = $request->locality;
                }
                if (isset($request->city))
                {
                    $updateAddress->city = $request->city;
                }
                if (isset($request->state)) 
                {
                    $updateAddress->state = $request->state;
                }
                if (isset($request->save_as)) 
                {
                    $updateAddress->save_as = $request->save_as;
                }
                if (isset($request->is_default))
                {
                    $updateAddress->is_default = $isDefault;
                }
                if ($isDefault == '1')
                {
                    $updateDefaultAdd = UserAddress::where('deleted_at','')->where('user_id',$userId)->where('id','!=',$addressId)->update(['is_default'=>'0']);
                    $updateAddress->is_default = $isDefault;
                }
                $updateAddress->deleted_at = '';
                $updateAddress->save();
                return $this->sendResponse(200, $updateAddress, "Address Updated SuccessFully"); 
            }
            else
            {
                $updateAddress = new stdClass;
                return $this->sendResponse(201,$updateAddress,'Address not found');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function removeAddress(Request $request)
    {
         try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $addressId = $request->address_id;
            $removeAddress = UserAddress::where('deleted_at','')->where('user_id',$userId)->find($addressId);
            if ($removeAddress)
            {
                $removeAddress->deleted_at = config('constant.CURRENT_DATETIME');
                $removeAddress->save();

                return $this->sendResponse(200, $removeAddress,'Address Removed');
            }
            else
            {
                $removeAddress = new stdClass;
                return $this->sendResponse(201, $removeAddress,'Address not found');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function verifyMobileNo_(Request $request)
    {
        try
        {
            $phoneNo = $request->mobile_number;
            if (isset($phoneNo))
            {
                $chekUser = User::where('deleted_at','')->where('mobile_number',$phoneNo)->where('status','1')->first();
                if ($chekUser)
                {
                    // $otp = '1234';
                    $otp = (string) mt_rand(1000,9999);
                    sendSms($phoneNo,$otp);
                    $chekUser->otp = $otp;
                    $chekUser->save();
                    unset($chekUser->otp);
                    unset($chekUser->created_at);
                    unset($chekUser->updated_at);
                    unset($chekUser->deleted_at);
                    return $this->sendResponse(200, $chekUser,'Otp sent On Registred Mobile Number');
                }
                else
                {
                    $chekUser = new stdClass;
                    return $this->sendResponse(201, $chekUser, 'User not Found');
                }
            }
            else
            {
                return $this->sendError(201,'Please Enter Valid credentials');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());
            
        }
    }
    public function verifyMobileNo(Request $request)
    {
        try
        {
            $mobileNo = $request->mobile_number;
            $otp = $request->otp;
            $user = User::where('deleted_at','')->where('mobile_number',$mobileNo)->where('otp',$otp)->first();
            if($user)
            {
                $user->otp_verified = '1';
                $user->save();
                unset($user->otp);
                unset($user->created_at);
                unset($user->updated_at);
                unset($user->deleted_at);
                $token = $user->createToken('apiToken')->plainTextToken;
                $user->token = $token;
                $user->profile_pic = (!empty($user->profile_pic)) ? asset('uploads/user/'.$user->profile_pic) : '';
                return $this->sendResponse(200, $user, 'OTP Verified ');
            }
            else
            {
                $user = new stdClass;
                return $this->sendResponse(201, $user, 'Invalid otp Please Enter Valid otp');
            }   
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());
            
        }
    }
    public function verifyChangeMobileOtp(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $mobileNo = $request->mobile_number;
            $otp = $request->otp;
            if ($mobileNo)
            {
                $checkMobile = User::where('deleted_at','')->where('mobile_number',$mobileNo)->first();
                if ($checkMobile)
                {
                    unset($checkMobile->otp);
                    unset($checkMobile->created_at);
                    unset($checkMobile->updated_at);
                    unset($checkMobile->deleted_at);
                    $checkMobile = new stdClass;
                    return $this->sendResponse(201,$checkMobile, 'This Number is already used');
                }
                else
                {
                    $user = User::where('deleted_at','')->where('id',$userId)->where('otp',$otp)->first();
                    if($user)
                    {
                        $user->mobile_number = $mobileNo;
                        $user->save();
                        $user->profile_pic = (!empty($user->profile_pic)) ? asset('uploads/user/'.$user->profile_pic) : '';
                        unset($user->otp);
                        unset($user->created_at);
                        unset($user->updated_at);
                        unset($user->deleted_at);
                        // $token = $user->createToken('apiToken')->plainTextToken;
                        // $user->token = $token;
                        return $this->sendResponse(200, $user, 'OTP Verified ');
                    }
                    else
                    {
                        $user = new stdClass;
                        return $this->sendResponse(201, $user, 'Invalid otp Please Enter Valid otp');
                    }   
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201, $data, 'Please Fill All required field');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());
            
        }
    }
    public function changeMobileNo(Request $request)
    {
        try
        {
            $mobileNo = $request->mobile_number;
            // $userId = $request->user_id;
            $userData = $request->user();
            $userId = $userData->id;
            $checkMobile = User::where('deleted_at','')->where('mobile_number',$mobileNo)->where('id','!=',$userId)->first();
            if ($checkMobile)
            {
                unset($checkMobile->otp);
                unset($checkMobile->created_at);
                unset($checkMobile->updated_at);
                unset($checkMobile->deleted_at);
                $checkMobile = new stdClass;
                return $this->sendResponse(201,$checkMobile, 'This Number is already used');
            }
            else
            {
                $user = User::where('deleted_at','')->find($userId);
                if ($user)
                {
                    $user->otp = '1234';
                    // $user->mobile_number = $mobileNo;
                    unset($user->otp);
                    unset($user->created_at);
                    unset($user->updated_at);
                    unset($user->deleted_at);
                    return $this->sendResponse(200, $user,'Otp sent On Registred Mobile Number');
                }
                else
                {
                    unset($user->otp);
                    unset($user->created_at);
                    unset($user->updated_at);
                    unset($user->deleted_at);
                    $user = new stdClass;
                    return $this->sendResponse(201,$user, 'user data not found');
                }
            }
        }
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());
            
        }
    }
}
