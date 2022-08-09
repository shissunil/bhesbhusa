<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CmsMaster;

class CmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function cmsList()
    {
        $cmsList = CmsMaster::where('deleted_at','')->get();
        return view('admin.cms.cmsList',compact('cmsList'));
    }
    public function addCms()
    {
        return view('admin.cms.addCms');
        
    }
    public function saveCms(Request $request)
    {
        $cms = new CmsMaster;
        $cms->cms_page = $request->cms_page;
        $cms->title = $request->title;
        $cms->discription = $request->discription;
        $cms->status = $request->status;
        $cms->deleted_at = '';
        $cms->save();
        return redirect()->route('cmsList')->with('success','Cms Created successfully');
    
    }
    public function editCms($id)
    {
       
        $cmsData = CmsMaster::where('deleted_at','')->findOrFail($id);
        return view('admin.cms.editCms',compact('cmsData'));            
        
    }
    public function updateCms(Request $request, $id)
    {
       
        $cms = CmsMaster::where('deleted_at','')->findOrFail($id);
        
        $cms->cms_page = $request->cms_page;
        $cms->title = $request->title;
        $cms->discription = $request->discription;
        $cms->status = $request->status;
        $cms->deleted_at = '';
        $cms->save();
        return redirect()->route('cmsList')->with('success','Cms Updated Successfully');
    }
    public function deleteCms($id)
    {
    
        $cms = CmsMaster::where('deleted_at','')->findOrFail($id);
        $cms->deleted_at = config('constant.CURRENT_DATETIME');
        $cms->save();
        return redirect()->route('cmsList')->with('success','Cms Deleted Successfully');
      
    }
}
