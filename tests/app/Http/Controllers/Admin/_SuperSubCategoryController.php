<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperSubCategory;
use App\Models\SuperCategory;

class SuperSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function superSubCategoryList()
    {
        $superSubCategoryList = SuperSubCategory::where('deleted_at','')->get();
        return view('admin.super_sub_categories.superSubCategoriesList',compact('superSubCategoryList'));
    }
    public function addsuperSubCategory()
    {
        $superCategory = SuperCategory::where('deleted_at','')->get();
        return view('admin.super_sub_categories.addSuperSubCategory',compact('superCategory'));
    } 
    public function savesuperSubCategory(Request $request)
    {
        
        $superSubCategory = new SuperSubCategory;
        $file = $request->file('supersub_cat_image');
        $uploadImage = date('y-m-d').'_'.time().'_'.$file->getClientOriginalName();
        $file->move('uploads/category',$uploadImage);

        $superSubCategory->supersub_cat_name = $request->supersub_cat_name;
        $superSubCategory->supersub_cat_image = $uploadImage;
        $superSubCategory->super_category_id = $request->super_category_id;
        $superSubCategory->supersub_status = $request->supersub_status;
        $superSubCategory->deleted_at='';
        $superSubCategory->save();
        return redirect()->route('superSubCategoryList')->with('success','SuperSub Category Created successfully');
    }
    
    public function deletesuperSubCategory($id)
    {
        $superSubCategory = superSubCategory::where('deleted_at','')->findOrFail($id);
        $superSubCategory->deleted_at = config('constant.CURRENT_DATETIME');
        $superSubCategory->save();
        return redirect()->route('superSubCategoryList')->with('success','SuperSub CategoryList Deleted successfully');
    }
    
    public function editsuperSubCategory(Request $req, $id)
    {
        $superSubCategoryData = SuperSubCategory::where('deleted_at','')->findOrFail($id);
        $superCategory = SuperCategory::where('deleted_at','')->get();
        return view('admin.super_sub_categories.editSuperSubcategories',compact('superSubCategoryData','superCategory'));            
    } 
    
    public function updatesuperSubCategory(Request $request, $id)
    {
        $superSubCategory = SuperSubCategory::where('deleted_at','')->findOrFail($id);
        
        if ($request->file('supersub_cat_image')) 
        {
            $file = $request->file('supersub_cat_image');
            $uploadImage = date('y-m-d').'_'.time().'_'.$file->getClientOriginalName();
            $file->move('uploads/category',$uploadImage);
            $superSubCategory->supersub_cat_image = $uploadImage;

        }
            $superSubCategory->supersub_cat_name = $request->supersub_cat_name;
            $superSubCategory->super_category_id = $request->super_category_id;
            $superSubCategory->supersub_status = $request->supersub_status;
            $superSubCategory->deleted_at='';
            $superSubCategory->save();
            return redirect()->route('superSubCategoryList')->with('success','Ticket Updated Successfully');
    }
}
