@section('currentPage', ' Customer Order Details ')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Customer Orders')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid" id="invoiceType">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
                  
      
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Order of : {{ucwords(strtolower( $getInfo->first_name )).' '.ucwords(strtolower( $getInfo->last_name )) }}</h3>
                      <div class="card-tools">
                      
                        <a type="button" href="{{ route('admin.masters.customers') }}" class="btn btn-dark text text-light">
                          Back
                        </a>
                        
                      </div>
                    </div>
                    <div class="card-body">
                          <div class="row">
                            <div class="col-md-6">
                              
                                    <div class="form-group">
                                        <label for="order_date ">Select Date </label>
                                        <select class="form-control filterOrder order_date selectPicker" name="select_order_date" id="select_order_date">
                                            <option value="">Select Date</option>
                                            @if (isset($getInfo->orders) && !empty($getInfo->orders) )
                                               @foreach ( $getInfo->orders as $filter)
                                                 <option value="{{$filter->order_date}}">{{date('d-m-Y', strtotime($filter->order_date))}}</option>
                                               @endforeach

                                            @endif   
                                                
                                        </select>
                                    </div>
                            </div>
                            <div class="col-md-6">
                              
                                    <div class="form-group">
                                        <label for="invoice_number ">Select Invoice </label>
                                        <select class="form-control filterOrder invoice_number selectPicker" name="select_invoice_number" id="select_invoice_number">
                                            <option value="">Select Invoice</option>
                                            @if (isset($getInfo->orders) && !empty($getInfo->orders) )
                                               @foreach ( $getInfo->orders as $filterInvoice)
                                                 <option value="{{$filterInvoice->invoice_number}}">{{$filterInvoice->invoice_number}}</option>
                                               @endforeach

                                            @endif   
                                                
                                        </select>
                                    </div>
                            </div>
                          </div>
                    
                        <div class="row">
                           <div class="col-md-12">
                                                  <div class="invoice p-3 mb-3">
                                                    <div class="row invoice-info">
                                                                    <div class="col-sm-12 invoice-col">
                                                                      
                                                                      <address>
                                                                        <strong>Name :</strong>  {{ ucwords(strtolower( $getInfo->first_name)).' '.ucwords(strtolower($getInfo->last_name))}}<br>
                                                                        <strong>Phone : </strong> {{$getInfo->phone}} / {{$getInfo->alternative_phone}}<br>
                                                                        <strong>Email :</strong>  {{$getInfo->email}}<br>
                                                                        <strong>City : </strong> {{ ucwords(strtolower($getInfo->city )) }}<br>
                                                                        <strong>PIN : </strong> {{$getInfo->pin}}<br>
                                                                        <strong>State : </strong> {{ucwords(strtolower($getInfo->state )) }}<br>
                                                                        <strong>Address : </strong> {{ ucwords(strtolower($getInfo->address_line_one  )) }}<br>
                                                                          {{ucwords(strtolower( $getInfo->address_line_two )) }}
                                                                       
                                                                      </address>
                                                                    </div>
                                                                    <!-- /.col -->
                                                                    
                                                                    <!-- /.col -->
                                                                    
                                                                    <!-- /.col -->
                                                       </div>
                                                  </div>
                                                </div>
                            <div class="col-md-12">

                              @if (isset($getInfo->orders) && !empty($getInfo->orders) )
                                  @foreach ( $getInfo->orders as $orders)
                                    <div class="card collapsed-card" data-order-date="{{$orders->order_date}}" data-order-invoice="{{$orders->invoice_number}}">
                                        <div class="card-header">
                                          <h3 class="card-title">
                                              <b>Order Date</b>: {{ date('d-m-Y',strtotime($orders->order_date))  }}
                                              <b>Invoice</b> : #{{$orders->invoice_number}}
                                            </h3>

                                          <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                              <i class="fas fa-plus"></i>
                                            </button>
                                            
                                          </div>
                                        </div>
                                        <!-- /.card-header -->
                                        
                                        <div class="card-body p-0" style="display: none;">
                                            <div class="row">
                                                    <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr style="border:none">
                                                                    <th width="50%" colspan="2">
                                                                        <h2 class="text-start">Order Details</h2>
                                                                    </th>
                                                                    <th width="50%" colspan="2" class="text-end company-data">
                                                                        <span>Invoice Id: #{{$orders->invoice_number}}</span> <br>
                                                                        <span>Order Number: {{$orders->order_number}}</span> <br>
                                                                        <span>Date: {{ date('d-m-Y',strtotime($orders->order_date))  }} </span> <br>
                                                                        <br>
                                                                    </th>
                                                                    
                                                                </tr>
                                                                
                                                            </thead>
                                                            
                                                        </table>

                                                        <table>
                                                            <thead>
                                                                
                                                                <tr class="bg-blue">
                                                                    <th>Sl</th>
                                                                    <th>Product</th>
                                                                    <th>Price</th>
                                                                    <th>Quantity</th>
                                                                    <th>GST</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                          <tbody>
                                                            @if (isset($orders->orderDetails) && !empty($orders->orderDetails) )
                                                                @foreach ( $orders->orderDetails as $key=>$orderProd)
                                                                  <tr>
                                                                            <td width="10%">{{$key+1}}</td>
                                                                            <td>
                                                                            {{ $orderProd->product_name ? ucwords(strtolower($orderProd->product_name)) : ucwords(strtolower($orderProd->product->name)) }}
                                                                            </td>
                                                                            <td width="10%" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orderProd->selling_price}}</td>
                                                                            <td width="10%">{{$orderProd->quantity}}</td>
                                                                            <td width="10%">{{$orderProd->product_gst}} %</td>
                                                                            <td width="15%" class="fw-bold" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orderProd->amount}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                
                                                                    <tr>
                                                                        <td colspan="4" class="total-heading">Total Amount - <small>Inc. all vat/tax</small> :</td>
                                                                        <td colspan="1" class="">Over all GST : {{ $orders->extra_gst }} %</td>
                                                                        <td colspan="1" class="total-heading" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orders->total_amount}}</td>
                                                                    </tr>
                                                                  @endif
                                                              </tbody>
                                                        </table>
                                                </div>
                                                    </div>
                                            </div>
                                            <div class="row mt-3 mb-3">
                                              <div class="col-md-10"></div>
                                              <div class="col-md-2">
                                                <a onclick="return confirm('Are you sure you want to download invoice?');" 
                                                    href="{{ route('manage.invoicedownload',[$orders->order_number,'download'])  }}"
                                                    class="btn btn-primary text-light">
                                                    Download
                                                </a>
                                              
                                                @if (!empty($getInfo->email) )
                                                    <a onclick="return confirm('Are you sure you want to send the invoice via email?');" 
                                                    href="{{ route('manage.invoicedownload',[$orders->order_number,'mail'])  }}"
                                                    class="btn btn-warning text-dark">
                                                    Mail
                                                </a>
                                                @endif
                                              </div>
                                            </div>
                                        </div>
                                        
                                      </div>
                                  @endforeach
                                  
                              @else
                                
                              @endif
                            

                            </div>
                        </div>
                    </div>
                           
                    
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
              </div>

        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>