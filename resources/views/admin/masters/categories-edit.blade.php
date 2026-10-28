@section('currentPage', ' Edit Categories')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Categories')
    
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
                  <h3 class="card-title">Edit Categories</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.masters.categories')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.masters.categoriesUpdate',$getInfo->slug)}}"  enctype="multipart/form-data" method="post">
                 @csrf
                 @method('put')
                  <div class="card-body">
                                     
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" name="name"
                                data-section="categories"
                                data-for="mastersdata"
                                data-slug="{{$getInfo->slug}}"
                                value="{{ old('name', ucwords(strtolower($getInfo->name)))}}" 
                                class="form-control searchInput" id="title" placeholder="Enter Name *">
                                <ul id="suggestionList" style="list-style:none; padding:0;"></ul>

                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id ">Select Category *</label>
                                <select class="form-control parent_id selectPicker"  name="parent_id" id="parent_id">
                                    <option value="">Parent Category</option>
                                    @if (!empty($allCategories))
                                       
                                    @foreach ($allCategories as $cate)
                                      @if($getInfo->id != $cate['id']
                                         &&  $getInfo->bulk_for_backend === 0
                                      )

                                        <option value="{{$cate['id']}}" @if (
                                          $getInfo->parent_id == $cate['id']
                                          )
                                            selected="selected"
                                        @endif >{{ ucwords(strtolower( $cate['name'] )) }}</option>
                                        @endif
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Details </label>
                                <textarea id="description" name="description" class="form-control ckeditor" placeholder="Enter Details">{{ old('description', $getInfo->description)}}</textarea>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select class="form-control" name="status" id="status">
                                @php
                                    $status[0]='Active';
                                    $status[1]='Inactive';
                                @endphp
                                    @foreach ($status as $ks=>$stats)
                                            <option value="{{$ks}}" @if ($getInfo->status == $ks)
                                                selected="selected"
                                            @endif>{{$stats}}</option>
                                    @endforeach
                                
                            </select>
                            </div>
                        </div> --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type='file' class="form-control" id="image" name="image" accept=".png, .jpg, .jpeg" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if ($getInfo->image!='' && file_exists(public_path("storage/products/categories/" . $getInfo->image)))
                                 <img style="width: 150px; text-align: center; margin: 0 auto; display: block;" class="img-responsive" src="{{ asset('storage/products/categories/'.$getInfo->image)}}" alt="{{$getInfo->name}}" />
                              @else
                                <img  style="width: 150px; text-align: center; margin: 0 auto; display: block;" src="{{ asset('/storage/website-image/no-image.png')}}" alt="No Pic" />
                             @endif
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