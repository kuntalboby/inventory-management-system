<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Contact Form</title>

    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }
         body {
            background-image: url({{$siteLogo}});
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
            border: 1px solid #9f9f9f;
            padding: 5px 10px;
            font-size: 12px;
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

            background-color: #023a17 ;
            color: #fff;
        }
        .no-border td{
            border:none;
            padding:0 0;
        }
        
    </style>
</head>
<body>
    <h4 style="text-align:center; font-size:12px;">Guest Login Credentials</h4>
 
    <table class="order-details">
        <thead>
            <tr>
                <th width="100%" class="text-start company-data">
                    <table class="no-border">
                        <tr>
                           
                            <td style="padding-right:10px;">
                                <img src="{{ $siteLogo }}" style="width:100px;" alt="{{ $siteName }}">

                            </td>
                            <td>
                                <h3 class="text-start">{{$siteName}}</h3>
                                <span>Eriamara, Block Garbeta ll</span><br>
                                <span>West Medinipur, West Bengal</span><br>
                                <span>Pin- 721157</span><br>
                                <span>Phone: +91 7450922922</span><br>
                                <span>Email: enquire@bhoomijafresh.com</span>
                            </td>
                        </tr>
                    </table>
                    
                </th>
                
                
            </tr>
        </thead>
    </table>
    <h1 style="text-align:center;">Order Cancellation Request</h1>
    <p>{{ $messageContent }}</p>
    <h4 style="text-align:center; font-size:12px;">Thank your for Contacting with {{ $siteName }}</h4>
    
</body>
</html>