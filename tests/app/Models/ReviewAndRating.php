<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ReviewAndRating extends Model
{
    use HasFactory;
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function hasOneProduct()
    {
        return $this->hasOne('App\Models\Product','id','product_id');
    }
}
