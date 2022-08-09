<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\City;
use App\Models\Size;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Pincode;
use App\Models\Product;
use App\Models\Setting;
use App\Models\WishList;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function login()
    {
        return view('front.login');
    }

    public function handleGoogleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        if ($user)
	    {
	    	
	    	$firstName = $user->offsetGet('given_name');
	        $lastName = $user->offsetGet('family_name');
	        $email = $user->email;
	        $social_token = $user->id;
	        $social_media = 2;
	        $data['firstName'] = $firstName;
	        $data['lastName'] = $lastName;
	        $data['email'] = $email;
	        $data['social_token'] = $social_token;
	        $data['social_media'] = $social_media;
			$data['profile_pic'] = $user->getAvatar() ?? '';

	        $checkUser = User::where('social_token', $social_token)->first();
			if($checkUser){
				if($checkUser->mobile_number!=''){
					unset($checkUser->otp);
	                unset($checkUser->created_at);
	                unset($checkUser->updated_at);
	                unset($checkUser->deleted_at);
	                $checkUser->profile_pic = (!empty($checkUser->profile_pic)) ? asset('uploads/user/' . $checkUser->profile_pic) : '';
					$loginUser = Auth::login($checkUser);
                    return redirect()->intended(route('home'))->with('success', 'Login Successfully.');
				}else{
					return redirect()->route('auth.login')->with('data', $data);
				}
			}else{
				return redirect()->route('auth.login')->with('data', $data);
			}	        
	    }
	    else
	    {
	        return redirect()->route('login')->with('error',"You have not grant permission to connect with your Google account.");
    	}
    }

    public function loginForm()
    {
        return view('front.loginForm');
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|digits:10',
        ]);

        $mobileNo = $request->mobile_number;
        $socialMedia = $request->social_media;
        $socialToken = $request->social_token ?? '';
        $firstName = $request->first_name ?? '';
        $lastName = $request->last_name ?? '';
        $gender = $request->gender ?? '';
        $email = $request->email ?? '';
        $socialImage = $request->profile_pic ?? '';

        // dd($socialMedia);

        if (isset($socialMedia)) {
            $chekUser = User::where('mobile_number', $mobileNo)->first();
            if ($chekUser) {
                // dd($chekUser);
                if ($chekUser->status != '1') {
                    return redirect()->back()->with('error', 'Admin temporary blocked you.');
                } else {
                    if ($socialMedia == $chekUser->social_media) {
                        $otp = '1234';

                        if (isset($firstName) && $firstName != '') {
                            $chekUser->first_name = $firstName;
                        }

                        if (isset($lastName) && $lastName != '') {
                            $chekUser->last_name = $lastName;
                        }

                        if (isset($gender) && $gender != '') {
                            $chekUser->gender = $gender;
                        }

                        if (isset($email) && $email != '') {
                            $chekUser->email = $email;
                        }

                        $chekUser->otp = $otp;

                        if (isset($deviceType)) {
                            $chekUser->device_type = $deviceType;
                        }

                        if (isset($deviceToken)) {
                            $chekUser->device_token = $deviceToken;
                        }

                        if (isset($socialToken)) {
                            $chekUser->social_token = $socialToken;
                        }

                        if (isset($socialImage) && $socialImage!='')
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

                        // $token = $chekUser->createToken('apiToken')->plainTextToken;
                        // $chekUser->token = $token;
                        $chekUser->profile_pic = (!empty($chekUser->profile_pic)) ? asset('uploads/user/' . $chekUser->profile_pic) : '';

                        // dd($chekUser);

                        if ($socialMedia == 0) {
                            // return redirect(route('your_self'))->with('mobile_number', $mobileNo);
                            $this->sendOTP($mobileNo);
                            return redirect(route('otp'))->with(['mobile_number' => $mobileNo, 'social_media' => $socialMedia ]);
                        }else{

                            $loginUser = Auth::login($chekUser);

                            // dd(Auth::user());
                            return redirect()->intended(route('home'))->with('success', 'Login Successfully.');
                        }
                        
                    } else {
                        unset($chekUser->otp);
                        unset($chekUser->created_at);
                        unset($chekUser->updated_at);
                        unset($chekUser->deleted_at);
                        // $chekUser = Auth::user();
                        // $token = $chekUser->createToken('apiToken')->plainTextToken;
                        // $chekUser->token = $token;
                        $chekUser->profile_pic = (!empty($chekUser->profile_pic)) ? asset('uploads/user/' . $chekUser->profile_pic) : '';
                        if ($chekUser->social_media == '1') {
                            return redirect()->back()->with('error', 'This Mobile Number Already used With FaceBook Login.');
                        } elseif ($chekUser->social_media == '2') {
                            return redirect()->back()->with('error', 'This Mobile Number Already used With Google Login.');
                        } elseif ($chekUser->social_media == '3') {
                            return redirect()->back()->with('error', 'This Mobile Number Already used With Apple Login.');
                        } else {
                            return redirect()->back()->with('error', 'This Mobile Number Already used With Normal Login.');
                        }
                    }
                }
            } else {
                $user = new User;
                $otp = '1234';
                $user->first_name = '';
                if (isset($firstName) && $firstName != '') {
                    $user->first_name = $firstName;
                }
                $user->last_name = '';
                if (isset($lastName) && $lastName != '') {
                    $user->last_name = $lastName;
                }
                $user->full_name = '';
                $user->gender = '';
                if (isset($gender) && $gender != '') {
                    $user->gender = $gender;
                }
                $user->mobile_number = '';
                if (isset($mobileNo) && $mobileNo != '') {
                    $user->mobile_number = $mobileNo;
                }
                $user->otp = $otp;
                $user->mode_of_registration = 'Website';
                $user->social_media = '';
                if (isset($socialMedia) && $socialMedia != '') {
                    $user->social_media = $socialMedia;
                }
                $user->social_token = '';
                if (isset($socialToken)) {
                    $user->social_token = $socialToken;
                }
                $user->device_type = '';
                if (isset($deviceType)) {
                    $user->device_type = $deviceType;
                }
                $user->device_token = '';
                if (isset($deviceToken)) {
                    $user->device_token = $deviceToken;
                }

                $user->email_verified_at = '';
                $user->profile_pic = '';
                $user->deleted_at = '';

                $user->email = '';
	            if (isset($email) && $email != '') {
	                $user->email = $email;
	            }

	            if (isset($socialImage) && $socialImage!='')
                {
                    $img = date('y-m-d').'_'.time().'_userProfile.png';
                    $source = file_get_contents($socialImage);
                    file_put_contents("uploads/user/".$img, $source);
                    $user->profile_pic = $img;
                }
                
                $user->save();
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
                $user = User::where('deleted_at', '')->where('mobile_number', $mobileNo)->orderBy('id', 'desc')->first();
                // dd($user);
                unset($user->otp);
                unset($user->created_at);
                unset($user->updated_at);
                unset($user->deleted_at);
                $user->profile_pic = (!empty($user->profile_pic)) ? asset('uploads/user/' . $user->profile_pic) : '';
                
                if ($socialMedia == 0) {
                    // return redirect(route('your_self'))->with('mobile_number', $mobileNo);
                    $this->sendOTP($mobileNo);
                    return redirect(route('otp'))->with(['mobile_number' => $mobileNo, 'social_media' => $socialMedia ]);
                    
                } else {

                    // SOocial Login Mobile No Input
                    if($request->mobile_number && $request->mobile_number!=''){
                        $this->sendOTP($mobileNo);
                        return redirect(route('otp'))->with(['mobile_number' => $request->mobile_number, 'social_media' => $socialMedia ]);
                    }else{
                        $loginUser = Auth::login($user);
                        return redirect()->intended(route('profile'))->with('success', 'Login Successfully.');
                    }

                    // dd($user);

                    
                }
            }
        }
    }

    public function saveAboutYourSelf(Request $request)
    {
        $mobile_number = $request->mobile_number;
        $firstName = $request->first_name ?? '';
        $lastName = $request->last_name ?? '';
        $gender = $request->gender ?? '';

        $user = User::where('mobile_number', $mobile_number)->first();
        if ($user) {
            if (isset($firstName)) {
                $user->first_name = $firstName;
            }

            if (isset($lastName)) {
                $user->last_name = $lastName;
            }

            if (isset($gender)) {
                $user->gender = $gender;
            }

            $user->save();

            $checkUser = User::where('mobile_number', $mobile_number)->first();
            unset($checkUser->otp);
            unset($checkUser->created_at);
            unset($checkUser->updated_at);
            unset($checkUser->deleted_at);
            $checkUser->profile_pic = (!empty($checkUser->profile_pic)) ? asset('uploads/user/' . $checkUser->profile_pic) : '';

            $loginUser = Auth::login($checkUser);

            // dd(Auth::user());

            return redirect(route('profile'))->with('success', 'Login Successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function profile()
    {
        return view('front.profile');
    }

    public function updateProfile(Request $request)
    {
        $loginUserId = Auth::user()->id;

        $this->validate(
            $request,
            [
                'mobile_number' => 'required|digits:10|unique:users,mobile_number,' . $loginUserId,
                'email' => 'required|email|max:255',
                'date_of_birth' => 'before:tomorrow'
            ],
        );

        $user = User::findOrFail($loginUserId);
        $user->first_name = $request->first_name ?? '';
        $user->last_name = $request->last_name ?? '';
        $user->email = $request->email ?? '';
        $user->mobile_number = $request->mobile_number;
        $user->date_of_birth = $request->date_of_birth ?? '';
        $user->gender = $request->gender ?? '';
        $user->location = $request->location ?? '';
        $user->save();

        return redirect(route('profile'))->with('success', 'Profile Updated Successfully.');
    }

    public function my_address()
    {
        $loginUserId = Auth::user()->id;
        $userAddressList = UserAddress::where('deleted_at', '')->where('user_id', $loginUserId)->get();
        // dd($userAddressList);
        return view('front.my_address', compact('userAddressList'));
    }
    public function saveAddress(Request $request)
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

        if (
            isset($name) && isset($mobileNo) &&
            isset($email) && isset($pincode) &&
            isset($address) && isset($locality) &&
            isset($city) && isset($state) &&
            isset($saveAs) && isset($isDefault)
        ) {
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
            $saveAddress->is_default = $isDefault;
            if ($isDefault == '1') {
                $updateDefaultAdd = UserAddress::where('deleted_at', '')->where('user_id', $userId)->update(['is_default' => '0']);
            }
            $saveAddress->deleted_at = '';
            $saveAddress->save();
            return redirect()->intended(route('my_address'))->with('success', 'Address Saved Successfully.');
        } else {
            return redirect()->back()->with('error', 'Please Fill Required Fields');
        }
    }

    public function updateAddress(Request $request)
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
            return redirect()->intended(route('my_address'))->with('success', 'Address Updated Successfully.');
        } else {
            return redirect()->back()->with('error', 'Address Not Found.');
        }
    }

    public function removeAddress(Request $request)
    {	    
        $userId = Auth::user()->id;
        $addressId = $request->address_id;

        $updateAddress = UserAddress::where('user_id', $userId)->find($addressId);

        if ($updateAddress) {
            
            $updateAddress->deleted_at = config('constant.CURRENT_DATETIME');
            $updateAddress->save();
            return redirect()->intended(route('my_address'))->with('success', 'Address Removed Successfully.');
        } else {
            return redirect()->back()->with('error', 'Address Not Found.');
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $loginUserId = Auth::user()->id;
        $user = User::findOrFail($loginUserId);
        if ($user) {
            if ($request->file('profile_pic')) {
                $file = $request->file('profile_pic');
                $profile_pic = date('ymd') . time() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/user', $profile_pic);
                $user->profile_pic = $profile_pic;
                $user->save();

                return ['status' => true, 'message' => 'Profile Picture Updated Successfully'];
            } else {
                return ['status' => false, 'message' => 'Please select valid image'];
            }
        } else {
            return ['status' => false, 'message' => 'No user found'];
        }
    }
    
    public function otp()
    {
        return view('front.otp');
    }

    public function your_self()
    {
        return view('front.your_self');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Logout Successfully.');
    }

    //Orders
    public function thank_you()
    {
        return view('front.thank_you');
    }

    public function handleFacebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(Request $request)
    {

	    if ($request->error_code && $request->error_code == 200)
	    {
	        return redirect()->route('login')->with('error',"You have not grant permission to connect with your Facebook account.");
	    }
	    else
	    {
	    	//get the driver and set desired fields
			$driver = Socialite::driver('facebook')
		                ->fields([
		                    'name', 
		                    'first_name', 
		                    'last_name', 
		                    'email', 
		                    'gender', 			                    
		                ]);
			// retrieve the user
			$user = $driver->user();
			// dd($user->avatar);
	       
	        // $user = Socialite::driver('facebook')->user();
	        // dd($user);
	        $firstName = $user['first_name'];
	        $lastName = $user['last_name'];
	        $email = $user['email'];
	        $social_token = $user['id'];
	        $social_media = 1;
	        $data['firstName'] = $firstName;
	        $data['lastName'] = $lastName;
	        $data['email'] = $email;
	        $data['social_token'] = $social_token;
	        $data['social_media'] = $social_media;
	        $data['profile_pic'] = $user->avatar ?? '';
	        // dd($data);

			$checkUser = User::where('social_token', $social_token)->first();
			if($checkUser){
				if($checkUser->mobile_number!=''){
					unset($checkUser->otp);
	                unset($checkUser->created_at);
	                unset($checkUser->updated_at);
	                unset($checkUser->deleted_at);
	                $checkUser->profile_pic = (!empty($checkUser->profile_pic)) ? asset('uploads/user/' . $checkUser->profile_pic) : '';
					$loginUser = Auth::login($checkUser);
                    return redirect()->intended(route('home'))->with('success', 'Login Successfully.');
				}else{
					return redirect()->route('auth.login')->with('data', $data);
				}
			}else{
				return redirect()->route('auth.login')->with('data', $data);
			}
	        
	    }

        
    }

    public function settings()
    {
        return view('front.settings');
    }

    public function changeNotificationStatus(Request $request)
    {
        $loginUserId = Auth::user()->id;

        $user = User::findOrFail($loginUserId);
        $user->notification = ($request->notification==1 || $request->notification==0) ? $request->notification  : 0;
        $user->save();
        if ($request->notification==0) {
            $message = "Notification turned off successfully.";
        } else {
            $message = "Notification turned on successfully.";
        }
        return redirect()->back()->with('success', $message);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        $otp = $request->otp;
        $mobile_number = $request->mobile_number;
        $social_media = $request->social_media;

        if($mobile_number!=''){
            $user = User::where('mobile_number', $mobile_number)->first();
            if($user){
                if($user->otp!='' && $user->otp==$otp){

                    unset($user->otp);
                    unset($user->created_at);
                    unset($user->updated_at);
                    unset($user->deleted_at);
                    $user->profile_pic = (!empty($user->profile_pic)) ? asset('uploads/user/' . $user->profile_pic) : '';
                    $loginUser = Auth::login($user);
                    return redirect()->intended(route('home'))->with('success', 'Login Successfully.');
                    
                }else{
                    return redirect()->back()->withInput()->with('error', 'Incorrect OTP');
                }
            }else{
                return redirect()->route('login')->with('error', 'Mobile number not registered');
            }
        }else{
            return redirect()->route('login')->with('error', 'Mobile number required');
        }
        
    }

    public function sendOTP($mobile_number){

        $user = User::where('mobile_number', $mobile_number)->first();
        if($user){
            $otp = '1234';
            $user->save();

            // $args = http_build_query(array(
            //     'token' => configuration('sms_token'),
            //     'from'  => 'BhesBhusa',
            //     'to'    => $mobile_number,
            //     'text'  => 'Your OTP for login is '.$otp));

            // $url = "http://api.sparrowsms.com/v2/sms/";

            # Make the call using API.
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // Response
            // $response = curl_exec($ch);
            // $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // curl_close($ch);
        }

    }
}
