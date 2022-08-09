<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
</head>
<style type="text/css">
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    body {
        position: relative;
        width: 680px;
        margin: 0 auto;
        color: #001028;
        background: #FFFFFF;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-family: Arial;
        padding-bottom: 15px;
    }

    header {
        padding: 10px 0;
        /* margin-bottom: 30px; */
    }

    #logo {
        text-align: center;
        margin-bottom: 10px;
    }

    #project {
        display: inline-block;
        width: 38%;
        vertical-align: bottom;
    }

    .invoice-info span {
        color: #000;
        margin-right: 0;
        display: block;
        font-size: 14px;
        margin-bottom: 8px;
    }

    #company {
        display: inline-block;
        text-align: right;
        width: 61%;
        vertical-align: top;
        vertical-align: bottom;
    }

    .invoice-info div {
        white-space: nowrap;
        font-size: 14px;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }

    table th,
    table td {
        text-align: center;
        border-bottom: 1px solid #04c6d4;
    }

    table th {
        padding: 15px 5px 15px 5px;
        color: #000;
        font-size: 14px;
    }

    table td {
        padding: 15px 5px 15px 5px;
        font-size: 14px;
        line-height: 20px;
    }

    table tr th:last-child,
    table tr td:last-child{
        text-align: right;
    }


    table tr th:first-child,
    table tr td:first-child{
        text-align: left;
    }


    td.grand.total {
        color: #04c6d4;
        font-weight: bold;
        font-size: 16px;
    }

    #notices {
        font-size: 14px;
    }

    /* .date-time {
        margin: 20px 0;
        background-color: #04c6d4;
        padding: 15px 9px;
    } */

    .pink-bg{
        background-color: #04c6d4;
        padding: 9px 9px;
    }

    .date-time div {
        color: #fff;
        font-size: 14px;
        font-weight: bold;
        margin: 3px 0;
        display: inline-block;
        /* width: 33%; */
    }

    .date-time div.date {
        text-align: left;
        width: 49%;
    }

   /*  .date-time div.vat-no {
        text-align: center;
        width: 49%;
    } */

    .date-time div.time {
        text-align: right;
        width: 50%;
    }
    @media print {
         .invoice-info span{
            font-size: 14px;
         }

         .invoice-info div{
            font-size: 14px;
         }

         .date-time div{
            font-size: 14px;
            margin-top: 3px !important;
            margin-bottom: 3px !important;
         }

         .pink-bg{
            padding: 6px 9px;
         }

         table{
            width: 100%;
         }

         table th{
            font-size: 14px;
         }

         table td{
            font-size: 14px;
         }

         td.grand.total{
            font-size: 16px;
         }
      }
</style>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ URL::asset('assets/front/invoiceLogo.png') }}" height="80px">
        </div>

        <!-- <h1>INVOICE 3-2-1</h1> -->

        <div id="project" class="invoice-info">
            <div><span>Invoice Number:</span> <b>{{ $orderDetail['order_no'] }}</b></div>
        </div>

        <div id="company" class="invoice-info">
            <div><span>Address:</span> <b>{{ $delivery_address['pincode'].' '. $delivery_address['city'].', '. $delivery_address['state'] }}</b></div>
        </div>
    <div class="pink-bg">
        <div class="date-time">
            <div class="date">Date: {{ $orderDetail['order_date'] }}</div>
            <div class="time">Time: {{ $orderDetail['order_time'] }}</div>
        </div>
    </div>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Price in NR</th>
                </tr>
            </thead>
            <tbody>
                @if ($orderDetail['orderItem'])
                    @foreach ($orderDetail['orderItem'] as $orderItem)
                        <tr>
                            <td>{{ $orderItem['productData']['product_name'] }}</td>
                            <td>{{ $orderItem['quantity'] }}</td>
                            <td>{{ $orderItem['product_price'] }}</td>
                            <td>{{ $orderItem['price'] }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td>Cart Total</b></td>
                    <td></td>
                    <td></td>
                    <td class="total">{{ $orderDetail['total_discount'] + $orderDetail['total_amount'] }}</td>
                </tr>
                <tr>
                    <td>Discount</b></td>
                    <td></td>
                    <td></td>
                    <td class="total">{{ '-'.$orderDetail['total_discount'] }}</td>
                </tr>
                <tr>
                    <td>Shipping Charge</b></td>
                    <td></td>
                    <td></td>
                    <td class="total">{{ ($orderDetail['shipping_charge'] == '0') ? 'Free' : $orderDetail['shipping_charge'] }}</td>
                </tr>
                <tr>
                    <td>Product Price</b></td>
                    <td></td>
                    <td></td>
                    <td class="total">{{ $orderDetail['total_amount'] }}</td>
                </tr>
                <tr>
                    <td class="grand total">GRAND TOTAL</td>
                    <td></td>
                    <td></td>
                    <td class="grand total">{{ $orderDetail['total_amount'] }}</td>
                </tr>
            </tbody>
        </table>
        <div id="notices">
            <div>* Price Including VAT </div>
        </div>
    </main>
</body>

</html>