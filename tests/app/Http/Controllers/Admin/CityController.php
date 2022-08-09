<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $cities = City::whereHas('state', function ($query) {
            $query->where('deleted_at', '');
        })->where('deleted_at', '')->orderBy('id', 'DESC')->get();

        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        $states = State::where('deleted_at', '')->orderBy('id', 'DESC')->get();
        return view('admin.cities.create', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:cities,name',
            'state_id' => 'required|exists:states,id',
            'delivery_days' => 'required',
        ]);

        $city = new City;
        $city->name = $request->name;
        $city->state_id = $request->state_id;
        $city->status = $request->status ?? 0;
        $city->delivery_days = $request->delivery_days;
        $city->shipping_charge = $request->shipping_charge ?? '';
        $city->is_free = $request->is_free ?? 0;
        $city->deleted_at = '';
        $city->save();

        return redirect()->route('admin.city.index')->with('success', 'City added successfully.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $states = State::where('deleted_at', '')->orderBy('id', 'DESC')->get();
        return view('admin.cities.edit', compact('city', 'states'));
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        $input = $request->validate([
            'name' => 'required|max:255|unique:cities,name,'.$id,
            'state_id' => 'required|exists:states,id',
            'delivery_days' => 'required',
        ]);
        $cityStatus = $request->status ?? 0;

        $city->name = $request->name;
        $city->state_id = $request->state_id;
        $city->status = $cityStatus;
        $city->delivery_days = $request->delivery_days;
        $city->shipping_charge = $request->shipping_charge ?? '';
        $city->is_free = $request->is_free ?? 0;
        $city->deleted_at = '';
        $city->save();

        if ($cityStatus == 0)
        {
            $changePincodeStatus = Pincode::where('deleted_at','')->where('city_id',$id)->update(['status'=>0]);
            $changeAreatatus = Area::where('deleted_at','')->where('city_id',$id)->update(['status'=>0]);
        }
        else
        {
            $changePincodeStatus = Pincode::where('deleted_at','')->where('city_id',$id)->update(['status'=>1]);
            $changeAreatatus = Area::where('deleted_at','')->where('city_id',$id)->update(['status'=>1]);
        }
        return redirect()->route('admin.city.index')->with('success', 'City updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $city = City::findOrFail($id);
        $city->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'City deleted successfully.');
    }
}
