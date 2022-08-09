<?php

namespace App\Models;

use App\Models\Area;
use App\Models\State;
use App\Models\Pincode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','status','deleted_at','state_id','delivery_days'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
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
