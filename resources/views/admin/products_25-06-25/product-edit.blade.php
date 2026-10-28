@section('currentPage', 'Edit '.$getInfo['product']->name)
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Product')
    
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
                  <h3 class="card-title">Edit - {{$getInfo['product']->name}}</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.products')}}" class="btn btn-light">
                      Back
                    </a>
                    <a target="_blank" href="{{route('admin.products.products-vedors', [$getInfo['product']->slug]) }}" title="Vendor List" class="btn btn-light">
                       <i class="fas fa-list"></i>
                    </a>
                    
                  </div>
                </div>
            
                <form id="productAction" action="{{route('admin.products.productUpdate',$getInfo['product']->slug)}}" enctype="multipart/form-data" method="post">
                 @csrf
                 @method('PUT')
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" value="{{ old('name', $getInfo['product']->name)}}" name="name" class="form-control" id="title" placeholder="Enter Name *">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="product_code">Product Code *</label>
                                <input type="text" readonly value="{{ old('product_code', $getInfo['product']->product_code)}}" name="product_code" class="form-control" id="product_code" placeholder="Enter Product Code *">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="parent_id ">Select Category *</label>
                                <select class="form-control product_category_id selectPicker"  name="category_id" id="category_id">
                                    <option value="">Select Category</option>
                                    @if (!empty($getInfo['categories']))
                                       
                                    @foreach ($getInfo['categories'] as $cate)
                                        @if ($getInfo['product']->category_id == $cate['id'])
                                            <option value="{{$cate['id']}}" selected="selected">{{$cate['name']}}</option>
                                        @else
                                            <option value="{{$cate['id']}}" >{{$cate['name']}}</option>
                                        @endif
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-2">
                            <label for="gst">GST</label>
                            <div class="input-group input-group-md">
                                
                                <input type="number" value="{{ old('gst', $getInfo['product']->productDetails->gst)}}" step="0.1" min="0" max="100" name="gst" class="form-control" id="gst" placeholder="Enter GST if any">
                                <span class="input-group-append">
                                    <div class="btn btn-warning btn-flat"><i class="fas fa-percent"></i></div>
                                </span>
                                </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="main_unit_id ">Unit*</label>
                                <select class="form-control main_unit_id selectPicker"  name="main_unit_id" id="main_unit_id">
                                    <option value="">Select Unit</option>
                                        @foreach ($getInfo['units'] as $unit)
                                          <option value="{{ $unit->id }}" {{ $getInfo['product']->productDetails->unit_id == $unit->id ? 'selected' : '' }}>
                                              {{ $unit->name }}
                                          </option>
                                      @endforeach
                                        
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="short_description">Short Description </label>
                                <textarea id="short_description" name="short_description" class="form-control ckeditor" placeholder="Enter Short Description">{{ old('short_description', $getInfo['product']->short_description)}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description </label>
                                <textarea id="description" name="description" class="form-control ckeditor" placeholder="Enter Description">{{ old('description', $getInfo['product']->description)}}</textarea>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label for="quantity">Available Quantity</label>
                                <input type="text" readonly value="{{ old('quantity', $getInfo['product']->productDetails->quantity.' '.$getInfo['product']->productDetails->unit->name)}}" name="quantity" class="form-control" id="quantity" />
                            </div>
                        </div>
                          <input type="hidden" name="set_image" id="set_image" value="{{ $getInfo['product']->image }}" />

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type='file' class="form-control" id="image" name="image" accept=".png, .jpg, .jpeg" />
                                </div>
                            </div>
                        @if ($getInfo['product']->image !='' && file_exists(public_path("storage/products/" . $getInfo['product']->image )))
                            <div class="col-md-4">
                                <img widht="150px" alt="{{$getInfo['product']->name}}" src="{{ asset('/storage/profile-pics/'.$getInfo['product']->image )}}" />
                            </div>

                        @endif
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