<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    public function admins()
    {
        return $this->belongsToMany(Admin::class);
    }

}
