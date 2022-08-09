<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\City;
use App\Models\Pincode;
use Carbon\Carbon;

class PinCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $pincodes = Pincode::whereHas('city', function ($query) {
            $query->where('deleted_at','');
        })->where('deleted_at','')->orderBy('id', 'DESC')->get();

        return view('admin.pincodes.index', compact('pincodes'));
    }

    public function create()
    {
        $states = State::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.pincodes.create',compact('states'));
    }

    public function store(Request $request)
    {
        Pincode::create($request->validate([
            'pincode' => 'required',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'status' => ''
        ]));

        return redirect()->route('admin.pincode.index')->with('success', 'Pincode added successfully.');
    }

    public function edit($id)
    {
        $pincode = Pincode::findOrFail($id);
        $states = State::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $cities = City::where(['deleted_at'=>'','state_id'=>$pincode->state_id])->orderBy('id', 'DESC')->get();
        return view('admin.pincodes.edit', compact('cities','states','pincode'));
    }

    public function update(Request $request, $id)
    {
        $pincode = Pincode::findOrFail($id);

        $input = $request->validate([
            'pincode' => 'required',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'status' => ''
        ]);

        $input['status'] = $input['status']??0;

        $pincode->update($input);        

        return redirect()->route('admin.pincode.index')->with('success', 'Pincode updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $pincode = Pincode::findOrFail($id);
        $pincode->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Pincode deleted successfully.');
    }
}
