@section('currentPage', ' Thank You')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Thank You')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
      
                  <div class="card card-primary card-outline">
                    
                          <div class="thank-you-box">
                            <div class="card-body">
                                <i class="fas fa-check-circle thank-you-icon"></i>

                                @if (session('message'))
                                    
                                        <h1 class="text-success">Mail Sent!</h1>
                                        <p class="mt-3 mb-4 text-center text text-{{session('response')}}">{{session('message')}}</p>
                                 @else   
                                  <h1 class="text-success">Thank You!</h1>
                                  <p class="mt-3 mb-4 text-muted">Your order has been successfully placed.</p>
                                @endif
                                

                                <a href="{{ route('manage.invoicedownload',[$ordernumber,'download'])}}" 
                                  class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>

                                <a href="{{ route('manage.invoicedownload',[$ordernumber,'mail'])}}" class="btn btn-warning">
                                    <i class="fas fa-envelope"></i> Mail PDF
                                </a>

                                <a href="{{route('manage.new-order')}}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> New Order
                                </a>
                            </div>
                            </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
              </div>

        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>