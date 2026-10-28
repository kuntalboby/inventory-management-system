@section('currentPage', ' Godown Damaged Products')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Godown Damaged Products')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
      
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Godown Damaged Products</h3>
                      <div class="card-tools">
                        
                        <a href="{{route('admin.products.damaged-product-add')}}" class="btn btn-primary text text-light">
                          Add
                        </a>
                        <button type="button" class="btn btn-info text text-light" onclick="openSearchBox('searchDamangedGodownPrd');">
                          Search
                        </button>
                        
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div name="csrf-token" content="{{ csrf_token() }}"></div>
                        
                        <form id="searchDamangedGodownPrd" class="d-none card card-warning" method="POST" >
                              <div class="row">
                                <input type="hidden" id="tableOf" value="G">
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name">Product Name</label>
                                                    <input type="text"
                                                    name="name" data-for="addNew" class="form-control searchInput" id="name" placeholder="Enter Product Name">
                                                    <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                                                  </div>
                                            </div>
                                           
                                            
                                           
                                         {{-- Purchase Date --}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="purchase_date_from">Date From</label>
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="purchase_date_from">Date To</label>
                                                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                                            <input type="text" id="purchase_date_to" name="purchase_date_to" class="form-control datetimepicker-input"
                                                                  data-target="#datetimepicker2" placeholder="DD-MM-YYYY"
                                                                  value="">
                                                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                            </div>  
                                          
                                           
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
                                  <table id="damagedtbl" class="table table-bordered table-striped">
                                    <thead>
                                   <tr>
                                      <th>Serial No</th>
                                      <th>Date</th>
                                      <th>Name</th>
                                      <th>Quantity</th>
                                      <th class="noExport">Action</th>
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