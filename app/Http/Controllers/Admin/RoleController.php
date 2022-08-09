<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $roles = Role::orderBy('id','DESC')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $slug = Str::slug($name);
        $description = $request->description ?? '';
        $input = $request->validate([
            'name' => 'required|max:255|unique:roles,name',
            'slug' => '',
            'description' => ''
        ]);
        $role = new Role;
        $role->name = $name;
        $role->slug = $slug;
        $role->description = $description;
        $role->save();
        return redirect()->route('admin.role.index')->with('success', 'Role added successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $name = $request->name;
        $slug = Str::slug($name);
        $description = $request->description ?? '';
        $role = Role::findOrFail($id);
        $input = $request->validate([
            'name' => 'required|max:255|unique:roles,name,'.$id,
            'slug' => '',
            'description' => ''
        ]);
        $input['name'] = $name;
        $input['slug'] = $slug; 
        $input['description'] = $description; 
        $role->update($input);

        return redirect()->route('admin.role.index')->with('success', 'Role updated successfully.');
    }
}
