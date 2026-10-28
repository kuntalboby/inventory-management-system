  <!-- Navbar -->

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="javascript:void(0);" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        
        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="nav-link">Home</a>
        </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route(auth()->user()->role.'.profile')}}" class="nav-link">Profile</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

    
      <li class="nav-item">
        <a class="nav-link" href="{{route(auth()->user()->role.'.logout')}}">
          <i class="fa fa-power-off text-danger"></i>
        </a>
      </li>
     
    </ul>
  <!-- /.navbar -->