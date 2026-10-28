@section('currentPage', ' Edit Order :'.$getInfo->invoice_number)
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>

  @section('title','Edit Order :'.$getInfo->invoice_number)
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
                                                <h2 class="text-start">Edit Order Information</h2>
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
                                    
                                </table>
                                   <form id="customerDetailsEdit" 
                                   action="{{ route('manage.update-order-details',$getInfo->order_number) }}"
                                   method="post">
                                    @csrf
                                    @method('PUT')
                                  <div class="card-body">
                                            
                                    <div class="row">
                                      {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="order_date">Order Date*</label>
                                                  <div class="input-group date" id="order_date_picker" data-target-input="nearest">
                                                    <input type="text" name="order_date" class="form-control datetimepicker-input"
                                                           data-target="#order_date_picker" placeholder="DD-MM-YYYY HH:ii"
                                                           
                                                           value="{{ date('d-m-Y H:i',strtotime($getInfo->order_date)) }}"
                                                            id="order_date_pck">
                                                    <div class="input-group-append" data-target="#order_date_picker" 
                                                    data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>



                                            </div>
                                        </div> --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="first_name">First Name *</label>
                                                <input type="text" name="first_name"  
                                                class="form-control" id="first_name"
                                                value="{{ ucwords(strtolower($getInfo->customerAddress->first_name)) }}" 
                                                placeholder="Enter First Name *">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="last_name">Last Name *</label>
                                                <input type="text" name="last_name"  
                                                value="{{ ucwords(strtolower($getInfo->customerAddress->last_name)) }}"
                                                class="form-control" id="last_name" placeholder="Enter Last Name *">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" value="{{ $getInfo->customerAddress->email }}"  class="form-control" id="email" placeholder="Enter Email ">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Phone  *</label>
                                                <input type="text" name="phone"  value="{{ $getInfo->customerAddress->phone }}"
                                                 class="form-control" id="phone" placeholder="Enter Phone No  *">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone_1">Alternative Phone </label>
                                                <input type="text" name="alternative_phone" value="{{$getInfo->customerAddress->alternative_phone }}" accept=""class="form-control" id="alternative_phone" placeholder="Enter Alternative Phone">
                                            </div>
                                        </div>
                                        
                                      <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address_line_one">Address Line One *</label>
                                                <textarea id="address_line_one"
                                                    name="address_line_one" class="form-control" placeholder="Enter Address Line 1">{{ ucwords(strtolower($getInfo->customerAddress->address_line_one)) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address_line_two">Address Line Two</label>
                                                <textarea id="address_line_two" 
                                                    name="address_line_two" class="form-control" placeholder="Enter Address Line 2">{{ ucwords(strtolower($getInfo->customerAddress->address_line_two)) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="pin">PIN *</label>
                                                <input type="text" name="pin"  value="{{ $getInfo->customerAddress->pin }}"
                                                class="form-control" id="pin" placeholder="Enter PIN *">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="city">City *</label>
                                                <input type="text" value="{{ ucwords(strtolower($getInfo->customerAddress->city)) }}"
                                                name="city" class="form-control" id="city" placeholder="Enter City *">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="state">State *</label>
                                                <input type="text" value="{{ ucwords(strtolower($getInfo->customerAddress->state)) }}"
                                                name="state" class="form-control" id="state" placeholder="Enter State *">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="gst_number">GST Number </label>
                                                <input type="text" value="{{ $getInfo->customerAddress->gst_number }}"
                                                name="gst_number" class="form-control" id="gst_number" placeholder="GST Number">
                                            </div>
                                        </div>

                                    </div>
                                
                                  </div>
                                  <!-- /.card-body -->
                  
                                  <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                  </div>
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