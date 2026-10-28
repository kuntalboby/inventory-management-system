@section('currentPage', ' List of Products')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Variation List')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
      
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title">Products</h3>
                      <div class="card-tools">
                        
                        <a href="{{route('admin.products.products-add')}}" class="btn btn-primary text text-light">
                          Add
                        </a>
                        <button type="button" class="btn btn-info text text-light" onclick="openSearchBox('searchFilter');">
                          Search
                        </button>
                        
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div name="csrf-token" content="{{ csrf_token() }}"></div>
                        
                        <form id="searchFilter" class="@if (empty($getInfo['searchReslt']))  d-none @endif card card-warning" method="POST" >
                              <div class="row">
                              
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name">Product Name</label>
                                                    <input type="text" 
                                                      value="@if (isset($getInfo['searchReslt']['name']))  {{ $getInfo['searchReslt']['name']}}  @endif"
                                                    name="name" class="form-control searchInput" id="name" placeholder="Enter Product Name">
                                                    <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                                                  </div>
                                            </div>
                                            {{-- <div class="col-md-2">
                                              <div class="form-group">
                                                  <label for="purchase_date">Purchase Date</label>
                                                  <div class="input-group date datetimepicker" data-target-input="nearest">
                                                      <input required type="text" id="purchase_date" name="purchase_date" class="form-control datetimepicker-input" data-target=".datetimepicker"/>
                                                      <div class="input-group-append" data-target=".datetimepicker" data-toggle="datetimepicker">
                                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div> --}}
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="parent_id ">Select Category *</label>
                                                  <select class="form-control product_category_id selectPicker"  name="category_id" id="category_id">
                                                      <option value="">Select Category</option>
                                                      @if (!empty($getInfo['allCategories']))
                                                        
                                                      @foreach ($getInfo['allCategories'] as $cate)
                                                          
                                                          <option value="{{$cate['id']}}" 
                                                             @if (isset($getInfo['searchReslt']['category_id']) && $getInfo['searchReslt']['category_id'] == $cate['id'])
                                                                  selected="selected"
                                                             @endif
                                                          >{{$cate['name']}}</option>
                                                      @endforeach
                                                      @endif
                                                          
                                                  </select>
                                              </div>
                                          </div>
                                         
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="vendor_id">Vendor *</label>
                                              <select name="vendor_id" id="vendor_id" class="form-control selectPicker">
                                                  <option value="">Select Vendor</option>
                                                  @if (isset($getInfo['vendors']) && !empty($getInfo['vendors']))
                                                    @foreach ($getInfo['vendors'] as $vendor)
                                                        <option value="{{ $vendor->id }}"
                                                           @if (isset($getInfo['searchReslt']['vendor_id']) && $getInfo['searchReslt']['vendor_id'] == $vendor['id'])
                                                                  selected="selected"
                                                             @endif
                                                          >{{ $vendor->name }}</option>
                                                    @endforeach    
                                                  @endif                                      
                                                </select>
                                            </div>
                                          </div>
                                            {{-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="buying_price">Buying Price</label>
                                                    <input type="number" step="0.1" name="buying_price" class="form-control" id="buying_price" placeholder="Enter Buying Price">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="selling_price">Selling Price</label>
                                                    <input type="number" step="0.1" name="selling_price" class="form-control" id="selling_price" placeholder="Enter Selling Price">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="quantity">Quantity</label>
                                                    <input type="number" step="0.1" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="unit_id">Unit</label>
                                                    <select class="form-control" name="unit_id" id="unit_id">
                                                          <option value="">Select Unit</option>
                                                          @if (!empty($getInfo['unit']) && isset($getInfo['unit']) )
                                                              @foreach ($getInfo['unit'] as $unit)
                                                                  <option value="{{$unit->id}}">{{$unit->name}}</option>
                                                              @endforeach
                                                          @endif
                                                      
                                                      
                                                  </select>
                                              </div>
                                            </div> --}}
                                        </div>

                                        
                                        <div class="row">
                                          <div class="col-md-12">
                                              <button type="button" class="btn btn-warning float-right" id="filterBtnIndex"> Submit </button>
                                              <button type="button" class="btn btn-dark float-right mr-1" id="resetIndSrch"  data-form="searchFilter"> reset </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            
                            <div class="col-md-12 sgmts" id="segement_P">
                              

                              <div class="card card-warning">



                                
                                <div class="card-body">
                                    @csrf
                                  <table id="kt_table_1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>                                 
                                      <th>Name</th>
                                      <th>Category</th>
                                      <th>Product Code</th>
                                      <th>Total Quantity</th>
                                      <th>status</th>
                                      <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
					    
                                    </tbody>
                                    
                                  </table>
                                </div>
                              </div>

                            </div>
                            <div class="col-md-12 d-none" id="json-output"></div>
                            
                            
                        </div>   
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
              </div>

        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>