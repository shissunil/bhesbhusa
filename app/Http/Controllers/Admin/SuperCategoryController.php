<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SuperCategory;

class SuperCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $superCategoryList = SuperCategory::where('deleted_at', '')->orderBy('id','DESC')->get();
        return view('admin.super_categories.index', compact('superCategoryList'));
    }

    public function create()
    {
        return view('admin.super_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'supercategory_name' => 'required|max:255',
            'supercategory_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $supercategory_image = '';

        if ($image = $request->file('supercategory_image')) {
            $destinationPath = 'uploads/super_category/';
            $categoryImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $categoryImage);
            $supercategory_image = "$categoryImage";
        }

        $superCategory = new SuperCategory;
        $superCategory->supercategory_name = $request->supercategory_name;
        $superCategory->supercategory_image = $supercategory_image;
        $superCategory->supercategory_status = $request->supercategory_status;
        $superCategory->deleted_at = '';
        $superCategory->save();

        return redirect()->route('admin.super-category.index')->with('success', 'Supar Category Created successfully');
    }   

    public function edit($id)
    {
        $superCategory = SuperCategory::where('deleted_at', '')->findOrFail($id);
        return view('admin.super_categories.edit', compact('superCategory'));
    }

    public function update($id,Request $request)
    {
        $superCategory = SuperCategory::findOrFail($id);

        if ($image = $request->file('supercategory_image')) {
            $destinationPath = 'uploads/super_category/';
            $categoryImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $categoryImage);
            $superCategory->supercategory_image = "$categoryImage";
        }

        $superCategory->supercategory_name = $request->supercategory_name;
        $superCategory->supercategory_status = $request->supercategory_status;
        $superCategory->deleted_at = '';
        $superCategory->save();

        return redirect()->route('admin.super-category.index')->with('success', 'Super Category Updated Successfully');
    }

    public function destroy(Request $request, $id)
    {
        $superCategory = SuperCategory::where('deleted_at', '')->findOrFail($id);
        $superCategory->deleted_at = Carbon::now();
        $superCategory->save();
        $request->session()->flash('success', 'Super Sub Category Deleted Successfully.');
    }

}
