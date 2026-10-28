@section('currentPage', ' List of Customers')
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
                      <h3 class="card-title">Customers</h3>
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
                                                    <label for="first_name">First Name</label>
                                                    <input type="text"
                                                      value="@if (isset($getInfo['searchReslt']['first_name']))  {{ $getInfo['searchReslt']['first_name']}}  @endif"
                                                    name="name"  class="form-control searchInput" id="first_name" placeholder="Enter First Name">
                                                    <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                                                  </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="first_name">Last Name</label>
                                                    <input type="text"
                                                      value="@if (isset($getInfo['searchReslt']['first_name']))  {{ $getInfo['searchReslt']['first_name']}}  @endif"
                                                    name="name"  class="form-control searchInput" id="first_name" placeholder="Enter First Name">
                                                    <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
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
                                      <th>First Name</th>
                                      <th>Last Name</th>
                                      <th>Phone</th>
                                      <th>Purchase Date</th>
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