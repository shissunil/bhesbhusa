<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReason extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'reason_description','status','deleted_at'
    ];
}
