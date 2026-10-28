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
                        <div class="col-md-4">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="main_unit_id ">Unit*</label>
                                <select class="form-control main_unit_id selectPicker"  name="main_unit_id" id="main_unit_id">
                                    <option value="">Select Category</option>
                                    @if (!empty($getInfo['units']))
                                       
                                    @foreach ($getInfo['units'] as $unitIds)
                                        <option value="{{$unitIds['id']}}" >{{$unitIds['name']}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
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
   
<x-validation-script :getInfo="$getInfo"></x-validation-script>

</x-admininnerlayout>
