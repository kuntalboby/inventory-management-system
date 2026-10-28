@section('currentPage', ' Add Product for godown')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Add Products for Godown')
    
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">

              <div class="">
                <div class="alertBox" id="ajaxResponse"></div>
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
              <!-- general form elements -->
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Add Godown Product</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.products.go-down-products')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form id="goDownproductAction" action="{{route('storeGodownProduct')}}" enctype="multipart/form-data" method="post">
                 @csrf
                  {{-- <input type="hidden" name="randNm" value="{{$getInfo['randNumber']}}"/> --}}
                  <div class="card-body" id="action" mode="A" slug="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" name="name" 
                                data-slug=""
                                value="{{old('name')}}"
                                data-for="mastersdata"
                                data-section="godownprod"
                                class="form-control searchInput" id="title" 
                                 placeholder="Enter Name *">
                                 <ul id="suggestionList" style="list-style:none; padding:0;"></ul>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parent_id ">Select Category *</label>
                                <select class="form-control product_category_id selectPicker"  name="category_id" id="category_id">
                                    <option value="">Select Category</option>
                                    @if (!empty($getInfo['categories']))
                                       
                                    @foreach ($getInfo['categories'] as $cate)
                                        
                                        <option 
                                        @if ( old('category_id') == $cate['id'])
                                          selected="selected"
                                        @endif
                                        value="{{$cate['id']}}" >{{ ucwords(strtolower($cate['name'] ))}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div><div class="col-md-4">
                            <div class="form-group">
                                <label for="parent_id ">Product Unit Type *</label>
                                <select class="form-control mainUnitCatId unit_category_id selectPicker"  name="unit_category_id" id="unit_category_id">
                                    <option value="">Select Unit Type</option>
                                      @if (!empty($getInfo['mainUnits']))
                                        @php $list = []; @endphp
                                        @foreach ($getInfo['mainUnits'] as $ck => $ucTpe)
                                            @php
                                                $unitNames = $ucTpe->units->pluck('name')->toArray();
                                            @endphp
                                            <option 
                                             @if ( old('unit_category_id') == $ucTpe['unit_category_id'])
                                                selected="selected"
                                              @endif
                                            value="{{ $ucTpe['id'] }}">
                                                {{ $ucTpe['name'] }} : {{ implode(',', $unitNames) }}
                                            </option>
                                        @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                       
                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="short_description">Short Description </label>
                                <textarea id="short_description" name="short_description" class="form-control ckeditor" placeholder="Enter Short Description">{{old('short_description')}}</textarea>
                            </div>
                        </div>
                                               
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header" style="background: #c5def0">
                              <h3 class="card-title">Add More Vendors</h3>
                              
                            </div>
                        </div>
                    </div>
                    <div class="addVendorBox" id="addVendorBox"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header" style="">
                              <div class="card-tools">
                                <button title="Add Vendor" id="addVendorGoDown" mode="add" type="button" class="btn btn-primary" onclick="addVendor();">
                                    <i class="fa fa-plus"></i>
                              </button>
                              </div>
                            </div>
                        </div>
                    </div>

                    

               
                  </div>
                  <!-- /.card-body -->
                   
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
  
        
            </div>

            
    
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
   
<x-validation-script :getInfo="$getInfo"></x-validation-script>

</x-admininnerlayout>
