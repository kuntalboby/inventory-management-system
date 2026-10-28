@section('currentPage', ' Vendor Details ')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Vendor Details')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid" id="invoiceType">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
                  
      
                  <div class="card card-primary card-outline">
                    
                    <div class="card-body">
                        <div class="row">
                          
                            <div class="col-md-12">

                              @if (isset($getInfo) && !empty($getInfo) )
                                  @foreach ( $getInfo as $date => $items)
                                    <div class="card collapsed-card">
                                        <div class="card-header">
                                          <h3 class="card-title">
                                              <b>Purchase Date</b>: {{ date('d-m-Y',strtotime($date))  }}
                                              
                                            </h3>

                                          <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                              <i class="fas fa-plus"></i>
                                            </button>
                                            
                                          </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-0" style="display: none;">
                                            <div class="row">
                                                    <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr style="border:none">
                                                                    <th width="50%" colspan="2">
                                                                        <h2 class="text-start">Purchase Details</h2>
                                                                    </th>
                                                                </tr>
                                                                
                                                            </thead>
                                                            
                                                        </table>

                                                        <table>
                                                            <thead>
                                                                
                                                                <tr class="bg-blue">
                                                                    <th>Sl</th>
                                                                    <th>Product</th>
                                                                     <th>Actual Price</th>
                                                                     <th>GST</th>
                                                                     <th>Quantity</th>
                                                                    <th>Buying Price</th>                                                                   
                                                                </tr>
                                                            </thead>
                                                          <tbody>
                                                            @if (isset($items) && !empty($items) )
                                                                @foreach ( $items as $key=>$item)
                                                                  <tr>
                                                                            <td width="10%">{{$key+1}}</td>
                                                                            <td>
                                                                            {{ ucwords(strtolower($item->goDownProduct->name)) }}
                                                                            </td>
                                                                            <td width="10%" style="font-family: DejaVu Sans, sans-serif;">₹ {{ $item->actual_price }}</td>
                                                                            <td width="10%" style="font-family: DejaVu Sans, sans-serif;">{{ $item->gst }} % </td>
                                                                            <td width="10%">{{$item->stock_quantity }} {{ ucwords(strtolower($item->unit->name)) }} </td>
                                                                            <td width="10%" style="font-family: DejaVu Sans, sans-serif;">₹ {{ $item->buying_price  }}</td>
                                                                          </tr>
                                                                @endforeach
                                                                
                                                                    
                                                                  @endif
                                                              </tbody>
                                                        </table>
                                                </div>
                                                    </div>
                                            </div>
                                        </div>
                                        
                                      </div>
                                  @endforeach
                                  
                              @else
                                
                              @endif
                            

                            </div>
                        </div>
                    </div>
                           
                    
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
              </div>

        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>