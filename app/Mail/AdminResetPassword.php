<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, $token)
    {
        $this->admin = $admin;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('admin.email.forgot_password');
    }
}
