<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Offer;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $offers = Offer::where('deleted_at','')->orderBy('id','DESC')->get();
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $subCategoryList = SubCategory::where('deleted_at','')->where('status','1')->get();
        return view('admin.offers.create',compact('subCategoryList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'offer_name' => 'required|max:255',
            'offer_description' => 'required',
            'offer_code' => 'required|unique:offers,offer_code',
            'start_date' => 'required|before:end_date|after:yesterday',
            'end_date' => 'required|after:start_date',
            'is_global' => 'required',
            'offer_discount' => 'required|numeric|between:1,100',
            'total_use' => 'required',
            'offer_status' => ''
        ]);
        $discount = $request->offer_discount;
        $message = '';
        $subCategoryId = $request->sub_category_id;
        $totalAmount = $request->total_amount;
        $offer = new Offer;
        $offer->offer_name = $request->offer_name;
        $offer->offer_description = $request->offer_description;
        $offer->offer_code = $request->offer_code;
        $offer->start_date = $request->start_date;
        $offer->end_date = $request->end_date;
        $offer->offer_discount = $request->offer_discount;
        $offer->is_global = $request->is_global;
        $offer->sub_category_id = '';
        if ($subCategoryId)
        {
            $subCategoryId = implode(',',$subCategoryId);
            $offer->sub_category_id = $subCategoryId;            
        }
        $offer->total_use = $request->total_use;
        // $offer->total_amount = '';
        if ($totalAmount)
        {
            $offer->total_amount = $totalAmount;
        }
        $offer->offer_status = $request->offer_status ?? 0;
        $offer->deleted_at = '';
        $offer->save();

        $message = "Get Upto ".$discount."% off on Your Order Hurry Grab The Order!";
        $userList = User::where('deleted_at','')->where('status','1')->get();
        if (!empty($userList->toArray()))
        {
            foreach($userList as $user)
            {
                $notification = new Notification;
                $notification->receiver_id = $user->id;
                $notification->receiver_type = 'user';
                $notification->sender_id = '';
                $notification->sender_type = '';
                $notification->category_type = 'Promotion';
                $notification->notification_type = 'Offer';
                $notification->type_id = $offer->id;
                $notification->message = $message;
                $notification->deleted_at = '';
                $notification->save();

                if ($user->notification == '1')
                {
                    $token = $user->device_token;
                    sendCustomerNotification($message, $token, 'Promotion','2', 'Offer',$offer->id);
                }
            }
        }

        return redirect()->route('admin.offers.index')->with('success', 'Offer added successfully.');
    }

    public function edit($id)
    {
        $offer = Offer::findOrFail($id);
        $subCategoryList = SubCategory::where('deleted_at','')->where('status','1')->get();
        return view('admin.offers.edit', compact('offer','subCategoryList'));
    }

    public function update(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        // $input = $request->validate([
        //     'offer_name' => 'required',
        //     'offer_description' => 'required',
        //     'offer_code' => 'required',
        //     'start_date' => 'required',
        //     'end_date' => 'required',
        //     'end_date' => 'required',
        //     'offer_status' => ''
        // ]);
        // $input['offer_status'] = $input['offer_status']??0; 
        // $offer->update($input);
        $start_date = $offer->start_date;
        $request->validate([
            'offer_name' => 'required|max:255',
            'offer_description' => 'required',
            'offer_code' => 'required|unique:offers,offer_code,'.$id,
            'start_date' => 'required|before:end_date|after_or_equal:'.$start_date,
            'end_date' => 'required|after:start_date',
            'is_global' => 'required',
            'offer_discount' => 'required|numeric|between:1,100',
            'total_use' => 'required',
            'offer_status' => ''
        ]);
        $subCategoryId = $request->sub_category_id;
        $totalAmount = $request->total_amount;
        // $offer = new Offer;
        $offer->offer_name = $request->offer_name;
        $offer->offer_description = $request->offer_description;
        $offer->offer_code = $request->offer_code;
        $offer->start_date = $request->start_date;
        $offer->end_date = $request->end_date;
        $offer->offer_discount = $request->offer_discount;
        $offer->is_global = $request->is_global;
        $offer->sub_category_id = '';
        if ($subCategoryId)
        {
            $subCategoryId = implode(',',$subCategoryId);
            $offer->sub_category_id = $subCategoryId;
        }
        $offer->total_use = $request->total_use;
        // $offer->total_amount = '';
        if ($totalAmount)
        {
            $offer->total_amount = $totalAmount;
        }
        $offer->offer_status = $request->offer_status ?? 0;
        $offer->deleted_at = '';
        $offer->save();
        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Request $request,$id)
    {
        $offer = Offer::findOrFail($id);
        $offer->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Offer deleted successfully.');
    }

}
