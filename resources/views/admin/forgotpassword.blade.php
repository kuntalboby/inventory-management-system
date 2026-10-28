@section('title', ' Login')
<x-adminloginlayout>
  
    <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Enter your email to update password.</p>
          @if (session('message'))
            <div class="alertBox">
                <p class="text-center text text-{{session('response')}}">{{session('message')}}</p>
            </div>
         @endif
    
          <form  action="{{route('admin.verifyuseremail')}}" method="POST">
            @csrf
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control  @error('email') is-invalid @enderror" placeholder="Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
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
                  <a href="{{route('adminlogin')}}" title="Forgot Password ?">Back to login ?</a>
                </div>
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
         
        </div>
        <!-- /.login-card-body -->
      </div>
</x-adminloginlayout>