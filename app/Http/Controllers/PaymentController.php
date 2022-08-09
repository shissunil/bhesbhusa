<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function make_payment()
    {
        return view('khalti.payment');
    }
    
    public function verify_payment(Request $request)
    {
    }

    public function payment_success(Request $request)
    {
        $data['transaction_id'] = $request->transaction_id;
    	return view('khalti.success',compact('data'));
    }

    public function payment_failure()
    {
    	return view('khalti.error');
    }
}
