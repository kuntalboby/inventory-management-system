<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Page;
use App\Models\TrackLogin;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\CartService;
use App\Services\ShortcodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    //
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    public function myCustomerId($myUserid){
        return Customer::where('user_id',$myUserid)->pluck('id')->first();
    }
     public function dashboard(){
      
        //if(empty($slug)) { $slug = 'home'; }
                $pageArr = Page::where('id','>','0')->orderBy('sequence');
                $slug='';
                $menu = $this->menuList($pageArr->get(),$slug);
               $loggedInId = Auth::user()->id;
               $customerId = $this->myCustomerId($loggedInId);
               $limitHours = (int) env('RETURN_REJECT_AFTER');
            //return $customerId.' --- '.$loggedInId;

               $orders = Order::whereHas('orderDetails')
                    ->where('customer_id',$customerId)
                    ->orderByDesc('order_date')
                    ->get();

                    //  $orders = Order::whereHas('orderDetails')
                    // ->where(function ($query) use ($customerId) {
                    //     $query->where('customer_id', $customerId)
                    //         ->orWhereHas('customer', function ($q) {
                    //             $q->where('email', Auth::user()->email);
                    //         });
                    // })
                    // ->orderByDesc('order_date')
                    // ->get();

    //return $orders;
       
        $sitedata = $this->sitedata();
        $title = 'Order History';
        $bg_image= asset('storage/website-image/customer-banner.jpg');
       // return $menu;
    return view('users.dashboard', compact('title','sitedata',
    'menu','orders','bg_image','limitHours'));
}

public function orderView(Request $request , $order_number){
      
        //if(empty($slug)) { $slug = 'home'; }
                $pageArr = Page::where('id','>','0')->orderBy('sequence');
                $slug='';
                $menu = $this->menuList($pageArr->get(),$slug);
                $loggedInId = Auth::user()->id;
                 $customerId = $this->myCustomerId($loggedInId);

                 //return $loggedInId.'---'.$customerId;
               $orders = Order::whereHas('orderDetails')->whereHas('customerAddress')
                    ->with('orderDetails')
                    ->with('customerAddress')
                    ->with('customer')
                    ->where('customer_id',$customerId)
                    ->where('order_number',$order_number)
                    ->firstOrFail();
//return $orders;
        
       $limitHours = (int) env('RETURN_REJECT_AFTER');
        $sitedata = $this->sitedata();
        $title = 'Order Details';
        $bg_image= asset('storage/website-image/customer-banner.jpg');
       // return $menu;
      return view('users.view', compact('title','sitedata','limitHours','menu','orders','bg_image'));
}
public function cancelOrderRequest(Request $request , $order_number){
                 $loggedInId = Auth::user()->id;
                 $customerId = $this->myCustomerId($loggedInId);    
                
                 $order = Order::where('order_number', $order_number)
                 ->where('customer_id', $customerId)
                 ->with('customer:first_name,last_name,phone,id')
                 ->firstOrFail(); // fetch model instance

                // return $order;
                if ($order) {
                    $order->update(['status' => '4']);

                    $invoice_number = $order->invoice_number;
                    $customerName = $order->customer->first_name.' '.$order->customer->last_name;
                    $custPhone = $order->customer->phone;
                
                    $sitedata = $this->sitedata();
                    $mailFor = 'cancelRequest';
                    $siteLogo = asset('storage/website-image/' . $sitedata->site_logo);
                    $mailto=[];

                    $note = Auth::user()->first_name.' has placed a
                        <a href="' . route('manage.invoicedownload', [$order_number, 'view']) . '" 
                        class="text-link">cancel order request </a>';
                                    
                                    $params['update_fields'] = [
                                        'user_id' => $loggedInId,
                                        'note'    => $note,
                                    ];
                            $this->trackActivity($params);


                    if(!empty($sitedata->pdf_recvr_email_one)){
                         array_push( $mailto , $sitedata->pdf_recvr_email_one);
                    }if(!empty($sitedata->pdf_recvr_email_two)){
                         array_push( $mailto , $sitedata->pdf_recvr_email_two);
                    }
                    if(!empty($mailto)) {
                            $mail = Mail::to($mailto);
                            $msgCntent = 'Dear Admin , user :'.$customerName.' wants to cancel his/her request .Invoice Number is '.$invoice_number.'. 
                                And phone number of the user '.$custPhone.'. Please visit to your order section for next process.';
                            $mail->send(
                                new ContactFormMail(
                                    'Admin',
                                    '',
                                    'Order Cancel Request',
                                    $msgCntent,
                                    //site info
                                    $sitedata->site_name ,
                                    $siteLogo,
                                    $mailFor

                                )
                            );
                    }
                    session()->flash('response','success');
                    return redirect()->back()->with('message','Cancel request has been sent to admin.We will contact you soon.');
                    
                   
                }else{
                session()->flash('response','danger');
                return redirect()->back()->with('message','sorry! you cant access this');
        }

}
 
public function downloadInvoice(Request $request, string $order_number){
     $loggedInId = Auth::user()->id;
     $customerId = $this->myCustomerId($loggedInId); 
    return  $this->customerInvoice($order_number,'download','','',$customerId);
}
public function myProfile(Request $request){
    $pageArr = Page::where('id','>','0')->orderBy('sequence');
                $slug='';
                $menu = $this->menuList($pageArr->get(),$slug);
               $loggedInId = Auth::user()->id;
              // $customerId = $this->myCustomerId($loggedInId);

              
        $customerDetails = Customer::where('user_id',$loggedInId)
                ->with('user')
                ->where('default_info','1')
                ->firstOrFail();
        
        //return $customerDetails;
        $sitedata = $this->sitedata();
        $title = 'My Account';
        $bg_image= asset('storage/website-image/customer-banner.jpg');
       // return $menu;
    return view('users.profile', compact('title','sitedata','menu','customerDetails','bg_image'));

}

public function updateMyProfile(Request $request){
        $loggedInId = Auth::user()->id; // current user ID
        $customerId = $this->myCustomerId($loggedInId);  
    $request->validate([
        'first_name'       => 'required|string|max:255',
        'last_name'        => 'required|string|max:255',
        'email'            => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($loggedInId), //allow own email
        ],
    ]);
    $request->validate([
        'first_name'       => 'required|string|max:255',
        'last_name'        => 'required|string|max:255',
        // 'email'            => [
        //     'required',
        //     'email',
        //      Rule::unique('customers', 'email')->ignore($customerId), //allow own email
        // ],
        // 'phone'            => [
        //     'required',
        //      Rule::unique('customers', 'phone')->ignore($customerId), //allow own email
        // ],
        'address_line_one' => 'required|string|max:700',
        'pin'              => 'required|string|max:10',
        'state'            => 'required|string|max:10',
        'city'             => 'required|string|max:10',
    ]);
    
                 $customerId = $this->myCustomerId($loggedInId);    
                
                 $customer = Customer::where('user_id', $loggedInId)
                 ->where('id', $customerId)
                 ->firstOrFail();

                 $user = User::where('id', $loggedInId)
                 ->firstOrFail();
                
                 $user->update([
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'email'=>$request->email,
                 ]);
                 $update = $request->all();
                 $updateDone = $customer->update( $update);
                if($updateDone){
                    return redirect()->back()
                  ->with([
                      'message'=> 'Your information has been updated successfully',
                      'response'=>'success'
                  ]);
                } 
                return redirect()->back()
                  ->with([
                      'message'=> 'Your information has not been updated',
                      'response'=>'danger'
                  ]);

}
public function myPassword(Request $request){
 $pageArr = Page::where('id','>','0')->orderBy('sequence');
                $slug='';
                $menu = $this->menuList($pageArr->get(),$slug);
                $loggedInId = Auth::user()->id;
              // $customerId = $this->myCustomerId($loggedInId);
        
        //return $customerDetails;
        $sitedata = $this->sitedata();
        $title = 'Change Password';
        $bg_image= asset('storage/website-image/customer-banner.jpg');
       // return $menu;
    return view('users.password', compact('title','sitedata','menu','bg_image'));



}
public function updatePassword(Request $request){

Validator::make($request->all(), [
          'current_password' => 'required',
          'password' => 'required|confirmed|min:6',
          'password_confirmation'=>'required',
      ])->validate();


      $currentPasswordStatus = hash::check($request->current_password, Auth::user()->password);
      if($currentPasswordStatus){

          User::findOrFail(Auth::user()->id)->update([
              'password' => Hash::make($request->password),
          ]);

          session()->flash('response','success');
          return redirect()->back()->with('message','Password has been updated successfully.');
        }else{
                session()->flash('response','danger');
                return redirect()->back()->with('message','sorry! You have entered wrong current password!');
        }

}
    

   public function customerLogin(ShortcodeService $shortcodeService , Request $request)
{
    
    //return $request;
    $credentials = $request->validate([
        'email' => ['required','email'],
        'password' => ['required'],
    ]);
    $remember = $request->has('remember');
    if (Auth::attempt($credentials,$remember)) {
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'customer' && $user->status == 1) {
            // Track login
            $this->trackLoginDetails([
                'update_fields' => [
                    'user_id' => $user->id,
                    'role_type' => 'customer',
                    'last_login_at' => now(),
                    'last_login_ip_address' => $request->ip(),
                ]
            ]);
            $this->cartService->mergeCartAfterLogin();
            return redirect()->route('users.dashboard');
        }

        // Auth::logout();
        // return back()->with('message', 'Only active customers can login.')->withInput();
    }

    return back()->with('message', 'The provided credentials do not match our records.')->withInput();
}
public function forgetPassword( Request $request){
   $request->validate([
        'email' => ['required','email'],
    ]);
     $randString = Str::random(6).rand(5,90).Str::random(6);
     $link = url('/').'/forget-pass-form/'.$randString;
     
    
    $user = User::where('email',$request->email)->where('status','1')
    ->select(['first_name','last_name','id','email'])
    ->first();

    if( $user ){
        $siteInfo = $this->sitedata();
        User::where('id', $user->id)->update(['remember_token' => $randString]);
        $siteLogo = asset('storage/website-image/' . $siteInfo->site_logo);
                 $mailFor = 'forgetPassLink';
                        Mail::to($user->email)
                        ->send(
                            new ContactFormMail(
                                ucfirst(strtolower($user->first_name)).' '.ucfirst(strtolower($user->last_name)),
                                $user->email,
                                'Forget password updation',
                                $link,
                                $siteInfo->site_name ,
                                $siteLogo,
                                $mailFor

                            )
                        );
                        return back()->with([
                            'message'=>  'Password update link has been sent to your email address.',
                            'response'=>'success'
                        ]);
    }else{
        return back()->with([
                            'message'=>  'Email doesnt exists.',
                            'response'=>'danger'
            ]);
    }
    
}

// public function forgetPassForm(Request $request , string $slug){
//     $user = User::where('remember_token',$slug )->where('status','1')
//         ->select(['first_name','last_name','id'])
//         ->first();
//         if( $user ){
//              return route('product-details', $slug);
//         }else{
//             return back()->with(['message'=> 'This link is invalid','response'=>'danger'])->withInput();
//         }

// }

public function updateMyPass(Request $request , string $slug){

    $request->validate([
            'password' => 'required|confirmed|min:6',
            'password_confirmation'=>'required',
        ]);

    $user = User::where('remember_token',$slug )->where('status','1')
        ->select(['first_name','last_name','id'])
        ->first();


     if( $user ){

         $hassedPased = Hash::make($request->password);
            User::where('id', $user->id)
                    ->update(['remember_token' => null,'password'=>$hassedPased]);
        
            return redirect()->route('page','user-login')
                ->with([
                    'response'=>'success',
                    'message'=>'Password has been updated successfully'
                ]);
     }else{
         return redirect()->back()
                ->with([
                    'response'=>'danger',
                    'message'=>'This link  is invalid'
                ]);
     }   

}
    public function trackLoginDetails($param){

        $create = TrackLogin::create(
            $param['update_fields']
        );
        return $create;

    }
public function customerRegistration(Request $request){

     $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation'=>'required',
            'phone'      => 'required|string|max:15',
            'address_line_one'=>'required|string|max:700',
            'pin'        => 'required|string|max:10',
            'state'      => 'required|string|max:10',
            'city'       => 'required|string|max:10',
        ]);

        $postedData = $request->all();
        

        if(!empty($postedData) ){

                    
                    $hassedPased = Hash::make($postedData['password']);
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
                    $customer = Customer::create(
                        [
                            'user_id'           =>$user->id,
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
                    
                session()->flash('response','success');
                return redirect()->back()
                ->with('message','Your account has been created.');
        }else{
             session()->flash('response','danger');
                return redirect()->back()
                ->with('message','Your account has not been created.')->withInput();
        }
}

 public function wishlist(Request $request){
      

      $pageArr = Page::where('id','>','0')->orderBy('sequence');
                $slug='';
                $menu = $this->menuList($pageArr->get(),$slug);
               $loggedInId = Auth::user()->id;
               $customerId = $this->myCustomerId($loggedInId);
            //return $customerId.' --- '.$loggedInId;
                $bulkCategory = Category::where('bulk_for_backend', '1')->select('id')->first();

                $wishListProd = Wishlist::where('user_id', $loggedInId)
                    ->whereHas('product', function ($q) use ($bulkCategory) {
                        $q->when($bulkCategory, function ($q2) use ($bulkCategory) {
                            $q2->whereDoesntHave('goDownProducts', function ($q3) use ($bulkCategory) {
                                $q3->where('category_id', $bulkCategory->id);
                            });
                        });
                    })
                    ->with([
                        'product' => function ($q) {
                            $q->select('id', 'product_code', 'gst','slug', 'selling_price', 'name', 'quantity','image')
                            ->with('unit:id,name,slug');
                        }
                    ])
                    ->get();
              
       
        $sitedata = $this->sitedata();
        $title = 'Wishlist';
        $bg_image= asset('storage/website-image/customer-banner.jpg');
       // return $wishListProd;
    return view('users.wishlist', compact('title','sitedata','menu','wishListProd','bg_image'));

 }
 public function customerLogout(Request $request)
{
    Auth::logout(); // same guard
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('page','user-login')
        ->with('message', 'You have been logged out successfully.');
} 
}
