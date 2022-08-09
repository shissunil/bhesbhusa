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
        // dd($request->token);
        
        // $test_secret_key = config('app.khalti_secret_key');
        $test_secret_key = configuration('khalti_secret_key');
        $args = http_build_query(array(
            'token' => $request->token,
            'amount'  => $request->amount
        ));
        
        $url = "https://khalti.com/api/v2/payment/verify/";
        
        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $headers = ["Authorization: Key $test_secret_key"];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $response;
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
