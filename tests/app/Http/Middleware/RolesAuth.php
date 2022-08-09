<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_permissions = (auth()->user()->permissions);
        // dd($user_permissions);
        if($user_permissions){
            $user_permissions = $user_permissions->pluck('id')->toArray();
        }else{
            $user_permissions = [];
        }
        // get user permissions
        $permissions = Permission::whereIn('id',$user_permissions)->get();
        // dd($permissions);
        // get requested action
        $actionName = class_basename($request->route()->getActionname());
        // check if requested action is in permissions list
        foreach ($permissions as $permission) {
            $_namespaces_chunks = explode('\\', $permission->controller);
            $controller = end($_namespaces_chunks);
            if ($actionName == $controller . '@' . $permission->method) {
                if (in_array($permission->id,$user_permissions)) {
                    // authorized request
                    return $next($request);
                }
            }
        }
        // none authorized request
        return response('Unauthorized Action', 403);
    }
}
