<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ReviewAndRating;
use App\Models\SuperCategory;
use App\Models\SuperSubCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;

class Product extends Model
{
    use HasFactory;
    
    // protected $appends = ['discounted_price'];

    public function reviews()
    {
        return $this->hasMany(ReviewAndRating::class);
    }
    
    public function super_category()
    {
        return $this->hasOne(SuperCategory::class,'id','super_cat_id');
    }
    
    public function super_sub_category()
    {
        return $this->hasOne(SuperSubCategory::class,'id','super_sub_cat_id');
    }
    
    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }
    
    public function sub_category()
    {
        return $this->hasOne(SubCategory::class,'id','sub_category_id');
    }
    
    public function brand()
    {
        return $this->hasOne(Brand::class,'id','brand_id');
    }
    
    // public function productByRating($rating) {
    //     return $this->reviews()->avg('rate')->where('rate',5);
    // }

    // public function getDiscountedPriceAttribute()
    // {
    //    return (string)($this->price - round($this->price * $this->discount / 100));        
    // }
}
