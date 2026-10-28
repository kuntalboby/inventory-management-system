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
        h1,h2,h3,h4,h5,h6,p,span,label {
            font-family: sans-serif;
        }
        table {
            width: 100%;

            border-collapse: collapse;
            margin-bottom: 0px !important;
        }
        table thead th {
            height: 28px;
            text-align: left;
            font-size: 16px;
            font-family: sans-serif;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
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
            font-size: 14px;
            font-weight: 400;
        }
        .no-border {
            border: 1px solid #fff !important;
        }
        .bg-blue {

            background-color: #414ab1;
            color: #fff;
        }
        .bg-gray {

            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>

    <table class="order-details">
        <thead>
            <tr style="border:none">
                <th width="50%" colspan="2">
                    <h3 class="text-start">{{ env('APP_NAME') }} </h3>
                    <span>Vill - Eriamara, Block Garbeta ll</span><br>
                    <span>West Medinipur, West Bengal, Pin- 721157</span><br>
                    <span>Phone: +91 7450922922</span><br>
                    <span>Email: enquire@bhoomijafresh.com</span>
                    </p>
                </th>
                <th width="50%" colspan="2" class="text-end company-data">
                    <h2>INVOICE</h2>
                    <span>Invoice #{{$getInfo->invoice_number}}</span> <br>
                    <span>Order Number: {{$getInfo->order_number}}</span> <br>
                    <span>Date: {{ date('d-m-Y h:i A',strtotime($getInfo->order_date)) }} </span> <br>
                    <br>
                </th>
                
            </tr>
            <tr class="bg-gray">
                <th width="50%" colspan="2">Customer Details</th>
                <th  colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            
            <tr>
                <td>Full Name:</td>
                <td>{{ $getInfo->customer->first_name .' '.$getInfo->customer->last_name }}</td>
                
                 <td>Phone:</td>
                <td>{{$getInfo->customer->phone}} / {{$getInfo->customer->alternative_phone}}</td>
            </tr>
            <tr>
                <td>Email Id:</td>
                <td>{{$getInfo->customer->email}}</td>
                <td>Address:</td>
                <td>
                    {{$getInfo->customer->city}} , {{$getInfo->customer->state}} , {{$getInfo->customer->address_line_one }}
                    {{$getInfo->customer->address_line_two }}
                </td>
               
            </tr>
            
            <tr>
                

                <td>Pin code:</td>
                <td>{{$getInfo->customer->pin}}</td>

                <td>Order Status:</td>
                <td>Completed</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    Order Items
                </th>
            </tr>
            <tr class="bg-gray">
                <th>Sl</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>GST</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if ( !empty($getInfo->orderDetails))
                @foreach ($getInfo->orderDetails as $key=>$orderProd)
                    <tr>
                        <td width="10%">{{$key+1}}</td>
                        <td>
                           {{ ucwords(strtolower( $orderProd->product->name )) }}
                        </td>
                        <td width="10%" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orderProd->selling_price}}</td>
                        <td width="10%">{{$orderProd->quantity}}</td>
                        <td width="10%">{{$orderProd->product_gst}} %</td>
                        <td width="15%" class="fw-bold" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orderProd->amount}}</td>
                </tr>
                @endforeach
                
                
            @else
                <td colspan="6" class="total-heading">No Products</td>
            @endif
            


            <tr>
                <td colspan="4" class="total-heading">Total Amount - <small>Inc. all vat/tax</small> :</td>
                <td colspan="1" class="">Over all GST : {{ $getInfo->extra_gst}} %</td>
                <td colspan="1" class="total-heading" style="font-family: DejaVu Sans, sans-serif;">₹ {{ $getInfo->total_amount }}</td>
            </tr>
        </tbody>
    </table>

    <br>
    <p class="text-center">
        Thank your for shopping with {{ env('APP_NAME') }}
    </p>

</body>
</html>