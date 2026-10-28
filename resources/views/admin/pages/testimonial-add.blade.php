@section('currentPage', ' Add Testimonial')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Add Testimonial')
    
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <col-md-12>
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
            </col-md-12>
              <!-- general form elements -->
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Add Testimonial</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.pages.testimonials')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.pages.testimonial-store')}}" enctype="multipart/form-data" 
                method="post"
                
                >
                 @csrf
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="author">Author's Name *</label>
                                <input type="text" 
                                    value="{{old('author')}}"
                                  name="author"  class="form-control" id="title" placeholder="Enter Name *">
                                
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation">Author's Designation</label>
                                <input type="text"
                                  value="{{old('designation')}}"
                                  name="designation"  class="form-control" id="designation" placeholder="Enter Designation *">
                                
                              </div>
                        </div>
                        
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="content">Content </label>
                                <textarea id="content" name="content" class="form-control ckeditor" placeholder="Enter Content">{{old('content')}}</textarea>
                            </div>
                        </div>
                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type='file' class="form-control" id="image" name="image" accept=".png, .jpg, .jpeg" />
                            </div>
                        </div>
                    </div>
                    

                
                  </div>
                  <!-- /.card-body -->
  
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
  
        
            </div>
    
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>