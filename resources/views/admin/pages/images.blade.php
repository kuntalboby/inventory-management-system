
@php
    $bgColor="dark";
    $pageTl = 'List of Images';
    $col='2';
    $shortCode='';
    $isDeleteBtn = false;
    if($slug == 'HS'){
        $pageTl = 'Slider Images';
        $col='4';
        $isDeleteBtn = true;
        $shortCode="[BLOCK-HOME_SLIDER]" ;
    }
@endphp
@section('currentPage', $pageTl)

<x-admininnerlayout :$bgColor>
  @section('title',$pageTl)
    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">

            <div class="row">
              <div class="col-md-12" id="ajaxResponse"></div>
               
                <div class="col-12">
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
                  <!-- /.card -->
      
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Images
                        @if ($isDeleteBtn)
                        ( <button class="btn btn-warning copyBtn"  text-to-copy="{{$shortCode}}">
                          {{$shortCode}}</button>  )
                        @endif
                        </h3>
                      <div class="card-tools">
                        <div class="d-none">
                            <form action="{{ route('admin.pages.image-add') }}" 
                                method="POST" 
                                id="uploadImgForm"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="{{$slug}}" />
                                <input type="file" name="files[]" multiple id="zipFile" accept=".jpg,.jpeg,.png,.zip" required>
                            </form>
                        </div>
                        <button id="triggerUploadImage" class="btn btn-primary text text-light">
                          Upload .jpg/.png
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
                              

                              <div class="card card-warning">
                                
                                <div class="card-body">
                                    @csrf
                                 <div class="row">
                                    @if ($images)
                                        @foreach ($images as $image)
                                            
                                        <div class="col-md-12 col-lg-6 col-xl-{{$col}}" id="{{$image->name}}">
                                            <div class="card mb-2 bg-gradient-dark">
                                                 @if ($image->name!='' && file_exists(public_path("storage/frontend/pages/" . $image->name)))
                                                        <img class="card-img-top" src="{{ asset('storage/frontend/pages/'.$image->name)}}" alt="Image">
                                                 @endif
                                             <div class="card-img-overlay d-flex flex-column justify-content-end">
                                               
                                               <div class="row">
                                                    <div class="col-md-6 text-right">
                                                    <button class="btn btn-success copyBtn"
                                                    data-id="copyText_{{ $image->id }}" 
                                                    text-to-copy="{{ asset('storage/frontend/pages/' . $image->name) }}">
                                                    <span id="copyText_{{ $image->id }}">Copy</span>
                                                </button>
                                                    </div>
                                                    <div class="col-md-6 ">
                                                         @if ($isDeleteBtn)
                                                            <button data-name="{{$image->name}}" data-id="" tbl="img" id="removeImage" class="btn btn-danger text text-light">
                                                                Remove
                                                            </button>
                                                        @endif
                                                    </div>
                                               </div>
                                                
                                               
                                            </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="col-md-12 col-lg-6 col-xl-2">
                                            <p class="tex text-center text-dark">No image found !</p>
                                        </div>  
                                    @endif
                                  
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