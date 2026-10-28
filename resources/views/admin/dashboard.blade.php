@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Dashboard')
      <!-- Content Wrapper. Contains page content -->

    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
       <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $info['product'] }}</h3>

                <p>Products</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{route('admin.products')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>{{ $info['category'] }}</h3>

                  <p>Main Categories</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{route('admin.masters.categories')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $info['vendor'] }}</h3>

                <p>Vendors</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{route('admin.masters.vendors')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $info['order'] }}</h3>

                <p>Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="{{route('manage.orders')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
        </div>
       
        <div class="row">
            <div class="col-md-12">

              <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recent Activities</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                 
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  
                 @if ( !empty($info['activity']) )
                    @foreach ($info['activity'] as $activity )
                      <li class="item">
                        <div class="product-img">
                          <img src="{{ asset('/storage/website-image/history.png')}}" alt="Product Image" class="img-size-50">
                        </div>
                        <div class="product-info">
                          <a href="javascript:void(0)" class="product-title">
                            <span class="badge badge-info float-right">
                           {{ date( 'd-m-Y h:i A' ,strtotime($activity->created_at))}}
                          </span>
                          </a>
                          <span class="product-description">
                              {!! $activity->note !!}
                          </span>
                        </div>
                      </li>
                    @endforeach
                 @endif 
                
                </ul>
              </div>
              <!-- /.card-body -->
              <div class="card-footer text-center">
                {{-- <a href="javascript:void(0)" class="uppercase">View All Products</a> --}}
              </div>
              <!-- /.card-footer -->
            </div>
            </div>
        </div>
       
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  
  <!-- /.content-wrapper -->
</x-admininnerlayout>