<?php

namespace App\Models;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','supersub_cat_id','image','status','deleted_at'
    ];

    public function sub_categories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
