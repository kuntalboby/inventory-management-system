<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice #{{$getInfo->invoice_number}}</title>

    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }
         body {
            background-image: url("{{ public_path('storage/website-image/logo1.png') }}");
            background-repeat:no-repeat;
            background-size:70%;
            background-position:center center;
        }

        h1,h2,h3,h4,h5,h6,p,span,label {
            font-family: sans-serif;
        }
        table {
            width: 100%;

            border-collapse: collapse;
            margin-bottom: 0px !important;
        }
        table thead th {
            height: 24px;
            text-align: left;
            font-size: 12px;
            font-family: sans-serif;
        }
        table, th, td {
            border: 1px solid #b7b7b7;
            padding: 5px 7px;
            font-size: 12px;
        }
        .prod_detail th, .prod_detail td {
            border-bottom:none;
            border-top:none;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }
        .small-heading {
            font-size: 14px;
            font-family: sans-serif;
        }
        .total-heading {
            font-size: 14px;
            font-weight: 700;
            font-family: sans-serif;
        }
        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }
        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {

            text-align: left;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
            font-size: 12px;
            font-weight: 400;
        }
        .no-border {
            border: none !important;
        }
        .bg-blue {

            background-color: #414ab1;
            color: #fff;
        }
        .bg-gray {

            background-color: #6c757d ;
            color: #fff;
        }
        .bg-green {

            background-color: #17743a ;
            color: #fff;
        }
        .no-border td{
            border:none;
            padding:0 0;
        }
        .prod_detail tr th {
            font-size:10px!important;
        }
        .prod_detail tr td {
            font-size:12px!important;
        }
        .prod_detail thead tr th:nth-child(1){
           text-align:center;
        }
        .prod_detail thead tr th:nth-child(2){
            text-align:center;
        }
        .prod_detail thead tr th:nth-child(3){
            text-align:center;
        }
        .prod_detail thead tr th:nth-child(4){
            text-align:center;
        }
        .prod_detail thead tr th:nth-child(5){
            text-align:center;
        }
        .prod_detail thead tr th:nth-child(6){
             text-align:center;
        }
        .prod_detail thead tr th:nth-child(7){
            text-align:center;
        }
        
    </style>
</head>
<body>
    <h4 style="text-align:center; font-size:12px; color:#17743a;">TAX INVOICE</h4>
    <table class="order-details">
        <thead>
            <tr>
                <th width="50%" class="text-start company-data">
                    <table class="no-border">
                        <tr>
                           
                            <td style="padding-right:10px;">
                                <img src="{{ public_path('storage/website-image/logo2.png') }}" 
                                style="width:100px;" />
                            </td>
                            <td>
                                <h3 class="text-start" style="color:#17743a;">Bhoomija Fresh</h3>
                                <span>Eriamara, Block Garbeta ll</span><br>
                                <span>West Medinipur, West Bengal</span><br>
                                <span>Pin- 721157</span><br>
                                <span>Phone: +91 7450922922</span><br>
                                <span>Email: enquire@bhoomijafresh.com</span><br>
                                <span>Website: www.bhoomijafresh.com</span>
                            </td>
                        </tr>
                    </table>
                    
                </th>
                <th width="50%" class="text-end company-data">
                    <span>Invoice #{{$getInfo->invoice_number}}</span> <br>
                    <span>Order Number: {{$getInfo->order_number}}</span> <br>
                    <span>Date: {{ date('d-m-Y',strtotime($getInfo->order_date)) }} </span> <br>
                    <br>
                </th>
                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-start company-data">
                    <span>BILL TO</span><br>
                    <span style=""><strong>{{ ucwords(strtolower($getInfo->customer->first_name)) .' '.ucwords(strtolower($getInfo->customer->last_name)) }}</strong></span><br>
                    <span>{{ ucwords(strtolower($getInfo->customer->address_line_one))}}, {{ucwords(strtolower($getInfo->customer->address_line_two))}}, {{ucwords(strtolower($getInfo->customer->city))}}, {{ucwords(strtolower($getInfo->customer->state))}}, 
                        {{$getInfo->customer->pin}}</span><br>
                    <span>Phone: {{$getInfo->customer->phone}} / {{$getInfo->customer->alternative_phone}}</span><br>
                    <span>Email: {{$getInfo->customer->email}}</span>
                    
                </td>
                <td class="text-start company-data">
                    <span>SHIP TO</span><br>
                    <span style=""><strong>{{ ucwords(strtolower($getInfo->customer->first_name)) .' '.ucwords(strtolower($getInfo->customer->last_name))}}</strong></span><br>
                    <span>{{ucwords(strtolower($getInfo->customer->address_line_one ))}}, {{ucwords(strtolower($getInfo->customer->address_line_two)) }}, {{ucwords(strtolower($getInfo->customer->city))}}, {{ucwords(strtolower($getInfo->customer->state))}}, {{$getInfo->customer->pin}}</span><br>
                    <span>Phone: {{$getInfo->customer->phone}} / {{$getInfo->customer->alternative_phone}}</span><br>
                    <span>Email: {{$getInfo->customer->email}}</span>
                    
                </td>
            </tr>
        </tbody>
    </table>

    <table class="prod_detail">
        <thead>
            <tr class="bg-green">
                <th>SL NO.</th>
                <th>ITEMS</th>
                <th>QTY</th>
                <th>RATE</th>
                <th>DISCOUNT</th>
                <th>TAX</th>
                <th>AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @if ( !empty($getInfo->orderDetails))
                @php 
                 $sl = 1;
                @endphp
                @foreach ($getInfo->orderDetails as $key=>$orderProd)
                    @if ($orderProd->quantity > 0)

                        @php
                            $discountedAmt = $orderProd->quantity * $orderProd->selling_price * $orderProd->discount_percent /100;
                        @endphp
                        
                   
                    <tr>
                        <td width="7%">{{$sl}}</td>
                        <td width="43%">
                            @if (empty($orderProd->product_name))
                                {{ ucwords(strtolower( $orderProd->product->name )) }}
                            @else
                                {{ ucwords(strtolower( $orderProd->product_name )) }}
                            @endif
                           
                        </td>
                        <td width="10%" align="right">{{$orderProd->quantity}}</td>
                        <td width="10%" align="right" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orderProd->selling_price}}</td>
                        <td width="10%" align="right">{{$orderProd->discount_percent}} %</td>
                        <td width="10%" align="right">{{$orderProd->product_gst}} %</td>
                        <td width="10%" align="right" class="fw-bold" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orderProd->amount}}</td>
                </tr>
                @php $sl+=1; @endphp
                 @endif
                @endforeach
                
                
            @else
                <td colspan="7" class="total-heading">No Products</td>
            @endif
            


            <tr class="bg-green">
                <td colspan="2" class="total-heading">Total Amount - <small>Inc. all vat/tax</small>:</td>
                <td colspan="4" align="right" class="">Over all GST: {{ $getInfo->extra_gst}} %</td>
                <td class="total-heading" align="right" style="font-family: DejaVu Sans, sans-serif;">₹ {{ $getInfo->total_amount }}</td>
            </tr>
        </tbody>
    </table>
    
    <table class="order-details">
        <thead>
            <tr>
                <th width="45%" valign="middle" class="text-start company-data">
                    <span>You can pay via PhonePe/Gpay</span><br>
                    <span>Phone No- 9831955285</span><br>
                    <span>UPI Id- crown82@oksbi</span>
                    
                </th>
                <th width="20%" valign="top" align="centre" class="text-center company-data">
                    <span>QR Code</span><br>
                    <span><img src="{{ public_path('storage/website-image/qr_code.jpg') }}" style="width:130px;" /></span>
                    
                </th>
                <th width="35%" valign="bottom" align="centre" class="text-center company-data" style="color:#17743a;">
                    <span><strong>Authorised Signatory for<br>Bhoomija Fresh</strong></span>
                </th>
            </tr>
            
        </thead>
        <tbody>
            <tr>
                <td colspan="3" width="100%" valign="top" class="text-start company-data">
                    <span><strong>Terms and Conditions</strong></span><br>
                    <span>1. Goods once sold will not be taken back.</span><br>
                    <span>2. Disputes to be settled in West Bengal courts.</span>
                    
                </td>
                
            </tr>
        </tbody>
    </table>
    <h4 style="text-align:center; font-size:12px;">Thank your for shopping with Bhoomija Fresh</h4>
    
</body>
</html>