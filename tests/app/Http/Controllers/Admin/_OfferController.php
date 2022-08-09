<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Offer;
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
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        Offer::create($request->validate([
            'offer_name' => 'required',
            'offer_description' => 'required',
            'offer_code' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'end_date' => 'required',
            'offer_status' => ''
        ]));

        return redirect()->route('admin.offers.index')->with('success', 'Offer added successfully.');
    }

    public function edit($id)
    {
        $offer = Offer::findOrFail($id);
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        $input = $request->validate([
            'offer_name' => 'required',
            'offer_description' => 'required',
            'offer_code' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'end_date' => 'required',
            'offer_status' => ''
        ]);
        $input['offer_status'] = $input['offer_status']??0; 
        $offer->update($input);

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Request $request,$id)
    {
        $offer = Offer::findOrFail($id);
        $offer->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Offer deleted successfully.');
    }

}
