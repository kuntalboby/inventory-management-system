<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\FrontendImage;
use App\Models\Product;
use App\Models\SiteInfo;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Session;

class ShortcodeHelper
{
    public static function productBasicQuery()
    {
        $bulkCategory = Category::where('bulk_for_backend', '1')->select('id')->first();

        return Product::with(['unit:id,name,slug'])
            ->where('quantity', '>', '0')
            ->when($bulkCategory, function ($q) use ($bulkCategory) {
                $q->whereDoesntHave('goDownProducts', function ($q2) use ($bulkCategory) {
                    $q2->where('category_id', $bulkCategory->id);
                });
            });
    }

    public static function productsBlock($params)
    {
        $products = [];
        $html = '';

        if ($params['type'] && $params['type'] === 'L') { // Latest
            $products = self::productBasicQuery()
                ->orderByDesc('created_at')
                ->limit($params['limit'])
                ->get();

            if ($products->count()) {
                $html .= '<ul class="products">';
                foreach ($products as $product) {
                    $imagePath = (!empty($product->image) && file_exists(public_path("storage/products/" . $product->image)))
                        ? asset('/storage/products/' . $product->image)
                        : asset('/storage/website-image/no-image.png');

                    $html .= '<li id="product_' . $product->product_code . '">
                        <div class="image_frame scale-with-grid product-loop-thumb">
                            <div class="image_wrapper">
                                <div class="mask"></div>
                                <img width="600" height="500" src="' . $imagePath . '" class="scale-with-grid wp-post-image" alt="" />
                                <div class="image_links double">
                                    <a href="javascript:void(0);" class="link" onclick="addTocart(\'' . $product->product_code . '\',1,\'' . $product->selling_price . '\');">
                                        <i class="icon-basket"></i>
                                    </a>
                                    <a class="link" href="' . route('product-details', $product->slug) . '">
                                        <i class="icon-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="desc">
                            <h4><a href="' . route('product-details', $product->slug) . '">' . ucwords(strtolower($product->name)) . '</a></h4>
                            <span class="price">₹' . $product->selling_price . '</span>
                        </div>
                    </li>';
                }
                $html .= '</ul>';
            }

        }elseif ($params['type'] && $params['type'] === 'PROD-NAME') {
            $products = self::productBasicQuery()
                ->select(['name'])
                ->where('slug', $params['slug'])
                ->first();
                $html =  ucwords(strtolower($products->name));
        }elseif ($params['type'] && $params['type'] === 'D') { // Details Page
            $product = self::productBasicQuery()
                ->with('goDownProducts.category:id,name,slug')
                ->with('gallery:name,product_id')
                ->where('slug', $params['slug'])
                ->first();

                $isWishListed = $params['is_wishlisted'];
                $isAddtoWith = 'Y'; $actWishlst = '';
                 if( $isWishListed > 0 ){ $isAddtoWith = 'N' ; $actWishlst='background-color: #5c762f !important; color: #fff !important;'; }

            if ($product) {
                $imagePath = (!empty($product->image) && file_exists(public_path("storage/products/" . $product->image)))
                    ? asset('/storage/products/' . $product->image)
                    : asset('/storage/website-image/no-image.png');
                    $galleryImages = '';
                if(!empty($product->gallery)){
                    foreach ($product->gallery as $key => $gallery) {
                        if( (!empty($gallery->name) && file_exists(public_path("storage/products/" . $gallery->name))) ){
                             $galleryImages .='<div class="image_frame scale-with-grid">
                                            <div class="image_wrapper">
                                                <a href="'.asset('/storage/products/' . $gallery->name).'" 
                                                    class="woocommerce-main-image zoom" title="" 
                                                        data-rel="prettyPhoto[product-gallery]">
                                                    <div class="mask"></div>
                                                    <img width="180" height="180" 
                                                    src="'.asset('/storage/products/' . $gallery->name).'" 
                                                    class="attachment-shop_thumbnail size-shop_thumbnail"
                                                    alt="'.$product->name.'" title="'.$product->name.'" 
                                                    caption="" url="'.asset('/storage/products/' . $gallery->name).'">
                                                </a>
                                            </div>
                                        </div>';  
                                }
                            }
                    }            

                $html .= '<div class="product_wrapper clearfix">
                    <div class="column one-second product_image_wrapper">
                        <div class="images">
                            <div class="image_frame scale-with-grid">
                                <div class="image_wrapper">
                                    <a href="' . $imagePath . '" class="woocommerce-main-image zoom" title="' . ucwords(strtolower($product->name)) . '">
                                        <div class="mask"></div>
                                        <img width="600" height="600" src="' . $imagePath . '" alt="' . ucwords(strtolower($product->name)) . '">
                                    </a>
                                    <div class="image_links">
                                        <a href="' . $imagePath . '" class="woocommerce-main-image zoom"><i class="icon-search"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="thumbnails columns-3">

                                '.$galleryImages.'
                               
                            </div>
                        </div>
                    </div>
                    <div class="column one-second summary entry-summary">
                        <h1 class="product_title entry-title">' . ucwords(strtolower($product->name)) . '</h1>
                        <p class="price">₹' . $product->selling_price . '</p>
                        ' . $product->description . '
                            <div class="quantity">
                                        <input type="number" 
                                            min="1" 
                                            id="details_qty_'.$product->product_code.'"  
                                            name="quantity" 
                                            value="1"
                                            min="1" 
                                            class="input-text qty text">
                                    </div>

                                    <button type="button" 
                                        onclick="addTocart(
                                            \''.$product->product_code.'\',
                                            document.getElementById(\'details_qty_'.$product->product_code.'\').value,
                                            \''.$product->selling_price.'\'
                                        )"
                                        class="single_add_to_cart_button button alt">
                                        <i class="icon-basket"></i> Add to cart
                                    </button>
                                    <button type="button"
                                        class="wishlistBtn"
                                        style="'.$actWishlst.'"
                                        onclick="addtoWishlist(
                                            \''.$product->product_code.'\',
                                            \''.$isAddtoWith.'\'
                                        )"
                                        class="single_add_to_cart_button button alt">
                                        <i class="icon-heart-fa"></i>
                                    </button>
                        <div class="product_meta">
                            <span class="posted_in">Category: <a href="'.route('category-products',$product->goDownProducts->category->slug).'">' . ucwords(strtolower($product->goDownProducts->category->name)) . '</a>.</span>
                        </div>
                    </div>
                </div>';
            }

        } elseif ($params['type'] && $params['type'] === 'R') { // Related Products
            $currentProduct = Product::where('slug', $params['slug'])
                ->select('go_down_product_id')->firstOrFail();

            // Get category_id from go_down_products (related table)
            $categoryId = $currentProduct->goDownProducts->category_id ?? null;

            $products = Product::where('slug', '!=', $params['slug'])
                ->whereHas('goDownProducts', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                })
                ->limit($params['limit'] ?? 10)
                ->get();

            if ($products->count()) {
                $html .= '<ul class="products">';
                foreach ($products as $product) {
                    $imagePath = (!empty($product->image) && file_exists(public_path("storage/products/" . $product->image)))
                        ? asset('/storage/products/' . $product->image)
                        : asset('/storage/website-image/no-image.png');

                    $html .= '<li id="product_' . $product->product_code . '">
                        <div class="image_frame scale-with-grid product-loop-thumb">
                            <div class="image_wrapper">
                                <div class="mask"></div>
                                <img width="600" height="500" src="' . $imagePath . '" class="scale-with-grid wp-post-image" alt="" />
                                <div class="image_links double">
                                    <a href="javascript:void(0);" class="link" onclick="addTocart(\'' . $product->product_code . '\',1,\'' . $product->selling_price . '\');">
                                        <i class="icon-basket"></i>
                                    </a>
                                    <a class="link" href="' . route('product-details', $product->slug) . '">
                                        <i class="icon-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="desc">
                            <h4><a href="' . route('product-details', $product->slug) . '">' . ucwords(strtolower($product->name)) . '</a></h4>
                            <span class="price">₹' . $product->selling_price . '</span>
                        </div>
                    </li>';
                }
                $html .= '</ul>';
            }else{
                $html .= 'No related Products found';
            }

        } elseif ($params['type'] && $params['type'] === 'A') { // All Products
            $isSpecificCat='N';  
            $makeActiveCat ='';
            $productsQ = self::productBasicQuery()->select(['name', 'product_code', 'selling_price', 'slug', 'image']);            
            if (isset($params['cat_slug'])) {

                $isSpecificCat = 'Y';
                $productsQ->whereHas('goDownProducts.category', function ($q) use ($params) {
                    $q->where('slug', $params['cat_slug']);
                });

                $makeActiveCat = '<div class="d-none sidebar"><snap class="active_category" data-slug="'.$params['cat_slug'].'"></snap></div>';

            }
            $products = $productsQ->orderBy('name')->get();

            if ( !empty($products) ) {
                $html .= $makeActiveCat.'<ul class="products" id="productsContainer" specific-cat="'.$isSpecificCat.'">';
                foreach ($products as $product) {
                    $imagePath = (!empty($product->image) && file_exists(public_path("storage/products/" . $product->image)))
                        ? asset('/storage/products/' . $product->image)
                        : asset('/storage/website-image/no-image.png');

                    $html .= '<li id="product_' . $product->product_code . '" class="prodList">
                        <div class="image_frame scale-with-grid product-loop-thumb">
                            <div class="image_wrapper">
                                <div class="mask"></div>
                                <img width="600" height="500" src="' . $imagePath . '" class="scale-with-grid wp-post-image" alt="" />
                                <div class="image_links double">
                                    <a href="javascript:void(0);" class="link" onclick="addTocart(\'' . $product->product_code . '\',1,\'' . $product->selling_price . '\');">
                                        <i class="icon-basket"></i>
                                    </a>
                                    <a class="link" href="' . route('product-details', $product->slug) . '">
                                        <i class="icon-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="desc">
                            <h4><a href="' . route('product-details', $product->slug) . '">' . ucwords(strtolower($product->name)) . '</a></h4>
                            <span class="price">₹' . $product->selling_price . '</span>
                        </div>
                    </li>';
                }
                $html .= '</ul>';
            }
        }

        return $html;
    }

    public static function renderHomeSlider()
    {
        $slides = FrontendImage::where('type', 'HS')->get();
             
        //return $slides;
        $html = '<div class="mfn-main-slider" id="mfn-rev-slider">
                    <div id="rev_slider_1_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-source="gallery" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
                        <div id="rev_slider_1_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.6">
                            <ul>';
                                foreach ($slides as $key=>$slide) {
                                    if ($slide->name!='' && file_exists(public_path("storage/frontend/pages/" . $slide->name))){
                                        $imgePath = asset('storage/frontend/pages/'.$slide->name);
                                    }else{
                                        $imgePath =asset('frontendassets/assets/images/no-bnr-image.png');
                                    }

                                    $html .= '<li data-index="rs-'.$key.'" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300" data-rotate="0" data-saveperformance="off" data-title="Slide" data-param1="" 
                                        data-param2="" data-param3="" data-param4="" data-param5=""
                                            data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                                                <img src="'.$imgePath.'" title="'.env('APP_NAME').'"
                                                            width="1920" height="860" data-bgposition="center center"
                                                            data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="8"
                                                            class="rev-slidebg" data-no-retina />
                                            </li>';

                                }
                                $html .= '</ul>
                        </div>
                    </div>
                </div>';

        return $html;
    }

    public static function renderContactForm()
    {
       
        return ' <form id="contactform" method="post" action="'. route('contact-form')  .'">
                    ' . csrf_field() . '
                                                        
                <div class="column one-second">
                    <input placeholder="Your name" type="text" name="name" id="name" size="40" aria-required="true" aria-invalid="false" />
                </div>
                
                <div class="column one-second">
                    <input placeholder="Your e-mail" type="email" name="email" id="email" size="40" aria-required="true" aria-invalid="false" />
                </div>
                <div class="column one">
                    <input placeholder="Subject" type="text" name="subject" id="subject" size="40" aria-invalid="false" />
                </div>
                <div class="column one">
                    <textarea placeholder="Message" name="message" id="message" style="width:100%;" rows="10" aria-invalid="false"></textarea>
                </div>
                <div class="column one">
                    <input type="submit" value="Send A Message" id="submit">
                </div>
            </form>';
    }
    public static function renderTestimonials()
    {
        $testimonials = Testimonial::orderByDesc('id')->get();
        $html = '<ul class="testimonials_slider_ul">';

        if(!empty($testimonials)){
            foreach ($testimonials as $key => $testimonial) {
                            if ($testimonial->image!='' && file_exists(public_path("storage/frontend/pages/" . $testimonial->image))){
                                $image = '<img style="width: 50px; text-align: center; margin: 0 auto; display: block;" class="img-responsive" src="'.asset('storage/frontend/pages/'.$testimonial->image).'" alt="'.$testimonial->author.'" />';
                            }else{
                                $image = '<img  style="width: 50px; text-align: center; margin: 0 auto; display: block;" src="'.asset('/storage/website-image/no-image.png').'" alt="Default Image" />';
                            }
                                
                            
            $html .= '<li id="tstml_'.$key.'">
                <div class="single-photo-img">
                    '.$image.'
                </div>
                <div class="bq_wrapper">
                    <blockquote>
                        '.$testimonial->content.'
                    </blockquote>
                </div>
                <div class="author">
                    <h5> '.$testimonial->author.'</h5><span class="company"> '.$testimonial->designation.'</span>
                </div>
            </li>';
            }
        }

       
        $html .='</ul>';

        return $html;
    }

  public static function renderNewletter(){
            return '<form id="newsLetterForm" method="post">
                    ' . csrf_field() . '
                    <input placeholder="Your e-mail" type="email" name="email" id="newsletter_email" size="80">
                    <input type="submit" value="Subscribe" id="newsLetterSbt">
                    </form>';
        }

        
public static function generalBlock($params)
{
    if ($params['type'] == 'MAP') {
        return '<div class="google-map" id="google-map-area-5a12adb89bed2" style="width:100%; height:500px">
                    &nbsp;
                </div>';
    }
    elseif ($params['type'] == 'thanks') {
        $key = 'thanks-msg';
        $message = Session::get($key);

        if (!empty($message)) {
            // Remove it right after reading so it won't show again
            Session::forget($key);

            return '<h2>Thank You</h2>
                    <h5 style="font-size:15px;line-height:15px;font-weight:500;color:#5c762f">'
                    . e($message) .
                    '</h5>';
        }else{
            return redirect()->route('page','');
        }
    }elseif($params['type'] == 'sort-filter'){
        return '
                <form class="woocommerce-ordering" method="get">
                    <select id="productFilter" name="orderby" class="orderby">
                    <option value="name">Sort by name</option>
                    <option value="popularity">Sort by popularity</option>
                    <option value="price">Sort by price: low to high</option>
                    <option value="price-desc">Sort by price: high to low</option>
                </select>
                </form>
            ';
    } elseif($params['type'] == 'price-filter'){
        $minPrice = self::productBasicQuery()->min('selling_price');
        $maxPrice = self::productBasicQuery()->max('selling_price');
        $interval = 100;
        $start = floor($minPrice / $interval) * $interval;
        $endLimit = ceil($maxPrice / $interval) * $interval;
        $ranges=[];
        for ($i = $start; $i < $endLimit; $i += $interval) {
            $ranges[] = [$i, $i + $interval];
        }

        $html ='<ul>';
            if(!empty($ranges) ){
                foreach ($ranges as $key => $range) {
                    $block = (string)$range[0].'-'.(string)$range[1];
                    $block_text = '₹'.$range[0].' - ₹'.$range[1];
                    
                    $html .='<li class="" >
                        <a href="javascript:void(0)" class="data_range"
                            data-range="'.$block.'">'.$block_text.'</a>
                    </li>';
                }
            }
        $html .='</ul>';
        return $html;
    }elseif($params['type'] == 'login'){
        $responseMsg='';
        $errors = Session::get('errors');
                    if(session('message')){
                            $responseMsg = '<div class="alertBox">
                                <p 
                                    class="text-center text text-'.session('response').'">
                                    '.session('message').'
                                </p>
                            </div>';
                        }
                        if ($errors && $errors->any()){
                            $responseMsg = '<div class="alert alert-danger"><ul>';
                                    foreach ($errors->all() as $error){
                                        $responseMsg .='<li><p class="text text-danger"> *'.$error.'</p></li>';
                                    }
                                '</ul>
                            </div>';
                        }

        $forgetPasswordFrm = '<form id="forget_password" 
                                class="formhandlingClass forget_password d-none"
                                    method="post" 
                                    action="'.route('customer-forget-password').'"
                                >
                                <h2>Forget Password</h2>
                                ' . csrf_field() . '
                                <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column">
                                        <div class="form-group">
                                            <label>Email<span>*</span></label>
                                            <input type="email" name="email" placeholder="" required="required">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="mcb-wrap-inner">
                                    <div class="column mcb-column one-fourth column_column">
                                        <div class="form-group">
                                            <input type="submit" value="Submit" id="forgetPassBtn" style="width:100%;">                                                            
                                        </div>
                                    </div>
                                </div>
                                    

                            </form>';                
        $html = $responseMsg.'<div class="customer_forms" id="loginFormSec">        
    
    '.$forgetPasswordFrm.'
    <form id="loginForm" class="formhandlingClass customer_login_form form" 
    
        method="post" 
        action="'.route('customer-login').'">
        <h2>Login</h2>
        ' . csrf_field() . '
        <div class="mcb-wrap-inner">
            <div class="column mcb-column one column_column">
                <div class="form-group">
                    <label>Email<span>*</span></label>
                    <input type="email" name="email" placeholder="" required="required">
                </div>
            </div>
            <div class="column mcb-column one column_column">
                <div class="form-group">
                    <label>Password<span>*</span></label>
                    <input type="password" name="password" placeholder="" required="required">
                </div>
            </div>
        </div>
        <div class="mcb-wrap-inner">
            <div class="column mcb-column one-fourth column_column">
                <div class="form-group">
                    <input type="submit" value="Login" id="loginSubbtn" style="width:100%;">
                </div>
            </div>
            <div class="column mcb-column three-fourth column_column">
                <div class="form-group">
                    <div class="create-account" style="margin-top:10px;">
                        <input id="remember_me" name="remember" type="checkbox">
                        <label>Remember me</label>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
    <div class="mcb-wrap-inner">
                    <div class="column mcb-column one column_column forgetpsswordblock">
                        <div class="form-group">
                            <a href="javascript:void(0);" onclick="backtologinform()" 
                            id="swithloginforget"> Forget Password ? </a>
                        </div>
                    </div>
             </div>
</div>

<div class="customer_forms d-none" id="guest_form">
    <h2>Registration</h2>
    <form id="signupForm" class="formhandlingClass form" 
        action="'.route('customer-registration').'"
        method="post">
        ' . csrf_field() . '
        <div class="mcb-wrap-inner">
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>First Name<span>*</span></label>
                    <input type="text" 
                        value="'.old('first_name').'"
                        name="first_name" 
                        required="required">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Last Name<span>*</span></label>
                    <input type="text" name="last_name" 
                        value="'.old('last_name').'"
                        required="required">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" 
                        value="'.old('email').'"
                        name="email">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Phone Number<span>*</span></label>
                    <input type="number" 
                        value="'.old('phone').'"
                        name="phone" required="required">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Password<span>*</span></label>
                    <input type="password" 
                        value="'.old('password').'"
                        name="password" 
                        id="password"
                        placeholder="Password must be at least 6 characters long"
                        required="required">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Confirm Password<span>*</span></label>
                    <input type="text" name="password_confirmation" 
                        value=""
                        required="required">
                </div>
            </div>
            
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Alternative Phone</label>
                    <input type="text" 
                        value="'.old('alternative_phone').'"
                        name="alternative_phone">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Address Line 1<span>*</span></label>
                    <input type="text" 
                        value="'.old('address_line_one').'"
                        name="address_line_one" required="required">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Address Line 2</label>
                    <input type="text" 
                        value="'.old('address_line_two').'"
                        name="address_line_two">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>State<span>*</span></label>
                    <input type="text" 
                        value="'.old('state').'"
                        name="state" required="required">
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>City<span>*</span></label>
                    <input type="text" name="city" 
                        value="'.old('city').'"
                        required="required">
                </div>
            </div>
             
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Postal Code<span>*</span></label>
                    <input type="text" name="pin" 
                        id="user_pincode"
                        value="'.old('pin').'"
                        required="required">
                </div>
            </div>
        </div>
        <div class="mcb-wrap-inner">
            <div class="column mcb-column one column_column">
                <div class="form-group">
                    <input type="submit" id="signup" value="Signup" width="100%"/>
                </div>
            </div>
            
        </div>
        
    </form>
</div>
<div class="column mcb-column one column_column forgetpsswordblock">
                <div class="form-group">
        <div class="form-toggle">
            <a href="javascript:void(0);" onclick="showHide();" class="accountlink" id="toggleLink">
                Don’t have an account? Register
            </a>
        </div>
     </div>
    </div>';
return $html;


    }elseif(  $params['type'] =='forgetpassform'){
        $token = $params['token'];
        return '<form id="chngepss" class="formhandlingClass form" method="post"
        action="'.route('updatemypass',$token).'"
        >
        ' . csrf_field() . '
        <div class="mcb-wrap-inner">
            
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>New Password<span>*</span></label>
                    <input type="password" name="password" 
                    id="password"
                    placeholder="Please enter min 6 character" />
                </div>
            </div>
            <div class="column mcb-column one-second column_column">
                <div class="form-group">
                    <label>Confirm New Password<span>*</span></label>
                    <input type="text" name="password_confirmation" placeholder="Same as password"/>
                </div>
            </div>
        </div>
        <div class="mcb-wrap-inner">
            <div class="column mcb-column one-fourth column_column">
                <div class="form-group">
                    <input type="submit" value="Submit" id="forgetPassBtn" style="width:100%;">                                                            
                </div>
            </div>
        </div>
    </form>';
    }


}

      
    public static function categoryBlock( $params){
    $categories=[];
    $query = Category::where('bulk_for_backend', '0')->where('status','0');
    
    if(isset($params['type']) &&  $params['type'] ==='L'){
        $categories = $query->orderByDesc('updated_at')->limit($params['limit'])->get();

        $html = '';
                if(!empty($categories)){
                $html .= '<ul class="products">';

                foreach ($categories as $key => $category) {
                    if($category->image !='' && file_exists(public_path("storage/products/categories/" . $category->image )))
                    {
                        $imagePath = asset('storage/products/categories/'.$category->image);
                    }else{
                        $imagePath = asset('/storage/website-image/no-image.png');
                    }

                                
                    $html .='<li id="category_'.$category->slug.'">
                            <div class="image_frame scale-with-grid product-loop-thumb">
                                <div class="image_wrapper">
                                    <div class="mask">
                                    </div>
                                    <img width="600" height="500" src="'.$imagePath.'"
                                    class="scale-with-grid wp-post-image" alt="'.$category->name.'" sizes="(max-width: 600px) 100vw, 600px" />
                                    <div class="image_links">
                                        <a href="'.route('category-products',$category->slug).'" class="link">'. ucwords(strtolower($category->name)).'</a>
                                    </div>
                                </div>
                            </div>
                        </li>';
                }
                
                $html .='</ul>';
                }
    }elseif(isset($params['type']) && $params['type'] ==='A'){
        $categories = $query->orderBy('name')->get();
        $html = '';
         if(!empty($categories)){

                $html.='<ul>';
                 foreach ($categories as $category) {
                    $html.='<li id="cat_'.$category->slug.'" class="catFilter">
                        <a class="sortBycategory" href="javascript:void(0);" data-slug="'.$category->slug.'" id="categort_'.$category->slug.'">'. ucwords(strtolower($category->name)).'</a>
                    </li>';
                 }
			$html.='</ul>';
         }
    }elseif (isset($params['type']) && $params['type'] == 'cat_name') {
        $categories = $query->where('slug',$params['cat_slug'])
            ->select('name')
            ->first();
         $html = isset($categories->name)  ? $categories->name : null  ;
    }
    
      return $html;


  }

  public static function cartBlock($params){

    $counter = count($params['items']);
    $userPincode = $params['logged_in_pin'];
    $ids=[];
     $stockErr = '';
    //$productIncart=[];
    foreach ($params['items'] as $products) {
            $ids[] = $products['product_id'];
            //$productIncart[$products['product_id']] = $products;
    }
        $finalAmount = 0;
        $finalAmountInGst = 0;
        $extraTax = 0;
        $overAllTax = 100 +  $extraTax;
    
    $hideEmpy = 'block';
        $hideCart = 'none';
        $items='';
    if( $counter > 0 ){
        $hideCart = 'block';
        $hideEmpy = 'none';

            $products = self::productBasicQuery()
                ->select(['id','name','gst','slug','quantity','selling_price'])
                ->whereIn('id',$ids)
                ->orderBy('name')
                ->get();

              //dd($params['items']);
      $misMatched = 0;
      foreach ($products as $key => $product) {
                    $sl = $key + 1;
//dd($product);
                    if (isset($params['items'][$product->id])) {
                        $stockError = '';

                        $inputQty   = (float) $params['items'][$product->id]['quantity'];
                        $inputPrice = (float) $params['items'][$product->id]['price'];

                        if (
                            (float)$product->quantity < $inputQty ||
                            (float)$product->selling_price != $inputPrice
                        ) {
                            $stockError = 'revicon-info-circled text-danger';
                            $misMatched +=1;
                        }
                        $gst = $product->gst ? $product->gst : 0;
                        $sp = $inputPrice;
                        $qnt = $inputQty;
                        $prodCode = $params['items'][$product->id]['product_code'];
                        
                        $subtotal = $sp * $qnt;
                        $gstAmt = 100+$gst;
                        $subtotal = $subtotal * $gstAmt/100;
                        $finalAmount +=$subtotal;
                        
                        $items .='<tr id="product_'.$prodCode.'" class="cart_products">
                                <td class="SERIAL" data-title="Sl No."><i class="'.$stockError.'"></i>'.$sl.'</td>
                                <td class="product-des" data-title="Item">
                                    <p class="product-name"><a href="'.route('product-details', $product->slug).'">'.ucwords(strtolower($product->name)).'</a></p>
                                </td>
                                <td class="price" data-title="Rate"><span id="selling_price_'.$prodCode.'">₹ '.number_format($sp,2).'</span></td>
                                <td class="qty" data-title="Qty">
                                    <div class="input-group">
                                        <input type="number" name="data['.$prodCode.'][quantity]"
                                            id="quantity_'.$prodCode.'"
                                            data-sp="'.number_format($sp,2).'"
                                            data-gst="'.$gst.'"
                                            data-prev-qty="'.$qnt.'"
                                            min="0"
                                            data-code="'.$prodCode.'" class="input-number quantityCalc" value="'.$qnt.'">
                                    </div>
                                </td>
                                <td class="tax-amount" data-title="Tax"><span id="gst_'.$prodCode.'">'.$gst.'%</span></td>
                                <input type="hidden" name="data['.$prodCode.'][subtotal]"
                                    id="subtotal_input_'.$prodCode.'" 
                                    value="'.number_format($subtotal,2).'" />
                                <td class="total-amount" data-title="Total"><span class="cart_subtotal" id="subtotal_'.$prodCode.'" data-val="'.number_format($subtotal,2).'">₹'.number_format($subtotal,2).'</span></td>
                                <td class="action" data-title="Remove"><a href="javascript:void(0);" onclick="cartProdAction(\'' . $prodCode . '\',\'S\',\'\');"><i class="icon-trash-line"></i></a></td>
                            </tr>';
                }
                
        }
       
        if($misMatched > 0 ){
            $stockErr = '<p class="text text-danger text-center">Some product prices or quantities have changed and are marked in red. Please remove them from your cart.</p>';
        }
          $finalAmountInGst =   number_format($finalAmount * $overAllTax/100 , 2);

     }
     //dd($products);
       $html = '
       <div class="ajax_response" id="ajax_response">'.$stockErr.'</div>
       <div class="wrap mcb-wrap one  valign-top clearfix" id="cartContainer"
       style="display:'.$hideCart.'">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column ">
                                            <div class="column_attr clearfix">
                                            <form id="carTform" method="post">
                                                    <table class="table shopping-summery">
                                                        <thead>
                                                            <tr class="main-hading">
                                                                <th>SL NO.</th>
                                                                <th>ITEMS</th>
                                                                <th class="text-center">RATE</th>
                                                                <th class="text-center">QUANTITY</th>
                                                                <th class="text-center">TAX</th> 
                                                                <th class="text-center">TOTAL</th> 
                                                                <th class="text-center"><i class="icon-trash-line"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            '.$items.'
                                                        </tbody>
                                                    </table>
                                                    <div class="submit_btn d-none">
                                                        <input type="submit" id="proced_order" />
                                                    </div>
                                                </form>
												<!--/ End Shopping Summery -->
											</div>
                                        </div>
                                    </div>
									<div class="mcb-wrap-inner total-amount">
										<div class="column mcb-column three-fourth column_column">
											<div class="column_attr clearfix"></div>
										</div>
										<div class="column mcb-column one-fourth column_column">
											<div class="column_attr clearfix">
												<div class="right">
													<ul>
														<li>Cart Subtotal<span id="final_amount" data-final="'.number_format($finalAmount,2).'">₹'.number_format($finalAmount,2).'</span></li>
														<li>Tax<span id="overall_gst" data-extra-gst="'.number_format($extraTax,2).'">'.$extraTax.'%</span></li>
														<li class="last">You Pay<span id="final_ing_gst" data-final-inc-gst="'.number_format($extraTax,2).'">₹'.$finalAmountInGst.'</span></li>
													</ul>
													<div class="button5">
                                                        <div class="pincodeInput">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                    name="pincode"
                                                                    value="'.$userPincode.'"
                                                                    id="user_pincode"
                                                                    placeholder="Please enter address pin code"
                                                                    maxlength="6"
                                                                    minlength="6"
                                                                     class="input-number"
                                                                     />
                                                            </div>
                                                        </div>
														<a class="button button_size_2 button_js" data-page="cart" id="redirectToPlaceOrder" href="javascript:void(0);">Checkout</a>
														<a class="button button_size_2 button_js" href="'. route('page','product').'">Continue shopping</a>
													</div>
												</div>
											</div>
										</div>
									</div>
                                </div>';
                                
                                $html .='<div class="wrap mcb-wrap one  valign-top clearfix" 
        style="display:'.$hideEmpy.'" id="emptyCartBox">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column ">
                                            <div class="column_attr clearfix">
                                                <div class="emptyCart">
                                                    Oops! You have no products in your cart!
                                                </div>
                                                <div class="button5">
														<a class="button button_size_2 button_js" href="'.route('page','product').'">Continue shopping</a>
													</div>
                                            </div>
                                        </div>
                                    </div>
                    </div>';

              return $html;
          

     
  }


  
 public static function checkoutBlock($sessionParams){
        //dd($sessionParams);
        $getCartItems = $sessionParams['cart_items'];
        $loggeduserAddrs = $sessionParams['user_address'];
        $counter = count($getCartItems);
        if($counter > 0 ){

            $finalAmount = 0;
            $finalAmountInGst = 0;
            $extraTax = 0;
            $shippingCharges =  0;
            $overAllTax = 100 +  $extraTax;
            $freeShippingAfter = env('FREE_SHIPPING_CHARGE_AFTER');

            foreach ($getCartItems as $key => $product) {
                $sl = $key+1;
                           

                    $singleProdChecking = self::productBasicQuery()
                        ->select(['id','name','gst','slug','selling_price','product_code'])
                        ->where('quantity', '>=' , $product['quantity'])
                        ->where([
                            'id'=>$product['product_id'],
                            'selling_price'=>$product['price'],
                            'product_code'=> $product['product_code'],
                            'gst'=> $product['product_gst']
                        ]);
                        
                        //checked all firlds in session;
                             
                $getdt = $singleProdChecking->first();
                if( $getdt ){
                    $getEachProd = $getdt;

                    //dd($getEachProd);


                        $gst = $getEachProd->gst ? $getEachProd->gst : 0;
                        $sp = $getEachProd->selling_price;
                        $qnt = $product['quantity'];
                        $prodCode = $getEachProd->product_code;
                        
                        $subtotal = $sp * $qnt;
                        $gstAmt = 100+$gst;
                        $subtotal = $subtotal * $gstAmt/100;
                        $finalAmount +=$subtotal;                        
                
                }else{
                    $errorProd[$key]['product_code'] = '';
                }
        }

      
          $finalAmountInGst =   round($finalAmount * $overAllTax/100,2);
          $errorMsgBox = '';
           $errors = Session::get('errors');
                         if(session('message')){
                            $errorMsgBox = '<div class="alertBox">
                                <p class="text-center text text-'.session('response').'">
                                    '.session('message').'
                                </p>
                            </div>';
                        }
                        if ($errors && $errors->any()){
                            $errorMsgBox = '<div class="alert alert-danger"><ul>';
                            foreach ($errors->all() as $error){
                                $errorMsgBox .= '<li><p class="text text-danger"> *'.$error.'</p></li>';
                            }
                            $errorMsgBox .= '</ul></div>';
                        }
                        
            
    $loginBlock = '';

    $amount_calc = '<ul>
                        <li>Sub Total<span
                        id="finalSubtotal"
                        data-shubt="'.$finalAmountInGst.'"
                        >₹'.$finalAmountInGst.'</span></li>
                        <li>(+) Shipping<span
                            id="shipping_charges"
                             data-shipping-charge="'.$shippingCharges.'"
                             >₹'.$shippingCharges.'</span></li>
                          <li>(-) Free Shipping After<span
                            id="free_shipping"
                             data-free-shipping="'.$freeShippingAfter.'"
                             >₹'.$freeShippingAfter.'</span></li>    
                        <li class="last">Total<span
                            id="needToPay"
                            data-f-amount="'.$finalAmountInGst.'"
                        >₹'.$finalAmountInGst.'</span></li>
                    </ul>';
        $new_acc_opt = ''; // no create new account option for loggedin
    if( empty($loggeduserAddrs) ){
        $new_acc_opt = '<div class="column mcb-column one column_column">
															<div class="form-group create-account">
																<input id="create_new_account" 
                                                                                    name="create_new_account"
                                                                                    value="Y"
                                                                                    checked="checked"
                                                                                    type="checkbox" />
																<label>Create an account?</label>
															</div>
														</div>';

        $loginBlock = '<div class="checkout-form" id="checkout_loginForm">
													<h2>Returning customer? <a href="javascript:void(0);"
                                                        onclick="showHide();"
                                                    >Click here to login</a></h2>
													<div id="loginFormSec" class="lginReg d-none">
													<p>Please Enter detail bellow.</p>
                                                    <form id="forget_password" 
                                                        class="formhandlingClass form forget_password d-none"
                                                         method="post" 
                                                         action="'.route('customer-forget-password').'"
                                                        >
                                                        ' . csrf_field() . '
                                                        <div class="mcb-wrap-inner">
                                                             <div class="column mcb-column one column_column">
                                                                <div class="form-group">
                                                                    <label>Email<span>*</span></label>
                                                                    <input type="email" name="email" placeholder="" required="required">
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="mcb-wrap-inner">
                                                            <div class="column mcb-column one-fourth column_column">
                                                                <div class="form-group">
                                                                    <input type="submit" value="Submit" id="forgetPassBtn" style="width:100%;">                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                         

                                                    </form>
													<form id="loginForm" class="formhandlingClass form customerLogin" 
                                                        method="post" 
                                                        action="'.route('customer-login').'">
                                                        ' . csrf_field() . '
													<div class="mcb-wrap-inner">
                                                        <div class="column mcb-column one-second column_column">
                                                                <div class="form-group">
                                                                    <label>Email<span>*</span></label>
                                                                    <input type="email" name="email" placeholder="" required="required">
                                                                </div>
                                                            </div>
                                                            <div class="column mcb-column one-second column_column">
                                                                <div class="form-group">
                                                                    <label>Password<span>*</span></label>
                                                                    <input type="Password" name="password" placeholder="" required="required">
                                                                </div>
                                                            </div>
                                                        </div>
													<div class="mcb-wrap-inner">
                                                    <div class="column mcb-column one-fourth column_column">
                                                        <div class="form-group">
                                                            <input type="submit" value="Login" id="submit" style="width:100%;">                                                            
                                                        </div>
                                                    </div>
                                                    <div class="column mcb-column three-fourth column_column">
                                                            <div class="form-group">
                                                                <div class="create-account" style="margin-top:10px;">
                                                                <input id="cbox" type="checkbox" name="remember" />
                                                                <label for="cbox">Remember me</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
												</form>
                                                <div class="mcb-wrap-inner">
                                                            <div class="column mcb-column one-fourth column_column">
                                                                <div class="form-group">
                                                                   <a href="javascript:void(0);" onclick="backtologinform()" 
                                                                   id="swithloginforget"> Forget Password ? </a>
                                                                </div>
                                                            </div>
                                                </div>
                                                </div>
											</div>';

        }
        return '<div class="section mcb-section shop checkout" style="padding-top:0px; padding-bottom:20px;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one  valign-top clearfix">
                                    <div class="mcb-wrap-inner total-amount">
                                   
										<div class="column mcb-column two-third column_column">
											<div class="column_attr clearfix">
                                                '.$errorMsgBox.'
                                                '.$loginBlock.'
												<div class="checkout-form" id="guest_form">
												<h2>Make Your Checkout Here</h2>
												<p>Please register in order to checkout more quickly</p>
												
												<form id="placeOrderForm" class="formhandlingClass from" 
                                                        action="'.route('placeorder').'"
                                                        method="post">
                                                        ' . csrf_field() . '
													<div class="mcb-wrap-inner">
														<div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>First Name<span>*</span></label>
                                                                                <input type="text" 
                                                                                value="'.old('first_name', 
                                                                                    isset($loggeduserAddrs->first_name) ? $loggeduserAddrs->first_name : '' ).'"
                                                                                name="first_name" placeholder="" 
                                                                                required="required">
                                                                            </div>
                                                                        </div>
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>Last Name<span>*</span></label>
                                                                                <input type="text" name="last_name" 
                                                                                placeholder="" 
                                                                                value="'.old('last_name',isset($loggeduserAddrs->last_name) ? $loggeduserAddrs->last_name : '' ).'"
                                                                                required="required">
                                                                            </div>
                                                                        </div>
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>Email Address<span></span></label>
                                                                                <input type="email" 
                                                                                value="'.old('email',isset($loggeduserAddrs->email) ? $loggeduserAddrs->email : '' ).'"
                                                                                name="email" placeholder="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>Phone Number<span>*</span></label>
                                                                                <input type="text" 
                                                                                value="'.old('phone',isset($loggeduserAddrs->phone) ? $loggeduserAddrs->phone : '' ).'"
                                                                                name="phone" placeholder="" required="required">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>Alternative Phone<span></span></label>
                                                                                <input type="text" 
                                                                                value="'.old('alternative_phone', isset($loggeduserAddrs->alternative_phone) ? $loggeduserAddrs->alternative_phone : '' ).'"
                                                                                name="alternative_phone" placeholder="">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>Address Line 1<span>*</span></label>
                                                                                <input type="text" 
                                                                                value="'.old('address_line_one',isset($loggeduserAddrs->address_line_one) ? $loggeduserAddrs->address_line_one : '' ).'"
                                                                                name="address_line_one" placeholder="" required="required">
                                                                            </div>
                                                                        </div>
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>Address Line 2<span></span></label>
                                                                                <input type="text" 
                                                                                value="'.old('address_line_two' ,isset($loggeduserAddrs->address_line_two) ? $loggeduserAddrs->address_line_two : '' ).'"
                                                                                name="address_line_two" 
                                                                                    placeholder="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>State<span>*</span></label>
                                                                                <input type="text" 
                                                                                value="'.old('state' , isset($loggeduserAddrs->state) ? $loggeduserAddrs->state : '' ).'"
                                                                                name="state" placeholder="" required="required">
                                                                            </div>
                                                                        </div>
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>City<span>*</span></label>
                                                                                <input type="text" name="city" 
                                                                                value="'.old('city',isset($loggeduserAddrs->city) ? $loggeduserAddrs->city : '' ).'"
                                                                                placeholder="" required="required">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="column mcb-column one-second column_column">
                                                                            <div class="form-group">
                                                                                <label>Postal Code<span>*</span></label>
                                                                                <input type="text" name="pin" 
                                                                                id="user_pincode"
                                                                                value="'.old('pin', isset($loggeduserAddrs->pin) ? $loggeduserAddrs->pin : '' ).'"
                                                                                placeholder="" required="required">
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden"
                                                                        name="final_amount_aftercal"
                                                                        id="final_amount_aftercal"
                                                                        value="" />
                                                                '.$new_acc_opt.'
                                                        <div class="d-none">
                                                            <input type="submit" id="placeOrdrBtn" />
                                                        </div>    
													</div>
												</form>
												<!--/ End Form -->
											</div>
											</div>
										</div>
										<div class="column mcb-column one-third column_column">
											<div class="column_attr clearfix">
												<div class="order-details">
													<!-- Order Widget -->
													<div class="single-widget">
														<h2>CART  TOTALS</h2>
														<div class="content">
															'.$amount_calc.'
														</div>
													</div>
													<!--/ End Order Widget -->
													<!-- Order Widget -->
													<div class="single-widget">
														<h2>Payments</h2>
														<div class="content">
															<div class="checkbox">
																<label class="checkbox-inline" for="2">
                                                                <input name="news" id="cod" disabled type="checkbox" checked> Cash On Delivery</label>
															</div>
														</div>
													</div>
													<!--/ End Order Widget -->
													
													<!-- Button Widget -->
													<div class="single-widget get-button">
                                                                    
                                                                        <div class="content">
                                                                            <p>Verify you’re human</p>
                                                                            <div class="captcha-container">
                                                                                <span id="captchaQuestion"></span>
                                                                                <input type="number" id="captchaAnswer" placeholder="Enter answer" required>
                                                                                <button type="button" id="refreshCaptcha">↻</button>
                                                                            </div>
                                                                            <div class="button">
                                                                            <a class="button button_size_2 button_js"
                                                                                        id="placeOrderBtn"
                                                                                       href="javascript:void(0);">Place Order
                                                                            </a>
                                                                            
                                                                            </div>
                                                                        </div>
                                                                    </div>
													<!--/ End Button Widget -->
												</div>
											</div>
										</div>
									</div>
                                </div>
                            </div>
                        </div>';

        }else{
             return redirect()->route('page','product');
         }
        
        
 }

}
