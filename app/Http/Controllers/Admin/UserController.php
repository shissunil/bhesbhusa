<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Http\Request;



class UserController extends Controller

{

    public function __construct()

    {

        $this->middleware('auth:admin');

    }



    public function index(Request $request)

    {
        if($request->ajax()){
            $status = $request->status;
            if($status==''){
                $users = User::orderBy('id','DESC')->get();
                return view('admin.users.filter', compact('users'));
            }else{
                $users = User::orderBy('id','DESC')->where('status',$status)->get();
                return view('admin.users.filter', compact('users'));
            }

        }

        $users = User::orderBy('id','DESC')->get();

        return view('admin.users.index', compact('users'));

    }



    public function edit($id)

    {

        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));

    }



    public function update(Request $request, $id)

    {

        $user = User::findOrFail($id);

        $user->status = $request->status?? 0;

        $user->save();



        return redirect()->route('admin.users.index')->with('success', 'Customer updated successfully.');

    }



}

