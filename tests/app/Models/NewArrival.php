<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewArrival extends Model
{
    use HasFactory;
    protected $fillable = [
        'image','status','product_id','deleted_at'
    ];
}
