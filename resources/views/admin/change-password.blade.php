@section('currentPage', ' Password Update')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Update Password')
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
                  <h3 class="card-title">Update Login Password</h3>
                </div>
            
                <form action="{{route('admin.updatepassword')}}" enctype="multipart/form-data" method="post">
                 @csrf
                 @method('put')
                 <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="current_password">Current Password *</label>
                                <input type="password" value="" name="current_password" class="form-control" id="current_password" placeholder="Enter Current Password *">
                            </div>
        
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">New Password *</label>
                                <input type="password" value="" name="password" class="form-control" id="password" placeholder="Enter New Password *">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password_confirmation">Confirmed Password *</label>
                                <input type="password" value="" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter Confirmed Password *">
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