<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Daily Report of {{   env('APP_NAME') }} </title>

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
    </style>
</head>
<body>

     <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    Products in Packets
                </th>
            </tr>
            <tr class="bg-blue">
               
                <th>Product Name</th>
                <th>Total Stock</th>
                <th>Packet Quantity</th>
                <th>No of Packets</th>
                <th>Buying Price</th>
                <th>GST</th>
                <th>Selling Price</th>
            </tr>
        </thead>
        <tbody>
           @if ($products->isEmpty())
                <td colspan="8" class="">No Products Found</td>
           @else
                @foreach($products as $k => $product)
                    <tr>
                        <td>{{ ucwords(strtolower($product->name) ) }}</td>
                        <td>{{$product->goDownProducts->goDownProductDetails->quantity .''. $product->goDownProducts->goDownProductDetails->unit->name }}</td>
                        <td>
                            {{ $product->packet_size ?? '' }}
                            {{ $product->unit->name ?? '' }}
                        </td>
                        <td> {{$product->quantity}}  </td>
                        <td style="font-family: DejaVu Sans, sans-serif;"> ₹ {{$product->buying_price}}  </td>
                        <td> {{$product->gst}} % </td>
                        <td style="font-family: DejaVu Sans, sans-serif;"> ₹ {{$product->selling_price}}  </td>
                    </tr>
                @endforeach
                
        
            @endif
            


            
        </tbody>
    </table>

    <br>
    <p class="text-center">
       Regards, {{ env('APP_NAME') }}
    </p>

</body>
</html>