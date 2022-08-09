<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function hasOneUser()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
    public function hasOneDeliveryAssociate()
    {
        return $this->hasOne('App\Models\DeliveryAssociate','id','delivery_associates_id');
    }
    public function hasOneCoupon()
    {
        return $this->hasOne('App\Models\Offer','id','coupon_id');
    }
}
