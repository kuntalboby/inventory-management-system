@section('currentPage', ' List of Units')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Units List')
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
                      <h3 class="card-title">Units</h3>
                      <div class="card-tools">
                        
                        {{-- <a href="{{route('admin.masters.unit-add')}}" class="btn btn-primary text text-light">
                          Add
                        </a> --}}
                        
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
                                      <th>Serial No</th>
                                      <th>Name</th>
                                      <th>Base Unit</th>
                                      {{-- <th>Action</th> --}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @if (!empty($allUnit))
                                     
                                        @foreach ($allUnit as $k=>$unit)
                                           
                                            <tr>
                                              <td width="10%">{{$k+1}}</td>
                                              <td>{{ ucwords(strtolower($unit['name'])) }}</td>                                                
                                              
                                              <td>
                                                @if (!$unit['parent_id'])
                                                   <div class="text text-success">Base</div> 
                                                @else
                                                    <div class="text text-info text-bold">Base: {{ ucwords(strtolower( $unit['parent_id'] )) }}</div> 
                                                @endif
                                                 
                                                </td>
                                              {{--<td>
                                                <div class="row">
                                                  <div class="col-md-4">
                                                    @if ($unit['id'] <= 4 ) 
                                                      N/A
                                                    @else
                                                      <a href="{{route('admin.masters.unit-edit',[$unit['slug']])}}" title="Edit" class="text text-warning"><i class="fa fa-pen"></i></a>
                                                      @endif
                                                  </div>
                                                  
                                                </div>--}}
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