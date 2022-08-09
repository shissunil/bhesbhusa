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

    public function superCategoryList()
    {
        $superCategoryList = SuperCategory:: where('deleted_at','')->get();
        return view('admin.super_categories.superCategoriesList',compact('superCategoryList'));
        // dd($superCategoryList);
    }
    public function addSuperCategoryList()
    {
        return view('admin.super_categories.addSuperCategories');
    }
    // save data insert
    public function saveSuperCategoryList(Request $request)
    {
        
            $superCategory = new SuperCategory;
            $file = $request->file('supercategory_image');
            $uploadImage =  date('y-m-d').'_'.time().'_'.$file->getClientOriginalName();
            $file->move('uploads/category',$uploadImage);

            $superCategory->supercategory_name = $request->supercategory_name;
            $superCategory->supercategory_image = $uploadImage;
            $superCategory->supercategory_status = $request->supercategory_status;
            $superCategory->deleted_at = '';
            $superCategory->save();
            return redirect()->route('superCategoryList')->with('success','Supar Category Created successfully');
    }
    // deleted data
    public function deleteSuperCategoryList($id)
    {
        $superCategory = SuperCategory::where('deleted_at','')->findOrFail($id);
        $superCategory->deleted_at = config('constant.CURRENT_DATETIME');
        $superCategory->save();
        // $request->session()->flash('success', 'Category deleted successfully.');
        return redirect()->route('superCategoryList')->with('success','Super Category Deleted successfully');
    }
    
    // edit data list
    public function editSuperCategoryList($id)
    {
            $superCategory = SuperCategory::where('deleted_at','')->findOrFail($id);
            return view('admin.super_categories.editSuperCategories',compact('superCategory')); 

    } 
    
    // update 
    public function updateSuperCategoryList(Request $request, $id)
    {
        
            $superCategory = SuperCategory::where('deleted_at','')->findOrFail($id);
            
             if ($request->file('supercategory_image'))
            {
                $file = $request->file('supercategory_image');
                $image =  date('y-m-d').'_'.time().'_'.$file->getClientOriginalName();
                $file->move('uploads/category',$image);
                $superCategory->supercategory_image = $image;
            }

            $superCategory->supercategory_name = $request->supercategory_name;
            $superCategory->supercategory_status = $request->supercategory_status;
            $superCategory->deleted_at='';
            $superCategory->save();
            return redirect()->route('superCategoryList')->with('success','Cancel Updated Successfully');
    }
    
    // Active and inActive
    
    // public function cancelStatusWiseData(Request $req)
    // {
    //     $cancel_status = $req->cancel_status;
    //     $canceltList = CancelMaster::where('deleted_at','')->where('cancel_status',$cancel_status)->get();
    //     $row = '';
    //     $i = 0;
    //     foreach($canceltList as $cancel)
    //     {
            
    //         $i++;
    //         $row .= "<tr>
    //         <td>".$i."</td>
    //         <td>".$cancel->cancel_reason_en."</td><td>".$cancel->cancel_reason_ar."</td>";
    //         if ($cancel->cancel_status == '1')
    //         {
    //             $row .= "<td><a href='".route('updateCancelMasterStatus',$cancel->id)."'><div class='badge badge-success'>Active</div></a></td>";
    //         }
    //         else
    //         {
    //             $row .= "<td><a href='".route('updateCancelMasterStatus',$cancel->id)."'><div class='badge badge-danger'>InActive</div></a></td>";
    //         }
    //         $row .= "<td><a href='".route('editCancelMaster',$cancel->id)."' class='btn btn-success' title='Edit'><i class='fa fa-pencil'></i></a>
    //         <a href='".route('deleteCancelMaster',$cancel->id)."' class='btn btn-danger' title='Delete'><i class='fa fa-trash'></i></a>
    //         </td></tr>";
    //     }
    //     echo $row;
    //     exit;
    // } 

     // //  status update
    // public function updateCancelMasterStatus($id)
    // {
    //     if (session()->has('is_admin_logged_in'))
    //     {
    //         $CancelMaster = CancelMaster::where('deleted_at','')->findOrFail($id);
    //         $status = (int)$CancelMaster->cancel_status;
    //         if ($status == 1)
    //         {
    //             $CancelMaster->cancel_status = '0';
    //         }
    //         else
    //         {
    //             $CancelMaster->cancel_status = '1';
    //         }
            
    //         $CancelMaster->save();
    //         return redirect()->route('CancelMasterList')->with('success','Cancel Status Updated Successfully');
    //     }
    //     else
    //     {
    //         return redirect()->route('login');
    //     }
    // }   
    
}
