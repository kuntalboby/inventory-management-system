<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\GoDownProductDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SalesController extends Controller
{
    //

    public function index(){
        //$userType = Auth::user()->role;
        $getInfo['customers'] = Customer::distinct('phone')->get();
        
        return view('manager.orders', [
                'customers' => $getInfo['customers']
            ]);
    }
    public function orderlist(Request $request)
{
    if ($request->ajax()) {

        $requestedPostsData = $request->all();

        $draw = $requestedPostsData['draw'];
        $row = $requestedPostsData['start'];
        $rowperpage = $requestedPostsData['length'];
        $columnIndex = $requestedPostsData['order'][0]['column'];
        $columnName = $requestedPostsData['columns'][$columnIndex]['data'];
        $columnSortOrder = $requestedPostsData['order'][0]['dir'];

        $userType = Auth::user()->role;

        $query = Order::with('customer')->select('orders.*');

        if ($userType === 'manager') {
            $query->where('manager_id', Auth::id());
        }

        
        if (!empty($request->invoice_number)) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        if (!empty($request->ordered_from)) {
            $query->where('ordered_from', $request->ordered_from);
        }

        if (!empty($request->date_from) && empty($request->date_to)) {
            $dateFrom = Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay();
            $query->where('order_date', '>=', $dateFrom);
        } elseif (!empty($request->date_to) && empty($request->date_from)) {
            $dateTo = Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay();
            $query->where('order_date', '<=', $dateTo);
        } elseif (!empty($request->date_from) && !empty($request->date_to)) {
            $dateFrom = Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay();
            $dateTo = Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay();
            $query->whereBetween('order_date', [$dateFrom, $dateTo]);
        }

        $query->whereHas('customer', function ($q) use ($request) {

            if (!empty($request->name)) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('first_name', 'like', '%' . $request->name . '%')
                        ->orWhere('last_name', 'like', '%' . $request->name . '%');
                });
            }

            if (!empty($request->phone)) {
                $q->where('phone', 'like', '%' . $request->phone . '%');
            }
        });

        $totalRecords = $query->count();

        
        $ordersCollection = $query
            ->when($columnName === 'invoice_number', function ($q) use ($columnSortOrder) {
                $q->orderByRaw(
                    "CAST(SUBSTRING(invoice_number, 5) AS UNSIGNED) $columnSortOrder"
                );
            }, function ($q) use ($columnName, $columnSortOrder) {
                $q->orderBy($columnName, $columnSortOrder);
            })
            ->skip($row)
            ->take($rowperpage)
            ->get();

        $products = [];

        foreach ($ordersCollection as $i => $rowVal) {

            $customer = $rowVal->customer;

            $limitHours = (int) env('RETURN_REJECT_AFTER');
            $orderTime = Carbon::parse($rowVal->order_date);
            $cutoffTime = $orderTime->addHours($limitHours);

            if ($rowVal->ordered_from == '2') {
                $type = "warning";
                $tle = 'Online';
            } else {
                $type = "info";
                $tle = 'Inventory';
            }

            $products[$i]['ordered_from'] =
                '<span style="font-size:10px" class="text text-' . $type . '" title="' . $tle . '">
                    <i class="fa fa-circle"></i>
                 </span>';

            $products[$i]['order_date'] =
                Carbon::parse($rowVal->order_date)->format('d-m-Y h:i A');

            $products[$i]['first_name'] =
                ucwords(strtolower($customer->first_name ?? ''));

            $products[$i]['last_name'] =
                ucwords(strtolower($customer->last_name ?? ''));

            $products[$i]['email'] = $customer->email ?? '';
            $products[$i]['phone'] = $customer->phone ?? '';
            $products[$i]['invoice_number'] = $rowVal->invoice_number ?? '';

            $products[$i]['URL'] =
                '<a href="' . route('manage.invoicedownload', [$rowVal->order_number, 'view']) . '" class="btn btn-sm btn-info mr-1">View</a>';

            $products[$i]['URL'] .=
                '<a onclick="return confirm(\'Are you sure you want to download invoice?\')" href="' .
                route('manage.invoicedownload', [$rowVal->order_number, 'download']) .
                '" class="btn btn-sm btn-primary mr-1">Download</a>';

            if (!empty($customer->email) && $rowVal->status != '2') {
                $products[$i]['URL'] .=
                    '<a onclick="return confirm(\'Are you sure you want to send the invoice via email?\')" href="' .
                    route('manage.invoicedownload', [$rowVal->order_number, 'mail']) .
                    '" class="btn btn-sm btn-warning">Mail</a>';
            }

            if ($rowVal->status == '2') {
                $products[$i]['URL'] .=
                    '<button class="btn text text-danger">Cancelled</button>';
            } else {

                if (Carbon::now()->lte($cutoffTime)) {
                    $products[$i]['URL'] .=
                        '<a href="' . route('manage.invoicedownload', [$rowVal->order_number, 'return']) .
                        '" class="btn btn-sm btn-success ml-1">Return</a>';
                }

                if ($rowVal->status == '3') {
                    $products[$i]['URL'] .=
                        '<button class="btn text text-danger">Partially Returned</button>';
                }
            }
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "data" => $products
        ]);
    }
}

// public function orderlist(Request $request)
// {
//     if ($request->ajax()) {

//         //return $request;
//         $requestedPostsData = $request->all();

//         $draw = $requestedPostsData['draw'];
//         $row = $requestedPostsData['start'];
//         $rowperpage = $requestedPostsData['length'];
//         $columnIndex = $requestedPostsData['order'][0]['column'];
//         $columnName = $requestedPostsData['columns'][$columnIndex]['data'];
//         $columnSortOrder = $requestedPostsData['order'][0]['dir'];

//         $userType = Auth::user()->role;
//         $query = Order::with('customer')->select('orders.*');

//         if ($userType === 'manager') {
//             $query->where('manager_id', Auth::id());
//         }

//         // Search filters
//         if (!empty($request->invoice_number)) {
//             $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
//         }
//         if (!empty($request->ordered_from)) {
//             $query->where('ordered_from',  $request->ordered_from );
//         }

//         if (!empty($request->date_from) && empty($request->date_to)) {
//             $dateFrom = Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay();
//             $query->where('order_date', '>=', $dateFrom);
//         } elseif (!empty($request->date_to) && empty($request->date_from)) {
//             $dateTo = Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay();
//             $query->where('order_date', '<=', $dateTo);
//         } elseif (!empty($request->date_from) && !empty($request->date_to)) {
//             $dateFrom = Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay();
//             $dateTo = Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay();
//             $query->whereBetween('order_date', [$dateFrom, $dateTo]);
//         }
        
       

//         $query->whereHas('customer', function ($q) use ($request) {
//             if (!empty($request->name)) {
//                 $q->where(function ($sub) use ($request) {
//                     $sub->where('first_name', 'like', '%' . $request->name . '%')
//                         ->orWhere('last_name', 'like', '%' . $request->name . '%');
//                 });
//             }

//             if (!empty($request->phone)) {
//                 $q->where('phone', 'like', '%' . $request->phone . '%');
//             }

            
//         });

//         $totalRecords = $query->count();

//         $ordersCollection = $query
//             ->orderBy($columnName, $columnSortOrder)
//             ->skip($row)
//             ->take($rowperpage)
//             ->get();

//         $products = [];

//         //return $ordersCollection;

//         foreach ($ordersCollection as $i => $rowVal) {
//             $customer = $rowVal->customer;
//                 $limitHours = (int) env('RETURN_REJECT_AFTER'); // Ensure it's an integer
//                 $orderTime = Carbon::parse($rowVal->order_date);
//                 $cutoffTime = $orderTime->addHours($limitHours);
//                 if( $rowVal->ordered_from == '2' ){
//                     $type="warning";
//                     $tle = 'Online';
//                 }else{
//                     $type="info";
//                     $tle = 'Inventory';
//                 }
//              $products[$i]['ordered_from'] = '<span style="font-size:10px" class="text text-'.$type.'" title="'.$tle.'">
//             <i class="fa fa-circle"></i></span>';
//             $products[$i]['order_date'] = Carbon::parse($rowVal->order_date)->format('d-m-Y h:i A');
           
//             $products[$i]['first_name'] = ucwords(strtolower($customer->first_name ?? ''));
//             $products[$i]['last_name'] = ucwords(strtolower($customer->last_name ?? ''));
//             $products[$i]['email'] = $customer->email ?? '';
//             $products[$i]['phone'] = $customer->phone ?? '';
//             $products[$i]['invoice_number'] = $rowVal->invoice_number ?? '';

//             $products[$i]['URL'] = '<a href="' . route('manage.invoicedownload',[$rowVal->order_number,'view']). '" class="btn btn-sm btn-info mr-1">View</a>';
//             $products[$i]['URL'] .= '<a onclick="return confirm(\'Are you sure you want to download invoice?\')" href="' . route('manage.invoicedownload',[$rowVal->order_number,'download']) . '" class="btn btn-sm btn-primary  mr-1">Download</a>';
//           if( !empty($customer->email) && $rowVal->status != '2'){
//             $products[$i]['URL'] .= '<a onclick="return confirm(\'Are you sure you want to send the invoice via email?\'
//             )" href="' . route('manage.invoicedownload',[$rowVal->order_number,'mail']). '" class="btn btn-sm btn-warning">Mail</a>';
//           }
//           if($rowVal->status == '2'){
//                 $products[$i]['URL'] .= '<button class="btn text text-danger">Cancelled</button>';
//           }else{
            
//             if (Carbon::now()->lte($cutoffTime)) {
//                 $products[$i]['URL'] .= '<a  href="' . route('manage.invoicedownload',[$rowVal->order_number,'return']). '" class="btn btn-sm btn-success ml-1">Return</a>';           
//             }
//             if($rowVal->status == '3'){
//                 $products[$i]['URL'] .= '<button class="btn text text-danger">Partially Returned</button>';
//             }
//         }
                          
//         }

//         return response()->json([
//             "draw" => intval($draw),
//             "iTotalRecords" => $totalRecords,
//             "iTotalDisplayRecords" => $totalRecords,
//             "data" => $products
//         ]);
//     }
// }


    public function newOrder(){

            $query = Product::where('quantity', '>' , '0')
            ->whereHas('goDownProducts');
            $query->with(['goDownProducts:id,name']);
            $query->orderBy('name');
            $products = $query->get();
           
          // return $products;

        return view('manager.new-order', compact('products'));
    }
    public function findProduct( Request $request){

//return $request->search;

         $query = Product::where('quantity', '>' , '0')
            ->where('slug',$request->search)
            ->whereHas('goDownProducts')
            ->with([
                'goDownProducts:id,name',
                'unit:id,name',
            ]);
            
           
            $products = $query->get();
           if($query->count() > 0 ){
                $return = $products[0];
           }else{
                $return = '0';
           }
           // return $products; 
            return response()->json($return);
    }

    public function handleSession($params){
        
       if($params['mode'] == 'W'){
             Session::put($params['key'], $params['value']);
             return true;
        }elseif($params['mode'] == 'D'){
             Session::forget($params['key']);
             return true;
        }else{
             return Session::get($params['key']);
             
        }
    }
    public function saveOrder(Request $request){
        
        $params['mode'] = 'W';
        if( isset($request->form_type) && $request->form_type == 'product' ){
         
            if( isset($request->data) && count($request->data) > 0 ){
                        $params['key'] = 'orderProducts';
                        $params['value'] = $request->data;
                        $this->handleSession($params);
                        $return  = '1';
            }else{
                        $return  =  '0';
            }


        }else{
            //customer
              
            if( isset($request->first_name) && isset($request->phone) ){
                unset($request->form_type);

                        $params['key'] = 'orderCustomer';
                        $params['value'] = $request->all();
                        $this->handleSession($params);
                        $getSession['mode'] = 'R';
                        $getSession['key'] = 'orderProducts';
                        
                        $return['orderProducts'] =  $this->handleSession($getSession);
                        $return['orderCustomer'] =  $request->all();
                        

            }else{
                        $return  =  '2';
            }
        }
        return response()->json($return);
        //return $return;

    }

    public function placeOrder(Request $request)
{
    $productDetails = $this->handleSession(['mode' => 'R', 'key' => 'orderProducts']);
    $customerDetails = $this->handleSession(['mode' => 'R', 'key' => 'orderCustomer']);

    //return $productDetails;
    
    
    if (isset($productDetails, $customerDetails) && !empty($productDetails) && !empty($customerDetails)) {

        // Format order date
        list($datePart, $timePart) = explode(' ', $customerDetails['order_date']);
        list($hour, $minute) = explode(':', $timePart);
        $hour = str_pad($hour, 2, '0', STR_PAD_LEFT);
        $minute = str_pad($minute, 2, '0', STR_PAD_LEFT);
        $formatted = "$datePart $hour:$minute";
        $order_date = Carbon::createFromFormat('d-m-Y H:i', $formatted);
        unset($customerDetails['order_date']);

        if (is_array($productDetails)) {

            $prdMisMatch = 0;
            $subtotal = 0;
            $orderDetails = [];
            $products = [];
            
            foreach ($productDetails as $product_code => $value) {
                $query = Product::where('quantity', '>=', $value['quantity'])
                    ->whereHas('unit')
                    ->whereHas('goDownProductDetail')
                    ->where('product_code', $product_code)
                    ->where('gst', $value['gst'])
                    ->where('selling_price', $value['selling_price'])
                    ->with(['unit:id,name'])->lockForUpdate();

                if ($query->count() == 0 && isset($value['total']) && $value['total'] > 0 ) {
                    $prdMisMatch++;
                    $products[] = $product_code;
                } else {
                    $getProdInfo = $query->first();

                    
                    $calculatedTotal = $value['selling_price'] * $value['quantity'];
                 //  Clear Discount calculation
                    $totalWithDiscount =  $calculatedTotal - ($calculatedTotal * $value['discount_percent']/100); //discount
                    $totalWithDiscount = $totalWithDiscount + ( $totalWithDiscount * $value['gst'] /100); //gst
                    $subtotal += $totalWithDiscount;

                    $orderDetails[$product_code] = [
                        'product_id'    => $getProdInfo->id,
                        'product_name'    => $getProdInfo->name,
                        'buying_price'  => $value['buying_price'],
                        'selling_price' => $value['selling_price'],
                        'quantity'      => $value['quantity'],
                        'unit_id'       => $getProdInfo->unit->id,
                        'unit'          => $getProdInfo->unit->name,
                        'amount'        => $totalWithDiscount,
                        'product_gst'   => $value['gst'],
                        'discount_percent'=> $value['discount_percent']
                    ];
                }
            }

            //return $orderDetails;

            if ($prdMisMatch == 0) {
               
                //  Clear GST calculation
                $totalWithGst = $subtotal + ($subtotal * $request->extra_gst / 100);
                $totalWithGst = round($totalWithGst, 2);

                // Save/Update customer
                $customer = Customer::create(
                    [
                        'email'             => $customerDetails['email'],
                        'first_name'        => $customerDetails['first_name'],
                        'last_name'         => $customerDetails['last_name'],
                        'phone'             => $customerDetails['phone'],
                        'alternative_phone' => $customerDetails['alternative_phone'],
                        'address_line_one'  => $customerDetails['address_line_one'],
                        'address_line_two'  => $customerDetails['address_line_two'],
                        'city'              => $customerDetails['city'],
                        'pin'               => $customerDetails['pin'],
                        'state'             => $customerDetails['state'],
                        'gst_number'        => $customerDetails['gst_number'],
                    ]
                );

                

                $invoiceNmber = (Order::max('id') ?? 0) + 1;
                $randOrderNumber = 'ORD-' . rand(11, 500) . '-' . $this->randnoGenerator(8);

                $saveOrder = Order::create([
                    'order_date'     => $order_date,
                    'customer_id'    => $customer->id,
                    'order_number'   => $randOrderNumber,
                    'invoice_number' => 'INV-0000' . $invoiceNmber,
                    'manager_id'     => Auth::user()->id,
                    'extra_gst'      => $request->extra_gst,
                    'total_amount'   => $totalWithGst,
                    'comment'        => '',
                ]);

                
                    CustomerAddress::create(
                        [
                            'customer_id'       => $customer->id,
                            'order_id'          => $saveOrder->id,
                            'email'             => $customerDetails['email'],
                            'first_name'        => $customerDetails['first_name'],
                            'last_name'         => $customerDetails['last_name'],
                            'phone'             => $customerDetails['phone'],
                            'alternative_phone' => $customerDetails['alternative_phone'],
                            'address_line_one'  => $customerDetails['address_line_one'],
                            'address_line_two'  => $customerDetails['address_line_two'],
                            'city'              => $customerDetails['city'],
                            'pin'               => $customerDetails['pin'],
                            'state'             => $customerDetails['state'],
                            'gst_number'        => $customerDetails['gst_number'],
                        ]
                    );

                // Save order details & update stock
                if (!empty($orderDetails) && !empty($saveOrder)) {
                    foreach ($orderDetails as $details) {
                        OrderDetail::create([
                            'order_id'      => $saveOrder->id,
                            'product_id'    => $details['product_id'],
                            'product_name'  => $details['product_name'],
                            'buying_price'  => $details['buying_price'],
                            'selling_price' => $details['selling_price'],
                            'quantity'      => $details['quantity'],
                            'unit'          => $details['unit'],
                            'unit_id'       => $details['unit_id'],
                            'amount'        => $details['amount'],
                            'product_gst'   => $details['product_gst'],
                            'discount_percent' => $details['discount_percent']
                        ]);

                        // Update Product stock
                        $productDetail = Product::find($details['product_id']);
                        $goDownProdSubstract = GoDownProductDetail::where('go_down_product_id', $productDetail->go_down_product_id)->first();

                        if ($productDetail) {
                            $productDetail->quantity -= $details['quantity'];
                            $productDetail->save();
                        }
                        if ($goDownProdSubstract) {
                            $totalQnt = $productDetail['packet_size'] * $details['quantity'];
                            $convertedData = $this->unitConversion($totalQnt, $productDetail->unit_id);
                            $goDownProdSubstract->quantity -= $convertedData['quantity'];
                            $goDownProdSubstract->save();
                        }

                        // Track activity
                       
                }

                // Clear session data
                $this->handleSession(['mode' => 'D', 'key' => 'orderProducts']);
                $this->handleSession(['mode' => 'D', 'key' => 'orderCustomer']);
                $note = Auth::user()->first_name . ' has placed an <a href="' . route('manage.invoicedownload', [$randOrderNumber, 'view']) . '" class="text-link">order.</a>';
                        $params['update_fields'] = [
                            'user_id' => Auth::user()->id,
                            'note'    => $note,
                        ];
                        $this->trackActivity($params);
                    }
                return response()->json([
                    'status' => 'OK',
                    'msg'    => $saveOrder->order_number
                ], 200);
            }else{
                $errorProds = Product::whereIn('product_code',$products)->select('name')->get();
                $prodNm = '';
                if( !empty($errorProds)){
                    $count = count($errorProds)-1;
                    foreach ($errorProds as $key => $prod) {
                        $comma = ''; if(  $key < $count) { $comma = ','; }
                        $prodNm .= $prod->name.' '.$comma;
                    }
                }
                $msg = 'Something went wrong with '.$prodNm.' products';
                return response()->json([
                        'status' => 'ERROR-1',
                        'msg'    => $msg
                    ], 400);
            }
        }
    }

    return response()->json([
        'status' => 'ERROR',
        'msg'    => 'Invalid order data.'
    ], 400);
}


    public function thankYou( Request $request , string $ordernumber){

        $orderDetails = Order::where('order_number', $ordernumber)
                        ->with('customer')
        ->firstOrFail();

        //return $orderDetails->customer->email;
            return view('manager.thank-you', compact('orderDetails','ordernumber'));
    }

    public function downloadInvoice(string $ordernumber , string $mode){
           
        $getInfo = Order::where('order_number', $ordernumber)
                           // ->whereNot('status','2')
                            ->with('customerAddress')
                            ->with([
                                'customer',
                                'orderDetails.product' => function ($query) {
                                    $query->select('id', 'name', 'slug','product_code');
                                }
                            ])
                            ->first();
                             $rject='Y';

                            // return $getInfo;
                            
                            if( !empty($getInfo) ){
                                    $limitHours = (int) env('RETURN_REJECT_AFTER'); // Ensure it's an integer
                                    $orderTime = Carbon::parse($getInfo->order_date);
                                    $cutoffTime = $orderTime->addHours($limitHours);
                                   
                                    if(Carbon::now()->lte($cutoffTime)){
                                        $rject='N';
                                    }
                                    
                                
                            }
                              //  return $getInfo;
                        if( $mode == 'download' || $mode == 'mail' )  {  

                            $pdf = Pdf::loadView('pdf.invoice', [
                                'getInfo' => $getInfo,
                                'ordernumber' => $ordernumber
                            ]);
                             $custname = ucwords(strtolower($getInfo->customer->first_name)) .' '.ucwords(strtolower($getInfo->customer->last_name));
                             $orderDate = date('d-m-Y',strtotime($getInfo->order_date));
                             $fileName = 'invoice_'.$custname.'_'.$orderDate.'.pdf';

                            if( $mode == 'download'){
                                return $pdf->download($fileName);
                            }elseif(  $mode =='mail'){

                                if($getInfo->status == 2){
                                    session()->flash('response','danger');
                                    return redirect()->back()->with('message','Unauthorized access!');
                                
                            }else{
                                     $pdfContent = $pdf->output();
                                        if(!empty($getInfo->customer->email)){
                                            Mail::to($getInfo->customer->email)->send(new InvoiceMail($pdfContent, $getInfo));
                                            session()->flash('response','success'); 
                                            return back()->with('message', 'Invoice has been sent to the customer.');
                                            
                                        }

                            }

                                

                           
                            }

                        }elseif( $mode == 'return'){
                           
                            if($getInfo->status == 2){
                                session()->flash('response','danger');
                                return redirect()->back()->with('message','Unauthorized access!');
                                
                            }else{
                                if ($rject == 'N') {
                                    return view('manager.order-return', compact('getInfo','ordernumber'));
                                }else{
                                            session()->flash('response','danger');
                                            return redirect()->back()->with('message','Unauthorized access!');
                                }
                            }

                                
                        }else{
                            return view('manager.order-view', compact('getInfo','ordernumber','rject'));
                        }
                   //
                 
    }

    public function customerInfo(Request $request){
         $search = $request->search;
            $customers = Customer::where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('alternative_phone', 'like', "%{$search}%");
                });

                if( $customers->count() > 0 ){
                    $customersDetails = $customers->get();
                }else{
                    $customersDetails = '0';
                }

                return response()->json($customersDetails);

    }


    public function orderReturn(Request $request)
{

    //return $request;
    // Validate input
    $request->validate([
        'quantity.*' => 'required|numeric|min:0',
    ]);

    $action_type = $request->action_type;
    $ordeDataHas = Order::whereHas('orderDetails')
        ->where([
            'order_number' => $request->order_number,
            'invoice_number' => $request->invoice_number
        ])
        ->whereNot('status','2')
        ->with([
            'orderDetails',
            'orderDetails.product:id,quantity,unit_id,packet_size,go_down_product_id,name',
            'orderDetails.product.goDownProductDetail'
        ])
        ->select(['id', 'total_amount', 'extra_gst']);

       

if($ordeDataHas->count()>0){
    $ordeData =  $ordeDataHas->firstOrFail();
                $limitHours = (int) env('RETURN_REJECT_AFTER'); // Ensure it's an integer
                // $orderTime = Carbon::parse($ordeData->order_date);
                // $cutoffTime = $orderTime->addHours($limitHours);
                $orderTime = Carbon::parse($ordeData->order_date);
                $cutoffTime = $orderTime->copy()->addHours($limitHours);


                //return $ordeData;
if (Carbon::now()->lte($cutoffTime)) {
    $totalAmount = 0;
    $forNotification =[];
    foreach ($ordeData->orderDetails as $order) {
        $productId = $order->product->id;
        $pName = empty($order->product_name) ? $order->product->name : $order->product_name ;

        // Skip if quantity not set
        if (!isset($request->quantity[$productId])) {
            continue;
        }

        $returnQty = $request->quantity[$productId];
        
        $forNotification[$productId]['n_q'] = $request->quantity[$productId];
        $previousQty = $order->quantity;
        $forNotification[$productId]['p_q'] = $previousQty;
        $forNotification[$productId]['name'] = $pName;
        

        $sellingPrice = $order->selling_price;
        $discount = $order->discount_percent;

        // Calculate amount with GST
        $newAmount = $sellingPrice * $returnQty;
        $afterDiscount = $newAmount - ($newAmount*$discount/100);
        $gstAmount = $afterDiscount * ($order->product_gst / 100);
        $totalWithGst = $afterDiscount + $gstAmount;

        // Update OrderDetail
        $order->quantity = $returnQty;
        $order->amount = $totalWithGst;
        $order->save();

        // Update Product stock
        $diffQty = $previousQty - $returnQty; // qty returned to stock
        $order->product->quantity += $diffQty;

        
        $order->product->save();

        // Update GoDown stock
        $productWeight = $order->product->packet_size * $diffQty;
        $prodUnitId = $order->product->unit_id;

        $converted = $this->unitConversion($productWeight, $prodUnitId);
        $goDown = $order->product->goDownProductDetail;

        if ($goDown) {
            $goDown->quantity += $converted['quantity'];
            $goDown->save();
        }

        $totalAmount += $totalWithGst;
    }

    // Add overall GST if any
    $overAllGst = $totalAmount * ($ordeData->extra_gst / 100);
    $finalAmount = $totalAmount + $overAllGst;

    // Update order total
    $ordeData->total_amount = number_format((float)$finalAmount, 2, '.', '');
     

    if( $action_type == 'C'){
        $ordeData->status = '2'; // fully cancelled;
    }elseif( $action_type == 'PR'){
        $ordeData->status = '3'; // Partially Returned;
    }
    $ordeData->save();
    $text = '';
    if( !empty( $forNotification) ){
        foreach( $forNotification as $notification ){
            $text .= 'Changed quantity from <b>'.$notification['p_q'].'</b> to <b>'.$notification['n_q'] .
            ' </b> for product <b>' . $notification['name'] . '</b>';

        }
    }

      $note = Auth::user()->first_name.' has updated the order . '.$text;
                                    $params['update_fields'] = [
                                        'user_id'=> Auth::user()->id,
                                        'note'=> $note,
                                    ];
     $this->trackActivity($params);


        return redirect()->route('manage.orders')
        ->with('message', 'Return Order has been processed successfully.');

        }else{
            session()->flash('response','danger');
                        return redirect()->back()->with('message','Unauthorized access!');
        }
   
    }else{
        session()->flash('response','danger');
        return redirect()->route('manage.orders')
        ->with('message', 'Order has been cancelled');
    }
 }
 
  public function orderEdit(Request $request, string $ordernumber)
        {            

        $getInfo = Order::where('order_number', $ordernumber)
                                // ->whereNot('status','2')
                                    ->with('customerAddress')
                                    ->with([
                                        'customer',
                                        'orderDetails.product' => function ($query) {
                                            $query->select('id', 'name', 'slug','product_code');
                                        }
                                    ])
                                    ->first();

                                   // return $getInfo->customerAddress->first_name;

        return view('manager.order-edit', 
        compact('getInfo','ordernumber'));

        }

       public function orderDetailsUpdate(Request $request, string $ordernumber)
{
    // Get Order ID
    $order = Order::where('order_number', $ordernumber)->select('id')->firstOrFail();
    $orderId = $order->id;

    // Get Customer ID from CustomerAddress table
    $customerAddress = CustomerAddress::where('order_id', $orderId)->select('customer_id')->firstOrFail();
    $customerId = $customerAddress->customer_id; // You used ->id earlier, which was incorrect

    CustomerAddress::where('order_id', $orderId)->update([
        'email'             => $request->email,
        'first_name'        => $request->first_name,
        'last_name'         => $request->last_name,
        'phone'             => $request->phone,
        'alternative_phone' => $request->alternative_phone,
        'address_line_one'  => $request->address_line_one,
        'address_line_two'  => $request->address_line_two,
        'city'              => $request->city,
        'pin'               => $request->pin,
        'state'             => $request->state,
        'gst_number'        => $request->gst_number,
    ]);

    Customer::where('id', $customerId)->update([
        'email'             => $request->email,
        'first_name'        => $request->first_name,
        'last_name'         => $request->last_name,
        'phone'             => $request->phone,
        'alternative_phone' => $request->alternative_phone,
        'address_line_one'  => $request->address_line_one,
        'address_line_two'  => $request->address_line_two,
        'city'              => $request->city,
        'pin'               => $request->pin,
        'state'             => $request->state,
        'gst_number'        => $request->gst_number,
    ]);

    $note = Auth::user()->first_name . ' has updated customer details 
        <a href="' . route('manage.invoicedownload', [$ordernumber, 'view']) . '" class="text-link">order.</a>';
                        $params['update_fields'] = [
                            'user_id' => Auth::user()->id,
                            'note'    => $note,
                        ];
                        $this->trackActivity($params);

    session()->flash('response', 'success');
    return back()->with('message', 'Customer Details have been changed. Please download updated invoice.');
}


}