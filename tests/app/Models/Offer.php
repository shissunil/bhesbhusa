<?php

namespace App\Models;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_name','offer_description','offer_code','start_date','end_date','offer_status','deleted_at',
    ];

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
}
