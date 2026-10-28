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
              <div class="card card-primary card-outline">
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
                 <input type="hidden" id="prod_slug" value="" />
                  <div class="card-body" id="action" mode="addProd">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parent_id ">Select Product *</label>
                                <select class="form-control goDownproductName selectPicker suggestBuyingPrice"  name="go_down_product_id" id="go_down_product_id">
                                    <option value="">Go-down Product</option>
                                    @if (!empty($getInfo['goDownProduct']))
                                       
                                    @foreach ($getInfo['goDownProduct'] as $godownProd)

                                        <option unit-cat="{{ $godownProd->goDownProductDetails->unit->unit_category_id }}" value="{{$godownProd['id']}}" >
                                            {{ucwords(strtolower($godownProd['name']))}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-none">
                            <div class="form-group">
                                <label for="product_code">Product Code *</label>
                                <input type="text" value="{{$randCode}}" name="product_code" class="form-control" id="product_code" placeholder="Enter Product Code *">
                            </div>
                        </div>
                       
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="packet_size">Packet Size *</label>
                               <div class="input-group input-group-md">
                                <input type="number" name="packet_size" class="form-control" id="packet_size" placeholder="Enter Size of a Packet *">
                            </div>
                               
                            </div>
                        </div>
                        <div class="col-md-2">
                            
                             <div class="form-group">
                                <label for="abl">Available</label>
                                <div class="form-control" id="availableQnt"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="unit_id ">Unit*</label>
                                <select class="form-control unit_id"  name="unit_id" id="unit_id">
                                    <option value="">Select Unit</option>
                                    @if (!empty($getInfo['units']))
                                       
                                    @foreach ($getInfo['units'] as $unitIds)
                                        <option unit-cat-id="{{$unitIds['unit_category_id']}}" value="{{$unitIds['id']}}" >{{$unitIds['name']}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                <label for="quantity">No of Packets *</label>
                                <input type="number" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity *">
                            </div>
                         </div>

                         <div class="col-md-12 d-none" id="psgst">
                            <div class="form-group">
                                <label for="suggestedPrices">Price Suggestion</label>
                                <span  class="form-control" id="suggestedPrices"></span>
                            </div>
                         </div>
                        
                        <div class="col-md-4">
                            <label for="gst">GST</label>
                            <div class="input-group input-group-md">
                                
                                <input type="number" value="0" step="0.01" min="0" max="100" name="gst" class="form-control" id="gst" placeholder="Enter GST if any">
                                <span class="input-group-append">
                                    <div class="btn btn-warning btn-flat"><i class="fas fa-percent"></i></div>
                                </span>
                                </div>
                        </div>

                        
                        
                        <div class="col-md-4">
                            <label for="buying_price">Buying Price /Packet * </label>
                            
                            <div class="input-group input-group-md">
                                <span class="input-group-preppend">
                                    <div class="btn btn-warning btn-flat text-bold">₹</div>
                                </span>
                                <input type="number" step="0.01" min="0" name="buying_price" class="form-control" id="buying_price" placeholder="Enter Buying price / packet *">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="selling_price">Selling Price / Packet *</label>
                            <div class="input-group input-group-md">
                                <span class="input-group-preppend">
                                    <div class="btn btn-warning btn-flat text-bold">₹</div>
                                </span>
                                <input type="number" step="0.01" min="0" name="selling_price" class="form-control" id="selling_price" placeholder="Enter Selling price / packet *">
                                </div>
                        </div>
                        
                       
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description ( if any )</label>
                                <textarea id="description" name="description" class="form-control ckeditor" placeholder="Enter Description"></textarea>
                            </div>
                        </div>
                        
                         
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type='file' class="form-control" id="image" name="image" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Gallery Images</label>
                                <input type='file' class="form-control" id="gallery" multiple name="gallery_image[]" />
                            </div>
                        </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                   
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
  
        
            </div>

            
    
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>
