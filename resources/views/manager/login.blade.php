@section('title', ' Login')
<x-managerloginlayout>
  
        <div class="card-header text-center">
        <a href="javascript:void(0);" class="h1"><b>Manager</b> Login</a>
      </div>
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign in to start your session</p>

          @if (session('message'))
              <div class="alertBox">
                  <p class="text-center text text-{{session('response')}}">{{session('message')}}</p>
              </div>
          @endif
    
          <form  action="/managerlogin" method="POST">
            @csrf
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control  @error('email') is-invalid @enderror" placeholder="Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror" placeholder="Password">
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
                  <a href="{{route('manager.forgotPassword')}}" title="Forgot Password ?">Forgot Password ?</a>
              </div>
              <!-- <div class="col-8">
                <div class="icheck-primary">
                  <a href="{{route('managersignup')}}" >Signup</a>
                </div>
              </div>
              /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
         
        </div>
        <!-- /.login-card-body -->
     
</x-managerloginlayout>