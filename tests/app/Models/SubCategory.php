<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','image','status','category_id','deleted_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
