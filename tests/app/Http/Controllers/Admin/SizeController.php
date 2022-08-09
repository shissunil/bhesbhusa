<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use Carbon\Carbon;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $sizes = Size::where('deleted_at', '')->orderBy('id', 'DESC')->get();
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required|max:255|unique:sizes,size',
        ]);
        $size = new Size;
        $size->size = $request->size;
        $size->status = $request->status ?? 0;
        $size->deleted_at = '';
        $size->save();

        return redirect()->route('admin.sizes.index')->with('success', 'Size added successfully.');
    }

    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);

        $request->validate([
            'size' => 'required|max:255|unique:sizes,size,'.$id,
        ]);

        $size->size = $request->size;
        $size->status = $request->status ?? 0;
        $size->save();

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $size = Size::findOrFail($id);
        $size->deleted_at = Carbon::now();
        $size->save();
        $request->session()->flash('success', 'Size deleted successfully.');
    }
}
