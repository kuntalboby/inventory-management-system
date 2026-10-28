@section('currentPage', ' Order Details')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>

  @section('title','Order Details')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid" id="invoiceType">

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
                            <h3 class="card-title">Invoice - {{$getInfo->invoice_number}}</h3>
                            <div class="card-tools">
                                <a href="{{ route('manage.orders') }}" class="btn btn-dark text-light">
                                    Back
                                </a>
                                <a onclick="return confirm('Are you sure you want to Edit Order?');" 
                                    href="{{ route('manage.order-edit',[$getInfo->order_number])  }}"
                                     class="btn btn-warning text-light">
                                    Edit
                                </a>
                                 <a onclick="return confirm('Are you sure you want to download invoice?');" 
                                    href="{{ route('manage.invoicedownload',[$getInfo->order_number,'download'])  }}"
                                     class="btn btn-primary text-light">
                                    Download
                                </a>
                                @if (!empty($getInfo->customerAddress->email) && $getInfo->status != '2')
                                    <a onclick="return confirm('Are you sure you want to send the invoice via email?');" 
                                    href="{{ route('manage.invoicedownload',[$getInfo->order_number,'mail'])  }}" 
                                    class="btn btn-warning text-dark">
                                    Mail
                                    </a> 
                                @endif
                            @if ($getInfo->status == '2')
                                <button class="btn text text-danger">Cancelled</button>
                            @else
                                @if ($rject == 'N')
                                        <a  href="{{ route('manage.invoicedownload',[$getInfo->order_number,'return'])  }}" 
                                                class="btn btn-danger text-light">
                                                Return
                                                </a>
                                @endif
                            @endif
                               
                                    

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
                                            <th width="50%" colspan="2" class="text-end company-data" style="vertical-align: top;">
                                                <span>Invoice Id: #{{$getInfo->invoice_number}}</span> <br>
                                                <span>Order Number: {{$getInfo->order_number}}</span> <br>
                                                <span>Date: {{ date('d-m-Y h:i A',strtotime($getInfo->order_date)) }} </span> <br>
                                                <br>
                                            </th>
                                            
                                        </tr>
                                        <tr class="bg-gray">
                                            <th width="50%" colspan="4">Customer Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                        
                                            <td>Full Name:</td>
                                            <td>{{ ucwords(strtolower($getInfo->customerAddress->first_name )).' '. ucwords(strtolower($getInfo->customerAddress->last_name)) }}</td>
                                            <td>Phone:</td>
                                            <td>{{$getInfo->customerAddress->phone}} / {{$getInfo->customerAddress->alternative_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td>Email Id:</td>
                                            <td>{{$getInfo->customerAddress->email}}</td>
                                            
                                            <td>Address:</td>
                                            <td>
                                                 {{ ucwords(strtolower($getInfo->customerAddress->address_line_one)) }} ,
                                                 {{ ucwords(strtolower( $getInfo->customerAddress->address_line_two )) }} ,
                                                 {{ ucwords(strtolower( $getInfo->customerAddress->city ))}} , 
                                                 {{ ucwords(strtolower($getInfo->customerAddress->state))}}
                                               
                                            </td>
                                        
                                        </tr>
                                        
                                        <tr>

                                            <td>Pin code:</td>
                                            <td>{{$getInfo->customerAddress->pin}}</td>

                                            <td>Order Status:</td>
                                            <td>Completed</td>
                                        </tr>
                                    </tbody>
                                </table>

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
                                            <th>Quantity</th>
                                            <th>Discount</th>
                                            <th>GST</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ( !empty($getInfo->orderDetails))
                                            @foreach ($getInfo->orderDetails as $key=>$orderProd)
                                                <tr>
                                                    <td width="10%">{{$key+1}}</td>
                                                    <td>
                                                   @if (empty($orderProd->product_name))
                                                        {{ ucwords(strtolower( $orderProd->product->name )) }}
                                                    @else
                                                        {{ ucwords(strtolower( $orderProd->product_name )) }}
                                                    @endif
                                                    </td>
                                                    <td width="10%" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orderProd->selling_price}}</td>
                                                    <td width="10%">{{$orderProd->quantity}}</td>
                                                    <td width="10%">{{$orderProd->discount_percent}} %</td>
                                                     
                                                    <td width="10%">{{$orderProd->product_gst}} %</td>
                                                    <td width="15%" class="fw-bold" style="font-family: DejaVu Sans, sans-serif;">₹ {{$orderProd->amount}}</td>
                                            </tr>
                                            @endforeach
                                            
                                            
                                        @else
                                            <td colspan="7" class="total-heading">No Products</td>
                                        @endif
                                        

                                        
                                        @if ($getInfo->ordered_from == '2' )
                                                <tr>
                                                    
                                                    <td colspan="5" class="">
                                                       Delivery Free After : <strong>{{$getInfo->delvry_free_after}}</strong>
                                                    </td>
                                                    <td colspan="4" class="">
                                                       Delivery Charges (+) <strong 
                                                       @if( round($getInfo->total_amount,2) > round($getInfo->delvry_free_after,2) )
                                                            style="text-decoration:line-through;color:red"
                                                       @endif
                                                       >{{$getInfo->delivery_charge ? $getInfo->delivery_charge:0}}</strong>
                                                    </td>

                                                </tr>
                                            @endif
                                        <tr>
                                            <td colspan="4" class="total-heading">Total Amount - <small>Inc. all vat/tax</small> :</td>
                                            <td colspan="2" class="">Over all GST : {{ $getInfo->extra_gst ? $getInfo->extra_gst : 0}} %</td>
                                            <td colspan="1" class="total-heading" style="font-family: DejaVu Sans, sans-serif;">₹ {{ $getInfo->total_amount }}</td>
                                        </tr>
                                    </tbody>
                                </table>
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