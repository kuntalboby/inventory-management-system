@section('currentPage', ' Add Damaged Product')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Add Damaged Items')
    
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <col-md-12>
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
            </col-md-12>
              <!-- general form elements -->
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Add Damaged Items</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.products.damaged')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.products.damageStore')}}" enctype="multipart/form-data" method="post" id="damagedProducts">
                 @csrf
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-3">
                            <div class="form-group">
                                <label for="damaged_date">Date *</label>
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                    <input type="text" name="damaged_date" class="form-control datetimepicker-input"
                                                           data-target="#datetimepicker1" placeholder="DD-MM-YYYY"
                                                           value="{{old('damaged_date')}}">
                                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="parent_id ">Select Product *</label>
                                <select class="form-control goDownproductName selectPicker"  name="go_down_product_id" id="go_down_product_id">
                                    <option value="">Go-down Product</option>
                                    @if (!empty($getInfo['products']))
                                       
                                    @foreach ($getInfo['products'] as $godownProd)
                                        <option unit-cat="{{ $godownProd->goDownProductDetails->unit->unit_category_id}}" 
                                          
                                          value="{{$godownProd['id']}}" 
                                          @if ( old('go_down_product_id') == $godownProd['id'])
                                            selected="selected"
                                          @endif
                                          >{{ucwords(strtolower($godownProd['name']))}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                        
                      <div class="col-md-3">
                            <div class="form-group">
                                <label for="quantity">Quantity * </label>
                                <input type="number" value="{{old('quantity')}}" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity *">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit_id ">Unit*</label>
                                <select class="form-control unit_id"  name="unit_id" id="unit_id">
                                    <option value="">Select Unit</option>
                                    @if (!empty($getInfo['units']))
                                       
                                    @foreach ($getInfo['units'] as $unitIds)
                                        <option unit-cat-id="{{$unitIds['unit_category_id']}}" 
                                        @if ( old('unit_id') == $unitIds['id'])
                                            selected="selected"
                                          @endif
                                        value="{{$unitIds['id']}}" style="display: none">{{$unitIds['name']}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="comment">Remarks (if any ) </label>
                                <textarea name="comment" class="ckeditor form-control" id="comment">{{old('comment')}}</textarea>
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
</x-admininnerlayout>