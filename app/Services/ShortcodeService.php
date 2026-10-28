<?php

namespace App\Services;

use App\Helpers\ShortcodeHelper;

class ShortcodeService
{
    protected $shortcodes;
    public $getProductDetails;
    public $tokenForPasswordUpdate;
    public $catslug;
    protected $cartService;

    public function __construct(CartService $cartService)
    {
         $this->cartService = $cartService;
        $this->shortcodes = [
            '[BLOCK-NEWS_LETTER]'  => [ShortcodeHelper::class, 'renderNewletter'],
            '[BLOCK-HOME_SLIDER]'  => [ShortcodeHelper::class, 'renderHomeSlider'],
            '[BLOCK-CONTACT_FORM]' => [ShortcodeHelper::class, 'renderContactForm'],
            '[BLOCK-TESTIMONIALS]' => [ShortcodeHelper::class, 'renderTestimonials'],

            '[BLOCK-LATEST_PROD]' => function () {
                return ShortcodeHelper::productsBlock(['type' => 'L', 'limit' => 6]);
            },
            '[BLOCK-PRODUCT_NAME]' => function () {
                $slug = $this->getProductDetails;
                return ShortcodeHelper::productsBlock(['type' => 'PROD-NAME', 'slug' => $slug]);
            },
            '[BLOCK-PROD_DETAILS]' => function () {
                $slug = $this->getProductDetails;
                $isWishListed = $this->cartService->wishlistCount($slug);
                return ShortcodeHelper::productsBlock(['type' => 'D', 'slug' => $slug ,'is_wishlisted'=>$isWishListed]);
            },

            '[BLOCK-RELATED_PROD]' => function () {
                $slug = $this->getProductDetails;
                return ShortcodeHelper::productsBlock(['type' => 'R', 'limit' => 6, 'slug' => $slug]);
            },

            '[BLOCK-ALL_PROD]' => function () {
                //$slug = request()->route('slug'); // category slug
                return ShortcodeHelper::productsBlock(['type' => 'A']);
            },
            '[BLOCK-SORT_FILTER]' => function(){
                return ShortcodeHelper::generalBlock(['type' => 'sort-filter']);
                
            },
            '[BLOCK-PRICE_FILTER]' => function(){
                return ShortcodeHelper::generalBlock(['type' => 'price-filter']);
            },
            '[BLOCK-CART]' => function () {
              //  $this->cartService->emptyMyCart();
              $myCart = $this->cartService->getCartItems();
            //  dd($myCart);
                $ifLoggedIn = $this->cartService->getLoggedInPin();
                return ShortcodeHelper::cartBlock(['items'=> $myCart,'logged_in_pin'=>$ifLoggedIn]);

            },
            '[BLOCK-LATEST_CATEGORY]' => function () {
                return ShortcodeHelper::categoryBlock(['type' => 'L', 'limit' => 6, 'parent' => 'Y']);
            },
            '[BLOCK-ALL_CATEGORY]' => function () {
                return ShortcodeHelper::categoryBlock(['type' => 'A']);
            },
            '[BLOCK-ALL_CATEGORY_PAGE]' => function () {
                return ShortcodeHelper::categoryBlock(['type' => 'L', 'limit' => 500, 'parent' => 'Y']);
            },
            '[BLOCK-MAP]' => function () {
                return ShortcodeHelper::generalBlock(['type' => 'MAP']);
            },
            '[BLOCK-THANKS]' => function () {
                return ShortcodeHelper::generalBlock(['type' => 'thanks']);
            },
            '[BLOCK-CHECKOUT]' => function () {
                //$getCartItems = $this->cartService->emptyMyCart(); 
                 $getCartItems = $this->cartService->getCartItems();
                 //$shippingCharge = $this->cartService->shippingCharges('0'); 
                 $shippingCharge['charges'] = 0;
                 $loggedInUserAddress = $this->cartService->loggedInUserAddress();
                 //return $shippingCharge;
                 // initialy zero if no one stored
                return ShortcodeHelper::checkoutBlock(['cart_items'=>$getCartItems,
                    'shipping_charges'=>$shippingCharge['charges'],
                    'user_address'=>$loggedInUserAddress]);
            },
            '[BLOCK-LOGIN_FORM]'=>function () {
                return ShortcodeHelper::generalBlock(['type' => 'login']);
            },
            '[BLOCK-UPDATE_PASSWORD]'=>function () {
                $token = $this->tokenForPasswordUpdate;
                return ShortcodeHelper::generalBlock(['type' => 'forgetpassform','token'=>$token]);
            },
            '[BLOCK-CATEGORY_NAME]'=>function () {
                $catslug = $this->catslug;
                return ShortcodeHelper::categoryBlock(['type' => 'cat_name','cat_slug'=>$catslug]);
            },
            '[BLOCK-ALL_CAT_PROD]' => function () {
                $catslug = $this->catslug;
                return ShortcodeHelper::productsBlock(['type' => 'A','cat_slug'=>$catslug]);
            },
           
            
            
        ];
    }

    public function parse(?string $content , ?string $prodSlug , ?string $token , ?string $catslug): string{
    // Ensure $content is a string
    $this->getProductDetails = $prodSlug;
    $this->tokenForPasswordUpdate = $token;
    $this->catslug = $catslug;
    
    $content = (string) $content;
    


    foreach ($this->shortcodes as $tag => $callback) {
        if (strpos($content, $tag) !== false) {
            $replacement = call_user_func($callback);

            // Ensure the replacement is also string
            $replacement = is_string($replacement) ? $replacement : (string) $replacement;

            $content = str_replace($tag, $replacement, $content);
        }
    }

    return $content; // Always string
}

}
