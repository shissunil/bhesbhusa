<?php

namespace App\Models;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'image','status','offer_id','product_id','deleted_at','banner_location','banner_type',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
