<?php

namespace App\Models;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','status','deleted_at'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function pincodes()
    {
        return $this->hasMany(Pincode::class);
    }

    public function area()
    {
        return $this->hasMany(Area::class);
    }
}
