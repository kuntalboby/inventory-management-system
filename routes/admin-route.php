<?php
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\ManagerController;
use App\Http\Controllers\admin\MastersController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\PagesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['ifLoggedIn'])->group( function(){
    Route::get("/admin", function () {
        return view("admin.login");
    })->name("adminlogin");
    Route::get("signup",function(){
        return view("admin.signup"); 
    })->name("adminsignup");
    Route::get("admin/forgotPassword",function(){
        return view("admin.forgotpassword");
    })->name("admin.forgotPassword");
    
    Route::get("admin/otpVerification/{token}",function(string  $token){
        return view("admin.verify-otp")->with('token',$token);
    })->name("admin.otppage");
});


Route::get('auto-stock-report',[ProductsController::class,"dailyStockPdfGenerator"]);

/* Post Methods */
Route::post("adminlogin",[LoginController::class,"login"]);
Route::post("adminsignup",[LoginController::class,"signup"]);
Route::post("admin/verifyuseremail",[LoginController::class,"verifyuseremail"])->name('admin.verifyuseremail');
Route::post("admin/adminOtpVerification",[LoginController::class,"adminOtpVerification"])->name('admin.verifyotp');

Route::get("login/changepassword/{token}",function(string  $token){
    return view("admin.changepasswordform")->with('token',$token);
})->name("login.changepassword");

Route::put("login/updateloginpass",[LoginController::class,"updateloginpass"])->name('admin.updateloginpass');


/* middleware Grp */
Route::middleware(['validAdmin:admin'])->group( function(){
    Route::get("admin/masters/report",[MastersController::class,'reports'])
    ->name('admin.reports');
    

 Route::post("admin/masters/create-report",[MastersController::class,'reportGenerate'])
    ->name('admin.masters.create-report');


    
    Route::get("admin/create-report",[MastersController::class,'ajaxReportGenerate'])
    ->name('admin.create-report');

     Route::get('admin/masters/download-excel', function () {
        return response()->download(storage_path('app/public/files/bhmja25pn87cd01.xlsx'));
    })->name('admin.masters.download-excel');

});

// *********************
Route::middleware(['validAdmin:admin','precvenBack'])->group( function(){
    Route::get("admin/logout",[LoginController::class,'logout'])->name("admin.logout");
    Route::get("admin/dashboard",[AdminController::class,'dashboard'])->name("admin.dashboard");
    Route::get("admin/profile",[AdminController::class,'profile'])->name("admin.profile");
    Route::get("admin/siteinfo",[AdminController::class,'siteinfo'])->name("admin.siteinfo");
    
        Route::get("admin/change-password",function(){
            return view("admin.change-password");
        })->name("admin.password");
    
    Route::put("admin/updatepassword",[AdminController::class,'updatepassword'])->name('admin.updatepassword');
    Route::put("admin/updateinfo",[AdminController::class,'updateinfo'])->name('admin.updateinfo');
    Route::put("admin/updateprofile",[AdminController::class,'updateprofile'])->name('admin.updateprofile');



//manager Management
   Route::get("admin/managers/",[ManagerController::class,'index'])
        ->name('admin.managers.index');

        Route::get("admin/managers/manager-edit/{id}",[ManagerController::class,'managerEdit'])
        ->name('admin.managers.manager-edit');
 
    Route::get("admin/managers/manager-add",function(){
        return view("admin.managers.manager-add");
    })->name('admin.managers.manager-add');
    Route::post("admin/managers/manager-add",[ManagerController::class,"managerAdd"])->name('admin.managers.managerAdd');

    Route::put("admin/managers/managerUpdate/{id}",[ManagerController::class,"managerUpdate"])->name('admin.managers.managerUpdate');



//  Route::get("admin/masters/warehouses",[MastersController::class,'warehouses'])
//         ->name('admin.masters.warehouses');
        
//     Route::get("admin/masters/warehouses-add",function(){
//         return view("admin.masters.warehouses-add");
//     })->name('admin.masters.warehouses-add');
//     Route::post("admin/masters/warehouses-add",[MastersController::class,"warehousesAdd"])->name('admin.masters.warehousesAdd');
//     Route::put("admin/masters/warehousesUpdate/{slug}",[MastersController::class,"warehousesUpdate"])->name('admin.masters.warehousesUpdate');

   Route::get("admin/masters/unit",[MastersController::class,'unit'])
        ->name('admin.masters.unit');
        
    Route::get("admin/masters/unit-add",function(){
        return view("admin.masters.unit-add");
    })->name('admin.masters.unit-add');
    Route::post("admin/masters/unit-add",[MastersController::class,"unitAdd"])->name('admin.masters.unitAdd');
    Route::put("admin/masters/unitUpdate/{slug}",[MastersController::class,"unitUpdate"])->name('admin.masters.unitUpdate');


    //category
    Route::get("admin/masters/categories",[MastersController::class,'categories'])
    ->name('admin.masters.categories');
    
   
    Route::get("admin/masters/categories-add",[MastersController::class,"categoriesAdd"])->name('admin.masters.categories-add');
    Route::post("admin/masters/categories-add", [MastersController::class, "categoryStore"])
    ->name('admin.masters.categories.store');
    Route::get("admin/masters/categories-edit/{slug}",[MastersController::class,'CategoriesEdit'])->name('admin.masters.categories-edit');
    Route::get("admin/masters/categories-delete/{slug}",[MastersController::class,'delete'])->name('admin.masters.categories-delete');
    Route::put("admin/masters/categoriesUpdate/{slug}",[MastersController::class,"categoriesUpdate"])->name('admin.masters.categoriesUpdate');


    //unit

    Route::get("admin/masters/unit",[MastersController::class,'unit'])
    ->name('admin.masters.unit');
    
   
    Route::get("admin/masters/unit-add",[MastersController::class,"unitAdd"])->name('admin.masters.unit-add');
    Route::post("admin/masters/unit-add", [MastersController::class, "unitStore"])
    ->name('admin.masters.unit.store');
    Route::get("admin/masters/unit-edit/{slug}",[MastersController::class,'unitEdit'])->name('admin.masters.unit-edit');
    Route::get("admin/masters/unit-delete/{slug}",[MastersController::class,'delete'])->name('admin.masters.unit-delete');
    Route::put("admin/masters/unitUpdate/{slug}",[MastersController::class,"unitUpdate"])->name('admin.masters.unitUpdate');

     //warehouse

    Route::get("admin/masters/warehouses",[MastersController::class,'warehouses'])
    ->name('admin.masters.warehouses');
    
   
    Route::get("admin/masters/warehouse-add",[MastersController::class,"warehouseAdd"])->name('admin.masters.warehouse-add');
    Route::post("admin/masters/warehouse-add", [MastersController::class, "warehouseStore"])
    ->name('admin.masters.warehouse.store');
    Route::get("admin/masters/warehouse-edit/{slug}",[MastersController::class,'warehouseEdit'])->name('admin.masters.warehouse-edit');
    Route::get("admin/masters/warehouse-delete/{slug}",[MastersController::class,'delete'])->name('admin.masters.warehouse-delete');
    Route::put("admin/masters/warehouseUpdate/{slug}",[MastersController::class,"warehouseUpdate"])->name('admin.masters.warehouseUpdate');

        //brands

    Route::get("admin/masters/brands",[MastersController::class,'brands'])
    ->name('admin.masters.brands');
    
   
    Route::get("admin/masters/brand-add",[MastersController::class,"brandAdd"])->name('admin.masters.brand-add');
    Route::post("admin/masters/brand-add", [MastersController::class, "brandStore"])
    ->name('admin.masters.brand.store');
    Route::get("admin/masters/brand-edit/{slug}",[MastersController::class,'brandEdit'])->name('admin.masters.brand-edit');
    Route::get("admin/masters/brand-delete/{slug}",[MastersController::class,'delete'])->name('admin.masters.brand-delete');
    Route::put("admin/masters/brandUpdate/{slug}",[MastersController::class,"brandUpdate"])->name('admin.masters.brandUpdate');



    //customers

    Route::get("admin/masters/customers",[MastersController::class,'customers'])
    ->name('admin.masters.customers');
    
    Route::get("admin/masters/customer-view/{phone}",[MastersController::class,'customerView'])->name('admin.masters.customer-view');
    Route::post('admin/customerlist', [MastersController::class, 'customerlist'])->name('customerlist');




    
    // /
         //vendors

    Route::get("admin/masters/vendors",[MastersController::class,'vendors'])
    ->name('admin.masters.vendors');
    
   
    Route::get("admin/masters/vendor-add",[MastersController::class,"vendorAdd"])->name('admin.masters.vendor-add');
    Route::post("admin/masters/vendor-add", [MastersController::class, "vendorStore"])
    ->name('admin.masters.vendor.store');
    Route::get("admin/masters/vendor-edit/{slug}",[MastersController::class,'vendorEdit'])->name('admin.masters.vendor-edit');
    Route::get("admin/masters/vendor-view/{slug}",[MastersController::class,'vendorView'])->name('admin.masters.vendor-view');
    Route::get("admin/masters/vendor-delete/{slug}",[MastersController::class,'delete'])->name('admin.masters.vendor-delete');
    Route::put("admin/masters/vendorUpdate/{slug}",[MastersController::class,"vendorUpdate"])->name('admin.masters.vendorUpdate');

    //products
    Route::post('admin/productlist', [ProductsController::class, 'productlist'])->name('productlist');
    Route::get("admin/products",[ProductsController::class,'index'])
    ->name('admin.products');
    Route::get("admin/products/products-add",
    [ProductsController::class,"productsAdd"])->name('admin.products.products-add');
    Route::post("admin/products/storeProduct", [ProductsController::class, "productsStore"])
        ->name('storeProduct');
    Route::post("admin/autocomplete", [ProductsController::class, "autocomplete"])
    ->name('autocomplete');
        Route::post("admin/before-submit-form", [ProductsController::class, "beforSubmitForm"])
    ->name('before-submit-form');

       Route::post("admin/delete-gallery", [ProductsController::class, "deleteGallery"])
    ->name('delete-gallery');
    
    Route::get("admin/products/products-edit/{slug}",
    [ProductsController::class,"productsEdit"])->name('admin.products.products-edit');
    
    Route::get("admin/products/products-vendors/{slug}",
    [ProductsController::class,"prodVendors"])->name('admin.products.products-vendors');
    
    Route::put("admin/products/productUpdate/{slug}",[ProductsController::class,"productUpdate"])->name('admin.products.productUpdate');

    Route::post("admin/products/products-vendors/{slug}",
    [ProductsController::class,"prodVendors"])->name('admin.products.products-vendors');

    Route::post('admin/searchSession', [ProductsController::class, 'searchSession'])->name('searchSession');
    Route::post('admin/updateproductvnd', [ProductsController::class, 'updateproductvnd'])->name('updateproductvnd');

    //report

    




 Route::get("admin/masters/stickers/{sticker_code}",[MastersController::class,'stickers'])
    ->name('admin.masters.stickers');

    Route::get("admin/masters/sticker-sum",[MastersController::class,'stickerSum'])
    ->name('admin.masters.sticker-sum');
    
   Route::get("admin/masters/sticker-add", function () {
        return view("admin.masters.sticker-add");
    })->name("admin.masters.sticker-add");

Route::get("admin/masters/sticker-sold", function () {
        return view("admin.masters.sticker-sold");
    })->name("admin.masters.sticker-sold");

    
    Route::post("admin/masters/sticker-add", [MastersController::class, "stickerStore"])
    ->name('admin.masters.sticker.store');

    Route::get("admin/masters/sticker-edit/{slug}",[MastersController::class,'stickerEdit'])->name('admin.masters.sticker-edit');
    Route::put("admin/masters/stickerUpdate/{slug}",[MastersController::class,"stickerUpdate"])->name('admin.masters.stickerUpdate');
    Route::get("admin/track-logins",[AdminController::class,'trackLogins'])->name('admin.track-logins');

    Route::post('admin/get-stickers', [MastersController::class, 'getStickers'])->name('get-stickers');

    Route::get("admin/masters/pincodes",[MastersController::class,'pincodes'])
    ->name('admin.masters.pincodes');

   
    
    Route::post("admin/masters/pin-charge",[MastersController::class,'pinchargesUpdate'])
    ->name('admin.manager.pin-charge');
   
    //Modification of project 

    Route::get("admin/products/go-down-products",[ProductsController::class,'goDownProducts'])
    ->name('admin.products.go-down-products');

    Route::get("admin/products/add-godown-products",[ProductsController::class,'addGoDownProduct'])
    ->name('admin.products.add-godown-products');

    
    Route::post("admin/products/godownProductStore", [ProductsController::class, "godownProductStore"])
    ->name('storeGodownProduct');


       Route::get("admin/products/godown-products-edit/{slug}",
    [ProductsController::class,"goDownproductsEdit"])->name('admin.products.godown-products-edit');
    
    Route::post('admin/godownprodlist', [ProductsController::class, 'goDownproductlist'])->name('godownprodlist');
    Route::put("admin/products/godown-products-edit/{slug}",[ProductsController::class,"goDownProductUpdate"])->name('admin.products.goDownProductUpdate');


    Route::get("admin/products/damaged",[ProductsController::class,'damagedProducts'])
    ->name('admin.products.damaged');

    Route::get("admin/products/damaged-product-add",[ProductsController::class,'damagedProductsAdd'])
    ->name('admin.products.damaged-product-add');

     Route::get("admin/products/damaged-product-edit/{id}",[ProductsController::class,'damagedProductsEdit'])
    ->name('admin.products.damaged-product-edit');

     Route::post("admin/products/damageStore", [ProductsController::class, "damageStore"])
    ->name('admin.products.damageStore');
      Route::put("admin/products/damageUpdate/{id}", [ProductsController::class, "damageUpdate"])
    ->name('admin.products.damageUpdate');



    //

     Route::get("admin/products/damaged-packet",[ProductsController::class,'damagedPacketProducts'])
    ->name('admin.products.damaged-packet');

    Route::get("admin/products/damaged-packet-add",[ProductsController::class,'damagedPacketProductsAdd'])
    ->name('admin.products.damaged-packet-add');

     Route::get("admin/products/damaged-packet-edit/{id}",[ProductsController::class,'damagedPacketProductsEdit'])
    ->name('admin.products.damaged-packet-edit');

     Route::post("admin/products/damagePacketStore", [ProductsController::class, "damagePacketStore"])
    ->name('admin.products.damagePacketStore');
      Route::put("admin/products/damagePacketUpdate/{id}", [ProductsController::class, "damagePacketUpdate"])
    ->name('admin.products.damagePacketUpdate');
    
    
    Route::post("admin/damagedsum", [ProductsController::class, "damagedProdSum"])
    ->name('damagedsum');

    Route::post("admin/master-autocomplete", [MastersController::class, "masterAutocomplete"])
    ->name('master-autocomplete');

    Route::post("admin/check-availability", [ProductsController::class, "checkQuantity"])
    ->name('check-availability');

    Route::post("vendorprodlistview", [MastersController::class, "vendorProdView"])
    ->name('vendorprodlistview');
    Route::post("admin/product-bp-suggestion", [ProductsController::class, "productBuyingPrcSuggestion"])
    ->name('product-bp-suggestion');
 Route::post("admin/single-prod-data", [ProductsController::class, "getSingleProdData"])
    ->name('admin/single-prod-data');

    Route::post("admin/packeddamagedsum", [ProductsController::class, "damagedPackedProdSum"])
    ->name('packeddamagedsum');



    Route::post("admin/damaged-items",[ProductsController::class,'damagesItemLists'])
    ->name('admin.dameged-items');

    Route::post("admin/suggest-product",[ProductsController::class,'suggestProduct'])
    ->name('admin.suggest-product');
     Route::post("admin/suggest-stickers",[MastersController::class,'getAllStickers'])
    ->name('admin.suggest-stickers');


    //frontend 

    Route::get("admin/pages/images/{slug}",[PagesController::class,'imageDictionary'])
    ->name('admin.pages.images');
    Route::post("admin/pages/image-add",[PagesController::class,'imageAdd'])
    ->name('admin.pages.image-add');

    Route::get("admin/pages/testimonials",[PagesController::class,'testimonials'])
    ->name('admin.pages.testimonials');
    
    Route::post("admin/pages/delete-image",[PagesController::class,'imageDel'])
    ->name('admin.pages.delete-image');


    Route::get("admin/pages/testimonial-add", function () {
        return view("admin.pages.testimonial-add");
    })->name('admin.pages.testimonial-add');
    
    Route::get("admin/pages/testimonial-edit/{slug}",[PagesController::class,'testimonialEdit'])
    ->name('admin.pages.testimonial-edit');

    Route::post("admin/pages/testimonial-store",[PagesController::class,'testimonialStore'])
    ->name('admin.pages.testimonial-store');
    Route::put("admin/pages/testimonial-update/{slug}",[PagesController::class,'testimonialUpdate'])
    ->name('admin.pages.testimonial-update');
    Route::get("admin/pages/testimonial-del",[PagesController::class,'testimonialDel'])
    ->name('admin.pages.testimonial-del');
    //

    Route::get("admin/pages/",[PagesController::class,'index'])
    ->name('admin.pages');

    Route::get("admin/pages/add", function () {
        return view("admin.pages.add");
    })->name('admin.pages.add');

    
    Route::post("admin/pages/store",[PagesController::class,'pageAdd'])
    ->name('admin.pages.store');

     Route::get("admin/pages/edit/{slug}",[PagesController::class,'pageEdit'])
    ->name('admin.pages.edit');
    Route::put("admin/pages/update/{slug}",[PagesController::class,'pageUpdate'])
    ->name('admin.pages.update-page');


     Route::get("admin/pages/shortcodes", function () {
        return view("admin.pages.shortcodes");
    })->name("admin.pages.shortcodes");

    //   Route::any('{any}', function () {
    //         return redirect('/');
    //     })->where('any', '.*');
    
});




//cron Job
//  