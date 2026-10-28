<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\ParentController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\DamagedProductsDetail;
use App\Models\GoDownProduct;
use App\Models\GoDownProductDetail;
use App\Models\Product;
use App\Models\ProductAttributesPrice;
use App\Models\ProductDetail;
use App\Models\ProductGallery;
use App\Models\ProductVendor;
use App\Models\TempProductImage;
use App\Models\Unit;
use App\Models\UnitCategory;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ProductsController extends ParentController
{
    //
    public function index(Request $request){
        
       
        $categories = Category::orderBy('parent_id')->select(['id','name','parent_id'])->orderBy('name')->get(); // Get all categories
        $allCategories = Category::getCategoryTree($categories);
        $getInfo['allCategories'] = $allCategories;
        $getInfo['searchReslt'] = $this->handleSession(array('mode'=>'R','key'=>'prod-idx'));
        //return $getInfo['goDownProducts'];
        return view('admin.products.index',compact('getInfo'));
    }
    
  public function productlist(Request $request)
{
    if ($request->ajax()) {
        $requestedPostsData = $request->all();

        $draw = $requestedPostsData['draw'];
        $row = $requestedPostsData['start'];
        $rowperpage = $requestedPostsData['length'];
        $columnIndex = $requestedPostsData['order'][0]['column'];
        $columnName = $requestedPostsData['columns'][$columnIndex]['data'];
        $columnSortOrder = $requestedPostsData['order'][0]['dir'];

        
        $query = Product::whereHas('goDownProducts')
            ->with([
                'goDownProducts:id,name,slug',
                'unit:id,name,slug',
                ]);

               

           $query->whereHas('goDownProducts', function ($q) use ($request) {
                if (isset($request->category_id) && !empty($request->category_id)) {
                    // Get selected category + all its child IDs
                    $categoryIds = Category::where('id', $request->category_id)
                        ->orWhere('parent_id', $request->category_id)
                        ->pluck('id')
                        ->toArray();

                    $q->whereIn('category_id', $categoryIds);
                }


            if ( isset($request->name) && !empty($request->name) ) {
                $q->where('name', 'like', '%' . $request->name . '%');
            }
        });

        
        $fieldsArry = [
            'id', 'name', 'go_down_product_id', 'image', 'selling_price', 'buying_price', 'product_code',
            'description', 'unit_id', 'packet_size', 'quantity', 'slug'
        ];

        $productsCollection = $query->select($fieldsArry)
            ->orderBy($columnName, $columnSortOrder)
            ->get();

        

        $totalRecords = Product::whereHas('goDownProducts')->count();
        $totalFiltered = $productsCollection->count();

        // Pagination manually after filtering
        $paginatedProducts = $productsCollection->slice($row, $rowperpage);

        $products = [];

       // return $paginatedProducts;

        foreach ($paginatedProducts as $i => $rowVal) {
           
            $packet_size = $rowVal->packet_size;
            $quantity = $rowVal->quantity;
            $unitName = $rowVal->unit->name;
            $products[$i]['name'] =  ucwords(strtolower($rowVal->name));
            $products[$i]['packet'] = $packet_size.' '.$unitName;
            $products[$i]['quantity'] = $quantity;
            $products[$i]['total'] = $packet_size * $rowVal->quantity .' '.$unitName;
            $products[$i]['buying_price'] = '₹ '.$rowVal->buying_price;
            $products[$i]['selling_price'] = '₹ '.$rowVal->selling_price;
            $products[$i]['URL'] = '<a class="text text-warning" href="' . route('admin.products.products-edit', [$rowVal->slug]) . '">
                                        <i class="fa fa-pen"></i></a> &nbsp; &nbsp;';
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFiltered,
            "data" => $products
        ]);
    }
}


    public function productsEdit(Request $request , string $slug ){
            $getInfo['product'] = Product::whereHas('goDownProducts')
                ->with('gallery')
                ->with('goDownProducts.goDownProductDetails')
                ->where('slug', $slug)
                ->firstOrFail();

                //return $getInfo['product'];

                $go_down_product_id= $getInfo['product']->go_down_product_id;
                $buyingPrSuggestion = $this->buyingPriceCalculationLogic($go_down_product_id);

        

                $getInfo['suggestion'] = $buyingPrSuggestion;
                $unit = Unit::orderBy('name')->get(); // Get all categories
                $goDownProduct = GoDownProduct::whereHas('goDownProductDetails')
                    ->with('goDownProductDetails.unit:id,name,unit_category_id')
                    ->orderBy('name')->get(); // Get all categories

            
                $getInfo['units'] = $unit;
                $getInfo['goDownProduct'] = $goDownProduct;  
               $getInfo['mode'] = 'edit';
        
               //return $getInfo;
             return view('admin.products.product-edit',compact('getInfo'));    
    }

    public function productUpdate(Request $request , string $slug){

        //return $request;
           

                     $rules = [
                        'go_down_product_id' => 'required|exists:go_down_products,id',
                        'unit_id' => 'required|exists:units,id',
                        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'], // 3mb
                        'selling_price' => 'required|min:1',
                        'buying_price' => 'required',
                        'packet_size' => 'required|min:1',
                        'quantity' => 'required|min:0.1',
                        'gallery_image'   => 'nullable|array', // input can be null OR an array of files
                        'gallery_image.*' => 'nullable|file|mimes:jpg,jpeg,png|max:3072', 
                        
                    ];

                    // Check if any vendor is submitted
                    

            $validated = Validator::make($request->all(), $rules)->validate();     
            if( $validated ){
                   

                    if ($request->file('image') != null){
                        $productImage = $request->file('image');
                        $prodImageName = $productImage->hashName();
                        $request->image->storeAs('products',$prodImageName,'public');
                        $prodImage = public_path("storage")."/products/". $request->set_image;
            
                        if(file_exists($prodImage)){
                            @unlink($prodImage);
                        }
                        
                       $image = $prodImageName;
                    }else{
                       $image = $request->set_image;
                    } 
                    

                    
                    $product = Product::whereHas('goDownProducts')
                    ->where('slug', $slug)->first();
                    if ($product) {
                        
                       
                               // return ;

             $goDownProduct = GoDownProduct::where('id',$request->go_down_product_id)
            ->select(['name'])
            ->firstOrFail();

             $unitName = Unit::where('id',$request->unit_id)
            ->select(['name'])
            ->firstOrFail();

                $product->name = $goDownProduct->name.'-'.$request->packet_size.''.$unitName->name;
                $product->go_down_product_id = $request->go_down_product_id;
                $product->description = $request->description;
                $product->packet_size = $request->packet_size;
                $product->quantity = $request->quantity;
                $product->unit_id = $request->unit_id;
                $product->gst = $request->gst;
                $product->unit_id = $request->unit_id;
                $product->selling_price = $request->selling_price;
                $product->buying_price = $request->buying_price;
                $product->image = $image;
                $product->save();
                $Newslug = $product->slug;
                
                if ($request->hasFile('gallery_image')) {
                    foreach ($request->file('gallery_image') as $galleryFile) {
                        $fileName = $galleryFile->hashName();
                        $galleryFile->storeAs('products', $fileName, 'public');

                        ProductGallery::create([
                            'product_id' => $product->id,
                            'name' => $fileName,
                        ]);
                    }
                }
                               
                $note = Auth::user()->first_name.' has updated <a href="' . route('admin.products.products-edit', [$Newslug]) . '" class="text-link">Product.</a>';
                    $params['update_fields'] = [
                        'user_id'=> Auth::user()->id,
                        'note'=> $note,
                    ];
                    $this->trackRecords($params);
                    //return $slug;

                        session()->flash('response','success');
                        return redirect()->route('admin.products.products-edit',[$Newslug])
                        ->with('message', 'Information has been updated successfully.');

                    }else{
                        session()->flash('response','danger');
                        return redirect()->back()->with('message','No product found.');
            
                    }
            }else{
                        session()->flash('response','danger');
                        return redirect()->back()->with('message','Validation error.');
            
            }

    }
    
    public function productsAdd(Request $request){
        $unit = Unit::orderBy('name')->get(); // Get all categories
        $goDownProduct = GoDownProduct::whereHas('goDownProductDetails')
            ->with('goDownProductDetails.unit:id,name,unit_category_id')
            ->orderBy('name')->get(); // Get all categories

       
        $getInfo['units'] = $unit;
        $getInfo['goDownProduct'] = $goDownProduct;
       
        $getInfo['mode'] = 'add';
        $randCode = 'SKU-'.$this->randnoGenerator(8).rand(1,10).$this->randnoGenerator(2);
        $formRandCode = $this->randnoGenerator(12);

    //return $getInfo['goDownProduct'];
    return view('admin.products.product-add',compact('getInfo','randCode','formRandCode'));

    }


    public function productsStore(Request $request){

       
            $request->validate([
                    'go_down_product_id' => 'required|exists:go_down_products,id',
                    'product_code' => 'required|string|max:255|unique:products,product_code',
                    'unit_id' => 'required|exists:units,id',
                    'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'], // 3mb
                    'selling_price' => 'required|min:1',
                    'buying_price' => 'required',
                    'packet_size' => 'required|min:1',
                    'quantity' => 'required|min:0.1',
                    'gallery_image'   => 'nullable|array', // input can be null OR an array of files
                    'gallery_image.*' => 'nullable|file|mimes:jpg,jpeg,png|max:3072', 
                ]);

            $goDownProduct = GoDownProduct::where('id',$request->go_down_product_id)
            ->select(['name'])
            ->firstOrFail();
             $unitName = Unit::where('id',$request->unit_id)
            ->select(['name'])
            ->firstOrFail();


            $submiteData['name'] = $goDownProduct->name.'-'.$request->packet_size.''.$unitName->name;
            $submiteData['go_down_product_id'] = $request->go_down_product_id;
            $submiteData['product_code'] = $request->product_code;
            $submiteData['description'] = $request->description;
            $submiteData['packet_size'] = $request->packet_size;
            $submiteData['quantity'] = $request->quantity;
            $submiteData['unit_id'] = $request->unit_id;
            $submiteData['gst'] = $request->gst;
            $submiteData['selling_price'] = $request->selling_price;
            $submiteData['buying_price'] = $request->buying_price;
            
            

           
            if ($request->file('image') != null){
                $productImage = $request->file('image');
                $prodImageName = $productImage->hashName();
                $request->image->storeAs('products',$prodImageName,'public');
                $submiteData['image'] = $prodImageName;
            }

            
            
           // return $variableDetails['combination'];
           $product = Product::create($submiteData);
        if($product){
                            
                if ($request->hasFile('gallery_image')) {
                    foreach ($request->file('gallery_image') as $galleryFile) {
                        $fileName = $galleryFile->hashName();
                        $galleryFile->storeAs('products', $fileName, 'public');

                        ProductGallery::create([
                            'product_id' => $product->id,
                            'name' => $fileName,
                        ]);
                    }
                }
              
                $note = Auth::user()->first_name.' has added a <a href="' . route('admin.products.products-edit', [$product->slug]) . '" class="text-link">Product.</a>';
                $params['update_fields'] = [
                    'user_id'=> Auth::user()->id,
                    'note'=> $note,
                ];
                $this->trackRecords($params);
                session()->flash('response', 'success');
                return redirect()->route('admin.products')->with('message', 'Information has been updated successfully.');
               
            }else {
                session()->flash('response', 'danger');
                return redirect()->back()->with('message', 'Information has not been updated.');
            }

       

    }

   public function prodVendors(Request $request, $slug)
{
    // Convert date strings to Y-m-d format using Carbon
    $fromDate = $request->filled('purchase_date_from') ? Carbon::createFromFormat('d-m-Y', $request->purchase_date_from)->format('Y-m-d') : null;
    $toDate = $request->filled('purchase_date_to') ? Carbon::createFromFormat('d-m-Y', $request->purchase_date_to)->format('Y-m-d') : null;

    // Build base product query
    $productQuery = GoDownProduct::where('slug', $slug);

    // Filter product if related productVendors match
    $productQuery->whereHas('productVendors', function ($q) use ($request, $fromDate, $toDate) {
        if ($fromDate && $toDate) {
            $q->whereBetween('purchase_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $q->whereDate('purchase_date', '>=', $fromDate);
        } elseif ($toDate) {
            $q->whereDate('purchase_date', '<=', $toDate);
        }

        if ($request->filled('vendor_id')) {
            $q->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('buying_price')) {
            $q->where('buying_price', $request->buying_price);
        }
        if ($request->filled('quantity')) {
            $q->where('stock_quantity', $request->quantity);
        }
    });

    // Get product with related vendor details & filters
    $getInfo['product'] = $productQuery->with([
        'category:id,name',
        'productVendors' => function ($q) use ($request, $fromDate, $toDate) {
            $q->select('id', 'go_down_product_id', 'vendor_id', 'stock_quantity', 'unit_id', 'buying_price', 'purchase_date', 'gst','actual_price','expiry_date')
              ->orderBy('purchase_date', 'desc');

            if ($fromDate && $toDate) {
                $q->whereBetween('purchase_date', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $q->whereDate('purchase_date', '>=', $fromDate);
            } elseif ($toDate) {
                $q->whereDate('purchase_date', '<=', $toDate);
            }

            if ($request->filled('vendor_id')) {
                $q->where('vendor_id', $request->vendor_id);
            }
            if ($request->filled('buying_price')) {
                $q->where('buying_price', $request->buying_price);
            }
            if ($request->filled('quantity')) {
                $q->where('stock_quantity', $request->quantity);
            }
        },
        'productVendors.unit:id,name',
        'productVendors.vendor:id,name,slug',
        'goDownProductDetails:id,go_down_product_id,quantity,unit_id',
        'goDownProductDetails.unit:id,name'
    ])->firstOrFail();

    // Get all units for dropdown
    $getInfo['unit'] = Unit::orderBy('name')->get();

    // Get distinct vendors assigned to this product
    $getInfo['vendors'] = Vendor::whereHas('productVendors.godownproduct', function ($q) use ($slug) {
        $q->where('slug', $slug)->where('is_deleted', '0');
    })->select('id', 'name', 'slug')->distinct()->get();


   //return $getInfo['product'];

    return view('admin.products.product-vendors-list', compact('getInfo'));
}

    public function autocomplete(Request $request){
        $results = GoDownProduct::where('name', 'like', '%' . $request->search . '%')
                            ->whereHas('productVendors')
                            ->get(['name','slug']); 

        return response()->json($results);
    }
    public function searchSession(Request $request){
            //return $request; 
            $this->handleSession($request);
            return response()->json('1');
    }

   public function updateproductvnd(Request $request){
    $id = $request->update['id'];

    $savedInfo = [
        'purchase_date'  => Carbon::createFromFormat('d-m-Y', $request->update['purchase_date'])->format('Y-m-d'),
        'buying_price' => $request->update['buying_price'],
        'stock_quantity' => $request->update['quantity'],
        'unit_id' => $request->update['unit_id'],
        'actual_price' => $request->update['actual_price'],
        'gst' => $request->update['gst'],
    ];

   

    $productVendor = ProductVendor::with('goDownProduct:name,slug,id')
    ->with('vendor:name,id')
    ->find($id);

    $oldQuantityVal = $this->unitConversion($productVendor->stock_quantity,$productVendor->unit_id);
 //old val
   // return $productVendor->stock_quantity;

    $return = 0;
    if ($productVendor) {
            
            $go_down_product_id =  $productVendor->go_down_product_id;
      
        $convertedVal =  $this->unitConversion($request->update['quantity'],$request->update['unit_id']);
        $updateProd = GoDownProductDetail::where('go_down_product_id', $go_down_product_id );
        $getQnt = $updateProd->select('quantity','id')->first();

        $findPacktQunt = Product::where('go_down_product_id', $go_down_product_id )
        ->select(['packet_size','quantity','unit_id']);
        $quantSum = 0;
        if( $findPacktQunt->count() > 0 ){
            foreach ($findPacktQunt->get() as  $prodvl) {
                $qnt = $prodvl->quantity * $prodvl->packet_size;
                $convertedVal_product =  $this->unitConversion($qnt,$prodvl->unit_id);
                $quantSum +=$convertedVal_product['quantity'];

            }
        }
        
        // $quantSum already in packet


          $oldVal = $oldQuantityVal['quantity'];
          $newVal = $convertedVal['quantity'];
       
         $unPacked = $getQnt->quantity - $quantSum;
       // return $oldQuantityVal['quantity'] .'- '.$convertedVal['quantity']; //150-70 =80

       $changes = $oldVal - $newVal;
       



        if($oldVal > $newVal){
             if( $changes > $unPacked){ //80 > 70
                return '2';
             }
        }

// return $getQnt->quantity - $changes;
//         //
        
//         return  $newQnt;

        $productVendor->update($savedInfo);
              
            
             $getQnt->quantity = $getQnt->quantity - $changes;
                    $getQnt->unit_id = $convertedVal['unit_id'];
                    $getQnt->save();

                $note = '<a href="'.route('admin.products.products-vendors', [$productVendor->goDownProduct->slug]) .'" >'.ucwords(strtolower($productVendor->goDownProduct->name)).'</a> vendor ('.$productVendor->vendor->name.') data has been updated by '.Auth::user()->first_name;
                $params['update_fields'] = [
                                    'user_id'=> Auth::user()->id,
                                    'note'=> $note,
                                ];
                                $this->trackRecords($params);
                $return = 1;

        

       
    }

    return response()->json($return);
}


// modification
        public function goDownProducts(){
            $categories = Category::orderBy('parent_id')->select(['id','name','parent_id'])->orderBy('name')->get(); // Get all categories
            $allCategories = Category::getCategoryTree($categories);
            $getInfo['allCategories'] = $allCategories;
            return view('admin.products.go-down-products',compact('getInfo'));
        }
      public function addGoDownProduct(Request $request){
        $categories = Category::orderBy('parent_id')->select(['id','name','parent_id'])->orderBy('name')->get(); // Get all categories
        $allCategories = Category::getCategoryTree($categories);
        $unit = Unit::orderBy('name')->get(); // Get all categories
        $vendors = Vendor::where('status','0')->orderBy('name')->get(); // Get all categories


        $getInfo['mainUnits'] = UnitCategory::with(['units:id,name,unit_category_id'])->orderBy('name')->get(); // Get all categories

        $getInfo['categories'] = $allCategories;
        $getInfo['units'] = $unit;
        $getInfo['vendors'] = $vendors;
       // $getInfo['brands'] = $brands;
        $getInfo['mode'] = 'add';
       

        //masterData
        //attributes

    //return $getInfo;
    return view('admin.products.add-godown-product',compact('getInfo'));

    }

    public function godownProductStore(Request $request){

       
            $request->validate([
                    'name' => 'required|max:250|min:2|unique:go_down_products,name',
                    'category_id' => 'required|exists:categories,id',
                    'unit_category_id' => 'required|exists:unit_categories,id',
                    'vendors' => 'required|array|min:1',
                    'vendors.*.purchase_date' => 'required|string',
                    'vendors.*.vendor_id' => 'required|exists:vendors,id',
                    'vendors.*.actual_price' => 'required|numeric|min:0.01',
                    'vendors.*.buying_price' => 'required|numeric|min:0.01',
                    'vendors.*.unit_id' => 'required|exists:units,id',
                    'vendors.*.stock_quantity' => 'required|numeric',
                ]);

                  



            
            $submiteData['name'] = $request->name;
            $submiteData['short_description'] = $request->short_description;
            $submiteData['category_id'] = $request->category_id;
            $submiteData['unit_category_id'] = $request->unit_category_id;
           
           
            
           // return $variableDetails['combination'];
           $goDownproduct = GoDownProduct::create($submiteData);
        if($goDownproduct){
            $addStockQuantity = 0;
            $unitId = 0;
            foreach ($request->vendors as $vendorData) {
               
                    $returnedValue = $this->unitConversion( $vendorData['stock_quantity'], $vendorData['unit_id']);

                    $addStockQuantity += $returnedValue['quantity'];
                    $unitId = $returnedValue['unit_id'];

                    ProductVendor::create([
                        'go_down_product_id'     => $goDownproduct->id,
                        'vendor_id'      => $vendorData['vendor_id'],
                        'purchase_date'  => Carbon::createFromFormat('d-m-Y', $vendorData['purchase_date'])->format('Y-m-d'),
                        'gst' => $vendorData['gst'],
                        'actual_price' => $vendorData['actual_price'],
                        'stock_quantity' => $vendorData['stock_quantity'],
                        'unit_id'        => $vendorData['unit_id'],
                        'buying_price'   => $vendorData['stock_quantity'] * $vendorData['actual_price'] * (100+$vendorData['gst'])/100
                    ]);
                }

                 $existing = GoDownProductDetail::where('go_down_product_id', $goDownproduct->id)->first();

                if ($existing) {
                    $existing->quantity += $addStockQuantity;
                    $existing->unit_id = $unitId;
                    $existing->save();
                } else {

                    GoDownProductDetail::create([
                        'go_down_product_id' => $goDownproduct->id,
                        'quantity' => $addStockQuantity,
                        'unit_id' => $unitId
                    ]);
                }            
                $note = Auth::user()->first_name.' has added a <a href="' . route('admin.products.godown-products-edit', [$goDownproduct->slug]) . '" class="text-link">Product for godown.</a>';
                $params['update_fields'] = [
                    'user_id'=> Auth::user()->id,
                    'note'=> $note,
                ];
                $this->trackRecords($params);
                session()->flash('response', 'success');
                return redirect()
                ->route('admin.products.go-down-products')->with('message', 'Information has been updated successfully.')->withInput();
               
            }else {
                session()->flash('response', 'danger');
                return redirect()->back()
                ->with('message', 'Information has not been updated.')->withInput();
            }

       

    }

     public function goDownproductlist(Request $request)
{
    if ($request->ajax()) {
        $requestedPostsData = $request->all();

        //return $request;

        $draw = $requestedPostsData['draw'];
        $row = $requestedPostsData['start'];
        $rowperpage = $requestedPostsData['length'];
        $columnIndex = $requestedPostsData['order'][0]['column'];
        $columnName = $requestedPostsData['columns'][$columnIndex]['data'];
        $columnSortOrder = $requestedPostsData['order'][0]['dir'];

        
        $query = GoDownProduct::
              whereHas('productVendors')
            ->whereHas('goDownProductDetails')
            ->with([
                'category:id,name',
                'goDownProductDetails:id,go_down_product_id,unit_id,quantity',
                'productVendors:id,go_down_product_id,vendor_id,stock_quantity,unit_id,buying_price,purchase_date,expiry_date',
                'goDownProductDetails.unit:id,name,unit_category_id',
                'productVendors.vendor:id,name',
                'products:id,quantity,unit_id,packet_size,go_down_product_id'
            ]);

        // Apply filters
        if (!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->category_id)) {
            //$query->where('category_id', $request->category_id);
                    $categoryIds = Category::where('id', $request->category_id)
                        ->orWhere('parent_id', $request->category_id)
                        ->pluck('id')
                        ->toArray();

                    $query->whereIn('category_id', $categoryIds);
               
        }

        // Apply vendor-specific filters (except quantity)
        $query->whereHas('productVendors', function ($q) use ($request) {
            

                if (!empty($request->purchase_date_from) && empty($request->purchase_date_to)) {
                $dateFrom = Carbon::createFromFormat('d-m-Y', $request->purchase_date_from)->format('Y-m-d');
                $q->where('purchase_date','>=', $dateFrom);
                }elseif(!empty($request->purchase_date_to) && empty($request->purchase_date_from)) {
                    $dateTo = Carbon::createFromFormat('d-m-Y', $request->purchase_date_to)->format('Y-m-d');
                    $q->where('purchase_date','<=', $dateTo);
                }elseif(!empty($request->purchase_date_from) && !empty($request->purchase_date_to)){
                    $dateFrom = Carbon::createFromFormat('d-m-Y', $request->purchase_date_from)->format('Y-m-d');
                    $dateTo = Carbon::createFromFormat('d-m-Y', $request->purchase_date_to)->format('Y-m-d');

                    $q->whereBetween('purchase_date', [$dateFrom, $dateTo]);
                }
            
           
        });
        //return $request;

        $fieldsArry = [
            'id', 'category_id', 'name',
            'short_description',  'slug'
        ];

        $productsCollection = $query->select($fieldsArry)
            ->orderBy($columnName, $columnSortOrder)
            ->get();

           // return $productsCollection;
        // Apply quantity filtering AFTER loading (based on vendor logic)
        if (!empty($request->quantity)) {
            $productsCollection = $productsCollection->filter(function ($product) use ($request) {
                if (!empty($request->vendor_id)) {
                    // Match quantity from specific vendor
                    $vendor = $product->productVendors->firstWhere('vendor_id', $request->vendor_id);
                    return $vendor && $vendor->stock_quantity == $request->quantity;
                } else {
                    // Match sum of all vendor stock
                    $total = $product->productVendors->sum('stock_quantity');
                    return $total == $request->quantity;
                }
            })->values();
        }

        $totalRecords = GoDownProduct::count();
        $totalFiltered = $productsCollection->count();

        // Pagination manually after filtering
        $paginatedProducts = $productsCollection->slice($row, $rowperpage);

        $products = [];

        //return $paginatedProducts;

        
        foreach ($paginatedProducts as $i => $rowVal) {
             $quantity = $rowVal->goDownProductDetails->quantity;
             $unitName = $rowVal->goDownProductDetails->unit->name ?? '';

            if( !empty($rowVal->products)){
                $qnt_inPacket = 0;
                foreach ($rowVal->products as $value) {
                    $totalInPck = $value->packet_size * $value->quantity;
                        
                        $returndConvrted = $this->unitConversion( $totalInPck, $value->unit_id);
                         $qnt_inPacket += (float)$returndConvrted['quantity'];
                }
            }

           

            $params['mode'] = 'standard';
            $params['unit_category_id'] = $rowVal->goDownProductDetails->unit->unit_category_id;
            $params['quantity'] = $qnt_inPacket;
            
            $inpckt = $this->standartUnitConversion($params);


            $params['mode'] = 'standard';
            $params['unit_category_id'] = $rowVal->goDownProductDetails->unit->unit_category_id;
            $params['quantity'] = $quantity-$qnt_inPacket;


            
            $standardUnit = $this->standartUnitConversion($params);

            $products[$i]['name'] =  ucwords(strtolower($rowVal->name));
            $products[$i]['category_id'] =  ucwords(strtolower($rowVal->category->name)) ?? '';
           
            $products[$i]['in_packet'] =  $inpckt;
            $products[$i]['in_godown'] = $standardUnit;

            $paramsT['mode'] = 'standard';
            $paramsT['unit_category_id'] = $rowVal->goDownProductDetails->unit->unit_category_id;
            $paramsT['quantity'] = $quantity;
            $totalQnt = $this->standartUnitConversion($paramsT);

            $products[$i]['total'] = $totalQnt;

           

            $products[$i]['URL'] = '<a class="text text-warning" href="' . route('admin.products.godown-products-edit', [$rowVal->slug]) . '">
                                        <i class="fa fa-pen"></i></a> &nbsp; &nbsp;
                                    <a class="text text-info" href="' . route('admin.products.products-vendors', [$rowVal->slug]) . '"><i class="fa fa-list"></i></a>';
        }


       // return $products;

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFiltered,
            "data" => $products
        ]);
    }
}
 public function goDownproductsEdit(Request $request , string $slug ){
            $getInfo['product'] = GoDownProduct::with('category:id,name')
            ->with('goDownProductDetails:id,go_down_product_id,quantity,unit_id')
            ->with('goDownProductDetails.unit:id,name')
            ->where('slug', $slug)
            ->firstOrFail();

            $convertParam['mode'] = 'standard';
            $convertParam['unit_category_id'] = $getInfo['product']->unit_category_id;
            $convertParam['quantity'] = $getInfo['product']->goDownProductDetails->quantity;
            
            $standardUnit = $this->standartUnitConversion( $convertParam );

            //return $standardUnit;
            $getInfo['standard_unit'] = $standardUnit;

             $categories = Category::orderBy('parent_id')->select(['id','name','parent_id'])->orderBy('name')->get(); // Get all categories
             $getInfo['categories'] = Category::getCategoryTree($categories);    
             $getInfo['units'] = Unit::orderBy('name')->select('name','id','slug','unit_category_id','parent_id','conversion_factor')->get(); // Get all categories
             $getInfo['vendors'] = Vendor::where('status','0')
                ->select('name','id','slug')
                ->orderBy('name')->get(); // Get all categories
             $getInfo['mode'] = 'edit';
             $getInfo['mainUnits'] = UnitCategory::with(['units:id,name,unit_category_id'])->orderBy('name')->get(); // Get all categories

        //return $getInfo['mainUnits'];
             return view('admin.products.go-down-products-edit',compact('getInfo'));    
    }


    public function goDownProductUpdate(Request $request , string $slug){

        //return $request;
           

                     $rules = [
                        'name' => [
                            'required',
                            'string',
                            'max:255',
                            'min:2',
                             Rule::unique('go_down_products')->ignore($slug, 'slug')
                        ],
                        'category_id' => 'required|exists:categories,id',
                    ];

                    // Check if any vendor is submitted
                    if (!empty($request->vendors) && is_array($request->vendors)) {
                        $rules['vendors'] = 'array|min:1';
                        $rules['vendors.*.purchase_date'] = 'required|string';
                        $rules['vendors.*.vendor_id'] = 'required|exists:vendors,id';
                        $rules['vendors.*.buying_price'] = 'required|numeric|min:0';
                        $rules['vendors.*.unit_id'] = 'required|numeric|min:1';
                        $rules['vendors.*.stock_quantity'] = 'required';
                    }

            $validated = Validator::make($request->all(), $rules)->validate();     
            if( $validated ){
                   

                    $goDownproduct = GoDownProduct::where('slug', $slug)->first();
                    if ($goDownproduct) {
                        $existing = GoDownProductDetail::where('go_down_product_id', $goDownproduct->id)->first();
  
                        $addStockQuantity = 0;
                        if( !empty( $request->vendors ) ){
                            foreach ($request->vendors as $vendorData) {
                                    $returnedValue = $this->unitConversion( $vendorData['stock_quantity'], $vendorData['unit_id']);
                                    $addStockQuantity += $returnedValue['quantity'];
                                    $unitId = $returnedValue['unit_id'];


                                    ProductVendor::create([
                                            'go_down_product_id'     => $goDownproduct->id,
                                            'vendor_id'      => $vendorData['vendor_id'],
                                            'purchase_date'  => Carbon::createFromFormat('d-m-Y', $vendorData['purchase_date'])->format('Y-m-d'),
                                            'gst' => $vendorData['gst'],
                                            'actual_price' => $vendorData['actual_price'],
                                            'stock_quantity' => $vendorData['stock_quantity'],
                                            'unit_id'        => $vendorData['unit_id'],
                                            'buying_price'   => $vendorData['stock_quantity'] * $vendorData['actual_price'] * (100+$vendorData['gst'])/100
                                    ]);
                                }
                        }else{
                            $unitId = $existing->unit_id;
                        }
                               

                                $goDownproduct->name = $request->name;
                               // $goDownproduct->slug = $request->name;
                                $goDownproduct->category_id = $request->category_id;
                                $goDownproduct->short_description = $request->short_description;
                                $goDownproduct->save();
                           
                            
                                if ($existing) {
                                        $existing->quantity += $addStockQuantity;
                                        $existing->unit_id = $unitId;
                                        $existing->save();                                        
                                }

                    //    /biulir-dal-premium-prod1

                    $note = Auth::user()->first_name.' has updated <a href="' . route('admin.products.godown-products-edit', [$goDownproduct->slug]) . '" class="text-link">Product.</a>';
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'note'=> $note,
                        ];
                        $this->trackRecords($params);


                        session()->flash('response','success');
                        return redirect()->route('admin.products.godown-products-edit', [$goDownproduct->slug])
                                ->with('message', 'Information has been saved successfully.');

            
                    }else{
                        session()->flash('response','danger');
                         return redirect()->route('admin.products.godown-products-edit', [$goDownproduct->slug])
                                ->with('message', 'Information has not been saved.');
            
                    }
            }else{
                        session()->flash('response','danger');
                        return redirect()->back()->with('message','Validation error.');
            
            }

    }

    public function damagedProducts(Request $request){
        $damagedProds = DamagedProductsDetail::where('status','0')
                ->where('damaged_in','1')
                ->whereHas('goDownProduct')
                ->whereHas('user')
                ->whereHas('unit')
                ->with([
                    'goDownProduct:id,name,slug',
                    'unit:id,name',
                    'user:id,first_name,last_name'
                ])
                ->orderByDesc('damaged_date')->get();
        // /            return $damagedProds;
                return view('admin.products.damaged-products',compact('damagedProds'));
    }

    public function damagesItemLists(Request $request)
{
    if ($request->ajax()) {
        $requestedPostsData = $request->all();
        $for = $request->for;
        
       
        
        $draw = $requestedPostsData['draw'];
        $row = $requestedPostsData['start'];
        $rowperpage = $requestedPostsData['length'];
        $columnIndex = $requestedPostsData['order'][0]['column'];
        $columnName = $requestedPostsData['columns'][$columnIndex]['data'];
        $columnSortOrder = $requestedPostsData['order'][0]['dir'];


        $query = DamagedProductsDetail::where('status', '0')
            ->whereHas('goDownproduct')
            ->whereHas('user')
            ->whereHas('unit');


            if (!empty($request->purchase_date_from) && empty($request->purchase_date_to)) {
                $dateFrom = Carbon::createFromFormat('d-m-Y', $request->purchase_date_from)->format('Y-m-d');
                $query->where('damaged_date','>=', $dateFrom);
                }elseif(!empty($request->purchase_date_to) && empty($request->purchase_date_from)) {
                    $dateTo = Carbon::createFromFormat('d-m-Y', $request->purchase_date_to)->format('Y-m-d');
                    $query->where('damaged_date','<=', $dateTo);
                }elseif(!empty($request->purchase_date_from) && !empty($request->purchase_date_to)){
                    $dateFrom = Carbon::createFromFormat('d-m-Y', $request->purchase_date_from)->format('Y-m-d');
                    $dateTo = Carbon::createFromFormat('d-m-Y', $request->purchase_date_to)->format('Y-m-d');

                    $query->whereBetween('damaged_date', [$dateFrom, $dateTo]);
                }

        if ($for == 'P') {

            $damaged_in = '2';
            $query->where('damaged_in', $damaged_in)
                ->with('product:id,name')
                ->with('unit:id,name');

            $query->whereHas('product', function ($q) use ($request) {
                if (!empty($request->name)) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                }
            });

            $cond = ['status' => '0', 'damaged_in' => $damaged_in];
        } else {
            // godown case
            $damaged_in = '1';
            $query->where('damaged_in', $damaged_in)
                ->with([
                    'goDownProduct:id,name,slug',
                    'unit:id,name',
                    'user:id,first_name,last_name'
                ]);

            $query->whereHas('goDownProduct', function ($q) use ($request) {
                if (!empty($request->category_id)) {
                    $categoryIds = Category::where('id', $request->category_id)
                        ->orWhere('parent_id', $request->category_id)
                        ->pluck('id')
                        ->toArray();
                    $q->whereIn('category_id', $categoryIds);
                }

                if (!empty($request->name)) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                }
            });

            $cond = ['status' => '0', 'damaged_in' => $damaged_in];
        }

        $totalRecords = DamagedProductsDetail::whereHas('goDownProduct')->where($cond)->count();

        $productsCollection = $query->get();


        // Handle sorting by related model fields manually
        if ($columnName === 'name') {
            $productsCollection = $productsCollection->sortBy(function ($item) use ($for) {
                return $for == 'P' ? ($item->product->name ?? '') : ($item->goDownProduct->name ?? '');
            }, SORT_REGULAR, $columnSortOrder === 'desc')->values();
        }elseif ($columnName === 'damaged_date') {
            $productsCollection = $productsCollection->sortBy(function ($item) use ($for) {
                return $item->damaged_date;
            }, SORT_REGULAR, $columnSortOrder === 'desc')->values();
        }

        $totalFiltered = $productsCollection->count();

        $paginatedProducts = $productsCollection->slice($row, $rowperpage);

        $products = [];

        foreach ($paginatedProducts as $i => $rowVal) {
            $products[$i]['sl'] = $i + 1;
            $products[$i]['damaged_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $rowVal->damaged_date)->format('d-m-Y');

            if ($rowVal->damaged_in == '1') {
                $products[$i]['name'] = ucwords(strtolower($rowVal->goDownProduct->name));
                $editPath = 'damaged-product-edit';
            } elseif ($rowVal->damaged_in == '2') {
                $products[$i]['name'] = ucwords(strtolower($rowVal->product->name));
                $products[$i]['no_of_packet'] = $rowVal->no_of_packet;
                $editPath = 'damaged-packet-edit';
            }

            $products[$i]['quantity'] = $rowVal->quantity . ' ' . $rowVal->unit->name;
            $products[$i]['URL'] = '<a class="text text-warning" href="' . route('admin.products.' . $editPath, [$rowVal->id]) . '">
                                        <i class="fa fa-pen"></i></a> &nbsp; &nbsp;';
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFiltered,
            "data" => $products
        ]);
    }
}







    public function damagedProductsAdd(Request $request){
        $getInfo['units'] = Unit::orderBy('name')->get(); // Get all categories
        $goDownProduct = GoDownProduct::whereHas('goDownProductDetails')
            ->select(['id','name','slug'])
            ->with('goDownProductDetails.unit:id,name,unit_category_id')
            ->orderBy('name')->get(); 
        $getInfo['products'] = $goDownProduct;
          
            return view('admin.products.damaged-product-add',compact('getInfo'));

    }
    

    public function damageStore(Request $request){
             $request->validate([
                    'go_down_product_id' => 'required|exists:go_down_products,id',
                    'damaged_date' => 'required',
                    'unit_id' => 'required',
                    'quantity' => 'required|min:0.01',
                ]);
            $savedInfo  = $request->all();

                
           
            $savedInfo['damaged_date'] = Carbon::createFromFormat('d-m-Y', $savedInfo['damaged_date'])->format('Y-m-d');
            $savedInfo['user_id']  = Auth::user()->id;
            $unit = Unit::select('name')->where('id',$request->unit_id)->first();


             
            $goDownProduct = GoDownProduct::select(['name','slug','id'])->where('id',$request->go_down_product_id)
            ->with('goDownProductDetails:id,quantity,unit_id,go_down_product_id')
            ->first();
            // substract Quant
            $dataToPass['from'] = 'G'; // godown
            $dataToPass['go_down_product_id'] = $savedInfo['go_down_product_id']; 
            $dataToPass['quantity'] = $savedInfo['quantity'];
            $dataToPass['unit_id'] = $savedInfo['unit_id'];
            $dataToPass['previously_substracted_val'] = 0;
            $dataToPass['previously_substracted_val_unit'] = 0;


                $packagedQunt = Product::where('go_down_product_id',$savedInfo['go_down_product_id'])
                    ->select('quantity','unit_id','packet_size');
                    $sumPckgdQunt =0;
                    if( $packagedQunt->count() > 0 ){
                         foreach ($packagedQunt->get() as $value) {
                                 $qnt = $value->quantity*$value->packet_size;
                                 $getData = $this->unitConversion($qnt,$value->unit_id);
                                 $sumPckgdQunt += $getData['quantity'];
                         }
                    }
                //$goDownProductQunt = $this->unitConversion($goDownProduct->quantity,$goDownProduct->unit_id);
                $availableInGodown = $goDownProduct->goDownProductDetails->quantity - $sumPckgdQunt;


                $requestedQnt = $this->unitConversion( $savedInfo['quantity'] , $savedInfo['unit_id']);

                if( $availableInGodown >= $requestedQnt['quantity'] ){

                    $this->substractQuantity($dataToPass);
        
                    

                    $dmgedProd = DamagedProductsDetail::create($savedInfo);
        
                    $note = $request->quantity . ' ' . $unit->name . ' of ' . '<a href="'.route('admin.products.damaged-product-edit',[$dmgedProd->id]).'">'.$goDownProduct->name.'</a>' . 
                        ' marked as a damaged product by ' . Auth::user()->first_name;
        
                    $params['update_fields'] = [
                                'user_id'=> Auth::user()->id,
                                'note'=> $note,
                            ];
                    $this->trackRecords($params);

                    session()->flash('response','success');
                        return redirect()->route('admin.products.damaged')
                                ->with('message', 'Information has been saved successfully.');
                }else{
                    session()->flash('response','danger');
                                return redirect()->back()
                                        ->with('message', 'You have entered more than available stock in godown.')->withInput();
                }


            

    }
    public function damagedProductsEdit(Request $request , string $id){
        $getInfo['units'] = Unit::orderBy('name')->get(); // Get all categories
                $goDownProduct = GoDownProduct::whereHas('goDownProductDetails')
                    ->select(['id','name','slug'])
                    ->with('goDownProductDetails.unit:id,name,unit_category_id')
                    ->orderBy('name')->get(); 
                $getInfo['products'] = $goDownProduct;
                $getInfo['damaged'] = DamagedProductsDetail::with('goDownproduct:unit_category_id,id')
                                        ->where('id',$id)->firstOrFail();
                $getInfo['id'] = $id;
                // return  $getInfo;
                    return view('admin.products.damaged-product-edit',compact('getInfo'));

    }
    
     public function damageUpdate(Request $request , string $id){

                $request->validate([
                    'go_down_product_id' => 'required|exists:go_down_products,id',
                    'damaged_date' => 'required',
                    'unit_id' => 'required',
                    'quantity' => 'required|min:0.01',
                ]);
                                     
                  // return ;

            $savedInfo  = $request->all();
            $damaged_date = Carbon::createFromFormat('d-m-Y', $savedInfo['damaged_date'])->format('Y-m-d');
            $user_id  = Auth::user()->id;

            $goDownProduct = GoDownProduct::select(['name','slug','id'])
                ->where('id',$request->go_down_product_id)
                ->with('goDownProductDetails:id,quantity,unit_id,go_down_product_id')
                ->first();
              $updateProd = DamagedProductsDetail::where('id', $request->id);
             $preViousRecord = $updateProd->select(['quantity','unit_id'])->first();

             //return $preViousRecord->quantity;
// .....................................
                $packagedQunt = Product::where('go_down_product_id',$savedInfo['go_down_product_id'])
                    ->select('quantity','unit_id');
                    $sumPckgdQunt =0;
                    if( $packagedQunt->count() > 0 ){
                         foreach ($packagedQunt->get() as $value) {
                                    $qnt = $value->quantity*$value->packet_size;
                                 $getData = $this->unitConversion($qnt,$value->unit_id);
                                 $sumPckgdQunt += $getData['quantity'];
                         }
                    }
                $prvRecd = $this->unitConversion( $preViousRecord->quantity , $preViousRecord->unit_id);

               // return $goDownProduct;

                $availableInGodown = $goDownProduct->goDownProductDetails->quantity + $prvRecd['quantity'] - $sumPckgdQunt;
                $requestedQnt = $this->unitConversion( $savedInfo['quantity'] , $savedInfo['unit_id']);

                    //return $availableInGodown;


                if( $availableInGodown >= $requestedQnt['quantity'] ){
                       
            
                            
                            $dataToPass['from'] = 'G'; // godown
                            $dataToPass['go_down_product_id'] = $request->go_down_product_id; 
                            $dataToPass['quantity'] = $request->quantity;
                            $dataToPass['unit_id'] = $request->unit_id;
                            $dataToPass['previously_substracted_val'] = $preViousRecord->quantity;
                            $dataToPass['previously_substracted_val_unit'] = $preViousRecord->unit_id;
                            $res = $this->substractQuantity($dataToPass);

                         if($res == 'Y' ){

                                $updateProd->update([
                                        'go_down_product_id'=>$request->go_down_product_id,
                                        'quantity'=>$request->quantity,
                                        'unit_id'=>$request->unit_id,
                                        'user_id'=>$user_id,
                                        'damaged_date'=>$damaged_date,
                                        'comment'=>$request->comment
                                    ]);

                                $note = Auth::user()->first_name.' has updated damaged product <a href="'.route('admin.products.damaged-product-edit',[$request->id]).'">'.$goDownProduct->name.'</a> details.';
                                $params['update_fields'] = [
                                            'user_id'=> $user_id,
                                            'note'=> $note,
                                            
                                        ];
                                $this->trackRecords($params);


                                    session()->flash('response','success');
                                    return redirect()->back()->with('message','Information has been saved successfully.');
                            }else{
                                        session()->flash('response','danger');
                                    return redirect()->back()->with('message','Something went wrong in calculation.')->withInput();
                            
                            }

                }else{
                    session()->flash('response','danger');
                                    return redirect()->back()->with('message','You have entered more than available stock in godown.')->withInput();
                }







                

           
            }
    public function damagedProdSum(Request $request){
            $damagedProds = DamagedProductsDetail::where('go_down_product_id',$request->filteredData)->get();
            $prodname = GoDownProduct::select('name')->where('id',$request->filteredData)->first();
            $sum = 0;
            $returndata =[];
            if( !empty( $damagedProds )){
            $getData=[];
                    foreach($damagedProds as $sumCl ){
                    $getData = $this->unitConversion($sumCl->quantity,$sumCl->unit_id);
                    $sum += $getData['quantity'];
                    $unit = $getData['unit_id'];
                    }
            }
        $damagedProds = Unit::where('id',$unit)->first();
        if($sum > 1000 ){
            $params['mode'] = 'standard';
            $params['unit_category_id'] = $damagedProds->unit_category_id;
            $params['quantity'] = $sum;
            
            $returndata['standard_conversion'] = $this->standartUnitConversion($params);
        }
        $returndata['quantiy'] = $sum;
        $unitD = Unit::select('name')->where('id',$unit)->first();
        
        $returndata['unit'] = $unitD->name;
        $returndata['name'] = $prodname->name;
        
            //return $returndata;
        

        return response()->json($returndata);
    }

     public function damagedPackedProdSum(Request $request){
            $damagedProds = DamagedProductsDetail::where('packet_prodid',$request->filteredData)->get();
            $prodname = Product::select('name')->where('id',$request->filteredData)->first();
            $sum = 0;
            $packets=0;
            $returndata =[];
            //return $damagedProds;
            if( !empty( $damagedProds )){
            $getData=[];
                    foreach($damagedProds as $sumCl ){
                    $getData = $this->unitConversion($sumCl->quantity,$sumCl->unit_id);
                    $sum += $getData['quantity'];
                    $packets += $sumCl['no_of_packet'];
                    $unit = $getData['unit_id'];
                    }
            }
        $damagedProds = Unit::where('id',$unit)->first();
        if($sum > 1000 ){
            $params['mode'] = 'standard';
            $params['unit_category_id'] = $damagedProds->unit_category_id;
            $params['quantity'] = $sum;
            
            $returndata['standard_conversion'] = $this->standartUnitConversion($params);
        }
        $returndata['quantiy'] = $sum;
        $unitD = Unit::select('name')->where('id',$unit)->first();
        
        $returndata['unit'] = $unitD->name;
        $returndata['name'] = $prodname->name;
        $returndata['no_of_packet'] = $packets;
        
            //return $returndata;
        

        return response()->json($returndata);
    }

    
    
    
    private function getProdDetails( $params ){
                        $goDownProduct = Product::whereHas('goDownProducts')
                        ->with('unit:id,name,unit_category_id');
                      
        if($params['mode'] == 'A' ){

            return $goDownProduct->select(['id','name','slug','unit_id'])->orderBy('name')->get();

        }elseif($params['mode'] == 'S' && isset($params['id'] )) {
            return $goDownProduct->select(['id','go_down_product_id','unit_id','packet_size','quantity','name','slug'])->find($params['id']);
        }
                
    }

    public function damagedPacketProducts(Request $request){
        $damagedProds = DamagedProductsDetail::where('status','0')
                ->where('damaged_in','2')
                ->whereHas('goDownProduct')
                ->whereHas('product')
                ->whereHas('user')
                ->whereHas('unit')
                ->with([
                        'goDownProduct:id,name,slug',
                        'product:id,name,slug',
                        'unit:id,name',
                        'user:id,first_name,last_name'
                ])
                ->orderByDesc('damaged_date')->get();
              //  return $damagedProds;
                return view('admin.products.damaged-packet',compact('damagedProds'));
    }



    public function getSingleProdData(Request $request){
 
        $param['mode'] = 'S';
        $param['id'] = $request['query'];
        $returndata = $this->getProdDetails($param);
        return response()->json($returndata);
    }
    public function damagedPacketProductsAdd(Request $request){
        $getInfo['units'] = Unit::orderBy('name')->get(); // Get all categories

        $goDownProduct = $this->getProdDetails(['mode'=>'A']);
            $getInfo['products'] = $goDownProduct;
         // return $getInfo['products'];
            return view('admin.products.damaged-packet-add',compact('getInfo'));

    }


    public function damagePacketStore(Request $request){
             $request->validate([
                    'product_id' => 'required|exists:products,id',
                    'damaged_date' => 'required',
                    'quantity' => 'required',
                ]);
                //return $request;

        $checkQuantity =  $this->getProdDetails(['mode'=>'S','id'=> $request->product_id]);


        if( !empty($checkQuantity) && $request->quantity <= $checkQuantity->quantity ){
                 
           // $savedInfo  = $request->all();
            $savedInfo['damaged_date'] = Carbon::createFromFormat('d-m-Y', $request['damaged_date'])->format('Y-m-d');
            $savedInfo['user_id']  = Auth::user()->id;
            $savedInfo['packet_prodid']  = $request->product_id;
            $savedInfo['damaged_in']  ='2';
            $savedInfo['go_down_product_id']  = $checkQuantity->go_down_product_id;
            $savedInfo['no_of_packet']  = $request->quantity;
            $savedInfo['quantity']  = $checkQuantity->packet_size * $request->quantity;
            $savedInfo['unit_id']  = $checkQuantity->unit_id;
            $savedInfo['comment']  = $request->comment;
            
            
           //return $savedInfo;
            

             $dmgedProd = DamagedProductsDetail::create($savedInfo);
            
            
                    $dataToPass['from'] = 'P'; // Packaged Item
                    $dataToPass['go_down_product_id'] = $savedInfo['go_down_product_id']; 
                    $dataToPass['quantity'] = $savedInfo['quantity'];
                    $dataToPass['unit_id'] = $savedInfo['unit_id'];
                    $dataToPass['previously_substracted_val'] = 0;
                    $dataToPass['previously_substracted_val_unit'] = 0;
                    $dataToPass['previously_packed'] = 0;
                    $dataToPass['no_of_packet'] = $savedInfo['no_of_packet'];
                    $dataToPass['product_id'] = $savedInfo['packet_prodid'];
                    $this->substractQuantity($dataToPass);
                    // substract Quant
            

            
            

            $note = 'Quantity '.$request->quantity.' <a href="'.route('admin.products.damaged-packet-edit',[$dmgedProd->id]).'">'.$checkQuantity->name.'</a>' . 
                ' marked as a damaged product by ' . Auth::user()->first_name;

            $params['update_fields'] = [
                        'user_id'=> Auth::user()->id,
                        'note'=> $note,
                    ];
            $this->trackRecords($params);

            session()->flash('response','success');
            return redirect()->route('admin.products.damaged-packet')
                    ->with('message', 'Information has been saved successfully.');
        }else{
             session()->flash('response','danger');
                return redirect()->route('admin.products.damaged-packet-add')
                        ->with('message', 'You have entered more than available quantity.')->withInput();
        }
           

    }


    public function damagedPacketProductsEdit(Request $request , string $id){
             $goDownProduct = $this->getProdDetails(['mode'=>'A']);
             $getInfo['products'] = $goDownProduct;

                $getInfo['damaged'] = DamagedProductsDetail::with('goDownproduct:unit_category_id,id')
                ->with('unit:id,name')
                ->with('product.unit:id,name')
                ->where('id',$id)->firstOrFail();
                $getInfo['id'] = $id;
                  // return $getInfo['damaged'];
                    return view('admin.products.damaged-packet-edit',compact('getInfo'));

    }


    public function damagePacketUpdate(Request $request , string $id){

                $request->validate([
                    'product_id' => 'required|exists:products,id',
                    'damaged_date' => 'required',
                    'quantity' => 'required',
                ]);
                                     
                 // return $id;
        $checkQuantity =  $this->getProdDetails(['mode'=>'S','id'=> $request->product_id]);

       // return $checkQuantity;
      

         if( !empty($checkQuantity) && $request->quantity <= $checkQuantity->quantity ){
                 
           // $savedInfo  = $request->all();
            $updateInfo['damaged_date'] = Carbon::createFromFormat('d-m-Y', $request['damaged_date'])->format('Y-m-d');
            $updateInfo['user_id']  = Auth::user()->id;
            $updateInfo['packet_prodid']  = $request->product_id;
            $updateInfo['damaged_in']  ='2';
            $updateInfo['go_down_product_id']  = $checkQuantity->go_down_product_id;
            $updateInfo['no_of_packet']  = $request->quantity;
            $updateInfo['quantity']  = $checkQuantity->packet_size * $request->quantity;
            $updateInfo['unit_id']  = $checkQuantity->unit_id;


            $updateProd = DamagedProductsDetail::where('id', $id );
            $oldRecord = $updateProd->first();
             
            //return $oldRecord;
            $updateProd->update($updateInfo);


                    $dataToPass['from'] = 'P'; // Packaged Item
                    $dataToPass['go_down_product_id'] = $checkQuantity->go_down_product_id; 
                    $dataToPass['quantity'] = $checkQuantity->packet_size * $request->quantity;
                    $dataToPass['unit_id'] = $checkQuantity->unit_id;
                    $dataToPass['previously_substracted_val'] = $oldRecord->quantity;
                    $dataToPass['previously_substracted_val_unit'] = $oldRecord->unit_id;
                    $dataToPass['no_of_packet']  = $request->quantity;
                    $dataToPass['previously_packed'] = $oldRecord->no_of_packet;
                    $dataToPass['product_id'] = $oldRecord->packet_prodid;
                    $dataToPass['comment'] = $request->comment;


                    //return $dataToPass;
                    $this->substractQuantity($dataToPass);
                    // // substract Quant


            $note = 'Quantity '.$request->quantity.' <a href="'.route('admin.products.damaged-packet-edit',[$id]).'">'.$checkQuantity->name.'</a>' . 
                ' marked as a damaged product by ' . Auth::user()->first_name;

            $params['update_fields'] = [
                        'user_id'=> Auth::user()->id,
                        'note'=> $note,
                    ];
            $this->trackRecords($params);

            session()->flash('response','success');
            return redirect()->route('admin.products.damaged-packet')
                    ->with('message', 'Information has been saved successfully.');
        }else{
             session()->flash('response','danger');
                return redirect()->back()
                        ->with('message', 'You have entered more than available quantity.')->withInput();
        }
           


                
        }
             //check Godown Product quantity Is available or not during product add
             
            public function checkQuantity( Request $request){
                    $param['mode'] = $request->search['mode'];
                    $param['go_down_product_id'] = $request->search['go_down_product_id'];
                    $param['packet_size'] = $request->search['packet_size'];
                    $param['unit_id'] = $request->search['unit_id'];
                    $param['quantity'] = $request->search['quantity'];
                    $param['slug'] = $request->search['slug'];
             
                    //prevent Duplicate
                    
//return $request;
                        $resultsQnr = Product::where('packet_size', $request->search['packet_size'])
                            ->where('go_down_product_id', $request->search['go_down_product_id'])
                            ->where('unit_id', $request->search['unit_id']);

                            if ($param['mode'] == 'editProd') {
                                $resultsQnr->where('slug', '!=', $request->search['slug']);
                            }

                        $results = $resultsQnr->count();
                       // return 'dss'.$results;
                        if( $results > 0 ){
                            $returndata['reject_request'] = 'D';
                        }else{
                           // return $request->search;
                           
                            if ($param['mode'] == 'editProd' && isset($request->search['check_qnt']) && $request->search['check_qnt'] == 'Y') {
                                
                                        
                                         $newQry = Product::where('slug', $request->search['slug'])
                                         ->where('quantity', '!=', $request->search['quantity'])
                                         ->select('quantity');
                                        
                                        
                                        //quantity either updated or changed
                                         $qntReslt = $newQry->count();
                                         //return $qntReslt.'---sdsdsa';
                                         if( $qntReslt > 0 ){
                                            $currentQnt = $newQry->first();
                                            $returndata['reject_request'] = 'Q';
                                            $returndata['current_quantity'] = $currentQnt->quantity;
                                         }else{
                                            $returndata = $this->goDownProdQuantityCheck( $param );
                                         }
                                 }else{
                                    // return 'here-else';
                                        $returndata = $this->goDownProdQuantityCheck( $param );
                                 }
                            
                        }
                        

                return response()->json($returndata);
            }

     
        private function buyingPriceCalculationLogic($goDownProductId)
                {
                    $prodVendor = ProductVendor::where('go_down_product_id', $goDownProductId)
                        ->where('stock_quantity','>','0')
                        ->select(['buying_price','purchase_date','unit_id','stock_quantity','id','go_down_product_id'])
                        ->with('unit:id,name,unit_category_id')
                        ->with('goDownProduct:id,unit_category_id');

                    $returnData = [];
                    $totalQunatiy = 0;
                    $totalPrice = 0;
                    $lowestAndHighestPrice = [];
                    $otherInfo = [];
                    $count = $prodVendor->count();
                    $returnData['avrg']['base']='';
                    $returnData['avrg']['standard']='';
                    $returnData['min']['b']='';
                    $returnData['min']['s']='';
                    $returnData['min']['p_date']='';
                    $returnData['max']['b'] = '';
                    $returnData['max']['s'] = '';
                    $returnData['max']['p_date'] = '';

                    if ($count > 0) {
                        $prodVendorArr = $prodVendor->get();


                       
                        foreach ($prodVendorArr as $prod) {

                            
                            $oldQuantityVal = $this->unitConversion($prod->stock_quantity, $prod->unit_id);
                            $totalQunatiy += $oldQuantityVal['quantity'];
                            $totalPrice += $prod->buying_price;

                            $findPerUnit = $prod->buying_price / $oldQuantityVal['quantity'];
                            $lowestAndHighestPrice[$prod->id] = $findPerUnit;
                            $otherInfo[$prod->id]['p_date'] = $prod->purchase_date;

                            $param['mode'] = 'price_per_stnd_unit';
                            $param['unit_category_id'] = $prod->unit->unit_category_id;
                            $param['quantity'] = $oldQuantityVal['quantity'];
                            $param['gross_buying_price'] = $prod->buying_price;

                            $standardUnit = $this->standartUnitConversion($param);
                            $otherInfo[$prod->id]['standard_unit'] = $standardUnit;

                            $unit_category_id = $prod->goDownProduct->unit_category_id;
                        }

                        $baseUntName = Unit::where('unit_category_id', $unit_category_id)
                            ->whereNull('parent_id')
                            ->select('name')
                            ->first();

                        $avrgPrice = number_format($totalPrice / $totalQunatiy, 3) . '/' . $baseUntName->name;

                        $param['mode'] = 'price_per_stnd_unit';
                        $param['unit_category_id'] = $unit_category_id;
                        $param['quantity'] = $totalQunatiy;
                        $param['gross_buying_price'] = $totalPrice;

                        $standardUnit = $this->standartUnitConversion($param);

                        $returnData['avrg']['base'] = $avrgPrice;
                        $returnData['avrg']['standard'] = $standardUnit;

                        $minKey = $maxKey = key($lowestAndHighestPrice);
                        $minVal = $maxVal = current($lowestAndHighestPrice);

                        foreach ($lowestAndHighestPrice as $key => $value) {
                            if ($value < $minVal) {
                                $minVal = $value;
                                $minKey = $key;
                            }
                            if ($value > $maxVal) {
                                $maxVal = $value;
                                $maxKey = $key;
                            }
                        }

                        $returnData['min']['b'] = $minVal . ' / ' . $baseUntName->name;
                        $returnData['min']['s'] = $otherInfo[$minKey]['standard_unit'];
                        $returnData['min']['p_date'] =
                        Carbon::createFromFormat('Y-m-d H:i:s', $otherInfo[$minKey]['p_date'])->format('d-m-Y');
                       
                        $returnData['max']['b'] = $maxVal . ' / ' . $baseUntName->name;
                        $returnData['max']['s'] = $otherInfo[$maxKey]['standard_unit'];
                        $returnData['max']['p_date'] =
                        Carbon::createFromFormat('Y-m-d H:i:s', $otherInfo[$maxKey]['p_date'])->format('d-m-Y');
                        
                    }

                    $productQnt = Product::where('go_down_product_id',$goDownProductId)->select(['quantity','unit_id','packet_size']);
                    $inGoDown = GoDownProductDetail::where('go_down_product_id',$goDownProductId)->select(['quantity','go_down_product_id'])
                    ->with('goDownproduct:id,unit_category_id')->first();

                   
                    $prodQnSm = 0;
                   
                    if( $productQnt->count() > 0 ){
                        
                        foreach ($productQnt->get() as $key => $value) {
                            $allquantity = $value->quantity * $value->packet_size;
                               $quantityVal = $this->unitConversion($allquantity,$value->unit_id);
                                $prodQnSm +=$quantityVal['quantity'];
                                $unitId = $quantityVal['unit_id'];
                                
                        }
                        
                    }

                    $productQntUnit = 
                    Unit::where('unit_category_id',$inGoDown->goDownproduct->unit_category_id)
                        ->whereNull('parent_id')->select(['name'])->first();

                    $remainingInGoDown = $inGoDown->quantity - $prodQnSm;
                    $parm = ['mode'=>'standard','unit_category_id'=> $inGoDown->goDownproduct->unit_category_id ,'quantity'=>$remainingInGoDown ];
                    $standarunt =  $this->standartUnitConversion($parm);
                    $returnData['after_pack_go_down'] = $standarunt."/".$remainingInGoDown."".$productQntUnit->name;
                    return $returnData;
                }

    public function productBuyingPrcSuggestion(Request $request)
{
    $data = $this->buyingPriceCalculationLogic($request->search);
    return response()->json($data);
}


public function suggestProduct( Request $request){

    $results = Product::whereHas('goDownProducts')
                ->with([
                    'goDownProducts:id,name,slug',
                    'unit:id,name,slug',
                    ])->select(['name','slug'])->get();
            return response()->json($results);

}

public function beforSubmitForm( Request $request){
     
    $name = $request->search;
    $mode = $request->param['mode'];
    $slug = $request->param['slug'];
   

    $resultsQnr = GoDownProduct::where('name',$name);
        if($mode == 'E' ){
                $resultsQnr->whereNot('slug',$slug);
        }
        $results = $resultsQnr->count();
            return response()->json($results);

}
public function deleteGallery( Request $request){
     
    $gallery = ProductGallery::where('name', $request->filename)->first();

    if ($gallery) {
        Storage::disk('public')->delete('products/' . $gallery->image);
        $gallery->delete();
        return response()->json(['success' => 'Y', 'message' => 'Gallery image deleted']);
    }

    return response()->json(['success' => 'N', 'message' => 'Image not found']);

}
    
} //gst Final Price = (Product Price × Quantity) × (1 + GST Rate ÷ 100)