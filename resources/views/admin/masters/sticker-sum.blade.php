@section('currentPage', ' List of Stickers')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Stickers List')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
              <div class="col-md-12">
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
                </div>
                <div class="col-12">
                 
                  <!-- /.card -->
      
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Stickers</h3>
                      <div class="card-tools">
                        
                        <a href="{{route('admin.masters.sticker-sold')}}" class="btn btn-warning text text-light">
                          Sold
                        </a>
                        <a href="{{route('admin.masters.sticker-add')}}" class="btn btn-primary text text-light">
                          Bought
                        </a>
                        
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @csrf

                        <div class="form-group text-center">
                         
                          
                        </div>
                        <div class="row">
                            
                            <div class="col-md-12 sgmts" id="segement_P">
                              

                              <div class="card card-primary">
                                
                                <div class="card-body">
                                    @csrf
                                    <div class="table-responsive">
                                  <table id="basic_table" class="scrollable table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                      <th>SL</th>
                                      <th>Name</th>
                                      <th>Quantity</th>
                                      <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @if (!empty($allSticker))
                                     
                                        @foreach ($allSticker as $k=>$sticker)
                                           
                                            <tr>
                                              <td width="10%">{{$k+1}}</td>
                                              <td>{{$sticker['name']}}</td>                                                
                                              <td>{{$sticker['quantity']}}</td>                                                
                                              
                                              
                                              <td>
                                                <div class="row">
                                                   <div class="col-md-4">
                                                    <a href="{{route('admin.masters.stickers',[$sticker['sticker_code']])}}" title="List" class="text text-warning"><i class="fa fa-eye"></i></a>
                                                  </div>
                                                 
                                                </div>
                                              </td>
                                            </tr>
                                          
                                            
                                        @endforeach
                                        
                                      @endif
                                    
                                   
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                     
                                    </tr>
                                    </tfoot>
                                  </table>
                                  </div>
                                </div>
                              </div>

                            </div>
                            <div class="col-md-12 d-none" id="json-output"></div>
                            
                            
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