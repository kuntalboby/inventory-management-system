@section('currentPage', ' Edit Vendor')
@php
    $bgColor="dark";
   
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Vendor')
    
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <col-md-12>
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
            </col-md-12>
              <!-- general form elements -->
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Edit Vendor</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.masters.vendors')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.masters.vendorUpdate',$getInfo->slug)}}"  enctype="multipart/form-data" method="post" id="vendorForm">
                 @csrf
                 @method('put')
                  <div class="card-body">
                                     
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" name="name" 
                                data-slug="{{$getInfo->slug}}"
                                data-for="mastersdata"
                                data-section="vendors"
                                value="{{ old('name', ucwords(strtolower($getInfo->name)))}}" 
                                class="form-control searchInput" id="title" placeholder="Enter Name *">
                                 <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_person">Contact Person *</label>
                                <input type="text" name="contact_person" value="{{ old('contact_person', $getInfo->contact_person)}}" class="form-control" id="contact_person" placeholder="Enter Person Name *">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_1">Phone 1 *</label>
                                <input type="text" name="phone_1" value="{{ old('phone_1', $getInfo->phone_1)}}" class="form-control" id="phone_1" placeholder="Enter Phone No 1 *">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_1">Phone 2 </label>
                                <input type="text" name="phone_2" value="{{ old('phone_2', $getInfo->phone_2)}}" class="form-control" id="phone_2" placeholder="Enter Phone No 2 *">
                            </div>
                        </div>

                         <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" value="{{ old('email', $getInfo->email)}}" class="form-control" id="email" placeholder="Enter Email">
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="pin">PIN </label>
                                <input type="text" name="pin" value="{{ old('pin', $getInfo->pin)}}" class="form-control" id="pin" placeholder="Enter PIN">
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">City </label>
                                <input type="text" name="city" value="{{ old('city', $getInfo->city)}}" class="form-control" id="city" placeholder="Enter City">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State </label>
                                <input type="text" name="state" value="{{ old('state', $getInfo->state)}}" class="form-control" id="state" placeholder="Enter State">
                            </div>
                        </div>

                       <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address </label>
                                <textarea id="address" name="address" class="form-control ckeditor" placeholder="Enter Address">{{ old('address', $getInfo->address)}}</textarea>
                            </div>
                        </div>
                       

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gst_number">GST Number </label>
                                <input type="text" name="gst_number"   value="{{ old('gst_number', $getInfo->gst_number)}}" class="form-control" id="gst_number" placeholder="GST Number">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pan_number">PAN Number </label>
                                <input type="text" name="pan_number"  value="{{ old('pan_number', $getInfo->pan_number)}}" class="form-control" id="pan_number" placeholder="Enter PAN">
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bank_name">Bank Name</label>
                                <input type="text" name="bank_name"  value="{{ old('bank_name', $getInfo->bank_name)}}" class="form-control" id="bank_name" placeholder="Enter Bank Name">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bank_account_number">Bank A/C No </label>
                                <input type="text" name="bank_account_number"   value="{{ old('bank_account_number', $getInfo->bank_account_number)}}" class="form-control" id="bank_account_number" placeholder="Enter Bank Account No">
                            </div>
                        </div>

                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="ifsc_code">IFCI Code </label>
                                <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $getInfo->ifsc_code)}}" class="form-control" id="ifsc_code" placeholder="Enter IFCI Code">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="payment_terms">Payment terms </label>
                                <input type="text" name="payment_terms" value="{{ old('payment_terms', $getInfo->payment_terms)}}"  class="form-control" id="payment_terms" placeholder="Enter Payment terms">
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select class="form-control" name="status" id="status">
                                @php
                                    $status[0]='Active';
                                    $status[1]='Inactive';
                                @endphp
                                    @foreach ($status as $ks=>$stats)
                                            <option value="{{$ks}}" @if ($getInfo->status == $ks)
                                                selected="selected"
                                            @endif>{{$stats}}</option>
                                    @endforeach
                                
                            </select>
                            </div>
                        </div>
                       
                    </div>
                
                  </div>
                  <!-- /.card-body -->
  
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
  
        
            </div>
    
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>