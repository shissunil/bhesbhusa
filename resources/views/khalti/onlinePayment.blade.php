<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
</head>
<body>
    

    <form method="post" id="success_form" action="{{ route('payment_success') }}">
        @csrf
        <input type="hidden" name="transaction_id" id="transaction_id">
    </form>
    
    <!-- Place this where you need payment button -->
    <!-- <button id="payment-button">Pay with Khalti</button> -->
    <!-- Place this where you need payment button -->    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <!-- Paste this code anywhere in you body tag -->
    <script>
        var config = {
            // replace the publicKey with yours
            "publicKey": "{{ configuration('khalti_public_key') }}",
            "productIdentity": "{{ $data['productId'] }}",
            "productName": "{{ $data['productName'] }}",
            "productUrl": "{{ $data['productUrl'] }}",
            "paymentPreference": [
                "KHALTI",
                "EBANKING",
                "MOBILE_BANKING",
                "CONNECT_IPS",
                "SCT",
                ],
            "eventHandler": {
                onSuccess (payload) {
                    // hit merchant api for initiating verfication
                    console.log('Payload :');
                    console.log(payload);
                    var kamount = {{ $data['payableAmount'] * 100 }};
                    //verify payment
                    $.ajax({
                        url : "{{ route('verify_payment') }}",
                        method : 'POST',
                        data : {
                            amount : kamount,
                            token : payload.token,
                            // token:'1234567890123456789011',
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response){
                            // console.log('Verification request :');                           
                           var response = JSON.parse(response);
                           console.log(response);
                           if(!("error_key" in response)){
                            var transaction_id = response.idx;
                            if(transaction_id!=''){
                                $("#transaction_id").val(transaction_id);
                                $("#success_form").submit();
                                //window.location.href = "{{ route('payment_success') }}";
                            }else{
                                window.location.href = "{{ route('payment_failure') }}";
                            } 
                           }else{
                                window.location.href = "{{ route('payment_failure') }}";
                           }       
                        },
                        error:function(error){
                            console.log(error);
                            window.location.href = "{{ route('payment_failure') }}";
                        }
                    });
                },
                onError (error) {
                    console.log(error);
                    window.location.href = "{{ route('payment_failure') }}";
                },
                onClose () {
                    console.log(error);
                    window.location.href = "{{ route('payment_failure') }}";
                }
            }
        };

        var checkout = new KhaltiCheckout(config);
        // var btn = document.getElementById("payment-button");
        // btn.onclick = function () {
            // minimum transaction amount must be 10, i.e 1000 in paisa.
            checkout.show({amount: {{ $data['payableAmount'] * 100}}});
        // }
    </script>
    <!-- Paste this code anywhere in you body tag -->
    
</body>
</html>