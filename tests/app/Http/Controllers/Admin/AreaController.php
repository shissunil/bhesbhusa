<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\State;
use App\Models\City;
use App\Models\Pincode;
use Carbon\Carbon;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $areas = Area::whereHas('pincode', function ($query) {
            $query->where('deleted_at','');
        })->where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        $states = State::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.areas.create',compact('states'));
    }

    public function store(Request $request)
    {
        Area::create($request->validate([
            'area' => 'required|max:255|unique:areas,area',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'pincode_id' => 'required|exists:pincodes,id',
            'status' => ''
        ]));

        return redirect()->route('admin.area.index')->with('success', 'Area added successfully.');
    }

    public function edit($id)
    {
        $area = Area::findOrFail($id);
        $states = State::where('deleted_at','')->orderBy('id', 'DESC')->get();
        $cities = City::where(['deleted_at'=>'', 'state_id'=>$area->state_id])->orderBy('id', 'DESC')->get();
        $pincodes = Pincode::where(['deleted_at'=>'', 'city_id'=>$area->city_id])->orderBy('id', 'DESC')->get();
        return view('admin.areas.edit', compact('cities','states','pincodes','area'));
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $input = $request->validate([
            'area' => 'required|max:255||unique:areas,area,'.$id,
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'pincode_id' => 'required|exists:pincodes,id',
            'status' => ''
        ]);

        $input['status'] = $input['status']??0;

        $area->update($input);        

        return redirect()->route('admin.area.index')->with('success', 'Area updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        $area->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Pincode deleted successfully.');
    }
}
