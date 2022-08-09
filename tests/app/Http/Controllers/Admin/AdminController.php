<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Route;
// use function PHPUnit\Framework\matches;
use App\Models\User;
use App\Models\DeliveryAssociate;
use App\Models\Order;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {        
        $admin = Admin::where('id', Auth::id())->first();
        $role = Role::where('id',$admin->role_id)->first();
        if($role){
            if($role->slug=='super-admin'){
                $this->insertPermissions();
            }
        }
        $totalCustomer = User::count();
        $totalDeliveryAssociate = DeliveryAssociate::count();
        $ticketRaiseCount = Order::where('cancel_id','!=','0')->count();
        $ongoingOfferCount = Offer::where('deleted_at', '')->count();
        $availableProductCount = Product::where('deleted_at', '')->where('product_status','1')->count();
        $canceledOrderCount = Order::where('deleted_at','')->where('order_status','3')->count();
        $deliveredOrderCount = Order::where('deleted_at','')->where('order_status','4')->count();
        $returnOrderCount = Order::where('deleted_at','')->where('order_status','5')->where('pickup_done','1')->count();
        //today Statistics
        $todayCustomer = User::whereDate('created_at', Carbon::today())->count();
        $todayDeliveryAssociate = DeliveryAssociate::whereDate('created_at', Carbon::today())->count();
        $todayTicketRaiseCount = Order::where('cancel_id','!=','0')->where('return_date',Carbon::today())->count();

        $todayOngoingOfferCount = Offer::where('deleted_at', '')->where('offer_status','1')->whereDate('start_date','<=',Carbon::today())->whereDate('end_date','>=',Carbon::today())->count();
        $todayCanceledOrderCount = Order::where('deleted_at','')->where('order_status','3')->whereDate('updated_at', Carbon::today())->count();
        $todayAssignOrder = Order::where('deleted_at','')->where('order_status','2')->whereDate('assign_date', date('Y-m-d'))->count();
        $todayDeliveredOrderCount = Order::where('deleted_at','')->where('order_status','4')->whereDate('created_at', Carbon::today())->count();
        $todayReturnOrderCount = Order::where('deleted_at','')->where('order_status','5')->where('pickup_done','1')->whereDate('return_date', Carbon::today())->count();
        $todayCashCollected = Order::where('deleted_at','')->where('order_status','4')->whereDate('delivered_date', Carbon::today())->sum('total_amount');
        
        return view('admin.dashboard',compact('totalCustomer','totalDeliveryAssociate','ticketRaiseCount','ongoingOfferCount','availableProductCount','canceledOrderCount','todayCustomer','todayDeliveryAssociate','todayTicketRaiseCount','todayOngoingOfferCount','todayCanceledOrderCount','todayAssignOrder', 'deliveredOrderCount', 'returnOrderCount', 'todayDeliveredOrderCount', 'todayReturnOrderCount','todayCashCollected'));
    }

    public function profile()
    {        
        return view('admin.profile');
    }

    public function update_profile()
    {
        $user = Auth::user();

        $this->validate(
            request(),
            [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:admins,email,' . $user->id,
            ],
        );

        $user->update([
            'name' => strip_tags(request('name')),
            'email' => strip_tags(request('email')),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Profile Updated Successfully.');
    }

    public function showChangePasswordForm()
    {
        return view('admin.change_password');
    }

    public function change_password()
    {
        $user = Auth::user();

        $this->validate(
            request(),
            [
                'cpassword' => 'required|current_password:admin',
                'password' => 'required|max:255|confirmed',
                'password_confirmation' => 'required',
            ],
        );

        $user->update([
            'password' => bcrypt(strip_tags(request('password'))),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Password Changed Successfully.');
    }

    public function sub_admins()
    {
        $sub_admins = Admin::where('id', '!=', Auth::id())->orderBy('id', 'DESC')->get();
        return view('admin.sub_admins.index', compact('sub_admins'));
    }

    public function create()
    {
        $permissions = Permission::groupBy('controller')
            ->select('controller')
            ->selectRaw('GROUP_CONCAT(method) as route_methods')
            ->selectRaw('GROUP_CONCAT(name) as route_names')
            ->selectRaw('GROUP_CONCAT(id) as pids')
            // ->orderBy('id', 'ASC')
            ->get();

        $roles = Role::all();

        // dd($permissions);

        return view('admin.sub_admins.create', compact('permissions', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        $permissions = $request->permissions;

        $admin = new Admin;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        $admin->role_id = $request->role_id;
        $admin->status = $request->status ?? 0;
        $admin->token = '';
        $admin->email_verified_at = '';
        $admin->is_verified = 0;
        $admin->remember_token = '';
        $admin->save();

        // atache all permissions to sub admin 
        if ($permissions) {
            //get already attach Ids
            $attachedIds = $admin->permissions()->whereIn('permission_id', $permissions)->pluck('permission_id')->toArray();
            //Remove the attached IDs from the request array
            $newIds = array_diff($permissions, $attachedIds);
            // atache all permissions to admin.
            $admin->permissions()->attach($newIds);
        }

        return redirect()->route('admin.sub_admins.index')->with('success', 'Sub admin added successfully.');
    }

    public function edit($id)
    {
        $sub_admin = Admin::findOrFail($id);
        $sub_admin_permissions = ($sub_admin->permissions)->pluck('id')->toArray();
        $permissions = Permission::groupBy('controller')
            ->select('controller')
            ->selectRaw('GROUP_CONCAT(method) as route_methods')
            ->selectRaw('GROUP_CONCAT(name) as route_names')
            ->selectRaw('GROUP_CONCAT(id) as pids')
            // ->orderBy('id', 'ASC')
            ->get();

        $roles = Role::all();
        return view('admin.sub_admins.edit', compact('sub_admin','permissions', 'roles','sub_admin_permissions'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            // 'password' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($request->password != '') {
            //if new password updated
            if (!Hash::check($request->password, $admin->password)) {
                $admin->password = bcrypt($request->password);
            }
        }

        $permissions = $request->permissions;

        $admin->name = $request->name;
        $admin->email = $request->email;
        // $admin->password = bcrypt($request->password);
        $admin->role_id = $request->role_id;
        $admin->status = $request->status ?? 0;
        $admin->token = '';
        $admin->email_verified_at = '';
        $admin->is_verified = 0;
        $admin->remember_token = '';
        $admin->save();

        // atache all permissions to sub admin 
        if ($permissions) {
            //delete old permisssions
            $deletedOldPermisssions = ($admin->permissions()->detach());
            //get already attach Ids
            $attachedIds = $admin->permissions()->whereIn('permission_id', $permissions)->pluck('permission_id')->toArray();
            //Remove the attached IDs from the request array
            $newIds = array_diff($permissions, $attachedIds);
            // atache all permissions to admin.
            $admin->permissions()->attach($newIds);
        }

        return redirect()->route('admin.sub_admins.index')->with('success', 'Sub admin updated successfully.');
    }

    public function insertPermissions()
    {
        // $routes = Route::getRoutes();
        // $routeList = new Collection;
        // foreach ($routes as $route) {
        //     $middleware = $route->middleware();
        //     for ($i = 0; $i < count($middleware); $i++) {
        //         if ($middleware[$i] == 'role') {
        //             $routeList->push($route);
        //         }
        //     }
        // }

        // dd($new_routes);

        $notIncludedRoutes = [
            'admin.login', 'admin.login.submit', 'admin.forgot-password', 'admin.forgot-password.submit', 'admin.reset-password.submit', 'admin.reset-password', 'admin.logout', 'admin.profile', 'admin.profile.update', 'admin.change-password', 'admin.dashboard', 'admin.get_city', 'admin.get_pincode'
        ];

        $routeList = Route::getRoutes()->getRoutesByName();

        // dd($routeList);

        $permission_ids = []; // an empty array of stored permission IDs
        // iterate though all routes
        foreach ($routeList as $key => $route) {

            if (strpos($route->getName(), 'admin.') !== false) {
                if (!in_array($route->getName(), $notIncludedRoutes)) {

                    $middleware = $route->middleware();
                    // dd($middleware);

                    if(in_array("role",$middleware)){

                        // get route action
                        $action = $route->getActionname();
                        // separating controller and method
                        $_action = explode('@', $action);

                        $controller = $_action[0];
                        $method = end($_action);
                        $routeName = $route->getName();

                        // check if this permission is already exists
                        $permission_check = Permission::where(
                            ['controller' => $controller, 'method' => $method]
                        )->first();
                        if (!$permission_check) {
                            $permission = new Permission;
                            $permission->controller = $controller;
                            $permission->name = $routeName;
                            $permission->controller = $controller;
                            $permission->method = $method;
                            $permission->save();
                            // add stored permission id in array
                            $permission_ids[] = $permission->id;
                        }

                    }
                }
            }
        }

        if ($permission_ids) {
            // find admin.
            $admin = Admin::where('id', Auth::id())->first();
            //get already attach Ids
            $attachedIds = $admin->permissions()->whereIn('permission_id', $permission_ids)->pluck('permission_id')->toArray();
            //Remove the attached IDs from the request array
            $newIds = array_diff($permission_ids, $attachedIds);
            // atache all permissions to admin.
            $admin->permissions()->attach($newIds);
        } else {
            $permission_ids = Permission::pluck('id')->toArray();
            $admin = Admin::where('id',Auth::id())->first();
            //get already attach Ids
            $attachedIds = $admin->permissions()->whereIn('permission_id', $permission_ids)->pluck('permission_id')->toArray();
            //Remove the attached IDs from the request array
            $newIds = array_diff($permission_ids, $attachedIds);
            $admin->permissions()->attach($newIds);
        }
    }

    public function adminNotificationList()
    {
        $notificationList = Notification::where('deleted_at','')->where('receiver_type','admin')->where('is_read','0')->orderBy('id','desc')->get();
        if (!empty($notificationList->toArray()))
        {
            Notification::where('deleted_at','')->where('receiver_type','admin')->where('is_read','0')->orderBy('id','desc')->update(['is_read'=>'1']);
        }
        return view('admin.notification.notificationList',compact('notificationList'));
    }
    public function redirectNotification($id)
    {
        $notificationData = Notification::where('deleted_at','')->findOrFail($id);
        if ($notificationData)
        {
            $notificationData->is_read = '1';
            $notificationData->save();
            if ($notificationData->category_type == 'register')
            {
                return redirect()->route('admin.users.index');
            }
            if ($notificationData->category_type == 'Order')
            {
                return redirect()->route('admin.booking.list');
            }
        }
    }
}
