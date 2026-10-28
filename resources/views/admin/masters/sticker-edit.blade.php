@section('currentPage', ' Edit Sticker')
@php
    $bgColor="dark";
   
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Sticker')
    
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
                  <h3 class="card-title">Edit Sticker</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.masters.sticker-sum')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.masters.stickerUpdate',$getInfo->slug)}}"  enctype="multipart/form-data" method="post">
                 @csrf
                 @method('put')
                  <div class="card-body">
                                     
                    <div class="row">
                        

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mode">Mode *</label>
                                <input type="hidden"  name="mode"  id="mode" value="{{$getInfo->mode}}" />
                                <select class="form-control" disabled>
                               <option value="">Select Mode</option>
                                @php
                                    $mode[0]='Bought';
                                    $mode[1]='Sold';
                                @endphp
                                    @foreach ($mode as $ks=>$md)
                                            <option value="{{$ks}}" @if ($getInfo->mode == $ks)
                                                selected="selected"
                                            @endif>{{$md}}</option>
                                    @endforeach
                                
                            </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="action_date">Date *</label>
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                    <input type="text" name="action_date" class="form-control datetimepicker-input"
                                                           data-target="#datetimepicker1" placeholder="DD-MM-YYYY"
                                                           value="{{ old('action_date', date('d-m-Y',strtotime($getInfo->action_date)))}}">
                                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" readonly value="{{ old('name', $getInfo->name)}}" name="name" class="form-control" id="title" placeholder="Enter Name *">
                            </div>
                        </div>

                         <div class="col-md-3">
                            <div class="form-group">
                                <label for="quantity">Quantity *</label>
                                <input type="number" value="{{ old('quantity', $getInfo->quantity)}}" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity *">
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