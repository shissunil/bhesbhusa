<?php

namespace App\Models;

use App\Models\Area;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pincode extends Model
{
    use HasFactory;

    protected $fillable = [
        'pincode','status','deleted_at','state_id','city_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function areas()
    {
        return $this->belongsTo(Area::class);
    }
}
