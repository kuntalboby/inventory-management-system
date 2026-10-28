@section('currentPage', ' Edit Page:'.$page->title)
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Page:'.$page->title)
    
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
                  <h3 class="card-title">Edit {{$page->title}}</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.pages')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.pages.update-page',$page->slug)}}" enctype="multipart/form-data" method="post">
                 @csrf
                 @method('PUT')
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">Page Name *</label>
                                <input type="text" data-slug="" 
                                  value="{{old('title',$page->title)}}"
                                  readonly="readonly"
                                  name="title" class="form-control" id="title" placeholder="Enter Name *">
                              </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="author">Meta Author Name</label>
                                <input type="text" 
                                  value="{{old('author',$page->author)}}"
                                  name="author" class="form-control"  id="author" placeholder="Enter Author Name">
                              </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="body_class">Body Class </label>
                                <input type="text" data-slug="" 
                                  value="{{old('body_class',$page->body_class)}}"
                                  name="body_class" class="form-control"  id="body_class" placeholder="Enter Special class name">
                              </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="author">Body Id </label>
                                <input type="text" 
                                  value="{{old('body_id',$page->body_id)}}"
                                  name="body_id" class="form-control"  id="body_id" placeholder="Enter Body id">
                              </div>
                        </div> 
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="author">Banner Image (for inner page)</label>
                                <input type="text"
                                  value="{{old('bg_image',$page->bg_image)}}"
                                  name="bg_image" class="form-control"  id="bg_image" placeholder="Image url">
                              </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="meta_keyword">Meta Keyword </label>
                                <textarea id="meta_keyword" name="meta_keyword" class="form-control" placeholder="Enter Keyword">{{old('meta_keyword',$page->meta_keyword)}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="meta_description">Meta Description </label>
                                <textarea id="meta_description" name="meta_description" class="form-control" placeholder="Enter Description">{{old('meta_description',$page->meta_description)}}</textarea>
                            </div>
                        </div>
                        
                        
                    </div>
                    @php
                        $sectionCounter = 1;
                    @endphp
                    @if (!empty($page->pageSections))
                       <div class="mb-5 additionalSections" id="additionalSections" data-val="{{$page->pageSections->count()}}"> 
                       
                        @foreach ( $page->pageSections as $key=>$section)
                            <div class="row  @if ($key > 0) innerSections @endif pageSections" id="section_{{$key}}">
                               <div class="card-header mb-3 w-80">
                                    <h3 class="card-title  @if ($key > 0) draggableSection  @endif">
                                      @if ($key > 0)
                                        <span class="handle ui-sortable-handle">
                                              <i class="fas fa-ellipsis-v"></i>
                                              <i class="fas fa-ellipsis-v"></i>
                                          </span>
                                         @endif
                                        Section-<span class="secName">{{$sectionCounter}}</span>
                                    </h3>
                                </div>
                                @if ($key > 0) 
                                  {{-- Banner cant b removed --}}
                                <div class="card-tools">
                                        <a onclick="trashSection({{$key}});" class="btn btn-danger">
                                          <i class="fa fa-trash"></i>
                                        </a>
                                </div>
                                @endif
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="heading_[{{$key}}]">Heading </label>
                                    <input type="hidden" class="form-control" 
                                    
                                    name="data[{{$key}}][sort_position]" id="sort_position_section_{{$key}}" value="{{$section->sort_position}}"/>

                                    <input type="text" class="form-control" 
                                    value="{{ old('data['.$key.'][heading]', $section->heading) }}"
                                    name="data[{{$key}}][heading]" id="heading_{{$key}}"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content[{{$key}}]">Content *</label>
                                        <textarea id="content_{{$key}}" name="data[{{$key}}][content]" class="form-control ckeditor" placeholder="Enter full content">{{old('data['.$key.'][content]',$section->content)}}</textarea>
                                    </div>
                                </div>
                                    
                            </div>
                            @php
                              $sectionCounter=$sectionCounter+1;
                            @endphp
                        @endforeach
                        </div>
                    @endif
                    
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header" style="">
                              <div class="card-tools">
                                <button title="Section" type="button" class="btn btn-primary" onclick="sectioncntl('add','N')">
                                    <i class="fa fa-plus"></i>
                              </button>
                              </div>
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