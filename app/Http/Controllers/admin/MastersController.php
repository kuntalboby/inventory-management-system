<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\Customer;
use App\Models\DamagedProductsDetail;
use App\Models\GoDownProduct;
use App\Models\MasterRecord;
use App\Models\Order;
use App\Models\Pincodes;
use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\Sticker;
use App\Models\TotalSticker;
use App\Models\UnitCategory;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\admin\ParentController;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Vendor;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockReportExport;
use App\Models\GoDownProductDetail;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class MastersController extends ParentController
{
    
    public function categories()
    {

      
        $categories = Category::where(['is_deleted' => '0'])->orderBy('name')->get(); // Get all categories
        $allCategories = [];
        
        foreach ($categories as $category) {
            $allCategories[] = [
                'id' => $category->id,
                'name' => ucwords(strtolower($category->name)) ,
                'slug' => $category->slug,
                'status' => $category->status,
                'parent_tree' => Category::getParentTree($category) // Get all parents in a single key
            ];
        }
       
        return view('admin.masters.categories',compact('allCategories'));
        // Return structured response
    }
    public function categoriesAdd()
    {
        $categories = Category::where(['is_deleted' => '0','bulk_for_backend'=>'0'])->orderBy('name')->get(); // Get all categories
        $allCategories = Category::getCategoryTree($categories);
        return view('admin.masters.categories-add',compact('allCategories'));
        // Return structured response
    }
    public function categoryStore(Request $request)
    {
        //dd($request->file);
        $request->validate([
            'name' => [
                'required',
                'max:250',
                'min:2',
                'unique:categories,name'
            ],
            'image' => 'nullable|mimes:png,jpg,jpeg|max:3000', // Added nullable for optional image
            'description' => [
                'nullable', // Description can be empty
                'max:1200',
                'min:2',
            ],
            'slug'=>[
                Rule::unique('categories')
            ],
           'parent_id' => [
                'nullable',
                'numeric',
                
                    function ($attribute, $value, $fail) {
                        if ($value != 0 && ! Category::where('id', $value)->exists()) {
                            $fail('The selected parent category is invalid.');
                        }
                    },
],
           // Ensure status has valid values
        ]);
        

        if ($request->file('image') != null){

            $categoryImage = $request->file('image');
            $catImagename = $categoryImage->hashName();
            $request->image->storeAs('products/categories',$catImagename,'public');
            $savedInfo = array_merge($request->all(), ['image' =>  $catImagename]);
        }else{
            $savedInfo  = $request->all();
        }

        $updateRes = Category::create($savedInfo);
       
        if($updateRes){
            
            $note = Auth::user()->first_name.' has added a category <a href="' . route('admin.masters.categories') . '" class="text-link">'.$request->name.'.</a>';
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'note'=> $note,
                        ];
                        $this->trackRecords($params);
            
            session()->flash('response','success');
            return redirect()->route('admin.masters.categories')->with('message','Information has been added successfully.');
            
            
        }else{
            session()->flash('response','danger');
            return redirect()->back()->with('message','Information has not been added.')->withInput();
        }
    }

    public function categoriesEdit(string $slug)
    {

        
        $categories = Category::where(['is_deleted' => '0'])->get();
        $getInfo = $categories->firstWhere('slug', $slug);

        $allCategories = Category::getCategoryTree($categories);
       // return $getInfo;
        return view('admin.masters.categories-edit',compact('allCategories','getInfo'));
        // Return structured response
    }
    public function categoriesUpdate(Request $request, string $slug)
{
    // Find the category (returns a single model, not a collection)
    $category = Category::where('slug', $slug)->firstOrFail();

    // Validate request data
    $request->validate([
       
        'name' => [
            'required',
            'string',
            'max:255',
            'min:2',
             Rule::unique('categories')->ignore($slug, 'slug')
        ],
        'image' => 'nullable|mimes:jpg,jpeg,png|max:3000', // Case-insensitive check for image types
        'description' => 'nullable|max:1200|min:2',
        'parent_id' => 'nullable|exists:categories,id',
       
    ]);

    // Generate new slug
    $requestData['slug'] = \Illuminate\Support\Str::slug($request->name, '-');

    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image');
        $imageFileName = $imagePath->hashName();
        $request->image->storeAs('products/categories', $imageFileName, 'public');

        // Delete old image if it exists
        if (!empty($category->image)) {
            $oldImagePath = public_path("storage/products/categories/" . $category->image);
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }

        $newData['image'] = $imageFileName;
    } else {
        $newData['image'] = $category->image; 
    }
    $newData['slug'] = \Illuminate\Support\Str::slug($request->name, '-'); 
    // Merge request data with the image file name
    $requestData = array_merge($request->except(['_token', '_method']), $newData );

    // Update the category
    $updateRes = $category->update($requestData);

    // Redirect based on success or failure
    if ($updateRes) {
        
         $note = Auth::user()->first_name.' has updated category - <a href="' . route('admin.masters.categories') . '" class="text-link">'.$request->name.'.</a>';
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'note'=> $note,
                        ];
                        $this->trackRecords($params);
                        
        session()->flash('response', 'success');
        return redirect()->route('admin.masters.categories')->with('message', 'Information has been updated successfully.');
    } else {
        session()->flash('response', 'danger');
        return redirect()->back()->with('message', 'Information has not been updated.')->withInput();
    }
}


//unit

public function unit()
    {
        $unts = Unit::with('parent:id,name')->orderBy('name')->get(); 
        
        $allUnit = [];
      //  return $unts;
        foreach ($unts as $unt) {
            $allUnit[] = [
                'id' => $unt->id,
                'name' => $unt->name,
                'parent_id' => !empty($unt->parent->name) ? $unt->parent->name : null,
                'slug' => $unt->slug,
                'unit_category_id' => $unt->unit_category_id,
                'status' => $unt->status,
            ];
        }
        //return $allCategories;
        return view('admin.masters.unit',compact('allUnit'));
        // Return structured response
    }
 public function unitAdd()
    {
        $allUnit = Unit::orderBy('name')->get();
        $unitCats = UnitCategory::orderBy('name')->get(); 
        return view('admin.masters.unit-add',compact('allUnit','unitCats'));
        // Return structured response
    }
    public function unitStore(Request $request)
    {
        //dd($request->file);
        $request->validate([
            'name' => [
                'required',
                'max:250',
                'min:2',
                'unique:units,name'
            ],
            'unit_category_id'=>'required|exists:unit_categories,id',
            'conversion_factor' => 'required', // Ensure status has valid values
        ]);
        

        $savedInfo  = $request->all();

        $updateRes = Unit::create($savedInfo);
       
        if($updateRes){
             $note = Auth::user()->first_name.' has added an unit <a href="' . route('admin.masters.unit') . '" class="text-link">'.$request->name.'.</a>';
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'note'=> $note,
                        ];
                        $this->trackRecords($params);
            session()->flash('response','success');
            return redirect()->route('admin.masters.unit')->with('message','Information has been added successfully.');
        }else{
            session()->flash('response','danger');
            return redirect()->back()->with('message','Information has not been added.');
        }
    }

    
    public function unitEdit(string $slug)
    {
        $getInfo = Unit::where(['slug'=>$slug])->firstOrFail();
        $unts = Unit::orderBy('name')->get(); 
        $unitCats = UnitCategory::orderBy('name')->get(); 

       // return $getInfo->slug;
        return view('admin.masters.unit-edit',compact('unts','getInfo','unitCats'));
        // Return structured response
    }
    public function unitUpdate(Request $request, string $slug)
{
    // Find the category (returns a single model, not a collection)
    $unit = Unit::where('slug', $slug)->firstOrFail();

    // Validate request data
     $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'min:2',
             Rule::unique('units')->ignore($slug, 'slug'),
        ],
        'unit_category_id'=>'required|exists:unit_categories,id',
        'conversion_factor' => 'required',
    ]);
    $newData['slug'] = \Illuminate\Support\Str::slug($request->name, '-'); 
    // Merge request data with the image file name
    $requestData = array_merge($request->except(['_token', '_method']), $newData );

    // Update the category
    $updateRes = $unit->update($requestData);

    // Redirect based on success or failure
    if ($updateRes) {
        $note = Auth::user()->first_name.' has updated unit <a href="' . route('admin.masters.unit') . '" class="text-link">'.$request->name.'.</a>';
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'note'=> $note,
                        ];
                        $this->trackRecords($params);
                        
        session()->flash('response', 'success');
        return redirect()->route('admin.masters.unit')->with('message', 'Information has been updated successfully.');
    } else {
        session()->flash('response', 'danger');
        return redirect()->back()->with('message', 'Information has not been updated.');
    }
}


//brand

public function brands()
    {
        $brands = Brand::where(['is_deleted' => '0'])->orderBy('name')->get(); // Get all categories
        $allbrands = [];
    
        foreach ($brands as $brand) {
            $allbrands[] = [
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'status' => $brand->status,
            ];
        }
        //return $allCategories;
        return view('admin.masters.brands',compact('allbrands'));
        // Return structured response
    }
 public function brandAdd()
    {
        $allbrand = Brand::where(['is_deleted' => '0'])->orderBy('name')->get(); 
       
        return view('admin.masters.brand-add',compact('allbrand'));
        // Return structured response
    }
    public function brandStore(Request $request)
    {
        //dd($request->file);
       
        $validated = $request->validate([
        'name' => 'required|string|max:200|min:2',
        'status' => 'required|in:0,1',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072', // Max size in KB (3MB)
    ]);
        if ($request->file('image') != null){

            $site_thum_logo_path = $request->file('image');
            $brandImageName = $site_thum_logo_path->hashName();
            $request->image->storeAs('ecommerce/brands',$brandImageName,'public');

        }else{
             $brandImageName = null;
        }  
        $addRequest = Brand::create([
        'name' => $validated['name'],
        'status' => $validated['status'],
        'description' => $validated['description'],
        'image' => $brandImageName,
    ]);
        if($addRequest){
            session()->flash('response','success');
            return redirect()->route('admin.masters.brands')->with('message','Information has been added successfully.');
        }else{
            session()->flash('response','danger');
            return redirect()->back()->with('message','Information has not been added.');
        }
    }

    
    public function brandEdit(string $slug)
    {
        $getInfo = Brand::where(['is_deleted' => '0','slug'=>$slug])->firstOrFail();

       // return $getInfo->slug;
        return view('admin.masters.brand-edit',compact('getInfo'));
        // Return structured response
    }
    public function brandUpdate(Request $request, string $slug)
{
    // Find the category (returns a single model, not a collection)
    $brand = Brand::where('slug', $slug)->firstOrFail();

    // Validate request data
    $validated = $request->validate([
        'name' => 'required|string|max:200|min:2',
        'status' => 'required|in:0,1',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072', // Max size in KB (3MB)
    ]);

    if ($request->file('image') != null){

            $site_thum_logo_path = $request->file('image');
            $brandImageName = $site_thum_logo_path->hashName();
            $request->image->storeAs('ecommerce/brands',$brandImageName,'public');

            $brandImg = public_path("storage")."/ecommerce/brands/". $brand->image;
     
            if(file_exists($brandImg)){
                @unlink($brandImg);
            }

        }else{
             $brandImageName = $brand->image;
        }
    $newData['image'] = $brandImageName;
    $newData['slug'] = \Illuminate\Support\Str::slug($request->name, '-'); 
    // Merge request data with the image file name
    $requestData = array_merge($request->except(['_token', '_method']), $newData );

    // Update the category
    $updateRes = $brand->update($requestData);

    // Redirect based on success or failure
    if ($updateRes) {
        session()->flash('response', 'success');
        return redirect()->route('admin.masters.brands')->with('message', 'Information has been updated successfully.');
    } else {
        session()->flash('response', 'danger');
        return redirect()->back()->with('message', 'Information has not been updated.');
    }
}

//warehouses


public function warehouses()
    {
        $warehouses = Warehouse::where(['is_deleted' => '0'])->orderBy('name')->get(); // Get all categories
        $allWarehouses = [];
    
        foreach ($warehouses as $warehouse) {
            $allWarehouses[] = [
                'id' => $warehouse->id,
                'name' => $warehouse->name,
                'slug' => $warehouse->slug,
                'address' => $warehouse->address,
                'contact_person' => $warehouse->contact_person,
                'phone_1' => $warehouse->phone_1,
                'phone_2' => $warehouse->phone_2,
                'pin' => $warehouse->pin,
                'state' => $warehouse->state,
                'status' => $warehouse->status,
            ];
        }
        //return $allCategories;
        return view('admin.masters.warehouses',compact('allWarehouses'));
        // Return structured response
    }
 public function warehouseAdd()
    {
        $allWarehouses = Warehouse::where(['is_deleted' => '0'])->orderBy('name')->get(); 
       
        return view('admin.masters.warehouse-add',compact('allWarehouses'));
        // Return structured response
    }
    public function warehouseStore(Request $request)
    {
        //dd($request->file);
        $request->validate([
            'name' => [
                'required',
                'max:250',
                'min:2',
            ],
            'contact_person' => [
                'required',
                'max:250',
                'min:2',
            ],
            'phone_1' => [
                'required',
                'max:250',
                'min:2',
            ],
            'status' => 'required', // Ensure status has valid values
        ]);
        

        $savedInfo  = $request->all();

        $updateRes = Warehouse::create($savedInfo);
       
        if($updateRes){
            session()->flash('response','success');
            return redirect()->route('admin.masters.warehouses')->with('message','Information has been added successfully.');
        }else{
            session()->flash('response','danger');
            return redirect()->back()->with('message','Information has not been added.');
        }
    }

    
    public function warehouseEdit(string $slug)
    {
        $getInfo = Warehouse::where(['is_deleted' => '0','slug'=>$slug])->firstOrFail();

       // return $getInfo->slug;
        return view('admin.masters.warehouse-edit',compact('getInfo'));
        // Return structured response
    }
    public function warehouseUpdate(Request $request, string $slug)
{
    // Find the category (returns a single model, not a collection)
    $warehouse = Warehouse::where('slug', $slug)->firstOrFail();

    // Validate request data
    $request->validate([
        'name' => 'required|max:250|min:2',
        'contact_person' => 'required|max:250|min:2',
        'phone_1' => 'required|max:15|min:10',
        'status' => 'required',
    ]);
    $newData['slug'] = \Illuminate\Support\Str::slug($request->name, '-'); 
    // Merge request data with the image file name
    $requestData = array_merge($request->except(['_token', '_method']), $newData );

    // Update the category
    $updateRes = $warehouse->update($requestData);

    // Redirect based on success or failure
    if ($updateRes) {
        session()->flash('response', 'success');
        return redirect()->route('admin.masters.warehouses')->with('message', 'Information has been updated successfully.');
    } else {
        session()->flash('response', 'danger');
        return redirect()->back()->with('message', 'Information has not been updated.');
    }
}

// vendors 

public function vendors()
    {
        $vendors = Vendor::where(['is_deleted' => '0'])->orderBy('name')->get(); // Get all categories
        $allVendors = [];
    
        foreach ($vendors as $vendor) {
            $allVendors[] = [
                'id' => $vendor->id,
                'name' => ucwords(strtolower($vendor->name)),
                'slug' => $vendor->slug,
                'contact_person' => $vendor->contact_person,
                'phone_1' => $vendor->phone_1,
                'city' => $vendor->city,
                'state' => $vendor->state,
                'status' => $vendor->status,
            ];
        }
       // return $allvendors;
        return view('admin.masters.vendors',compact('allVendors'));
        // Return structured response
    }
 public function vendorAdd()
    {
        $allvendors = Vendor::where(['is_deleted' => '0'])->orderBy('name')->get(); 
       
        return view('admin.masters.vendor-add',compact('allvendors'));
        // Return structured response
    }
    public function vendorstore(Request $request)
    {
        //dd($request->file);
        $request->validate([
            'name' => [
                'required',
                'max:250',
                'min:2',
                'unique:vendors,name'
            ],
            'contact_person' => [
                'required',
                'max:250',
                'min:2',
            ],
            'phone_1' => [
                'required',
                'max:250',
                'min:2',
            ],
            'status' => 'required', // Ensure status has valid values
        ]);
        

        $savedInfo  = $request->all();

        $updateRes = Vendor::create($savedInfo);
       
        if($updateRes){
            
            $note = Auth::user()->first_name.' has added a vendor - <a href="' . route('admin.masters.vendors') . '" class="text-link">'.$request->name.'</a>';
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'note'=> $note,
                        ];
                        $this->trackRecords($params);
                        
            session()->flash('response','success');
            return redirect()->route('admin.masters.vendors')->with('message','Information has been added successfully.');
        }else{
            session()->flash('response','danger');
            return redirect()->back()->with('message','Information has not been added.')->withInput();
        }
    }

    
    public function vendorEdit(string $slug)
    {
        $getInfo = Vendor::where(['is_deleted' => '0','slug'=>$slug])->firstOrFail();

        //return $getInfo->slug;
        return view('admin.masters.vendor-edit',compact('getInfo'));
        // Return structured response
    }

    public function vendorView(string $slug)
    {
       $vendor = Vendor::where('slug', $slug)->firstOrFail();

            // Get all product vendors for this vendor, eager load product and unit
            // $productVendors = $vendor->productVendors()
            //     ->with('goDownProduct:id,name')
            //     ->with('unit:id,name')
            //     ->orderBy('purchase_date')
            //     ->get();

            //     return $productVendors;
            // // Group by purchase_date
            // $getInfo = $productVendors->groupBy('purchase_date');

        //return $getInfo;
        return view('admin.masters.vendor-view',compact('slug','vendor'));
        // Return structured response
    }
    public function vendorProdView(Request $request){
        if ($request->ajax()) {
            //return $request;
            $slug = $request->slug;
                $requestedPostsData = $request->all();

                $draw = $requestedPostsData['draw'];
                $row = $requestedPostsData['start'];
                $rowperpage = $requestedPostsData['length'];
                $columnIndex = $requestedPostsData['order'][0]['column'];
                $columnName = $requestedPostsData['columns'][$columnIndex]['data'];
                $columnSortOrder = $requestedPostsData['order'][0]['dir'];
        $vendor = Vendor::where('slug', $slug)->firstOrFail();

       $query = ProductVendor::query()
                ->whereHas('goDownProduct')
                ->where('vendor_id', $vendor->id)
                ->leftJoin('go_down_products', 'product_vendors.go_down_product_id', '=', 'go_down_products.id')
                ->leftJoin('units', 'product_vendors.unit_id', '=', 'units.id')
                ->select(
                    'product_vendors.*',
                    'go_down_products.name as product_name',
                    'units.name as unit_name'
                );
               
            

        if (!empty($request->purchase_date_from) && empty($request->purchase_date_to)) {
                $dateFrom = Carbon::createFromFormat('d-m-Y', $request->purchase_date_from)->format('Y-m-d');
                $query->where('purchase_date','>=', $dateFrom);
        }elseif(!empty($request->purchase_date_to) && empty($request->purchase_date_from)) {

            $dateTo = Carbon::createFromFormat('d-m-Y', $request->purchase_date_to)->format('Y-m-d');
            
            $query->where('purchase_date','<=', $dateTo);
        }elseif(!empty($request->purchase_date_from) && !empty($request->purchase_date_to)){
            $dateFrom = Carbon::createFromFormat('d-m-Y', $request->purchase_date_from)->format('Y-m-d');
            $dateTo = Carbon::createFromFormat('d-m-Y', $request->purchase_date_to)->format('Y-m-d');

            $query->whereBetween('purchase_date', [$dateFrom, $dateTo]);
    }
              
            $query->whereHas('goDownProduct', function ($q) use ($request) {
                
                if ( isset($request->name) && !empty($request->name) ) {
                     $q->where('name', 'like', '%' . $request->name . '%');
                }


            });

        

        
        

        $productsCollection = $query
            ->orderBy($columnName, $columnSortOrder)
            ->get();

        

        $totalRecords = ProductVendor::where('vendor_id',$vendor->id)->count();
        $totalFiltered = $productsCollection->count();

        // Pagination manually after filtering
        $paginatedProducts = $productsCollection->slice($row, $rowperpage);

        $products = [];

       // return $paginatedProducts;

        foreach ($paginatedProducts as $i => $rowVal) {
           
           
            $products[$i]['sl'] =  $i+1;
            $products[$i]['product_name'] =  ucwords(strtolower($rowVal->goDownProduct->name));
            $products[$i]['purchase_date'] = date('d-m-Y', strtotime($rowVal->purchase_date));
            $products[$i]['actual_price'] = '₹'.$rowVal->actual_price;
            $products[$i]['gst'] = $rowVal->gst. ' %';
            $products[$i]['quantity'] = $rowVal->stock_quantity .' '. ucwords(strtolower($rowVal->unit->name));
            $products[$i]['buying_price'] = '₹'.$rowVal->buying_price;
            
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFiltered,
            "data" => $products
        ]);
    }
    }
    
    public function vendorUpdate(Request $request, string $slug)
{
    // Find the category (returns a single model, not a collection)
    $warehouse = Vendor::where('slug', $slug)->firstOrFail();

    // Validate request data
    $request->validate([
       'name' => [
            'required',
            'string',
            'max:255',
            'min:2',
             Rule::unique('vendors')->ignore($slug, 'slug'),
        ],
        'contact_person' => 'required|max:250|min:2',
        'phone_1' => 'required|max:15|min:10',
        'status' => 'required',
    ]);
    $newData['slug'] = \Illuminate\Support\Str::slug($request->name, '-'); 
    // Merge request data with the image file name
    $requestData = array_merge($request->except(['_token', '_method']), $newData );

    // Update the category
    $updateRes = $warehouse->update($requestData);

    // Redirect based on success or failure
        if ($updateRes) {
            $note = Auth::user()->first_name.' has updated a vendor - <a href="' . route('admin.masters.vendors') . '" class="text-link">'.$request->name.'</a>';
                            $params['update_fields'] = [
                                'user_id'=> Auth::user()->id,
                                'note'=> $note,
                            ];
                            $this->trackRecords($params);
            session()->flash('response', 'success');
            return redirect()->route('admin.masters.vendors')->with('message', 'Information has been updated successfully.');
        } else {
            session()->flash('response', 'danger');
            return redirect()->back()->with('message', 'Information has not been updated.')->withInput();;
        }
    }

    public function reports(Request $request){
       
         return view('admin.masters.reports');
    }

         public function reportGenerate(Request $request)
            {
                $reportType = $request->input('report_type');
                $dateFrom_ = $request->input('date_from');
                $dateTo_ = $request->input('date_to');

                // Convert dates to Y-m-d format
                if ($dateFrom_) {
                    $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom_)->format('Y-m-d');
                }

                if ($dateTo_) {
                    $dateTo = Carbon::createFromFormat('d-m-Y', $dateTo_)->format('Y-m-d');
                }

                if($reportType == 'DRPT' ){
                        // trigger cron job file to get todays record
                    Artisan::call('daily:report-generator', [
                                'source' => 'Controller',
                            ]);


                        $fileName = 'daily_stock_report';
                }else{
                        $fileName = 'daily_sales_report';
                }

    

                return Excel::download(
                    new StockReportExport($reportType, $dateFrom, $dateTo,[]),
                    $fileName.'_'.$dateFrom_.'-'.$dateTo_.'.xlsx'
                );
            }

            //customers
       public function customers(){

        return view('admin.masters.customers');
       } 


       public function ajaxReportGenerate(Request $request)
{
    $reportType = $request->report_type;

    if ($reportType === 'ORD') {
        $invoice_number = $request->invoice_number;
        $phone = $request->phone;
        $name = $request->name;
        $dateFrom_ = $request->date_from;
        $dateTo_ = $request->date_to;
        $ordered_from = $request->ordered_from;

        $dateFrom = $dateTo = '';

        if ($dateFrom_) {
            $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom_)->format('Y-m-d');
        }

        if ($dateTo_) {
            $dateTo = Carbon::createFromFormat('d-m-Y', $dateTo_)->format('Y-m-d');
        }

        $extraParam = compact('name', 'phone', 'invoice_number','ordered_from');

        $fileName = 'Order_' . $dateFrom_ . '-' . $dateTo_ . '.xlsx';
        return Excel::download(
            new StockReportExport($reportType, $dateFrom, $dateTo, $extraParam),
            $fileName
        );
    }

    abort(400, 'Invalid Report Type');
}


public function customerlist(Request $request)
{
    if ($request->ajax()) {
        $requestedPostsData = $request->all();

        $draw = $requestedPostsData['draw'];
        $row = $requestedPostsData['start'];
        $rowperpage = $requestedPostsData['length'];
        $columnIndex = $requestedPostsData['order'][0]['column'];
        $columnName = $requestedPostsData['columns'][$columnIndex]['data'];
        $columnSortOrder = $requestedPostsData['order'][0]['dir'];

        // Start query
        $query = Customer::select('customers.*');
        $query->whereHas('orders');
        //return $query;
        // Filter by phone
        if (!empty($request->phone)) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        // Filter by name (first or last)
        if (!empty($request->name)) {
            $query->where(function ($sub) use ($request) {
                $sub->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%');
            });
        }

        // Filter by order date range using created_at
        if (!empty($request->date_from) || !empty($request->date_to)) {
            $query->whereHas('orders', function ($orderQuery) use ($request) {
                try {
                    if (!empty($request->date_from) && empty($request->date_to)) {
                        $from = Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d');
                        $orderQuery->whereDate('order_date', '>=', $from);
                    } elseif (!empty($request->date_to) && empty($request->date_from)) {
                        $to = Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d');
                        $orderQuery->whereDate('order_date', '<=', $to);
                    } elseif (!empty($request->date_from) && !empty($request->date_to)) {
                        $from = Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d');
                        $to = Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d');
                        $orderQuery->whereBetween('order_date', [$from, $to]);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Invalid date format in order filter: " . $e->getMessage());
                }
            });
        }

        // Unique by phone (optional)
        //$query->distinct('phone');

        $totalRecords = $query->count();

        // Apply ordering and pagination
        $customersCollection = $query
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();

        // Format response
        $customers = [];

        foreach ($customersCollection as $i => $rowVal) {
            $customers[$i]['first_name'] = ucwords(strtolower($rowVal->first_name ?? ''));
            $customers[$i]['last_name'] = ucwords(strtolower($rowVal->last_name ?? ''));
            $customers[$i]['email'] = $rowVal->email ?? '';
            $customers[$i]['phone'] = $rowVal->phone ?? '';
            $customers[$i]['URL'] = '<a href="' . route('admin.masters.customer-view', $rowVal->phone) . '" class="btn btn-sm btn-info">View</a>';
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "data" => $customers
        ]);
    }
}

       
    public function customerView( $phone){
              
        $getInfo = Customer::with(['orders.orderDetails.product:id,name'])
                    ->where('phone', $phone)
                    ->whereHas('orders', function ($q) {
                        $q->orderByDesc('order_date');            
                    })

                    ->firstOrFail();

                //return $getInfo;
        return view('admin.masters.customer-view',compact('getInfo'));
       } 


      
       //stickers

    public function stickerSum(){
        $stickers = TotalSticker::whereHas('sticker')
        ->with('sticker:sticker_code,name')->distinct('sticker_code')->orderByDesc('id')->get(); // Get all categories
        $allSticker = [];
    
        //return $stickers;
        foreach ($stickers as $stickerD) {
            $allSticker[] = [
                'id' => $stickerD->id,
                'name' => $stickerD->sticker[0]->name,
                'quantity' => $stickerD->quantity,
                'sticker_code' => $stickerD->sticker_code
               ];

        }
        //$randCode = $this->randnoGenerator(4).''.$this->randnoGenerator(6);
        //return $allSticker;
        return view('admin.masters.sticker-sum',compact('allSticker'));
    }

       
public function stickers( string $sticker_code)
    {
        $stickers = Sticker::where([ 'is_deleted' => '0','sticker_code'=>$sticker_code ])->orderBy('name')->get(); // Get all categories
        $allSticker = [];
    
        foreach ($stickers as $sticker) {
            $allSticker[] = [
                'id' => $sticker->id,
                'name' => $sticker->name,
                'quantity' => $sticker->quantity,
                'slug' => $sticker->slug,
                'action_date'=>$sticker->action_date,
                'mode' => $sticker->mode,
                'sticket_code'=>$sticker->sticket_code
            ];

        }
        //$randCode = $this->randnoGenerator(4).''.$this->randnoGenerator(6);
        //return $allSticker;
        return view('admin.masters.stickers',compact('allSticker'));
        // Return structured response
    }


     public function stickerStore(Request $request){
            $request->validate([
                        'name' => [
                            'required',
                            'max:250',
                            'min:2',
                        ],
                        'action_date'=>'required',
                        'quantity'=>'numeric|required',
                        'mode' => 'required',
                    ]);

                   // return $request->all();
                    $alreadyPsent = Sticker::where('name',$request->name)->select('sticker_code');

                    $stkrCode = $this->randnoGenerator(5).''.$this->randnoGenerator(4);
                    if( $alreadyPsent->count() > 0 ){
                        $stkrCodeArr = $alreadyPsent->first();
                        $stkrCode = $stkrCodeArr->sticker_code;
                    } 
                    $request['sticker_code'] = $stkrCode;
                    $request['action_date'] = date('Y-m-d',strtotime($request->action_date));

                    
                    $create = Sticker::create($request->all());


                    $totalStck = TotalSticker::where('sticker_code',$stkrCode);

                
                    
                    if( $totalStck->count() > 0 ){
                        $update = $totalStck->first();

                        if( $request->mode == '1' ){
                                $marked = 'Sold';
                                $sUpdt['quantity'] =  $update->quantity - $request->quantity;
                        }else{
                                $marked = 'Bought';
                                $sUpdt['quantity'] =  $update->quantity + $request->quantity;
                        }

                         
                         $update->update($sUpdt);
                       
                    }else{
                         $marked = 'Bought';
                        if( $request->mode == '1' ){
                                $marked = 'Sold';

                        }
                        $newStkr['sticker_code'] = $stkrCode;
                        $newStkr['quantity'] = $request->quantity;
                        TotalSticker::create($newStkr);
                    }

                      $note = '<a href="'. route('admin.masters.sticker-sum').'">'.$request->quantity.' stickers </a> have been marked as <b> '.$marked.' </b> by the '.Auth::user()->first_name;
                            $params['update_fields'] = [
                                'user_id'=> Auth::user()->id,
                                'note'=> $note,
                            ];
                            $this->trackRecords($params);
                        session()->flash('response','success');
                        return redirect()->route('admin.masters.sticker-sum')->with('message','Information has been added successfully.');
                    

                 

     }

    
    public function stickerEdit(string $slug)
    {
        $getInfo = Sticker::where(['is_deleted' => '0','slug'=>$slug])->firstOrFail();
        return view('admin.masters.sticker-edit',compact('getInfo'));
       
    }
    public function stickerUpdate(Request $request, string $slug)
{
    // Find the category (returns a single model, not a collection)
    $sticker = Sticker::where('slug', $slug)->firstOrFail();
    $oldQunt = $sticker->quantity;
    // Validate request data
    $request->validate([
        'name' => 'required|max:250|min:2',
        'mode' => 'required',
        'action_date' => 'required',
        'quantity' => 'required',
    ]);
    //$newData['slug'] = \Illuminate\Support\Str::slug($request->name, '-'); 
    // Merge request data with the image file name
    //$requestData = array_merge($request->except(['_token', '_method']), $newData );

    $request['action_date'] = date('Y-m-d',strtotime($request->action_date));
    $updateRes = $sticker->update($request->all());

    // Redirect based on success or failure
    if ($updateRes) {
         $update = TotalSticker::where('sticker_code',$sticker->sticker_code)->first();



         if( $request->mode == '1' ){
                $sUpdt['quantity'] =  $update->quantity + $oldQunt - $request->quantity;
        }else{
                $sUpdt['quantity'] =  $update->quantity - $oldQunt + $request->quantity;
        }
         //return $sUpdt;
             $update->quantity = $sUpdt['quantity'];
             $update->save();

        $note = Auth::user()->first_name.' has updated sticker <a href="' . route('admin.masters.sticker-sum') . '" class="text-link">'.$request->name.'.</a>';
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'note'=> $note,
                        ];
                        $this->trackRecords($params);
                        
        session()->flash('response', 'success');
        return redirect()->route('admin.masters.sticker-sum')->with('message', 'Information has been updated successfully.');
    } else {
        session()->flash('response', 'danger');
        return redirect()->back()->with('message', 'Information has not been updated.')->withInput();
    }
}

 public function getStickers(Request $request){
    
       

        $results = Sticker::where('mode', '0')
            ->where('name', 'like', '%' . $request->search . '%')
            ->whereHas('totalsticker', function ($q) {
                $q->where('quantity', '>', 0);
            })
            ->with('totalsticker:sticker_code,quantity')
            ->get()
            ->unique('sticker_code')
            ->values();

        return response()->json($results);
      }


      
 public function masterAutocomplete(Request $request){
   // return $request;
    $table = $request->section;
    $results = [];
    if( $table == 'categories'){
         $results = Category::where('name', 'like', '%' . $request->search . '%');
            if( isset($request->slug) && !empty($request->slug)){
                $results->whereNot('slug',$request->slug);
            }
            $results = $results->get(['name','slug']);

    }elseif( $table == 'vendors'){
         $results = Vendor::where('name', 'like', '%' . $request->search . '%');
            if( isset($request->slug) && !empty($request->slug)){
                $results->whereNot('slug',$request->slug);
            }
            $results = $results->get(['name','slug']);
    }elseif( $table == 'units'){
         $results = Unit::where('name', 'like', '%' . $request->search . '%');
            if( isset($request->slug) && !empty($request->slug)){
                $results->whereNot('slug',$request->slug);
            }
            $results = $results->get(['name','slug']);
    }elseif( $table == 'godownprod'){
         $results = GoDownProduct::where('name', 'like', '%' . $request->search . '%');
            if( isset($request->slug) && !empty($request->slug)){
                $results->whereNot('slug',$request->slug);
            }
            $results = $results->get(['name','slug']);
    }

    return response()->json($results);  
    }

    public function getAllStickers( Request $request){

       // return $request;
        $getS = Sticker::where('mode','0')
        ->where('name', 'like', '%' . $request->search . '%')
        ->select('name','sticker_code')->distinct('sticker_code')->get();
        return response()->json($getS);  
    }

    public function pincodes(Request $request){
         $pincodes = Pincodes::get();
         return view('admin.masters.pincodes',compact('pincodes'));
    }

    
    public function pinchargesUpdate(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('excel_file');
        $data = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath())
                    ->getActiveSheet()
                    ->toArray();

        Pincodes::truncate();
        // Skip the header row (assuming 1st row is header)
        foreach (array_slice($data, 1) as $row) {
            $trimmedCharges = $row[1];
            if(!empty($trimmedCharges)){
                $trimmedCharges = $row[1];
            }else{
                $trimmedCharges = 0;
            }

            Pincodes::create([
                'pin_code' => trim($row[0]),
                'charges' => (float)$trimmedCharges,
            ]);
        }

        return back()->with('success', 'Information has been uploaded successfully!');
    }
}