<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\City;
use App\Models\State;
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
            $query->where('deleted_at','');
        })->where('deleted_at','')->orderBy('id', 'DESC')->get();

        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        $states = State::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.cities.create',compact('states'));
    }

    public function store(Request $request)
    {
        City::create($request->validate([
            'name' => 'required',
            'state_id' => 'required|exists:states,id',
            'status' => ''
        ]));

        return redirect()->route('admin.city.index')->with('success', 'City added successfully.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $states = State::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.cities.edit', compact('city','states'));
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        $input = $request->validate([
            'name' => 'required',
            'state_id' => 'required|exists:states,id',
            'status' => ''
        ]);

        $input['status'] = $input['status']??0;

        $city->update($input);        

        return redirect()->route('admin.city.index')->with('success', 'City updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $city = City::findOrFail($id);
        $city->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'City deleted successfully.');
    }
}
