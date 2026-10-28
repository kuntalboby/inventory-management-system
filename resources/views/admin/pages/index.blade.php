@section('currentPage', ' List of Pages')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Page List')
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
                      <h3 class="card-title">Pages</h3>
                      <div class="card-tools">
                        
                        <a href="{{route('admin.pages.add')}}" class="btn btn-primary text text-light">
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
                              

                              <div class="card card-primary">
                                
                                <div class="card-body">
                                    @csrf
                                    <div class="table-responsive">
                                  <table id="basic_table" class="scrollable table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                      <th>Serial No</th>
                                      <th>Name</th>
                                      <th>URL</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @if (!empty($pages))
                                     
                                        @foreach ($pages as $k=>$page)
                                           
                                            <tr>
                                              <td width="10%">{{$k+1}}</td>
                                              <td>{{ ucwords(strtolower($page->title)) }}</td>                                                
                                              
                                              <td>{{ url('/') .'/'.$page->slug}}</td>
                                              <td>
                                                @if ($page->status=='0')
                                                    <span class="tex tex-success">Active</span>
                                                @else
                                                    <span class="tex tex-danger">Inactive</span>
                                                @endif
                                              </td>
                                              <td>
                                                <div class="row">
                                                  <div class="col-md-4">
                                                    <a href="{{route('admin.pages.edit',[$page->slug])}}" title="Edit" class="text text-warning"><i class="fa fa-pen"></i></a>
                                                  </div>
                                                   {{-- <div class="col-md-4">
                                                        <a href="javascript:void(0);"
                                                             data-name="{{$page->slug}}"
                                                             tbl="page"
                                                             data-id="{{$page->slug}}" 
                                                             id="removeImage" class="text-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        
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