@section('currentPage', ' List of Vendors')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Vendors')
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-12">
                 
                  <!-- /.card -->
      
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title">Vendors</h3>
                      <div class="card-tools">
                        
                        <a href="{{route('admin.masters.vendor-add')}}" class="btn btn-primary text text-light">
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
                                    <div class="table-responsive">
                                         <table id="basic_table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                      <th>Serial No</th>
                                      <th>Name</th>
                                      <th>Address</th>
                                      <th>Contact</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @if (!empty($allVendors))
                                     
                                        @foreach ($allVendors as $k=>$vendors)
                                           
                                            <tr>
                                              <td width="10%">{{$k+1}}</td>
                                              <td>{{$vendors['name']}}</td>                                                
                                              <td>{{$vendors['city']}} , {{$vendors['state']}}</td>
                                              <td>{{$vendors['phone_1']}}</td>  

                                              <td>
                                                @if ($vendors['status'] === '0')
                                                   <div class="text text-success">Active</div> 
                                                @else
                                                    <div class="text text-danger">Inactive</div> 
                                                @endif
                                                 
                                                </td>
                                              <td>
                                                <div class="row">
                                                  <div class="col-md-4">
                                                    <a href="{{route('admin.masters.vendor-edit',[$vendors['slug']])}}" title="Edit" class="text text-warning"><i class="fa fa-pen"></i></a>
                                                  </div>
                                                  {{-- <div class="col-md-4">
                                                    <a  data-owner-id="{{$vendors['id']}}" onclick="if(confirm('Are you sure you want to delete it?')){ return true; }else{ return false; }" href="{{route('admin.masters.warehouse-delete',[$vendors['slug']])}}" id="page_delete_{{$vendors['id']}}" title="Delete" class="text text-danger anchore"><i class="fa fa-trash"></i></a>
                                                  </div> --}}
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