  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="javascript:void(0);" target="_b" class="brand-link">
      <span class="brand-text font-weight-light">
        {{$siteInfo->site_name}}
      </span>
    </a>

    
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          
          @if ($profileImage->profile_image!='' && file_exists(public_path("storage")."/profile-pics/".$profileImage->profile_image) )

             <img alt="profile Pic" src="{{ asset('/storage/profile-pics/'.$profileImage->profile_image)}}" class="img-circle elevation-2" /> 
             @else
               <img alt="No Pic" src="{{ asset('/storage/website-image/no-image.png')}}" class="img-circle elevation-2" />
             @endif
          
        </div>
        <div class="info">
          <a href="javascript:void(0);" class="d-block">{{auth()->user()->first_name.' '.auth()->user()->last_name}}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
        @if(empty($menuList))

        <li class="nav-item">
          <a href="{{route('admin.dashboard')}}" class="nav-link @if (request()->routeIs('admin.dashboard')) active @endif">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin.siteinfo')}}" class="nav-link @if (request()->routeIs('admin.siteinfo')) active @endif">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Site Information
            </p>
          </a>
        </li>
       
        @php
          $getPageName = request()->route()->getName();
          $PersonalPages = array('admin.profile','admin.password','admin.managers.index', 'admin.managers.manager-add','admin.managers.manager-edit','admin.track-logins');
        @endphp
        @if (in_array( $getPageName, $PersonalPages))
        <li class="nav-item menu-is-opening menu-open">
          <a href="javascript:void(0);" class="nav-link active">
        @else
        <li class="nav-item">
          <a href="javascript:void(0);" class="nav-link"> 
        @endif
            <i class="nav-icon fas fa-copy"></i>
            <p>
             Personal Information
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

             <li class="nav-item">
              <a href="{{route('admin.managers.index')}}" class="nav-link @if (request()->routeIs('admin.managers*') || request()->routeIs('admin.manager-*')) active @endif">
                <i class="far fa-circle nav-icon"></i>
                <p>Managers</p>
              </a>
            </li>
            

            <li class="nav-item">
              <a href="{{route('admin.profile')}}" class="nav-link @if (request()->routeIs('admin.profile')) active @endif">
                <i class="far fa-circle nav-icon"></i>
                <p>Profile</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.password')}}" class="nav-link @if (request()->routeIs('admin.password')) active @endif">
                <i class="far fa-circle nav-icon"></i>
                <p>Update Password</p>
              </a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.track-logins')}}" class="nav-link @if (request()->routeIs('admin.track-logins')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Track Logins</p>
                </a>
              </li>
          </ul>
        </li>
         <li class="nav-item {{ request()->routeIs('admin.masters*') ? 'menu-open': '' }}">
          <a href="javascript:void(0);" class="nav-link {{ request()->routeIs('admin.masters*') ? 'active': '' }}">

            <i class="nav-icon fas fa-copy"></i>
            <p>
              Masters
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
           
            <li class="nav-item">
              <a href="{{route('admin.masters.unit')}}" class="nav-link
               @if ( request()->routeIs('admin.masters.unit*') || request()->routeIs('admin.masters.unit-*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Unit of Measure</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.masters.categories')}}" class="nav-link
               @if ( request()->routeIs('admin.masters.categories*') || request()->routeIs('admin.masters.categories-*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Categories</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('admin.masters.sticker-sum')}}" class="nav-link
               @if ( request()->routeIs('admin.masters.sticker*') || request()->routeIs('admin.masters.sticker-*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Stickers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.masters.pincodes')}}" class="nav-link
               @if ( request()->routeIs('admin.masters.pincodes'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>PIN Codes</p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="{{route('admin.masters.brands')}}" class="nav-link
               @if ( request()->routeIs('admin.masters.brand*') || request()->routeIs('admin.masters.brand-*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Brands</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.masters.warehouses')}}" class="nav-link
               @if ( request()->routeIs('admin.masters.warehouse*') || request()->routeIs('admin.masters.warehouse-*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Warehouses</p>
              </a>
            </li> --}}
            <li class="nav-item">
              <a href="{{route('admin.masters.vendors')}}" class="nav-link
               @if ( request()->routeIs('admin.masters.vendor*') || request()->routeIs('admin.masters.vendor-*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Vendors</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('admin.masters.customers')}}" class="nav-link
               @if ( request()->routeIs('admin.masters.customer*') || request()->routeIs('admin.masters.customer-*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Customers</p>
              </a>
            </li>


          </ul>
        

        {{-- <li class="nav-item">
          <a href="{{route('admin.products')}}" class="nav-link @if (request()->routeIs('admin.products*')) active @endif">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Products
            </p>
          </a>
        </li> --}}


           @php

    $prdMgmnt = request()->route()->getName();
    $prdMgmntPages = [
        'admin.products',
        'admin.products.products-add',
        'admin.products.products-edit*',
        'admin.products.go-down-products',
        'admin.products.add-godown-products',
        'admin.products.godown-products-edit*',
        'admin.products.products-vendors',
        'admin.products.damaged',
        'admin.products.damaged-product-add',
        'admin.products.damaged-product-edit*',
        'admin.products.damaged-packet',
        'admin.products.damaged-packet-add',
        'admin.products.damaged-packet-edit*'
        
    ];

          $isProductActive = false;

          foreach ($prdMgmntPages as $pattern) {
              if (Illuminate\Support\Str::is($pattern, $prdMgmnt)) {
                  $isProductActive = true;
                  break;
              }
          }
      @endphp

      <li class="nav-item {{ $isProductActive ? 'menu-is-opening menu-open' : '' }}">
          <a href="javascript:void(0);" class="nav-link {{ $isProductActive ? 'active' : '' }}">

              <i class="nav-icon fas fa-copy"></i>
            <p>
             Products Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview" style="">

             <li class="nav-item">
              <a href="{{route('admin.products.go-down-products')}}"
              class="nav-link
              @if (request()->routeIs('admin.products.go-down-products')
              || request()->routeIs('admin.products.add-godown-products')
              || request()->routeIs('admin.products.godown-products-edit*')
              || request()->routeIs('admin.products.products-vendors')
              

              
            )
            
               active 
            @endif">
                <i class="far fa-circle nav-icon"></i>
                <p>Godown Stat</p>
              </a>
            </li>
            

            <li class="nav-item">
              <a href="{{route('admin.products')}}" class="
              @if (request()->routeIs('admin.products')
                || request()->routeIs('admin.products.products-add')
                || request()->routeIs('admin.products.products-edit*')
              )
              active
              @endif
              nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p>For Sale</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('admin.products.damaged')}}" class=" 
               @if (request()->routeIs('admin.products.damaged') 
                 || request()->routeIs('admin.products.damaged-product-add')
                 || request()->routeIs('admin.products.damaged-product-edit*')
                 
                )
                active
              @endif
              nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p>Godown Damaged Prod</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('admin.products.damaged-packet')}}" class=" 
               @if (request()->routeIs('admin.products.damaged-packet') 
                 || request()->routeIs('admin.products.damaged-packet-add')
                 || request()->routeIs('admin.products.damaged-packet-edit*')
                 
                 
                )
                active
              @endif
              nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p> Damaged Packet Prod</p>
              </a>
            </li>
            
          </ul>
        </li>


        <li class="nav-item {{ request()->routeIs('admin.pages*') ? 'menu-open': '' }}">
          <a href="javascript:void(0);" class="nav-link {{ request()->routeIs('admin.pages*') ? 'active': '' }}">

            <i class="nav-icon fas fa-copy"></i>
            <p>
              Frontend Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
           
            <li class="nav-item">
              <a href="{{route('admin.pages.images','G')}}" class="nav-link
               @if ( request()->routeIs('admin.pages.images') && request()->route('slug') ===  'G')
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Images</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.pages.images','HS')}}" class="nav-link
               @if ( request()->routeIs('admin.pages.images') && request()->route('slug') === 'HS')
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Home Slider</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.pages.testimonials')}}" class="nav-link
               @if ( request()->routeIs('admin.pages.testimonials*') || request()->routeIs('admin.pages.testimonial-*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Testimonials</p>
              </a>
            </li>
             <li class="nav-item">
              <a href="{{route('admin.pages.shortcodes')}}" class="nav-link
               @if ( request()->routeIs('admin.pages.shortcodes'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Short Codes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin.pages')}}" class="nav-link
               @if ( request()->routeIs('admin.pages') || request()->routeIs('admin.pages.add*')
               || request()->routeIs('admin.pages.edit*'))
                  active
               @endif
              ">
                <i class="far fa-circle nav-icon"></i>
                <p>Pages</p>
              </a>
            </li>
            

          </ul>
        </li>  
        <li class="nav-item">
          <a href="{{route('manage.orders')}}" class="nav-link
            @if (request()->routeIs('manage.new-order*') 
            || request()->routeIs('manage.orders')
            || request()->routeIs('manage.invoice*')
            
            )
            
               active 
            @endif">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Orders
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{route('admin.reports')}}" class="nav-link @if (request()->routeIs('admin.reports')) active @endif">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Reports
            </p>
          </a>
        </li>
        @else
        
        
            @foreach ($menuList as $menuName=>$menu)

            <li class="nav-item">
              <a href="{{route($menu[0])}}" class="nav-link 
                @if (in_array(request()->route()->getName(), $menu)) 
                    active 
                @endif
                 ">
                <i class="nav-icon fas fa-th"></i>
                <p>
                  {{$menuName}}
                </p>
              </a>
            </li>
              
            @endforeach

        @endif


          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>