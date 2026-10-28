@section('currentPage', ' Return Order :'.$getInfo->invoice_number)
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>

  @section('title','Return Order :'.$getInfo->invoice_number)
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid" id="invoiceType">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
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
                  <!-- /.card -->
                  
      
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                            <h3 class="card-title">Invoice - {{$getInfo->invoice_number}}</h3>
                            <div class="card-tools">
                                <a href="{{ route('manage.invoicedownload',[$getInfo->order_number,'view']) }}" class="btn btn-dark text-light">
                                    Back
                                </a>
                                
                                 {{-- <a onclick="return confirm('Are you sure you want to download invoice?');" 
                                    href="{{ route('manage.invoicedownload',[$getInfo->order_number,'download'])  }}"
                                     class="btn btn-primary text-light">
                                    Download
                                </a>
                                @if (!empty($getInfo->customer->email) )
                                    <a onclick="return confirm('Are you sure you want to send the invoice via email?');" 
                                    href="{{ route('manage.invoicedownload',[$getInfo->order_number,'mail'])  }}" 
                                    class="btn btn-warning text-dark">
                                    Mail
                                </a>
                                @endif
                                 --}}

                            </div>
                        </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="border:none">
                                            <th width="50%" colspan="2" style="vertical-align: top;">
                                                <h2 class="text-start">Order Information</h2>
                                            </th>
                                            <th width="50%" colspan="2" style="vertical-align: top;" class="text-end company-data">
                                                <span>Invoice Id: #{{$getInfo->invoice_number}}</span> <br>
                                                <span>Order Number: {{$getInfo->order_number}}</span> <br>
                                                <span>Date: {{ date('d-m-Y h:i A',strtotime($getInfo->order_date)) }} </span> <br>
                                                <br>
                                            </th>
                                            
                                        </tr>
                                        <tr class="bg-gray">
                                            <th colspan="4">Customer Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                            <td>Full Name:</td>
                                            <td>{{ $getInfo->customer->first_name .' '.$getInfo->customer->last_name }}</td>
                                            
                                            <td>Phone:</td>
                                            <td>{{$getInfo->customer->phone}} / {{$getInfo->customer->alternative_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td>Email Id:</td>
                                            <td>{{$getInfo->customer->email}}</td>
                                            <td>Address:</td>
                                            <td>
                                                {{$getInfo->customer->city}} , {{$getInfo->customer->state}} , {{$getInfo->customer->address_line_one }}
                                                {{$getInfo->customer->address_line_two }}
                                            </td>
                                        
                                        </tr>
                                        
                                        <tr>
                                            

                                            <td>Pin code:</td>
                                            <td>{{$getInfo->customer->pin}}</td>

                                            <td>Order Status:</td>
                                            <td>Completed</td>
                                        </tr>
                                    </tbody>
                                </table>
                                    <form method="post" 
                                            id="OrderReturnForm"
                                            action="{{route('manage.order-return')}}"
                                            >
                                            @csrf
                                        <input type="hidden" name="order_number" value="{{$getInfo->order_number}}"/>
                                        <input type="hidden" name="invoice_number" value="{{$getInfo->invoice_number}}"/>
                                        <input type="hidden" name="action_type" id="action_type" value="PR"/>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="no-border text-start heading" colspan="5">
                                                Order Items
                                            </th>
                                        </tr>
                                        <tr class="bg-gray">
                                            <th>Sl</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity (<small>min:0</small>)</th>
                                            <th>Discount %</th>
                                            <th>GST %</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @if ( !empty($getInfo->orderDetails))
                                        
                                            @foreach ($getInfo->orderDetails as $key=>$orderProd)

                                                <tr>
                                                    <td width="10%">{{$key+1}}</td>
                                                    <td width="30%">
                                                    {{ ucwords(strtolower($orderProd->product->name)) }}
                                                    </td>
                                                    <td width="10%" style="font-family: DejaVu Sans, sans-serif;"
                                                    >                                                    
                                                    <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                                            </div>
                                                        <input type="number" class="form-control" id="selling_price_{{$orderProd->product->product_code}}" readonly value="{{$orderProd->selling_price}}" />
                                                    </div>
                                                    

                                                </td>
                                                    <td width="10%">
                                                        <input class="form-control quantityCalc" min="0" edit-from="R" 
                                                        data-code="{{$orderProd->product->product_code}}" 
                                                        type="number" 
                                                        id="quantity_{{$orderProd->product->product_code}}"
                                                        required
                                                        @if ($orderProd->quantity == '0' )
                                                            readonly="readonly"
                                                        @endif
                                                        name="quantity[{{$orderProd->product->id}}]" 
                                                        value="{{$orderProd->quantity}}" 
                                                        max="{{$orderProd->quantity}}"/>
                                                    
                                                    </td>
                                                    <td width="10%" >
                                                        
                                                    <div class="input-group">
                                                            <input type="number" class="form-control"
                                                            id="discount_percent_{{$orderProd->product->product_code}}" 
                                                            value="{{$orderProd->discount_percent}}"
                                                            readonly
                                                            /> 
                                                              <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                            </div>
                                                    </div> 
                                                    </td>
                                                    <td width="10%" >
                                                        
                                                    <div class="input-group">
                                                            <input type="number" class="form-control" id="gst_{{$orderProd->product->product_code}}" readonly value="{{$orderProd->product_gst}}" /> 
                                                              <div class="input-group-prepend">  
                                                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                            </div>
                                                    </div> 
                                                    </td>
                                                    

                                                    <td width="15%" class="fw-bold" style="font-family: DejaVu Sans, sans-serif;"
                                                    >
                                                    <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                                            </div>
                                                        <input type="number" class="form-control subtotal" id="amount_{{$orderProd->product->product_code}}" readonly value="{{$orderProd->amount}}" />
                                                    </div>                                                    
                                                </td>
                                            </tr>
                                            @endforeach
                                                
                                          
                                        @else
                                            <td colspan="7" class="total-heading">No Products</td>
                                        @endif
                                        
                                            @if ($getInfo->ordered_from == '2' )
                                                <tr>
                                                    
                                                    <td colspan="5" class="">
                                                       Delivery Free After : <strong style="font-family: DejaVu Sans, sans-serif;">₹ {{$getInfo->delvry_free_after}}</strong>
                                                    </td>
                                                    <td colspan="4" class="">
                                                       Delivery Charges (+) <strong id="deliveryChrg"
                                                        @if( round($getInfo->total_amount,2) > round($getInfo->delvry_free_after,2) )
                                                            style="text-decoration:line-through;color:red;font-family: DejaVu Sans, sans-serif;"
                                                            data-d-charges="0"
                                                        @else
                                                            data-d-charges="{{$getInfo->delivery_charge}}"
                                                        @endif
                                                       >₹ {{$getInfo->delivery_charge}}</strong>
                                                    </td>

                                                </tr>
                                            @endif

                                        <tr>
                                            <td colspan="4" class="total-heading">Total Amount - <small>Inc. all vat/tax</small> :</td>
                                            <td colspan="2" class="" id="extra_gst" data-extra-gst="{{$getInfo->extra_gst}}" >Over all GST : {{ $getInfo->extra_gst}} %</td>
                                            <td colspan="1" class="total-heading" style="font-family: DejaVu Sans, sans-serif;" id="totalAmount">₹ {{ $getInfo->total_amount }}</td>
                                        </tr>
                                        <tr >
                                            <td colspan="6">
                                                <button type="button" id="returnFormBtn" class="btn btn-warning">Partial Return</button>
                                                <button type="button" id="cancelOrder" class="btn btn-danger">Cancel Order</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </form> 
                        </div>
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