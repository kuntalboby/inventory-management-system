@section('currentPage', ' Edit Unit')
@php
    $bgColor="dark";
   
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Unit')
    
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
                  <h3 class="card-title">Edit Unit</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.masters.unit')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.masters.unitUpdate',$getInfo->slug)}}"  enctype="multipart/form-data" method="post">
                 @csrf
                 @method('put')
                  <div class="card-body">
                                     
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" name="name" value="{{ old('name', $getInfo->name)}}" class="form-control" id="title" placeholder="Enter Name *">
                            </div>
                        </div>
                       
                        <div class="col-md-3">
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