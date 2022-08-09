<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use App\Models\Banner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $banners = Banner::whereHas('offer', function ($query) {
            $query->where('deleted_at','');
        })->where('deleted_at', '')->orderBy('id', 'DESC')->get();
        
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.banners.create',compact('offers'));
    }

    public function store(Request $request)
    {
        $request->validate([            
            'offer_id' => 'required|exists:offers,id',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_location' => 'required'
        ]);

        $input = $request->all();

        if ($image = $request->file('banner_image')) {
            $destinationPath = 'uploads/banners/';
            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $bannerImage);
            $input['banner_image'] = "$bannerImage";
        }

        $input['banner_status'] = $input['banner_status'] ?? 0;

        Banner::create($input);

        return redirect()->route('admin.banners.index')->with('success', 'Banner added successfully.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        $offers = Offer::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.banners.edit', compact('banner','offers'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'banner_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_location' => 'required'
        ]);

        $input = $request->all();

        if ($image = $request->file('banner_image')) {
            $destinationPath = 'uploads/banners/';
            $bannerImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $bannerImage);
            if ($banner->banner_image != '' && file_exists("uploads/banners/" . $banner->banner_image)) {
                unlink("uploads/banners/" . $banner->banner_image);
            }
            $input['banner_image'] = "$bannerImage";
        } else {
            unset($input['banner_image']);
        }

        $input['banner_status'] = $input['banner_status'] ?? 0;

        $banner->update($input);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Banner deleted successfully.');
    }
}
