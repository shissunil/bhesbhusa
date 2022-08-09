<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['return_day','support_number','khalti_secret_key','khalti_public_key','sms_token','mail_mailer','mail_host','mail_port','mail_username','mail_password','mail_encryption','mail_from_address','user_notification_sender_id','user_notification_key','driver_notification_sender_id','driver_notification_key'];
}
