<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CmsMaster;
use App\Models\WebCmsMaster;

class CmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $cmsList = CmsMaster::where('deleted_at', '')->orderBy('id', 'DESC')->get();
        return view('admin.cms.index', compact('cmsList'));
    }

    public function create()
    {
        return view('admin.cms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cms_page' => 'required|max:255',
            'title' => 'required',
            'discription' => 'required',
        ]);

        $cms = new CmsMaster;
        $cms->cms_page = $request->cms_page;
        $cms->title = $request->title;
        $cms->discription = $request->discription;
        $cms->status = $request->status;
        $cms->app_type = $request->app_type;
        $cms->deleted_at = '';
        $cms->save();

        return redirect()->route('admin.cms-master.index')->with('success', 'CMS Created Successfully.');
    }

    public function edit($id)
    {
        $cmsData = CmsMaster::where('deleted_at', '')->findOrFail($id);
        return view('admin.cms.edit', compact('cmsData'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'discription' => 'required',
        ]);

        $cms = CmsMaster::where('deleted_at', '')->findOrFail($id);

        // $cms->cms_page = $request->cms_page;
        $cms->title = $request->title;
        $cms->discription = $request->discription;
        // $cms->status = $request->status;
        // $cms->app_type = $request->app_type;
        $cms->save();

        return redirect()->route('admin.cms-master.index')->with('success', 'CMS Updated Successfully.');
    }

    public function destroy(Request $request,$id)
    {
        $cms = CmsMaster::where('deleted_at', '')->findOrFail($id);
        $cms->deleted_at = Carbon::now();
        $cms->save();
        $request->session()->flash('success', 'CMD Deleted Successfully.');
    }


    public function webCmsindex()
    {
        $cmsList = WebCmsMaster::where('deleted_at', '')->get();
        return view('admin.cms.webCmsindex', compact('cmsList'));
    }
    public function webCmsCreate()
    {
        return view('admin.cms.webCmsCreate');
    }
    public function webCmsstore(Request $request)
    {
        // $request->validate([
        //     'image_one' => 'required',
        //     'image_two' => 'required',
        //     'image_three' => 'required',
        //     'title_one' => 'required|max:255',
        //     'title_two' => 'required|max:255',
        //     'title_three' => 'required|max:255',
        //     'discription_one' => 'required',
        //     'discription_two' => 'required',
        //     'discription_three' => 'required',
        // ]);

        $cms = new WebCmsMaster;

        $file = $request->file('image_one');
        $image = time() . "_" . $file->getClientOriginalName();
        $file->move('uploads/cms/',$image);
        $cms->image_one = $image;


        $image_two = $request->file('image_two');
        $image = time() . "_" . $image_two->getClientOriginalName();
        $image_two->move('uploads/cms/',$image);
        $cms->image_two = $image;

        $image_three = $request->file('image_three');
        $image = time() . "_" . $image_three->getClientOriginalName();
        $image_three->move('uploads/cms/',$image);
        $cms->image_three = $image;


        $cms->title_one = $request->title_one ?? '';
        $cms->title_two = $request->title_two ?? '';
        $cms->title_three = $request->title_three ?? '';
        $cms->discription_one = $request->discription_one ?? '';
        $cms->discription_two = $request->discription_two ?? '';
        $cms->discription_three = $request->discription_three ?? '';
        $cms->deleted_at = '';
        $cms->save();

        return redirect()->route('admin.web-cms-master.index')->with('success', 'CMS Created Successfully.');
    }
    public function webCmsedit($id)
    {
        $cmsData = WebCmsMaster::where('deleted_at', '')->findOrFail($id);
        return view('admin.cms.webCmsedit', compact('cmsData'));
    }
    public function webCmsupdate(Request $request,$id)
    {
        // $request->validate([
        //     'title_one' => 'required|max:255',
        //     'title_two' => 'required|max:255',
        //     'title_three' => 'required|max:255',
        //     'discription_one' => 'required',
        //     'discription_two' => 'required',
        //     'discription_three' => 'required',
        // ]);

        $cms = WebCmsMaster::where('deleted_at','')->findOrFail($id);

        if ($request->file('image_one'))
        {
            $file = $request->file('image_one');
            $image = time() . "_" . $file->getClientOriginalName();
            $file->move('uploads/cms/',$image);
            $cms->image_one = $image;
        }

        if ($request->file('image_two'))
        {
            $image_two = $request->file('image_two');
            $image = time() . "_" . $image_two->getClientOriginalName();
            $image_two->move('uploads/cms/',$image);
            $cms->image_two = $image;
        }
        if ($request->file('image_three'))
        {
            $image_three = $request->file('image_three');
            $image = time() . "_" . $image_three->getClientOriginalName();
            $image_three->move('uploads/cms/',$image);
            $cms->image_three = $image;
        }
        $cms->title_one = $request->title_one ?? '';
        $cms->title_two = $request->title_two ?? '';
        $cms->title_three = $request->title_three ?? '';
        $cms->discription_one = $request->discription_one ?? '';
        $cms->discription_two = $request->discription_two ?? '';
        $cms->discription_three = $request->discription_three ?? '';
        $cms->deleted_at = '';
        $cms->save();

        return redirect()->route('admin.web-cms-master.index')->with('success', 'CMS Updated Successfully.');
    }
}
