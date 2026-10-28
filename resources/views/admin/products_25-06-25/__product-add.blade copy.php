@section('currentPage', ' Add Product')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Add Product')
    
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">

              <div class="">
                <div class="alertBox" id="ajaxResponse"></div>
              @if (session('message'))
              <div class="alertBox">
                  <p class="text-center text text-{{session('response')}}">{{session('message')}}</p>
              </div>
              
              @endif
              @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            </div>
              <!-- general form elements -->
              <div class="card card-warning">
                <div class="card-header">
                  <h3 class="card-title">Add Product</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.products')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form id="productAction" action="{{route('storeProduct')}}" enctype="multipart/form-data" method="post">
                 @csrf
                 <input type="hidden" name="randForm" id="formRandCode" value="{{$formRandCode}}" />
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" name="name" class="form-control" id="title" placeholder="Enter Name *">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="product_code">Product Code *</label>
                                <input type="text" value="{{$randCode}}" name="product_code" class="form-control" id="product_code" placeholder="Enter Product Code *">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="parent_id ">Select Category *</label>
                                <select class="form-control product_category_id selectPicker"  name="category_id" id="category_id">
                                    <option value="">Select Category</option>
                                    @if (!empty($getInfo['categories']))
                                       
                                    @foreach ($getInfo['categories'] as $cate)
                                        <option value="{{$cate['id']}}" >{{$cate['name']}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-2">
                            <label for="gst">GST</label>
                            <div class="input-group input-group-md">
                                
                                <input type="number" step="0.1" min="0" max="100" name="gst" class="form-control" id="gst" placeholder="Enter GST if any">
                                <span class="input-group-append">
                                    <div class="btn btn-warning btn-flat"><i class="fas fa-percent"></i></div>
                                </span>
                                </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="short_description">Short Description </label>
                                <textarea id="short_description" name="short_description" class="form-control ckeditor" placeholder="Enter Short Description"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description </label>
                                <textarea id="description" name="description" class="form-control ckeditor" placeholder="Enter Description"></textarea>
                            </div>
                        </div>
                        
                        
                        <!--<div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select class="form-control" name="status" id="status">
                                @php
                                    $status[0]='Active';
                                    $status[1]='Inactive';
                                @endphp
                                    @foreach ($status as $ks=>$stats)
                                            <option value="{{$ks}}">{{$stats}}</option>
                                    @endforeach
                                
                            </select>
                            </div>
                        </div>-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type='file' class="form-control" id="image" name="image" accept=".png, .jpg, .jpeg" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header" style="background: #c5def0">
                              <h3 class="card-title">Add Vendors</h3>
                              <div class="card-tools">
                                <button type="button" class="btn btn-primary" onclick="addVendor();">
                                    <i class="fa fa-plus"></i>
                              </button>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5 addVendorBox" id="addVendorBox"></div>

                <div class="row d-none" id="bottomAddOption">
                    <div class="col-md-12">
                            <div class="card-header" style="">
                             
                              <div class="card-tools">
                                <button type="button" class="btn btn-primary" onclick="addVendor();">
                                    <i class="fa fa-plus"></i>
                              </button>
                              </div>
                            </div>
                        </div>
                   </div>
                  </div>
                  <!-- /.card-body -->
                   
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
  
        
            </div>

            
    
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
     <script>
let vendorIndex = 1;
function addVendor() {
    console.log(vendorIndex);
    

    
    const html = `
        <div class="vendor-group" id="vendor_${vendorIndex}">
            <div class="row">
                <div class="col-md-8"><h4 class="vendorNumber">Vendor ${vendorIndex} </h4></div>
                <div class="col-md-4 text text-danger trashVendor" onclick="removeVendor(${vendorIndex})">
                        <i class="fa fa-trash"></i>    
                    </div>
                   
            </div>
             <hr />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date *</label>
                        <div class="input-group date datetimepicker_${vendorIndex}" data-target-input="nearest">
                            <input required type="text" id="purchase_date_${vendorIndex}" name="vendors[${vendorIndex}][purchase_date]" class="form-control datetimepicker-input" data-target=".datetimepicker_${vendorIndex}"/>
                            <div class="input-group-append" data-target=".datetimepicker_${vendorIndex}" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="expiry_date">Expiry  Date</label>
                        <div class="input-group date expirypicker_${vendorIndex}" data-target-input="nearest">
                            <input type="text" id="expiry_date_${vendorIndex}" name="vendors[${vendorIndex}][expiry_date]" class="form-control datetimepicker-input" data-target=".expirypicker_${vendorIndex}"/>
                            <div class="input-group-append" data-target=".expirypicker_${vendorIndex}" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           

           <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vendor">Vendor *</label>
                         <select name="vendors[${vendorIndex}][vendor_id]" required id="vendor" class="form-control">
                            <option value="">Select Vendor</option>
                            @foreach ($getInfo['vendors'] as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="brand_id ">Brand Name</label>
                                <select class="form-control selectPicker"  name="vendors[${vendorIndex}][brand_id]" id="brand_id">
                                    <option value="">Select Brand</option>
                                    @if (!empty($getInfo['brands']))
                                       
                                    @foreach ($getInfo['brands'] as $brand)
                                        <option value="{{$brand['id']}}" >{{$brand['name']}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="batch_number">Product Batch No </label>
                                <input type="text" name="vendors[${vendorIndex}][batch_number]" class="form-control" id="batch_number" placeholder="Enter Batch No. if any">
                            </div>
                    </div>
                     <div class="col-md-6">
                            <div class="form-group">
                                <label for="buying_price">Buying Price *</label>
                                <input type="number" step="0.01" min="0" required  name="vendors[${vendorIndex}][buying_price]" class="form-control" id="buying_price" placeholder="Enter Buying Price *">
                            </div>
                    </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="selling_price">Selling Price *</label>
                                <input type="number" step="0.01" min="0" required  name="vendors[${vendorIndex}][selling_price]" class="form-control" id="selling_price" placeholder="Enter Selling Price *">
                            </div>
                    </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_quantity">Quantity *</label>
                                <input type="number" step="1" required  name="vendors[${vendorIndex}][stock_quantity]" class="form-control" id="stock_quantity" placeholder="Enter Quantity *">
                            </div>
                    </div>
                    
                    <div class="col-md-6">
                            <div class="form-group">

                                <label for="unit_id_${vendorIndex}">Unit *</label>
                                <select name="vendors[${vendorIndex}][unit_id]" required id="unit_id_${vendorIndex}" class="form-control">
                                    <option value="">Select Unit</option>
                                    @foreach ($getInfo['units'] as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                </div>
                    </div>
                    <!--<div class="col-md-12">
                            <div class="form-group">
                                <label for="description_${vendorIndex}">Note ( if any )</label>
                                <textarea id="description_${vendorIndex}" name="vendors[${vendorIndex}][description]" class="form-control ckeditor" placeholder="Enter Description"></textarea>
                            </div>
                    </div>-->
            </div>
        </div>
    `;

    // Append to DOM
    document.getElementById('addVendorBox').insertAdjacentHTML('beforeend', html);
    let counterVendor = $('.vendor-group').length;
   // alert(counterVendor)
    if( counterVendor > 1 ){
        $('#bottomAddOption').removeClass('d-none');

        $('.vendorNumber').each(function(i) {
            let afterDlt = i+1;
            $(this).html('Vendor '+afterDlt);
        });
    }

    
    // Initialize Tempus Dominus DateTime Picker for the newly added field
    $(`.datetimepicker_${vendorIndex}`).datetimepicker({
        format: 'DD-MM-YYYY',
    });
    $(`.expirypicker_${vendorIndex}`).datetimepicker({
        format: 'DD-MM-YYYY',
    });


// Add rules to dynamic fields
        $(`[name="vendors[${vendorIndex}][vendor_id]"]`).rules("add", {
            required: true,
           // messages: { required: "Vendor is required" }
        });

        $(`[name="vendors[${vendorIndex}][purchase_date]"]`).rules("add", {
            required: true,
           // messages: { required: "Purchase Date is required" }
        });

        $(`[name="vendors[${vendorIndex}][buying_price]"]`).rules("add", {
            required: true,
            number: true,
            min: 0,
           // messages: { required: "Buying price is required", number: "Must be a number" }
        });

        $(`[name="vendors[${vendorIndex}][selling_price]"]`).rules("add", {
            required: true,
            number: true,
            min: 0,
           // messages: { required: "Selling price is required", number: "Must be a number" }
        });

        $(`[name="vendors[${vendorIndex}][stock_quantity]"]`).rules("add", {
            required: true,
            digits: true,
            min: 1,
           // messages: { required: "Quantity is required", digits: "Only whole numbers" }
        });

        $(`[name="vendors[${vendorIndex}][unit_id]"]`).rules("add", {
            required: true,
            //messages: { required: "Unit is required" }
        });

    vendorIndex++;
}
</script>

</x-admininnerlayout>