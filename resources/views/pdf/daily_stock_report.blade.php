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
                   Godown Products Daily Report
                </th>
            </tr>
            <tr class="bg-blue">
               
                <th>Date</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Opening Quantity</th>
                <th>Added Quantity</th>
                <th>Sold Quantity</th>
                <th>Damaged Quantity</th>
                <th>Closing Quantity</th>
                <th>Unit</th>
            </tr>
        </thead>
        <tbody>
           @if ($products->isEmpty())
                <td colspan="8" class="">No Products Found</td>
           @else
                @foreach($products as $k => $record)
                    @php
                        $conversionFactor = $record->goDownProducts->unitCategory->standardUnit->conversion_factor;
                        $category = ucwords(strtolower($record->goDownProducts->category->name));
                        $openingStock = $record->opening_stock_quantity/$conversionFactor;
                        $addStock = $record->add_stock_quantity/$conversionFactor;
                        $soldStock = $record->sold_stock_quantity/$conversionFactor;
                        $damangedStock = $record->damaged_stock_quantity/$conversionFactor;
                        $closingStock = $record->closing_stock_quantity/$conversionFactor;
                    @endphp
                    <tr>
                        <td>{{ date('d-m-Y',strtotime($record->record_date))}}</td>
                        <td>{{ ucwords(strtolower($record->goDownProducts->name) ) }}</td>
                        <td>{{ $category }}</td>
                        <td>{{$openingStock ?? 0 }}</td>
                        <td>{{ $addStock ?? 0 }}</td>
                        <td>{{ $soldStock ?? 0 }}</td>
                        <td>{{ $damangedStock ?? 0 }}</td>
                        <td>{{ $closingStock ?? 0 }}</td>
                        <td>{{ $record->goDownProducts->unitCategory->standardUnit->name }}</td>
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