<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Pincodes;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function addToCart($productCode, $qty, $price)
    {
        //Session::forget('cart');
        // find product id from product code
        $product = Product::where('product_code', $productCode)->select(['id','name','gst','quantity','product_code','selling_price'])->firstOrFail();
        $productId = $product->id;
       // $prodName = $product->name;
        $prodCode = $product->product_code;
        $prodGst = $product->gst;
       
        // If logged in and role is customer
        if (Auth::check() && Auth::user()->role === 'customer') {
            $cart = Cart::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            $cart->quantity += $qty;
            //$cart->name = $prodName;
            $cart->price = $price;
            $cart->product_code = $prodCode;
            $cart->product_gst = $prodGst;

            $cart->save();
        } else {
            // Guest 
            $cart = Session::get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $qty;
            } else {
                $cart[$productId] = [
                    'product_id' => $productId,
                   // 'name' => $prodName,
                    'quantity' => $qty,
                    'price' => $price,
                    'product_code'=>$prodCode,
                    'product_gst'=>$prodGst
                ];
            }
            Session::put('cart', $cart);
        }
    }
    public function addToWishList($productCode, $isAdd)
    {
        //Session::forget('cart');
        // find product id from product code
        $product = Product::where('product_code', $productCode)->select(['id'])->firstOrFail();
        $productId = $product->id;
       
        if (Auth::check() && Auth::user()->role === 'customer') {
//dd('here-' .$isAdd);
            if( $isAdd === 'Y'){
                    Wishlist::create([
                        'user_id' => Auth::id(),
                        'product_id' => $productId
                    ]);
                     $msg = 'Added Successfully.';
            }else{
                 Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
                $msg = 'Removed Successfully.';
            }
            $counter = Wishlist::where('user_id',Auth::id())->count();
            return ['status'=>'success','msg'=>$msg,'count'=>$counter];
        }else{
            return ['status'=>'error','msg'=>'Please login first.','count'=>0];
        }
    }

    public function wishlistCount($slug = null){
    if (Auth::check()) {
        $wishlistQuery = Wishlist::where('user_id', Auth::id());

        if (!empty($slug)) {
            $product = Product::where('slug', $slug)->first();
            if ($product) {
                $wishlistQuery->where('product_id', $product->id);
            } else {
                // If product not found, return 0 immediately
                return 0;
            }
        }

        return $wishlistQuery->count();
    } else {
        return 0;
    }
}


    


    public function getCartCount()
    {
       
        if (Auth::check() && Auth::user()->role === 'customer') {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = Session::get('cart', []);
            return collect($cart)->sum('quantity');
        }
    }

    public function getCartItems()
{
    if (Auth::check() && Auth::user()->role === 'customer') {
        // Get items from DB
        return Cart::with('product')
            ->where('user_id', Auth::user()->id)
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->product_id => [
                        'product_id'   => $item->product_id,
                        'quantity'     => $item->quantity,
                        'price'        => $item->price,
                        'product_code' => $item->product_code,
                        'product_gst'  => $item->product_gst,
                    ]
                ];
            })
            ->toArray();
    } else {
        // Get items from session
        return session()->get('cart', []);
    }
}
    public function removeFromCart($productCode){
        // Find product id from product code
        $product = Product::where('product_code', $productCode)
            ->select('id')
            ->first();

        if (!$product) {
            return false; // or throw exception
        }

        $productId = $product->id;

        if (Auth::check() && Auth::user()->role === 'customer') {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {


            $cart = Session::get('cart', []);
            //dd($cart);
            
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                Session::put('cart', $cart);
            }

          // session::remove('cart');
        }

        return true;
    }


    public function emptyMyCart()
    {
         
         if (Auth::check() && Auth::user()->role === 'customer') {
            Cart::where('user_id', Auth::id())
                ->delete();
        } else {
            session()->forget('cart');
        }

    }

    public function mergeCartAfterLogin()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            $cart = Session::get('cart', []);
            foreach ($cart as $item) {
                // add using product_id (already stored in session)
                $cartItem = Cart::firstOrNew([
                    'user_id' => Auth::id(),
                    'product_id' => $item['product_id']
                ]);
                $cartItem->quantity += $item['quantity'];
                $cartItem->price = $item['price'];
                $cartItem->product_code = $item['product_code'];
                $cartItem->save();
            }
            Session::forget('cart');
        }
    }
    public function shippingCharges($pincode){
        //dd($pincode);
        // if( Auth::check() && Auth::user()->role === 'customer' ){
        //     $loggedInId = Auth::user()->id;
        //     $customer = Customer::whereHas('user', function ($q) use ($loggedInId) {
        //         $q->where('id', $loggedInId);
        //     })->select(['id','user_id','pin'])->first();

        //     $pincode = $customer->pin;
            
        // }
       // dd($customer);
        
        $pincodeQ = Pincodes::where('pin_code',$pincode)->select('charges')->first();
        $return['pin'] = $pincode;
        $return['charges'] = isset($pincodeQ->charges) ?  $pincodeQ->charges : null;
        
        return $return;

    }
    public function getLoggedInPin(){
        $pincode='';
         if( Auth::check() && Auth::user()->role === 'customer' ){

            $loggedInId = Auth::user()->id;
            $customer = Customer::whereHas('user', function ($q) use ($loggedInId) {
                $q->where('id', $loggedInId);
            })->select(['id','user_id','pin'])->first();

            $pincode = $customer->pin;
            
        }
        return $pincode;
    }

    public function loggedInUserAddress(){
        $returnData=[];
        if( Auth::check() ){

            $returnData = Customer::whereHas('user', function ($q) {
                        $q->where(['role'=>'customer','id'=>Auth::user()->id ]);
             })->first();
        }

         return $returnData;
    }
}
