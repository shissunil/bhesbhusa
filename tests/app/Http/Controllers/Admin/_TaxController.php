<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use Carbon\Carbon;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $taxes = Tax::where('deleted_at','')->orderBy('id','DESC')->get();
        return view('admin.taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('admin.taxes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $input = $request->all();

        $input['status'] = $input['status'] ?? 0;

        Tax::create($input);

        return redirect()->route('admin.tax.index')->with('success', 'Tax added successfully.');
    }

    public function edit($id)
    {
        $tax = tax::findOrFail($id);
        return view('admin.taxes.edit', compact('tax'));
    }

    public function update(Request $request, $id)
    {
        $tax = Tax::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $input = $request->all();

        $input['status'] = $input['status'] ?? 0;

        $tax->update($input);

        return redirect()->route('admin.tax.index')->with('success', 'Tax updated successfully.');
    }

    public function destroy(Request $request,$id)
    {
        $tax = Tax::findOrFail($id);
        $tax->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Tax deleted successfully.');
    }
}
