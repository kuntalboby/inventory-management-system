@section('currentPage', ' Edit Warehouse')
@php
    $bgColor="dark";
   
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Warehouse')
    
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
              <div class="card card-warning">
                <div class="card-header">
                  <h3 class="card-title">Edit Warehouse</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.masters.warehouses')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.masters.warehouseUpdate',$getInfo->slug)}}"  enctype="multipart/form-data" method="post">
                 @csrf
                 @method('put')
                  <div class="card-body">
                                     
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" name="name" value="{{ old('name', $getInfo->name)}}" class="form-control" id="title" placeholder="Enter Name *">
                            </div>
                        </div>
                         <div class="col-md-5">
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
                                <label for="phone_1">Phone 2 *</label>
                                <input type="text" name="phone_2" value="{{ old('phone_2', $getInfo->phone_2)}}" class="form-control" id="phone_2" placeholder="Enter Phone No 2 *">
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
                                <label for="pin">PIN *</label>
                                <input type="text" name="pin" value="{{ old('pin', $getInfo->pin)}}" class="form-control" id="pin" placeholder="Enter PIN *">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State *</label>
                                <input type="text" name="state" value="{{ old('state', $getInfo->state)}}" class="form-control" id="state" placeholder="Enter State *">
                            </div>
                        </div>
                        <div class="col-md-4">
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