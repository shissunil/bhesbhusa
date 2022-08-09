<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $states = State::where('deleted_at','')->orderBy('id','DESC')->get();
        return view('admin.states.index', compact('states'));
    }

    public function create()
    {
        return view('admin.states.create');
    }

    public function store(Request $request)
    {
        State::create($request->validate([
            'name' => 'required|max:255|unique:states,name',
            'status' => ''
        ]));

        return redirect()->route('admin.state.index')->with('success', 'State added successfully.');
    }

    public function edit($id)
    {
        $state = State::findOrFail($id);
        $addressId = '';
        $selectPincode = Pincode::where('deleted_at','')->select('id','pincode')->where('status','1')->where('state_id',$id)->get();
        if (!empty($selectPincode->toArray()))
        {
            foreach($selectPincode as $value)
            {
                $selectAddress = UserAddress::where('deleted_at','')->select('id')->where('pincode',$value->pincode)->get();
                if (!empty($selectAddress->toArray()))
                {
                    
                    foreach($selectAddress as $address)
                    {
                        $addressId = $addressId != '' ? $addressId. ','. $address->id.',' : $address->id;
                        
                    }
                }
            }
        }

        $addressId = rtrim($addressId, ',');
        $addressId = explode(",",$addressId);
        $orderCount = Order::where('deleted_at','')->where('order_status','!=','3')->whereIn('user_address_id',$addressId)->count();
        return view('admin.states.edit', compact('state','orderCount'));

    }

    public function update(Request $request, $id)
    {
        $state = State::findOrFail($id);
        $input = $request->validate([
            'name' => 'required|max:255|unique:states,name,'.$id,
            'status' => ''
        ]);
        $input['status'] = $input['status']??0;
        $state->update($input);
        if ($input['status'] == 0)
        {
            $changeCityStatus = City::where('deleted_at','')->where('state_id',$id)->update(['status'=>0]);
            $changePincodeStatus = Pincode::where('deleted_at','')->where('state_id',$id)->update(['status'=>0]);
            $changeAreatatus = Area::where('deleted_at','')->where('state_id',$id)->update(['status'=>0]);
        }
        else
        {
            $changeCityStatus = City::where('deleted_at','')->where('state_id',$id)->update(['status'=>1]);
            $changePincodeStatus = Pincode::where('deleted_at','')->where('state_id',$id)->update(['status'=>1]);
            $changeAreatatus = Area::where('deleted_at','')->where('state_id',$id)->update(['status'=>1]);   
        }
        return redirect()->route('admin.state.index')->with('success', 'State updated successfully.');
    }

    public function destroy(Request $request,$id)
    {
        $state = State::findOrFail($id);
        $state->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'State deleted successfully.');
    }

    public function getCityFromState(Request $request)
    {
        $cities = City::where(['state_id' => $request->state_id, 'deleted_at'=>''])->where('status','1')->orderBy('id','DESC')->get();
        if(count($cities)>0){
            echo '<option value="">Select City</option>';
            foreach($cities as $city){
                echo "<option value=".$city->id.">".$city->name."</option>";
            }
        }else{
            echo '<option value="">Select City</option>';
        }
    }

    public function getPincodeFromCity(Request $request)
    {
        $pincodes = Pincode::where(['city_id' => $request->city_id, 'deleted_at'=>''])->orderBy('id','DESC')->get();
        if(count($pincodes)>0){
            echo '<option value="">Select Pincode</option>';
            foreach($pincodes as $pincode){
                echo "<option value=".$pincode->id.">".$pincode->pincode."</option>";
            }
        }else{
            echo '<option value="">Select Pincode</option>';
        }
    }

}
