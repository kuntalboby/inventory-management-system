@section('title', ' OTP verification')


<x-adminloginlayout>
  
    <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Enter New Password</p>

          @if (session('message'))
              <div class="alertBox">
                  <p class="text-center text text-{{session('response')}}">{{session('message')}}</p>
              </div>
          @endif 
    
          <form  action="{{route('admin.updateloginpass')}}" method="POST">
            @csrf
            @method('PUT')
            <div class="input-group mb-3">
                <input type="hidden" name="rand_token" value="{{$token}}">
                <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror" placeholder="Enter New Password">
                <div class="input-group-append">
                <div class="input-group-text">
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
                <input type="hidden" name="rand_token" value="{{$token}}">
                <input type="password" name="password_confirmation" class="form-control  @error('password_confirmation') is-invalid @enderror" placeholder="Enter Confirm Password">
                <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-eye"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                @if ($errors->any())
                <div class="error_box">
                  <div class="text text-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                      @endforeach
                    </ul>
                  </div>
                </div>
                
              @endif
              </div>
            </div>
            <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <a href="{{route('adminlogin')}}" >Back</a>
                </div>
                </div>
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Update</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
         
        </div>
        <!-- /.login-card-body -->
      </div>
</x-adminloginlayout >
