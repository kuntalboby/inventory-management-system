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
      
                  <div class="card card-primary card-outline">
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Product Name</label>
                                                    <input type="text"
                                                      value="@if (isset($getInfo['searchReslt']['name']))  {{ $getInfo['searchReslt']['name']}}  @endif"
                                                    name="name" data-for="searchProd" class="form-control searchInput" id="name" placeholder="Enter Product Name">
                                                    <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                                                  </div>
                                            </div>
                                           
                                            
                                            <div class="col-md-6">
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
                                      <th width="40%">Name</th>
                                      <th>Packet of</th>
                                      <th>Quantity</th>
                                      <th>Total</th>
                                      <th>Buying Price / pkt </th>
                                      <th>Selling Price / pkt</th>
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