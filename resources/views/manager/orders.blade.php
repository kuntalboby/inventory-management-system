@section('currentPage', ' List of Orders')
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
                <div>
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
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Orders</h3>
                      <div class="card-tools">
                        
                        <a href="{{route('manage.new-order')}}" class="btn btn-primary text text-light">
                           New Order
                        </a>

                        <button type="button" class="btn btn-info text text-light" onclick="openSearchBox('searchOrder');">
                          Search
                        </button>
                        
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div name="csrf-token" content="{{ csrf_token() }}"></div>
                        
                        <form id="searchOrder" class="@if (empty($getInfo['searchOrder'])) d-none @endif card card-warning" method="POST" >
                              <div class="row">
                              
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text"
                                                      value="@if (isset($getInfo['searchOrder']['name']))  {{ $getInfo['searchOrder']['name']}}  @endif"
                                                    name="name" data-for="" class="form-control" id="name" placeholder="Enter Customer Name">
                                                    
                                                  </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input type="text"
                                                      value="@if (isset($getInfo['searchOrder']['phone']))  {{ $getInfo['searchOrder']['phone']}}  @endif"
                                                    name="phone" data-for="" class="form-control" id="phone" placeholder="Enter Customer Phone">
                                                    
                                                  </div>
                                            </div>
                                             <div class="col-md-2">
                                               <div class="form-group">
                                                      <label for="date_from">Date From*</label>
                                                              <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                                  <input type="text" id="date_from" name="date_from" class="form-control datetimepicker-input"
                                                                        data-target="#datetimepicker1" placeholder="DD-MM-YYYY"
                                                                        value="">
                                                                  <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                  </div>
                                                              </div>
                                                      </div>
                                            </div>

                                      <div class="col-md-2">

                                            <div class="form-group">
                                                <label for="date_to">Date To*</label>
                                                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                                        <input type="text" id="date_to" name="date_to" class="form-control datetimepicker-input"
                                                              data-target="#datetimepicker2" placeholder="DD-MM-YYYY"
                                                              value="">
                                                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>

                                      </div>
                                      <div class="col-md-2">

                                            <div class="form-group">
                                                <label for="date_to">Order From*</label>
                                                    <div class="input-group" id="">
                                                        <select class="form-control" id="ordered_from" name="ordered_from">
                                                            <option value="">Select</option>
                                                            <option value="1">Inventory</option>
                                                            <option value="2">Online</option>
                                                        </select>
                                                    </div>
                                                </div>

                                      </div>
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="invoice_number">Invoice Number</label>
                                                    <input type="text"
                                                      value="@if (isset($getInfo['searchOrder']['invoice_number']))  {{ $getInfo['searchOrder']['invoice_number']}}  @endif"
                                                    name="invoice_number" data-for="" class="form-control" id="invoice_number" placeholder="Enter Order Invoice">
                                                    
                                                  </div>
                                            </div>
                                           
                                        </div>

                                        
                                        <div class="row">
                                          <div class="col-md-12">
                                              <button type="button" class="btn btn-warning float-right" id="filterOrder"> Submit </button>
                                              <button type="button" class="btn btn-dark float-right mr-1" id="resetOrderSrch"  data-form="searchOrder"> reset </button>
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
                                    <div class="dt-buttons-ord">
                                      <button class="dt-button buttons-excel" 
                                          tabindex="0"
                                          of="ORD"
                                          id="customExportBtn" 
                                          type="button">
                                        <span>Export to Excel</span>
                                      </button> 
                                    </div>
                              
                                    <div class="table-responsive">
                                      <table id="kt_table_order" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                          <th></th>
                                          <th>Date</th>
                                          <th>First Name</th>
                                          <th>Last Name</th>
                                          <th>Email</th>
                                          <th>Phone</th>
                                          <th>Invoice Number</th>
                                          <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                  
                                        </tbody>
                                        
                                      </table>
                                    </div>
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