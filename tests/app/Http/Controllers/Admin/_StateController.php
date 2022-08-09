<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
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
            'name' => 'required',
            'status' => ''
        ]));

        return redirect()->route('admin.state.index')->with('success', 'State added successfully.');
    }

    public function edit($id)
    {
        $state = State::findOrFail($id);
        return view('admin.states.edit', compact('state'));
    }

    public function update(Request $request, $id)
    {
        $state = State::findOrFail($id);
        $input = $request->validate([
            'name' => 'required',
            'status' => ''
        ]);
        $input['status'] = $input['status']??0; 
        $state->update($input);

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
        $cities = City::where(['state_id' => $request->state_id, 'deleted_at'=>''])->orderBy('id','DESC')->get();
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
