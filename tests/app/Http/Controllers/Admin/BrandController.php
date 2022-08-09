<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $brands = Brand::where('deleted_at','')->orderBy('id','DESC')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:brands,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/brand/';
            $brandImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $brandImage);
            $input['image'] = "$brandImage";
        }

        $input['status'] = $input['status'] ?? 0;

        Brand::create($input);

        return redirect()->route('admin.brand.index')->with('success', 'Brand added successfully.');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = brand::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:brands,name,'.$id,
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/brand/';
            $brandImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $brandImage);
            if ($brand->image!='' && file_exists("uploads/brand/".$brand->image)) {
                unlink("uploads/brand/".$brand->image);
            }
            $input['image'] = "$brandImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;

        $brand->update($input);

        return redirect()->route('admin.brand.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Request $request,$id)
    {
        $brand = brand::findOrFail($id);
        $brand->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Brand deleted successfully.');
    }
}
