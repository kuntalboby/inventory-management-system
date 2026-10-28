@section('title', ' Signup')
<x-adminloginlayout>
  
    <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign up form</p>
    
          <form  action="/adminsignup" method="POST">
            @csrf
            <div class="input-group mb-3">
                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name" >
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
                
              </div>
              <div class="input-group mb-3">
                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"   placeholder="Last Name" >
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
            </div>
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" >
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
              
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
             
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
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
                  <a href="{{route('adminlogin')}}" >Signin</a>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
          
        </div>
        <!-- /.login-card-body -->
      </div>
</x-adminloginlayout>