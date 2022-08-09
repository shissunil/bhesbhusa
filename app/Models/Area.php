<?php

namespace App\Models;

use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'area','status','deleted_at','state_id','city_id','pincode_id'
    ];

    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
