<?php

use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CustomerController;

// ===============================
// Frontend Routes
// ===============================
// use Illuminate\Support\Facades\Artisan;
// Route::get('/clear-all', function () {
//     Artisan::call('config:clear');
//     Artisan::call('cache:clear');
//     Artisan::call('view:clear');
//     Artisan::call('route:clear');
//     return 'All cache cleared!';
// });

Route::middleware(['validAdmin:admin,manager', 'precvenBack'])->group(function () {
    Route::prefix('manage')->group(function () {
        Route::get('orders', [SalesController::class, 'index'])->name('manage.orders');
        Route::get('new-order', [SalesController::class, 'newOrder'])->name('manage.new-order');
        Route::post('findproduct', [SalesController::class, 'findProduct'])->name('findproduct');
        Route::post('save-order', [SalesController::class, 'saveOrder'])->name('save-order');
        Route::post('place-order', [SalesController::class, 'placeOrder'])->name('placeOrder');
        Route::get('thank-you/{ordernumber}', [SalesController::class, 'thankYou'])->name('thank-you');
        Route::post('orderlist', [SalesController::class, 'orderlist'])->name('orderlist');
        Route::get('view/{ordernumber}', [SalesController::class, 'orderlist'])->name('manage.view');
        Route::post('customerinfo', [SalesController::class, 'customerInfo'])->name('manage.customerinfo');
        Route::get('invoice/{ordernumber}/{mode}', [SalesController::class, 'downloadInvoice'])->name('manage.invoicedownload');
        Route::post('order-return', [SalesController::class, 'orderReturn'])->name('manage.order-return');
        Route::get('order-edit/{ordernumber}', [SalesController::class, 'orderEdit'])->name('manage.order-edit');
        Route::put('update-order-details/{ordernumber}', [SalesController::class, 'orderDetailsUpdate'])->name('manage.update-order-details');
    
    });
});
// Product details page
// Route::get('/product-details/{slug?}', [FrontendController::class, 'page'])
//     ->where('prodCatSlug', '.*')
//     ->name('product-details');
Route::get('/product-details/{slug?}', [FrontendController::class, 'page'])
    ->where('slug', '.*')
    ->name('product-details');

Route::get('/category-products/{slug?}', [FrontendController::class, 'page'])
    ->where('slug', '.*')
    ->name('category-products');
Route::get('/forget-pass-form/{slug?}', [FrontendController::class, 'page'])
    ->where('slug', '.*')
    ->name('forget-pass-form');
    
// Page redirect
Route::get('/page/{slug?}', function ($slug = null) {
    return redirect($slug ? "/{$slug}" : "/", 301);
})->where('slug', '.*')->name('page');

// Category page
// Route::get('category/{slug?}', [FrontendController::class, 'categories'])
//     ->name('category');

// Contact / Order / Ajax
Route::post('contact-form', [FrontendController::class, 'contactFormSubmit'])
    ->name('contact-form');
    
Route::post('placeorder', [FrontendController::class, 'placeOrder'])
    ->name('placeorder');
Route::post('ajaxcall', [FrontendController::class, 'ajaxActions'])
    ->name('ajaxcall');

// Customer login & registration
Route::post('customer-login', [CustomerController::class, 'customerLogin'])
    ->name('customer-login');
Route::post('customer-registration', [CustomerController::class, 'customerRegistration'])
    ->name('customer-registration');
    

Route::get('customer-logout', [CustomerController::class, 'customerLogout'])
    ->name('customer-logout');

Route::post('/customer-forget-password', [CustomerController::class, 'forgetPassword'])
->name('customer-forget-password');

Route::post('/updatemypass/{slug}', [CustomerController::class, 'updateMyPass'])
->name('updatemypass');

// Route::get('/forget-my-password/{slug}', [CustomerController::class, 'forgetPassForm'])
// ->name('forget-my-password');


// ===============================
// Customer Protected Routes
// ===============================
Route::prefix('user')->middleware(['customer','precvenBack'])->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])
        ->name('users.dashboard');
    Route::get('/order-view/{order_number}', [CustomerController::class, 'orderView'])
        ->name('users.view');  
    Route::get('/wishlist', [CustomerController::class, 'wishlist'])
        ->name('users.wishlist');
    Route::get('/cancel-request/{order_number}', [CustomerController::class, 'cancelOrderRequest'])->name('users.cancel-request');   
    Route::get('/profile', [CustomerController::class, 'myProfile'])->name('users.profile');
    Route::get('/password', [CustomerController::class, 'myPassword'])->name('users.password');
  

    Route::put('/password-update', [CustomerController::class, 'updatePassword'])->name('users.password-update');
    Route::put('/profile-update', [CustomerController::class, 'updateMyProfile'])->name('users.profile-update');
    
});
Route::prefix('user')->middleware(['customer'])->group(function () {
    Route::get('/download-invoice/{order_number}', [CustomerController::class, 'downloadInvoice'])->name('users.download-invoice');   
});

// Admin & Manager routes
require __DIR__ . '/admin-route.php';
require __DIR__ . '/manager-route.php';

// Catch-all for CMS pages (LAST)
Route::get('/{slug?}', [FrontendController::class, 'page'])
    ->where('slug', '^(?!admin|manager|manage|api|product-details|category-products|forget-pass-form).*$');