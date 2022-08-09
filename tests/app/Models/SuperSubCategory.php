<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperCategory;


class SuperSubCategory extends Model
{
    use HasFactory;
    // protected $table = 'super_sub_categories';

    public function superSubCategory()
    {
        return $this->hasOne(SuperCategory::class, 'id', 'super_category_id');
    }
}
