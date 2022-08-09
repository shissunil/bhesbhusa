<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiBaseController as ApiBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SuperCategory;
use App\Models\SuperSubCategory;
use Auth;
use stdClass;

class CategoryController extends ApiBaseController
{
    // public function category_list(Request $request)
    // {
    //     $category_list = Category::where('is_deleted',0)->orderBy('id','DESC')->get();
    //     if(!empty($category_list)){
    //         foreach($category_list as $category){
    //             $category->image = (!empty($category->image)) ? asset('uploads/category/'.$category->image) : '';
    //         }
    //     }
        
    //     return response(['category_list'=>$category_list]);
    // }
    
    // public function category_details(Request $request)
    // {
    //     $input = $request->validate([
    //         'category_id' => 'required'
    //     ]);
        
    //     $category_id = $input['category_id'];
        
    //     $category_details = Category::where(['is_deleted' =>0, 'id' => $category_id])->orderBy('id','DESC')->first();
        
    //     if(!empty($category_details)){
    //         $category_details->image = (!empty($category_details->image)) ? asset('uploads/category/'.$category_details->image) : '';
    //         return response(['category_details'=>$category_details]);
    //     }else{
    //         return response(['message'=>'Category not found.']);
    //     }
        
    // }
    // public function subCategoryList(Request $request)
    // {
    //     try 
    //     {
    //         $input =$request->validate([
    //             'category_id' => 'required'
    //         ]);
    //         $categoryId = $input['category_id'];
    //         $subCategory = SubCategory::where(['is_deleted' =>0,'id' => $categoryId])->orderBy('id','DESC')->get();
    //         if ($subCategory) 
    //         {
    //             $data['subCategory'] = $subCategory;
    //             return $this->sendResponse($data,'Sub Category List...');
    //         }
    //         else
    //         {
    //             $data['subCategory'] = [];
    //             return $this->sendResponse($data,'Data Not Found...!');
    //         }
    //     } 
    //     catch (Exception $e) 
    //     {
    //         return $this->sendError($e->getMessage());  
    //     }
    // }
    public function superCategoryList(Request $request)
    {
        try 
        {
            $keyword = $request->keyword;
            if (isset($keyword))
            {
                $superCategoryList = SuperCategory::where('deleted_at','')->where('supercategory_status','1')->where('supercategory_name','like','%'.$keyword.'%')->get();
            }
            else
            {
                $superCategoryList = SuperCategory::where('deleted_at','')->where('supercategory_status','1')->get();
            }
            if(!empty($superCategoryList->toArray()))
            {
                foreach ($superCategoryList as $key => $value) 
                {
                    $value->supercategory_image = (!empty($value->supercategory_image)) ? asset('uploads/super_category/'.$value->supercategory_image) : '';
                    $superSubCategoryList = SuperSubCategory::where('deleted_at','')->where('supersub_status','1')->where('super_category_id',$value->id)->get();
                    $value->sub_category_text = '';
                    if (!empty($superSubCategoryList->toArray()))
                    {
                        $sub_category_text = '';
                        foreach($superSubCategoryList as $category)
                        {
                            // $value->sub_category_text .= $category->supersub_cat_name. ',';
                            $sub_category_text = $sub_category_text != '' ? $sub_category_text . ','. $category->supersub_cat_name : $category->supersub_cat_name;
                        }
                        $value->sub_category_text = $sub_category_text;
                    }
                }
                // $data['superCategoryList'] = $superCategoryList;
                return $this->sendResponse(200, $superCategoryList,'Super Category List...');
                // return response(['superCategoryList'=>$superCategoryList]);
            }
            else
            {
                // $data['superCategoryList'] = [];
                $superCategoryList = [];
                return $this->sendResponse(201, $superCategoryList,'Record Not Found...!');
                // return response(['message'=>'Category not found.']);
            } 
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());  
        }   
    }
    public function superSubCategory(Request $request)
    {
        try 
        {
            // $input = $request->validate([
            // 'super_category_id' => 'required'
            // ]);
            $SuperCategoryId = $request->super_category_id;
            // $SuperCategoryId = $input['super_category_id'];
            $superSubCategory = SuperSubCategory::where('deleted_at','')->where('super_category_id',$SuperCategoryId)->where('supersub_status','1')->get();
            if (!empty($superSubCategory->toArray()))
            {
                foreach ($superSubCategory as $key => $SubCategory) 
                {
                    $SubCategory->supersub_cat_image = (!empty($SubCategory->supersub_cat_image)) ? asset('uploads/super_sub_category/'.$SubCategory->supersub_cat_image) : '';

                    $category_list = Category::where('deleted_at','')->where('status','1')->where('supersub_cat_id',$SubCategory->id)->get();
                    if (!empty($category_list->toArray())) 
                    {
                        // $category_list->image = (!empty($category_list->image)) ? asset('uploads/category/'.$category_list->image) : '';
                        foreach ($category_list as $value) 
                        {
                            $value->image = (!empty($value->image)) ? asset('uploads/category/'.$value->image) : '';
                            $subCategory_list = SubCategory::where(['deleted_at' =>'','category_id' => $value->id,'status' => '1'])->get();
                            // $subCategory_list->image = 'uploads/category/'.$subCategory_list->image;
                            if (!empty($subCategory_list->toArray()))
                            {
                                foreach($subCategory_list as $sCValue)
                                {
                                    $sCValue->image = (!empty($sCValue->image)) ? asset('uploads/sub_category/'.$sCValue->image) : '';
                                }
                            }
                            $value->subCategory_list = $subCategory_list;
                        }
                    }
                    $SubCategory->category_list = $category_list;
                }
                // $data['superSubCategory'] = $superSubCategory;
                return $this->sendResponse(200,$superSubCategory,'Super Category List...!');
            }
            else
            {
                // $data['superSubCategory'] = [];
                $superSubCategory = [];
                return $this->sendResponse(201,$superSubCategory,'record not found !');
            }
        } 
        catch (Exception $e) 
        {
            return $this->sendError(201,$e->getMessage());  
            
        }
    }

}
