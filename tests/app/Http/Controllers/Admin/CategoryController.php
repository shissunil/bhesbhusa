<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SuperSubCategory;
use App\Models\SubCategory;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categories = Category::where('deleted_at','')->orderBy('id','DESC')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $superSubCategory = SuperSubCategory::where('deleted_at', '')->get();
        return view('admin.categories.create',compact('superSubCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'supersub_cat_id' => 'required|exists:super_sub_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/category/';
            $categoryImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $categoryImage);
            $input['image'] = "$categoryImage";
        }

        $input['status'] = $input['status'] ?? 0;
        $input['deleted_at'] = '';

        Category::create($input);

        return redirect()->route('admin.category.index')->with('success', 'Category added successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $superSubCategory = SuperSubCategory::where('deleted_at', '')->get();
        return view('admin.categories.edit', compact('category','superSubCategory'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'supersub_cat_id' => 'required|exists:super_sub_categories,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/category/';
            $categoryImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $categoryImage);
            if ($category->image!='' && file_exists("uploads/category/".$category->image)) {
                unlink("uploads/category/".$category->image);
            }
            $input['image'] = "$categoryImage";
        } else {
            unset($input['image']);
        }

        $input['status'] = $input['status'] ?? 0;

        $category->update($input);

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request,$id)
    {
        $category = Category::findOrFail($id);
        $category->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Category deleted successfully.');
    }

    public function getSuperSubCategory(Request $request)
    {
        $superCatId = $request->super_cat_id;
        $superSubCategoryList = SuperSubCategory::where('deleted_at','')->where('super_category_id',$superCatId)->where('supersub_status','1')->get();
        $option = '<option value="">Select Super Sub Category</option>';
        if (count($superSubCategoryList) > 0)
        {
            foreach($superSubCategoryList as $category)
            {
                $option .= "<option value=".$category->id.">".$category->supersub_cat_name."</option>";
            }
        }
        echo $option;exit;
    }
    public function getCategory(Request $request)
    {
        $superSubCatId = $request->super_sub_cat_id;
        $categoryList = Category::where('deleted_at','')->where('supersub_cat_id',$superSubCatId)->where('status','1')->get();
        $option = '<option value="">Select Category</option>';
        if (count($categoryList) > 0)
        {
            foreach($categoryList as $category)
            {
                $option .= "<option value=".$category->id.">".$category->name."</option>";
            }
        }
        echo $option;exit;
    }
    public function getSubCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $subCategoryList = SubCategory::where('deleted_at','')->where('category_id',$categoryId)->where('status','1')->get();
        $option = '<option value="">Select Sub Category</option>';
        if (count($subCategoryList) > 0)
        {
            foreach($subCategoryList as $category)
            {
                $option .= "<option value=".$category->id.">".$category->name."</option>";
            }
        }
        echo $option;exit;
    }
}
