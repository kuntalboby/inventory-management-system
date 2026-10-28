<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Mail\InvoiceMail;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\GoDownProductDetail;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteInfo;
use App\Models\User;
use App\Services\CartService;
use App\Services\ShortcodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FrontendController extends Controller
{
    //
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
     public function page( ShortcodeService $shortcodeService , $slug = null){
        $prodCatSlug=null;
        $token = null;
        $catslug = null ; 
        if ($slug === 'user-login' && Auth::check() && Auth::user()->role === 'customer') {
            return redirect()->route('users.dashboard');
         }elseif ($slug === 'user-login' && Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
         }elseif ($slug === 'user-login' && Auth::check() && Auth::user()->role === 'manager') {
            return redirect()->route('manager.dashboard');
         }

        if (request()->routeIs('product-details')) {
            $prodCatSlug = $slug;          
            $slug = 'product-details';
        }if (request()->routeIs('category-products')) {
            $catslug = $slug;          
            $slug = 'category-products';
        }else if (request()->routeIs('forget-pass-form')) {
            $token = $slug;
            $slug = 'forget-pass-form';
        }
       // return $prodCatSlug.'-'.$slug;

        if(empty($slug)) { $slug = 'home'; }
       $pageArr = Page::where('id','>','0')->orderBy('sequence');

                $menu = $this->menuList($pageArr->get(),$slug);
                $page = $pageArr->with(['pageSections' => function ($q) {
                    $q->orderBy('sort_position');
                }])->where('slug', $slug)
                ->firstOrFail();

               //return $page;
           $content= '';
           $headerBannerSlider = '';
        if(!empty($page->pageSections)){
             foreach ($page->pageSections as $k=>$contentblock) {
                if($k>0){
                    $content .= $shortcodeService->parse($contentblock->content,$prodCatSlug ,$token , $catslug);
                }else{
                    //return $contentblock->content;
                    $headerBannerSlider = $shortcodeService->parse($contentblock->content ,$prodCatSlug ,$token , $catslug);
                    
                }
             }
        }
       
        $sitedata = $this->sitedata();
       // return $menu;
    return view('page', compact('page', 'content','headerBannerSlider','sitedata','menu'));
}

public function contactFormSubmit( Request $request ){
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    $siteInfo = SiteInfo::where('id','1')->select(['site_email','site_name','site_logo'])->first();
    $siteLogo = asset('storage/website-image/' . $siteInfo->site_logo);
  
    $mailTo = $siteInfo->site_email;
    $mailFor = 'contact';
    Mail::to($mailTo)
    ->cc($request->email)
    ->send(
        new ContactFormMail(
            $request->name,
            $request->email,
            $request->subject,
            $request->message,
            //site info
            $siteInfo->site_name ,
            $siteLogo,
            $mailFor
        )
    );
//     $mailable = new ContactFormMail(
//             $request->name,
//             $request->email,
//             $request->subject,
//             $request->message,
//             //site info
//             $siteInfo->site_name ,
//             $siteLogo);
// $html = $mailable->render();

// dd($html);
    $param['mode'] = 'W';
    $param['key'] = 'thanks-msg';
    $param['value'] = 'Thank you for contacting us we will be in touch shortly.';
    
    $this->handleSession($param);
    return redirect()->route('page','thank-you');

}

public function postNewsLetter( $params ){
    $response['status'] = 'err';
    $response['msg'] = 'You have already subscribed our newsletter.';

    $hasAlready = NewsletterSubscriber::where('email',$params)->count();
    if($hasAlready == 0 ){
        NewsletterSubscriber::create(['email'=>$params]);
        $response['status'] = 'done';
        $response['msg'] = 'You have successfully subscribed our newsletter.';

        //mail
        $siteInfo = $this->sitedata();
        $siteLogo = asset('storage/website-image/' . $siteInfo->site_logo);
        $mailTo=[];
        if( !empty($siteInfo->pdf_recvr_email_one)){
                array_push($mailTo,$siteInfo->pdf_recvr_email_one);
        }
        if( !empty($siteInfo->pdf_recvr_email_two)){
                array_push($mailTo,$siteInfo->pdf_recvr_email_two);
        }
        if( !empty($mailTo) ){

                $mailFor = 'newsletter';
                Mail::to($mailTo)
                ->send(
                    new ContactFormMail(
                        'Admin',
                        $params,
                        $siteInfo->site_name.'-Newsletter subscription',
                        ' has subscribed our newsletter. Please take necessary actions.',
                        //site info
                        $siteInfo->site_name ,
                        $siteLogo,
                        $mailFor
                    )
                );
        }
            
            
    }
    return $response;
}

 public function sortfl($query , $sortSearch){
    

        if(isset($sortSearch['sort_val'])){
                $sorVal = $sortSearch['sort_val'];

               if($sorVal === 'popularity'){
                    $queryRe = $query->withCount(['orderDetails as order_count' => function ($q) {
                        $q->select(DB::raw('count(*)'));
                    }])
                    ->orderByDesc('order_count');

                }elseif($sorVal === 'date'){
                    $queryRe = $query->orderByDesc('created_at');
                

                }elseif($sorVal === 'price'){
                    $queryRe = $query->orderBy('selling_price');
                
                }elseif($sorVal === 'price-desc'){
                    $queryRe = $query->orderByDesc('selling_price');
                    
                }elseif($sorVal === 'name'){
                    $queryRe = $query->orderBy('name');
                    
                }

        }
        if(isset($sortSearch['cat_slug']) && !empty($sortSearch['cat_slug'])){
            $categorySlug = $sortSearch['cat_slug'];
            $categoryId = Category::where('slug',$categorySlug)->select('id')->firstOrFail();
            $allChildIds = Category::getAllChildIds($categoryId->id);
            array_push( $allChildIds , $categoryId->id );
               $queryRe =  $query->whereHas('goDownProducts', function ($q) use ($allChildIds) {
                    $q->whereIn('category_id', $allChildIds);
                });
        }
        if(isset($sortSearch['price_range']) && !empty($sortSearch['price_range'])){
                $explodeRange = explode('-',$sortSearch['price_range']);
                $queryRe = $query->whereBetween('selling_price', [$explodeRange[0], $explodeRange[1]]);

        }else{
             $queryRe = $query->orderBy('name');
        }
        
    return $queryRe;
 }
private function productSearchActions($req){

   // return $req;
    $bulkCategory = Category::where('bulk_for_backend', '1')->select('id')->first();
    //  start Main query
    $query = Product::with([
        'unit:id,name,slug',
    ])
    ->where('quantity', '>', '0')
    ->when($bulkCategory, function ($q) use ($bulkCategory) {
        $q->whereDoesntHave('goDownProducts', function ($q2) use ($bulkCategory) {
            $q2->where('category_id', $bulkCategory->id);
        });
    });
    //  main query

    //return $req['query'];
    $filtdQuery = $this->sortfl($query,$req['query']);
    $products = $filtdQuery->get();
    return $products;

}



 public function placeOrder(Request $request){

   // return false;
        
       $validator = Validator::make($request->all(), [
    'first_name' => 'required|string|max:255',
    'last_name'  => 'required|string|max:255',
    'email'      => [
        'nullable',
        'email',
        Rule::unique('users', 'email')
            ->where(fn($q) => $q->whereNotIn('role', ['admin', 'manager']))
            ->ignore(Auth::id()), // allow logged-in user to use their own email
    ],
    'phone'      => 'required|string|max:15',
    'address_line_one'=>'required|string|max:700',
    'pin'        => 'required|string|max:6',
    'state'      => 'required|string|max:50',
    'city'       => 'required|string|max:50',
    'final_amount_aftercal' => 'required|numeric|min:0',
], [
    'final_amount_aftercal.required' => 'Final amount is missing.',
    'final_amount_aftercal.min'      => 'Final amount must be greater than or equal to 0.',
]);

$validator->after(function ($validator) use ($request) {
    if ($request->email) {
        $exists = User::where('email', $request->email)
                      ->where('role', 'customer')
                      ->where('id', '!=', Auth::id()) // ignore self
                      ->exists();

        if ($exists && !Auth::check()) {
            $validator->errors()->add(
                'email',
                'This email belongs to a registered customer. Please log in to continue.'
            );
        }
    }
});

$validator->validate();

        //validation //
        $postedData = $request->all();
       // return $postedData;
         $shippingCharge = $this->cartService->shippingCharges($postedData['pin']);
        
        $shippingCharges = $shippingCharge['charges'];
        if( is_null($shippingCharges) ){
                    // order can not be delivered to the given pin
            return redirect()->route('page','checkout')
                ->with('message', 'Sorry ! currently we are not available in your given location.');
        }else{
             if(!empty($postedData) ){
                
                $create_new_account = isset($postedData['create_new_account']) ? $postedData['create_new_account']: 'N';
                $final_amount_aftercal = $postedData['final_amount_aftercal'];
                            //return $postedData['final_amount_aftercal'];
                $getCartItems =$this->cartService->getCartItems();
            
                
                $counter = count($getCartItems);
                $errorProd=[];
                $orderDetails=[];

            //return $counter;

                 

            if( $counter > 0 ){
        
                $finalAmount = 0;
                $finalAmountInGst = 0;
                $extraTax = 0;
                
                // writen in service so that it can be access from any where;
                $overAllTax = 100 +  $extraTax;

            $bulkCategory = Category::where('bulk_for_backend', '1')->select('id')->first();
            $count_available=0;
            foreach ($getCartItems as $key => $product) {
                
                
                $singleProdChecking = Product::whereHas('unit')->with(['unit:id,name,slug'])
                        ->where('quantity', '>', '0')
                        ->when($bulkCategory, function ($q) use ($bulkCategory) {
                            $q->whereDoesntHave('goDownProducts', function ($q2) use ($bulkCategory) {
                                $q2->where('category_id', $bulkCategory->id);
                            });
                        })->select(['id','name','gst','unit_id',
                            'slug','selling_price',
                            'product_code','buying_price','go_down_product_id'])
                            ->where('quantity', '>=' , $product['quantity'])
                            ->where([
                                'id'=>$product['product_id'],
                                'selling_price'=>$product['price'],
                                'product_code'=> $product['product_code'],
                                'gst'=> $product['product_gst']
                            ]);
                            
                            //checked all firlds in session;
                                
                    $getEachProd = $singleProdChecking->lockForUpdate()->first(); // 
                    if( !empty($getEachProd) ){
                        

                        //return $getEachProd;

                            $gst = $getEachProd->gst ? $getEachProd->gst : 0;
                            $sp = $getEachProd->selling_price;
                            $qnt = $product['quantity'];
                            //$prodCode = $getEachProd->product_code;
                            
                            $subtotal = $sp * $qnt;
                            $gstAmt = 100+$gst;
                            $subtotal = $subtotal * $gstAmt/100;
                            $finalAmount +=$subtotal;    
                            
                            
                        $orderDetails[$getEachProd->product_code] = [
                            'product_id'    => $getEachProd->id,
                            'product_name'    => $getEachProd->name,
                            'buying_price'  => $getEachProd->buying_price,
                            'selling_price' => $getEachProd->selling_price,
                            'quantity'      => $product['quantity'],
                            'unit_id'       => $getEachProd->unit->id,
                            'unit'          => $getEachProd->unit->name,
                            'amount'        => $subtotal,
                            'product_gst'   => $getEachProd->gst,
                            'discount_percent'=> '0'
                        ];
                    $count_available++;
                    
                    }else{
                        $prdNm = Product::where('product_code',$product['product_code'])->select('name')->first();
                        $errorProd[$key] = "'".$prdNm->name."'";
                    }
            }

            //end first foreach

            //return '---'.$count_available;
            if( $count_available == $counter ){

                $freeShippingAfter = env('FREE_SHIPPING_CHARGE_AFTER');
                $finalAmountInGst =   round($finalAmount * $overAllTax/100,2);
                $shpchagForTable = $shippingCharges;
                if( $finalAmountInGst > $freeShippingAfter ){
                        $shippingCharges = 0;
                }
            
            $finalAmountWithShippingCharges = $finalAmountInGst + $shippingCharges;
                
                if (round($finalAmountWithShippingCharges, 2) == round($final_amount_aftercal, 2)) {
                    //create_new_account == Y // mail will send
                    //return $orderDetails;
                    $user=[];
                            if( ! Auth::check() && $create_new_account == 'Y' ){
                                    
                                //guest 
                                    $passwordPlain = Str::random(6);
                                    $hassedPased = Hash::make($passwordPlain);
                                    $user = User::create(
                                    [
                                                'email'             => $postedData['email'],
                                                'first_name'        => $postedData['first_name'],
                                                'last_name'         => $postedData['last_name'],
                                                'role'              => 'customer',
                                                'signup_from'       => '1', // from online
                                                'status'            => '1',
                                                'password'          => $hassedPased
                                            ]
                                        );
                            }
                       
                        
                        $customer = Customer::updateOrCreate(
                            ['phone' => $postedData['phone'],
                                         'email'=> $postedData['email'],
                                   ],
                            [
                                'user_id'           => $user ? $user->id : (Auth::check() ? Auth::id() : null) ,
                                'email'             => $postedData['email'],
                                'first_name'        => $postedData['first_name'],
                                'last_name'         => $postedData['last_name'],
                                'phone'             => $postedData['phone'],
                                'alternative_phone' => $postedData['alternative_phone'],
                                'address_line_one'  => $postedData['address_line_one'],
                                'address_line_two'  => $postedData['address_line_two'],
                                'city'              => $postedData['city'],
                                'pin'               => $postedData['pin'],
                                'state'             => $postedData['state'],
                                'gst_number'        => '',
                            ]
                        );
                        $invoiceNmber = (Order::max('id') ?? 0) + 1;
                        $randOrderNumber = 'ORD-' . rand(11, 500) . '-' . $this->randnoGenerator(8);
                        $order_date = Carbon::now();

                        $saveOrder = Order::create([
                            'order_date'     => $order_date,
                            'customer_id'    => $customer->id,
                            'order_number'   => $randOrderNumber,
                            'invoice_number' => 'INV-0000' . $invoiceNmber,
                            'manager_id'     => null,
                            'ordered_from'   => '2', // from online
                            'extra_gst'      => $request->extra_gst,
                            'delivery_charge' => $shpchagForTable,
                            'delvry_free_after' => $freeShippingAfter,
                            'total_amount'   => $final_amount_aftercal,
                            'comment'        => '',
                        ]);

                        
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

                                

                            
                            }
                            //return $recorded;

                                $siteInfo = SiteInfo::where('id','1')
                                ->select(['pdf_recvr_email_one','pdf_recvr_email_two','site_name','site_logo'])->first();
                                $siteLogo = asset('storage/website-image/' . $siteInfo->site_logo);
                            
                                $adminEmails = array_filter([
                                        $siteInfo->pdf_recvr_email_one,
                                        $siteInfo->pdf_recvr_email_two,
                                    ]);
                                $this->customerInvoice( $randOrderNumber ,'mail' , $adminEmails , $postedData['email'],$customer->id);
                                if( !empty($postedData['email']) ){
                                        
                                    
                                    if (isset($user->wasRecentlyCreated) && $create_new_account == 'Y' ) {
                                            
                                    
                                        $mailFor = 'guestPassword';
                                        Mail::to($postedData['email'])
                                        ->send(
                                            new ContactFormMail(
                                                ucfirst(strtolower($postedData['first_name'])).' '.ucfirst(strtolower($postedData['last_name'])),
                                                $postedData['email'],
                                                '',
                                                $passwordPlain,
                                                //site info
                                                $siteInfo->site_name ,
                                                $siteLogo,
                                                $mailFor

                                            )
                                        );
                                    }
                                    
                                }
                                

                             CustomerAddress::create(
                            [
                                        'customer_id'       => $customer->id,
                                        'order_id'          => $saveOrder->id,
                                        'email'             => $postedData['email'],
                                        'first_name'        => $postedData['first_name'],
                                        'last_name'         => $postedData['last_name'],
                                        'phone'             => $postedData['phone'],
                                        'alternative_phone' => $postedData['alternative_phone'],
                                        'address_line_one'  => $postedData['address_line_one'],
                                        'address_line_two'  => $postedData['address_line_two'],
                                        'city'              => $postedData['city'],
                                        'pin'               => $postedData['pin'],
                                        'state'             => $postedData['state'],
                                        'gst_number'        => '',
                                    ]
                                );
                                
                        }
                       

                        // Track activity
                                    $note = $postedData['first_name']. ' '.$postedData['last_name'] .'
                                        has placed an <a href="' . route('manage.invoicedownload', [$randOrderNumber, 'view']) . '" class="text-link">order.</a>';
                                    
                                    $params['update_fields'] = [
                                        'user_id' => isset($user->id) ? $user->id : null,
                                        'note'    => $note,
                                    ];
                            $this->trackActivity($params);
                            $param['mode'] = 'W';
                            $param['key'] = 'thanks-msg';
                            $param['value'] = 'Thank you for shopping with us. We will deliver your items as soon as possible.';
                            $this->cartService->emptyMyCart();
                            $this->handleSession($param);
                            return redirect()->route('page','thank-you');

                }else{
                    return back()->with('message', 'Some product prices or quantities might have changed. Please try again later.')->withInput();
                }
            }else{
                $errorPrdName = implode(',',$errorProd);
                return back()->with([
                    'response'=>'danger',
                    'message'=>
                    $errorPrdName.' prices or quantities might have changed. Please try again later.'
                 ])->withInput();
            }



            }else{
                return redirect()->route('page','checkout')->with('message', 'Some product prices might have changed or sold out. Please try again later.');
            }


        }else{
             return redirect()->route('page','checkout')->with('message', 'Something went wrong! your cart is empty');
        }
    }

       

       
 }
public function ajaxActions(Request $request){
    
   //return $request['data'];
    if ($request->ajax()) {
        $requestedPostsData = $request->all();

        if( isset($requestedPostsData['data']['type']) && $requestedPostsData['data']['type'] == 'PROD-SORT'){
                $returndata['status'] = 'Y';
                $returndata['data'] = $this->productSearchActions($requestedPostsData['data']);
        }
        //addToCart Fnc
        elseif( isset($requestedPostsData['data']['type']) && $requestedPostsData['data']['type'] == 'CART'){

                $this->cartService->addToCart(
                            $request['data']['product_code'],
                            $request['data']['quantity'],
                            $request['data']['price']
                        );

                        return response()->json([
                            'status' => 'success',
                            'count' => $this->cartService->getCartCount()
                        ]);
        }elseif( isset($requestedPostsData['data']['type']) && $requestedPostsData['data']['type'] == 'WISHLIST'){

           // return $requestedPostsData['data'];
                $returnCount = $this->cartService->addToWishList(
                            $request['data']['product_code'],
                            $request['data']['is_add']
                        );

                        return response()->json([
                            'status' => $returnCount['status'],
                            'count' => $returnCount['count']
                        ]);
        }elseif( isset($requestedPostsData['data']['type']) && $requestedPostsData['data']['type'] == 'CART-UPDATE'){
                $product_code = $requestedPostsData['data']['product_code'];
                $response = $this->cartService->removeFromCart($product_code);
                
                $this->cartService->addToCart(
                            $request['data']['product_code'],
                            $request['data']['quantity'],
                            $request['data']['price']
                        );

                        return response()->json([
                            'status' => 'success',
                            'count' => $this->cartService->getCartCount()
                        ]);
        }elseif( isset($requestedPostsData['data']['type']) && $requestedPostsData['data']['type'] == 'CART-COUNT'){

                 return response()->json([
                        'count' => $this->cartService->getCartCount(), 
                        'wishListcount'=>$this->cartService->wishlistCount()
                    ]);
        }elseif( isset($requestedPostsData['data']['type']) && $requestedPostsData['data']['type'] == 'SINGLE-REMOVE'){
                // single product remove form the cart 
                $product_code = $requestedPostsData['data']['product_code'];
                $response = $this->cartService->removeFromCart($product_code);
                if($response){
                     $returndata['status'] = 'success'; //  Invalid Product
                     $returndata['msg'] = 'Product has been removed from cart';
                }else{
                     $returndata['status'] = 'ERROR-2'; //  Invalid Product
                     $returndata['msg'] = 'Something went wrong.This product is not available.'; 
                     $returndata['data']=[];
                }
            
        }elseif( isset($requestedPostsData['data']['type']) && $requestedPostsData['data']['type'] == 'SHIPPING-CHARGE'){
               
            $charge = $this->cartService->shippingCharges($requestedPostsData['data']['value']);
                //return $charge;
                if( is_null($charge['charges']) ){ //if null only that means we cnt delivered in this pin
                    $returndata['status'] = 'ERROR-1'; //  Invalid Product
                    $returndata['msg'] = '* Sorry! we do not serve your location ('.$charge['pin'].') . Please enter another pin code.';
                    $returndata['charge'] = '';
                }else{
                    $returndata['status'] = 'success'; 
                    $returndata['msg'] = 'Shipping charges might have changed. Please check before placing your order.';
                    $returndata['charge'] = $charge['charges'];
                }
                    
        }elseif( isset($requestedPostsData['data']['type']) && $requestedPostsData['data']['type'] == 'POST-NEWSLETTER'){

                $res =  $this->postNewsLetter($requestedPostsData['data']['email']);
                   return response()->json([
                            'status' => $res['status'],
                            'msg'=>$res['msg']
                        ]);
        }else{
             $returndata['status'] = 'ERROR-1'; // invalid Request
             $returndata['data']=[];
        }


    }else{
        $returndata['status'] = 'ERROR-1'; // invalid Request
        $returndata['msg'] = 'Something went wrong.Invalid Request'; 
        $returndata['data']=[];
    }
     return response()->json($returndata);
}

//cart functionality 

    
}
