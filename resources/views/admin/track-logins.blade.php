@section('currentPage', ' List of Logins')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Logins List')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
      
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Logins</h3>
                     
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
                                      <th>Serial No</th>
                                       <th>User</th>
                                       <th>Date</th>
                                      <th>IP Address</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @if (!empty($records))
                                     
                                        @foreach ($records as $k=>$record)
                                           
                                            <tr>
                                              <td width="10%">{{$k+1}}</td>
                                              <td>{{$record->user->first_name.' '.$record->user->last_name}}(
                                                    @if ($record->user->role == 'admin' )
                                                        <span class="text text-success">Admin</span> 
                                                    @else
                                                        <span class="text text-warning">Manager</span> 
                                                    @endif
                                               )
                                              </td>                                                
                                              <td>{{date('d-m-Y h:i:s A', strtotime($record['last_login_at']))}}</td>                                                
                                              <td>{{$record['last_login_ip_address']}}</td>                                                
                                              
                                              
                                              
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