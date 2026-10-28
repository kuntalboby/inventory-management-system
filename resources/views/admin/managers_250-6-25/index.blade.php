@section('currentPage', ' List of Managers')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Managers List')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
      
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title">Managers</h3>
                      <div class="card-tools">
                        
                        <a href="{{route('admin.managers.manager-add')}}" class="btn btn-primary text text-light">
                          Add
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
                              

                              <div class="card card-warning">
                                
                                <div class="card-body">
                                    @csrf
                                  <table id="basic_table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                      <th>Serial No</th>
                                      <th>Name</th>
                                      <th>Phone</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @if (!empty($managers))
                                     
                                        @foreach ($managers as $k=>$manager)
                                           
                                            <tr>
                                              <td width="10%">{{$k+1}}</td>
                                              <td>{{$manager['first_name']}} {{$manager['last_name']}}</td>                                                
                                              <td>{{$manager['managerdetail']['personal_phone_one']}} / {{$manager['managerdetail']['personal_phone_two']}}


                                              </td>
                                              <td>
                                                @if ($manager['status'] === '1')
                                                   <div class="text text-success">Active</div> 
                                                @elseif ($manager['status'] === '0')  
                                                   <div class="text text-danger">Inactive</div>  
                                                @else
                                                    <div class="text text-warning">Hold</div> 
                                                @endif
                                                 
                                                </td>
                                              <td>
                                                <div class="row">
                                                  <div class="col-md-4">
                                                    <a href="{{route('admin.managers.manager-edit',[$manager['id']])}}" title="Edit" class="text text-warning"><i class="fa fa-pen"></i></a>
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