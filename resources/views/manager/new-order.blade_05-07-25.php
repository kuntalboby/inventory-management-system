@section('currentPage', 'New Order')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','New Order')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
      

                  <div class="step_1 switchTabs" id="step1">


                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h3 class="card-title">Choose Prouct</h3>
                          <div class="card-tools">
                            
                            <a href="{{route('manage.orders')}}" class="btn btn-light text text-dark">
                              back
                            </a>                        
                          </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div name="csrf-token" content="{{ csrf_token() }}"></div>
                            <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <select name="product_slug" 
                                            id="choosenProduct" 
                                            class="form-control selectPicker"
                                            >
                                                <option value="0">Select Product</option>
                                                @if (isset($products) && $products->isNotEmpty())
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->slug }}">
                                                            {{ ucwords(strtolower($product->name)) }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                          
                        </div>
                        
                        <!-- /.card-body -->
                      </div>
                    
                    <div class="card card-warning card-outline d-none getProdDetails"> 
                        <div class="card-body" id="">
                          <form id="getProdDetails" method="POST">
                            <input type="hidden" name="form_type" value="product" />   
                            <button type="submit" name="save" id="saveAndCont" class="btn btn-primary">Save & Continue</button>
                          </form>
                        </div>
                        
                      </div>
                  </div>
                   <div class="step_2 d-none switchTabs" id="step2">
                      <div class="card card-primary card-outline">
                          <div class="card-header">
                            <h3 class="card-title">Customer Details</h3>
                            <div class="card-tools">
                              <button  type="button" onclick="switchTabs('step1');" class="btn btn-primary text text-light">
                                Selected Products
                              </button>
                              {{-- <button  type="button" id="showSearchBox" class="btn btn-warning text text-dark">
                               Search
                              </button> --}}
                              <button  type="button" id="resetCustomerForm" class="btn btn-dark text text-light">
                               Reset
                              </button>
                            </div>
                          </div>

                              {{-- customer form starts --}}
                              <div class="card-body">

                                <div class="row">
                                  <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="customer_details">Search Customer</label>
                                        <input type="input" class="form-control" name="customer_details" id="searchCustomer" placeholder="Enter phone number/ Name "/>
                                        <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                                      </div>
                                      </div>
                                </div>
                              </div>
                            <form id="customerDetails" method="post">
                                  <div class="card-body">
                                                  <input type="hidden" name="form_type" value="customer" />     
                                    <div class="row">
                                      <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="order_date">Order Date*</label>


                                                  <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                    <input type="text" name="order_date" class="form-control datetimepicker-input"
                                                           data-target="#datetimepicker1" placeholder="DD-MM-YYYY"
                                                           value="">
                                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="first_name">First Name *</label>
                                                <input type="text" name="first_name"  class="form-control" id="first_name" placeholder="Enter First Name *">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="last_name">Last Name *</label>
                                                <input type="text" name="last_name"  class="form-control" id="last_name" placeholder="Enter Last Name *">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email"  class="form-control" id="email" placeholder="Enter Email ">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone">Phone  *</label>
                                                <input type="text" name="phone"  class="form-control" id="phone" placeholder="Enter Phone No  *">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone_1">Alternative Phone </label>
                                                <input type="text" name="alternative_phone" class="form-control" id="alternative_phone" placeholder="Enter Alternative Phone">
                                            </div>
                                        </div>
                                        
                                      <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address_line_one">Address Line One *</label>
                                                <textarea id="address_line_one" name="address_line_one" class="form-control" placeholder="Enter Address Line 1"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address_line_two">Address Line Two</label>
                                                <textarea id="address_line_two" name="address_line_two" class="form-control" placeholder="Enter Address Line 2"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="pin">PIN *</label>
                                                <input type="text" name="pin"  class="form-control" id="pin" placeholder="Enter PIN *">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="city">City *</label>
                                                <input type="text" name="city" class="form-control" id="city" placeholder="Enter City *">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="district">Disrict</label>
                                                <input type="text" name="district" class="form-control" id="district" placeholder="Enter District">
                                            </div>
                                        </div> --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="state">State *</label>
                                                <input type="text" name="state" class="form-control" id="state" placeholder="Enter State *">
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="gst_number">GST Number </label>
                                                <input type="text" name="gst_number" class="form-control" id="gst_number" placeholder="GST Number">
                                            </div>
                                        </div>

                                    </div>
                                
                                  </div>
                                  <!-- /.card-body -->
                  
                                  <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save & Continue</button>
                                  </div>
                                </form>

                              {{-- end --}}



                        </div>


                   </div>

                  <div class="step_3  d-none switchTabs" id="step3">
                      <div class="card card-primary card-outline">
                          <div class="card-header">
                            <h3 class="card-title">Confirmation</h3>
                            <div class="card-tools">
                              
                              <button  type="button" onclick="switchTabs('step1');" class="btn btn-primary text text-light">
                                Selected Products
                              </button>
                              <button  type="button" onclick="switchTabs('step2');" class="btn btn-primary text text-light">
                                Customer Details
                              </button>
                            </div>
                          </div>
                          {{-- product List and customer details --}}


                               <div class="row">
                                  <div class="col-12">
                                    
                                      <div class="invoice p-3 mb-3">
                                      <!-- title row -->
                                      <div class="row">
                                        <div class="col-12">
                                          <h4>
                                            <i class="fas fa-globe"></i> Order Details
                                            
                                          </h4>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                      <!-- info row -->
                                      
                                      <!-- /.row -->

                                      <!-- Table row -->
                                      <div class="row">
                                        <div class="col-12 table-responsive">
                                          <table class="table table-striped">
                                            <thead>
                                            <tr>
                                              
                                              <th>Product</th>
                                              <th>Qty</th>
                                              <th>Selling Price</th>
                                              <th>GST</th>
                                              <th>Subtotal</th>
                                            </tr>
                                            </thead>
                                              <tbody id="selectedAllProducts">
                                              
                                              
                                              </tbody>
                                          </table>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                      <!-- /.row -->

                                      <div class="row">
                                        <!-- accepted payments column -->
                                        <div class="col-6 invoice-col card-warning card-outline">
                                          
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-6">
                                          

                                          <div class="table-responsive">
                                            <table class="table">
                                              <tbody><tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td id="subtotal" class="subtotal" amount="0"></td>
                                              </tr>
                                              
                                              <tr>
                                                <th>GST:</th>
                                                <td>
                                                  <input class="form-control" value="0" type="number" name="overAll_gst"  id="overAll_gst"/>
                                                </td>
                                              </tr>
                                              <tr>
                                                <th>Total:</th>
                                                <td id="final_amount" class="subtotal"></td>
                                              </tr>
                                            </tbody></table>
                                          </div>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                      <!-- /.row -->

                                      <!-- this row will not appear when printing -->
                                      <div class="row no-print">
                                        <div class="col-md-12" id="ajaxRespnMsg"></div>
                                        <div class="col-12">
                                          
                                          <button type="button" id="confirmOrder" class="btn btn-success float-right" onclick="confirmOrder();"><i class="far fa-credit-card"></i> 
                                            Place Order
                                          </button>
                                          
                                        </div>
                                      </div>
                                    </div>

                                   
                                  </div><!-- /.col -->
                                </div>

                          {{--  end --}}
                        </div>
                    </div>   
                 
                </div>
                <!-- /.col -->
              </div>

        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>