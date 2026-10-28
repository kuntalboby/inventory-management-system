@section('currentPage', ' Edit Unit')
@php
    $bgColor="dark";
   
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Unit')
    
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
                  <h3 class="card-title">Edit Unit</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.masters.unit')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.masters.unitUpdate',$getInfo->slug)}}"  enctype="multipart/form-data" method="post">
                 @csrf
                 @method('put')
                  <div class="card-body">
                                     
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" name="name" 
                                value="{{ old('name', $getInfo->name)}}" 
                                class="form-control searchInput" 
                                data-slug="{{$getInfo->slug}}"
                                data-for="mastersdata"
                                data-section="units"
                                id="title" placeholder="Enter Name *">
                                <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Unit Type*</label>
                                <select class="form-control" name="unit_category_id" id="unit_category_id">
                                      <option value="">Unit Type</option>
                                    @foreach ($unitCats as $uk=>$unitCat)
                                            <option 
                                                value="{{$unitCat->id}}"
                                                @if($getInfo->unit_category_id == $unitCat->id)
                                                  selected="selected"
                                                @endif 
                                              >{{$unitCat->name}}</option>
                                    @endforeach
                                
                            </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Base Unit </label>
                                <select class="form-control" name="parent_id" id="parent_id">
                                      <option value="">Base Unit</option>
                                    @foreach ($unts as $ks=>$baseUnit)
                                            <option 
                                             unit-cat={{$baseUnit->unit_category_id}}
                                             value="{{$baseUnit->id}}"
                                              @if ($getInfo->parent_id == $baseUnit->id)
                                                  selected="selected"
                                              @endif
                                              >{{$baseUnit->name}}</option>
                                    @endforeach
                                
                            </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="conversion_factor">Conversion factor *</label>
                                <input type="number" value="{{ old('name', $getInfo->conversion_factor)}}" name="conversion_factor" class="form-control" id="conversion_factor" placeholder="Enter Conversion factor">
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