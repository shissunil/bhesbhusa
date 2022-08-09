<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Carbon\Carbon;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $sub_categories = SubCategory::whereHas('category', function ($query) {
            $query->where('deleted_at','');
        })->where('deleted_at','')->orderBy('id', 'DESC')->get();
        
        return view('admin.sub_categories.index', compact('sub_categories'));
    }

    public function create()
    {
        $categories = Category::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.sub_categories.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/sub_category/';
            $subCategoryImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $subCategoryImage);
            $input['image'] = "$subCategoryImage";
        }

        $input['status'] = $input['status'] ?? 0;
        $input['deleted_at'] = '';

        SubCategory::create($input);

        return redirect()->route('admin.sub-category.index')->with('success', 'Sub category added successfully.');
    }

    public function edit($id)
    {
        $sub_category = SubCategory::findOrFail($id);
        $categories = Category::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.sub_categories.edit', compact('sub_category','categories'));
    }

    public function update(Request $request, $id)
    {
        $sub_category = SubCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/sub_category/';
            $subCategoryImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $subCategoryImage);
            if ($sub_category->image != '' && file_exists("uploads/sub_category/" . $sub_category->image)) {
                unlink("uploads/sub_category/" . $sub_category->image);
            }
            $input['image'] = "$subCategoryImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;

        $sub_category->update($input);

        return redirect()->route('admin.sub-category.index')->with('success', 'Sub category updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $sub_category = SubCategory::findOrFail($id);
        $sub_category->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Sub category deleted successfully.');
    }
}
