<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Color;
use Carbon\Carbon;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $colors = Color::where('deleted_at', '')->orderBy('id', 'DESC')->get();
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'color' => 'required|max:255|unique:colors,color',
            'code' => 'required|unique:colors,code',
        ]);

        $color = new Color;
        $color->color = $request->color;
        $color->code = $request->code;
        $color->status = $request->status ?? 0;
        $color->deleted_at = '';
        $color->save();

        return redirect()->route('admin.color.index')->with('success', 'Color added successfully.');
    }

    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        $request->validate([
            'color' => 'required|max:255|unique:colors,color,'.$id,
            'code' => 'required|unique:colors,code,'.$id,
        ]);

        $color->color = $request->color;
        $color->code = $request->code;
        $color->status = $request->status ?? 0;
        $color->save();

        return redirect()->route('admin.color.index')->with('success', 'Color updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $color->deleted_at = Carbon::now();
        $color->save();
        $request->session()->flash('success', 'Color deleted successfully.');
    }
}
