<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SuperCategory;
use App\Models\SuperSubCategory;
use App\Http\Controllers\Controller;

class SuperSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $superSubCategoryList = SuperSubCategory::where('deleted_at', '')->orderBy('id','desc')->get();
        return view('admin.super_sub_categories.index', compact('superSubCategoryList'));
    }

    public function create()
    {
        $superCategory = SuperCategory::where('deleted_at', '')->get();
        return view('admin.super_sub_categories.create', compact('superCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supersub_cat_name' => 'required|max:255',
            'super_category_id' => 'required|exists:super_categories,id',
            'supersub_cat_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $supersub_cat_image = '';

        if ($image = $request->file('supersub_cat_image')) {
            $destinationPath = 'uploads/super_sub_category/';
            $superSubCategoryImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $superSubCategoryImage);
            $supersub_cat_image = "$superSubCategoryImage";
        }

        $superSubCategory = new SuperSubCategory;
        $superSubCategory->supersub_cat_name = $request->supersub_cat_name;
        $superSubCategory->supersub_cat_image = $supersub_cat_image;
        $superSubCategory->super_category_id = $request->super_category_id;
        $superSubCategory->supersub_status = $request->supersub_status;
        $superSubCategory->deleted_at = '';
        $superSubCategory->save();

        return redirect()->route('admin.super-sub-category.index')->with('success', 'Super Sub Category Created Successfully');
    }

    public function edit(Request $request, $id)
    {
        $superSubCategoryData = SuperSubCategory::where('deleted_at', '')->findOrFail($id);
        $superCategory = SuperCategory::where('deleted_at', '')->get();
        return view('admin.super_sub_categories.edit', compact('superSubCategoryData', 'superCategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supersub_cat_name' => 'required|max:255',
            'super_category_id' => 'required|exists:super_categories,id',
            'supersub_cat_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $superSubCategory = SuperSubCategory::where('deleted_at', '')->findOrFail($id);

        if ($image = $request->file('supersub_cat_image')) {
            $destinationPath = 'uploads/super_sub_category/';
            $superSubCategoryImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $superSubCategoryImage);
            $superSubCategory->supersub_cat_image = "$superSubCategoryImage";
        }

        $superSubCategory->supersub_cat_name = $request->supersub_cat_name;
        $superSubCategory->super_category_id = $request->super_category_id;
        $superSubCategory->supersub_status = $request->supersub_status;
        $superSubCategory->deleted_at = '';
        $superSubCategory->save();

        return redirect()->route('admin.super-sub-category.index')->with('success', 'Super Sub Category Updated Successfully');
    }

    public function destroy(Request $request,$id)
    {
        $superSubCategory = SuperSubCategory::where('deleted_at', '')->findOrFail($id);
        $superSubCategory->deleted_at = Carbon::now();
        $superSubCategory->save();
        $request->session()->flash('success', 'Super Sub Category Deleted Successfully.');
    }
}
