@section('currentPage', ' List of PIN Codes')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','PIN Codes')
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
                      <h3 class="card-title">PIN Codes</h3>
                      <div class="card-tools">
                        <div class="d-none">
                            <form action="{{ route('admin.manager.pin-charge') }}" 
                                method="POST" 
                                id="uploadPinCharge"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls" required>
                            </form>
                        </div>
                        <a href="{{route('admin.masters.download-excel')}}" class="btn btn-warning text text-light">
                          Download Format
                        </a>
                         <button id="triggerForm" class="btn btn-primary text text-light">
                          Upload Format
                        </button>
                       
                        
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
                                  <table id="pin_charges_list" class="scrollable table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                      <th>SL</th>
                                      <th>PIN</th>
                                      <th>Charge</th>
                    
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @if (!empty($pincodes))
                                     
                                        @foreach ($pincodes as $k=>$pincode)
                                           
                                            <tr>
                                              <td width="10%">{{$k+1}}</td>
                                              <td>{{$pincode['pin_code']}}</td>                                                
                                              <td>₹ {{$pincode['charges']}}</td>                                                
                                            </tr>
                                          
                                            
                                        @endforeach
                                        
                                      @endif
                                    
                                   
                                    </tbody>
                                    
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