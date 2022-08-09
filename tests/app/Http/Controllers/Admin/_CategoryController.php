<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
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

        Category::create($input);

        return redirect()->route('admin.category.index')->with('success', 'Category added successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required',
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
}
