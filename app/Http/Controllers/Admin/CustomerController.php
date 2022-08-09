<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function customerList()
    {
    
        $userList = User::get();
        return view('admin.customer.customerDetails',compact('userList'));
    }
    public function changeUserStatus($id)
    {
       
        // return view('admin.service_category.add_service_category');
        $user = User::findOrFail($id);
        $status = (int)$user->status;
        if ($status == 1)
        {
            $user->status = '0';
        }
        else
        {
            $user->status = '1';
        }
        // dd($user->status);
            // code...
        // $user->deleted_at = config('constant.CURRENT_DATETIME');
        $user->save();
        return redirect()->route('customerList')->with('success','User Status Updated Successfully');
       
    }
    public function customerStatusWiseData(Request $request)
    {
        // echo "<pre>";print_r($request->user_status);
        $status = $request->user_status;
        $userList = User::where('status',$status)->get();
        $row = '';
        $i = 0;
        foreach($userList as $user)
        {
            $i++;
            $row .= "<tr>
            <td>".$i."</td>
            <td>".$user->first_name."</td>
            <td>". $user->last_name."</td>
            <td>".$user->gender."</td>
            <td>".$user->mobile_number."</td>
            <td>".$user->mode_of_registration."</td>";
            if ($user->status == 1)
            {
                $row .= "<td><a href='".route('changeUserStatus',$user->id)."'><div class='badge badge-success'>Active</div></a></td>";
            }
            else
            {
                $row .= "<td><a href='".route('changeUserStatus',$user->id)."'><div class='badge badge-danger'>InActive</div></a></td>";
            }
            $row .= "<td><a href='".route('userDetails',$user->id)."'><i class='fa fa-eye'></i></a></td></tr>";
        }
        echo $row;
        exit;
    }
    public function contactList()
    {
        $contactList = ContactUs::get();
        return view('admin.contact.contact_list',compact('contactList'));
    }
}
