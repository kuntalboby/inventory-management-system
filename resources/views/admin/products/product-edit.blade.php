@php  $prodName = ucwords(strtolower($getInfo['product']->name)); @endphp
@section('currentPage', 'Edit '.$prodName)
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Product - '.$prodName)
    
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
                  <h3 class="card-title">Edit - {{$prodName}}</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.products')}}" class="btn btn-light">
                      Back
                    </a>
                    {{-- <a target="_blank" href="{{route('admin.products.products-vendors', [$getInfo['product']->slug]) }}" title="Vendor List" class="btn btn-light">
                       <i class="fas fa-list"></i>
                    </a> --}}
                    
                  </div>
                </div>
            
                <form id="productAction" action="{{route('admin.products.productUpdate',$getInfo['product']->slug)}}" enctype="multipart/form-data" method="post">
                 @csrf
                 @method('PUT')
                 <input type="hidden" id="prod_slug" value="{{$getInfo['product']->slug}}" />
                  <div class="card-body" id="action" mode="editProd">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parent_id ">Select Product *</label>
                                <select class="form-control goDownproductName reloadProd selectPicker suggestBuyingPrice"  name="go_down_product_id" id="go_down_product_id">
                                    <option value="">Go-down Product</option>
                                    @if (!empty($getInfo['goDownProduct']))
                                       
                                    @foreach ($getInfo['goDownProduct'] as $godownProd)
                                        <option unit-cat="{{ $godownProd->goDownProductDetails->unit->unit_category_id}}" value="{{$godownProd['id']}}" 
                                                @if ($godownProd['id'] == $getInfo['product']->go_down_product_id)
                                                    selected="selected"
                                                @endif
                                            >{{ ucwords(strtolower($godownProd['name']))}}
                                        </option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-none">
                            <div class="form-group">
                                <label for="product_code">Product Code *</label>
                                <input type="text" readonly value="{{ old('product_code', $getInfo['product']->product_code)}}" name="product_code" class="form-control" id="product_code" placeholder="Enter Product Code *">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="packet_size">Packet Size *</label>
                                <input type="number" value="{{ old('packet_size', $getInfo['product']->packet_size)}}" name="packet_size" class="form-control" 
                                id="packet_size" placeholder="Enter Packet Size *">
                                 
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit_id ">Unit*</label>
                                <select class="form-control unit_id"  name="unit_id" id="unit_id">
                                    <option value="">Select Unit</option>
                                    @if (!empty($getInfo['units']))
                                       
                                    @foreach ($getInfo['units'] as $unitIds)
                                     {{-- @if (->unit_category_id == $unitIds['unit_category_id'] ) --}}
                                        <option
                                           unit-cat-id="{{$unitIds['unit_category_id']}}" 
                                           value="{{$unitIds['id']}}"
                                        
                                              @if ($unitIds['id'] == $getInfo['product']->unit_id)
                                                    selected="selected"
                                                @endif

                                                @if ( $getInfo['product']->goDownProducts->unit_category_id !=$unitIds['unit_category_id'])
                                                        style="display:none"
                                                @endif
                                        >{{$unitIds['name']}}</option>
                                        {{-- @endif --}}
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            
                             <div class="form-group">
                                <label for="abl">Available</label>
                                <div class="form-control" id="availableQnt">
                                    {{$getInfo['suggestion']['after_pack_go_down']}}
                                </div>
                            </div>
                        </div>

                     

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity">No of Packets *</label>
                                <input type="number" readonly value="{{ old('quantity', $getInfo['product']->quantity)}}" name="quantity" class="form-control" 
                                id="quantity" placeholder="Enter Quantity *">
                                <input type="hidden" value="{{$getInfo['product']->quantity}}" name="current_packet_qunatity" id="current_packet_qunatity" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_more_qnt">Add More</label>
                                <input type="number" min="0" value="0" id="add_more_qnt" class="form-control" 
                                id="quantity" placeholder="Add more Quantity">
                            </div>
                        </div>
                        <div class="col-md-12" id="psgst">
                            <div class="form-group">
                                <label for="suggestedPrices">Price Suggestion</label>
                                <span  class="form-control" id="suggestedPrices">
                                     @if ($getInfo['suggestion'])
                                            
                                                <span  class=""><b>Lowest :</b> {{$getInfo['suggestion']['min']['s']}} ( {{$getInfo['suggestion']['min']['b'] }}) </span>
                                                <span  class=""><b>Highest :</b> {{$getInfo['suggestion']['max']['s']}} ( {{$getInfo['suggestion']['max']['b'] }}) </span>
                                                <span  class=""><b>Average :</b> {{$getInfo['suggestion']['avrg']['standard']}} ( {{$getInfo['suggestion']['avrg']['base'] }}) </span>
                                                
                                        @endif
                                </span>
                            </div>
                         </div>

                        <div class="col-md-4">
                            <label for="gst">GST</label>
                            <div class="input-group input-group-md">
                                
                                <input type="number" value="{{ old('gst', $getInfo['product']->gst)}}" step="0.01" min="0" max="100" name="gst" class="form-control" id="gst" placeholder="Enter GST if any">
                                <span class="input-group-append">
                                    <div class="btn btn-warning btn-flat"><i class="fas fa-percent"></i></div>
                                </span>
                                </div>
                        </div>
                        <div class="col-md-4">
                            <label for="buying_price">Buying Price / Packet  * </label>
                            
                            <div class="input-group input-group-md">
                                <span class="input-group-preppend">
                                    <div class="btn btn-warning btn-flat text-bold">₹</div>
                                </span>
                                 
                                <input type="number" step="0.01" min="0" value="{{ old('buying_price', $getInfo['product']->buying_price)}}" name="buying_price" class="form-control" id="buying_price" placeholder="Enter Buying price / packet *">
                                
                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="selling_price">Selling Price / Packet *</label>
                            <div class="input-group input-group-md">
                                <span class="input-group-preppend">
                                    <div class="btn btn-warning btn-flat text-bold">₹</div>
                                </span>
                                <input type="number" step="0.01" min="0" value="{{ old('selling_price', $getInfo['product']->selling_price)}}" name="selling_price" class="form-control" id="selling_price" placeholder="Enter Selling price / packet *">
                                </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Short Description </label>
                                <textarea id="description" name="description" class="form-control ckeditor" placeholder="Enter Description">{{ old('description', $getInfo['product']->description)}}</textarea>
                            </div>
                        </div>
                       
                          <input type="hidden" name="set_image" id="set_image" value="{{ $getInfo['product']->image }}" />

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type='file' class="form-control" id="image" name="image" />
                                </div>
                            </div>
                        @if ($getInfo['product']->image !='' && file_exists(public_path("storage/products/" . $getInfo['product']->image )))
                            <div class="col-md-4">
                                <img style="width: 150px; text-align: center; margin: 0 auto; display: block;" class="img-responsive" src="{{ asset('/storage/products/'.$getInfo['product']->image )}}" alt="">
                            </div>

                        @endif
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Gallery Images</label>
                                <input type='file' class="form-control" id="gallery" multiple name="gallery_image[]" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    @if ( !empty($getInfo['product']->gallery ))
                                        @foreach ($getInfo['product']->gallery as $gallery)
                                            <div class="col-md-3" id="imageg_{{$gallery->id}}">
                                                <img
                                                style="width: 150px; text-align: center; margin: 0 auto; display: block;" class="img-responsive"
                                                src="{{ asset('/storage/products/'.$gallery->name )}}" alt="">
                                                <a class="btn btn-danger" onclick="removeGallerImage('{{$gallery->name}}',{{$gallery->id}});">Remove</a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
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