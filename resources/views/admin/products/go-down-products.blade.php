@section('currentPage', ' List of Godown Products')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Godown Products')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
      
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Godown Products</h3>
                      <div class="card-tools">
                        
                        <a href="{{route('admin.products.add-godown-products')}}" class="btn btn-primary text text-light">
                          Add
                        </a>
                        <button type="button" class="btn btn-info text text-light" onclick="openSearchBox('searchGodownFilter');">
                          Search
                        </button>
                        
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div name="csrf-token" content="{{ csrf_token() }}"></div>
                        
                        <form id="searchGodownFilter" class="d-none card card-warning" method="POST" >
                              <div class="row">
                              
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name">Product Name</label>
                                                    <input type="text"
                                                      {{-- value="@if (isset($getInfo['searchReslt']['name']))  {{ $getInfo['searchReslt']['name']}}  @endif" --}}
                                                    name="name" data-for="addNew" class="form-control searchInput" id="name" placeholder="Enter Product Name">
                                                    <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                                                  </div>
                                            </div>
                                           
                                            
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="parent_id ">Select Category *</label>
                                                  <select class="form-control product_category_id selectPicker"  name="category_id" id="category_id">
                                                      <option value="">Select Category</option>
                                                      @if (!empty($getInfo['allCategories']))
                                                        
                                                      @foreach ($getInfo['allCategories'] as $cate)
                                                          
                                                          <option value="{{$cate['id']}}" 
                                                             {{-- @if (isset($getInfo['searchReslt']['category_id']) && $getInfo['searchReslt']['category_id'] == $cate['id'])
                                                                  selected="selected"
                                                             @endif --}}
                                                          >{{$cate['name']}}</option>
                                                      @endforeach
                                                      @endif
                                                          
                                                  </select>
                                              </div>
                                          </div>
                                         {{-- Purchase Date --}}
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="purchase_date_from">Purchase Date From</label>
                                                            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                                <input type="text" id="purchase_date_from" name="purchase_date_from" class="form-control datetimepicker-input"
                                                                      data-target="#datetimepicker1" placeholder="DD-MM-YYYY"
                                                                      value="">
                                                                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                      
                                            </div>
                                            <!-- Purchase Date To -->
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="purchase_date_from">Purchase Date To</label>
                                                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                                            <input type="text" id="purchase_date_to" name="purchase_date_to" class="form-control datetimepicker-input"
                                                                  data-target="#datetimepicker2" placeholder="DD-MM-YYYY"
                                                                  value="{{ old('purchase_date_to', request('purchase_date_to')) }}">
                                                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                            </div>  
                                          {{-- <div class="col-md-4">
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
                                          </div> --}}
                                           
                                        </div>

                                        
                                        <div class="row">
                                          <div class="col-md-12">
                                              <button type="button" class="btn btn-warning float-right" id=""> Submit </button>
                                              <button type="button" class="btn btn-dark float-right mr-1" id="" onclick="window.location.reload();"  data-form="searchFilter"> reset </button>
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
                                  <table id="kt_table_goDownProd" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>                                 
                                      <th width="40%">Name</th>
                                      <th>Category</th>
                                      <th>In Godown</th>
                                      <th>Packaged Qunt</th>
                                      <th>Total Qunt</th>
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