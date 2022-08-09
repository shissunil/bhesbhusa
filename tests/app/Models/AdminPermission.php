<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminPermission extends Model
{
    use HasFactory;

    protected $table = 'admins_permissions';

    public function admin()
    {
        return $this->belongsToMany(Admin::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
