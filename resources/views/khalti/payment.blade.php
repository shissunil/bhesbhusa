<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
</head>
<body>
    
    <!-- Place this where you need payment button -->
    <!-- <button id="payment-button">Pay with Khalti</button> -->
    <!-- Place this where you need payment button -->
    
    <form method="post" id="success_form" action="{{ route('payment_success') }}">
        @csrf
        <input type="hidden" name="transaction_id" id="transaction_id">
    </form>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <!-- Paste this code anywhere in you body tag -->
    <script>
        var config = {
            // replace the publicKey with yours
            "publicKey": "{{ configuration('khalti_public_key') }}",
            "productIdentity": "1234567890",
            "productName": "Dragon",
            "productUrl": "http://gameofthrones.wikia.com/wiki/Dragons",
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
                    // console.log('Payload :');
                    // console.log(payload);                    
                    //verify payment
                    $.ajax({
                        url : "{{ route('verify_payment') }}",
                        method : 'POST',
                        data : {
                            amount : 1000,
                            token : payload.token,
                            // token:'12121221212121212121212',
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
                onClose() {
                    console.log('widget is closing');
                    window.location.href = "{{ route('payment_failure') }}";
                }
            }
        };

        var checkout = new KhaltiCheckout(config);
        // var btn = document.getElementById("payment-button");
        // btn.onclick = function () {
            // minimum transaction amount must be 10, i.e 1000 in paisa.
        checkout.show({amount: 1000});
        // }
    </script>
    <!-- Paste this code anywhere in you body tag -->
    
</body>
</html>