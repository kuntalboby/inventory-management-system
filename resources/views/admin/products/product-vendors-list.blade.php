
@php
    $bgColor="dark";
    $getInfo['product']->name = ucwords(strtolower($getInfo['product']->name))

@endphp
@section('currentPage', 'Vendors of '.$getInfo['product']->name)
<x-admininnerlayout :$bgColor>
  @section('title','Vendors of '.$getInfo['product']->name)
    
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
                  <h3 class="card-title">{{$getInfo['product']->name.'\'s'}} Vendor List</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.products.go-down-products')}}" class="btn btn-light mr-1">
                      Back
                    </a>
                    <button type="button" class="btn btn-info text text-light" onclick="openSearchBox('vendorSearchFilter');">
                          Search
                        </button>
                    
                  </div>
                </div>
                <div class="card-body">
                <form id="vendorSearchFilter"
                    class="card card-warning {{ request()->isMethod('post') ? '' : 'd-none' }}"
                    action="{{ route('admin.products.products-vendors', $getInfo['product']->slug) }}"
                    method="POST">
                    @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="row">

                                {{-- Purchase Date --}}
                                 <div class="col-md-4">
                                     <div class="form-group">
                                        <label for="purchase_date_from">Purchase Date From</label>
                                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                    <input type="text" name="purchase_date_from" class="form-control datetimepicker-input"
                                                           data-target="#datetimepicker1" placeholder="DD-MM-YYYY"
                                                           value="{{ old('purchase_date_from', request('purchase_date_from')) }}">
                                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                         </div>
                                           
                                </div>
                                <!-- Purchase Date To -->
                                 <div class="col-md-4">
                                     <div class="form-group">
                                        <label for="purchase_date_from">Purchase Date To</label>
                                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                                <input type="text" name="purchase_date_to" class="form-control datetimepicker-input"
                                                       data-target="#datetimepicker2" placeholder="DD-MM-YYYY"
                                                       value="{{ old('purchase_date_to', request('purchase_date_to')) }}">
                                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                 </div>        

                                

                                {{-- Vendor --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="vendor_id">Vendor</label>
                                        <select name="vendor_id" id="vendor_id" class="form-control selectPicker">
                                            <option value="">Select Vendor</option>
                                            @if (isset($getInfo['vendors']) && $getInfo['vendors']->isNotEmpty())
                                                @foreach ($getInfo['vendors'] as $vendorLst)
                                                    <option value="{{ $vendorLst->id }}"
                                                        {{ old('vendor_id', request('vendor_id')) == $vendorLst->id ? 'selected' : '' }}>
                                                        {{ $vendorLst->name}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                {{-- Buying Price --}}
                                {{-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="buying_price">Buying Price</label>
                                        <input type="number" step="0.1" name="buying_price"
                                            class="form-control" id="buying_price"
                                            placeholder="Enter Buying Price"
                                            value="{{ old('buying_price', request('buying_price')) }}">
                                    </div>
                                </div> --}}

                                

                                

                            </div>

                            {{-- Submit Button --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-warning float-right">Submit</button>
                                    <a href="{{ route('admin.products.products-vendors', $getInfo['product']->slug) }}" class="btn btn-dark float-right mr-1">Reset</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

                </div>
                <div class="card-body">
                    @if (!empty($getInfo['product']->productVendors))
                            @foreach ( $getInfo['product']->productVendors as $key=>$vendors)
                               
                                    <div class="vendor-group" id="vendor_{{$key+1}}">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4 class="vendorNumber">
                                                    <a target="_blank" href="{{ route('admin.masters.vendor-edit', [$vendors['vendor']->slug]) }}">
                                                        {{ $vendors['vendor']->name }}
                                                    </a>
                                                </h4>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Purchase Date *</label>
                                                    <div class="input-group date search_datetimepicker_{{$key+1}}" data-target-input="nearest">
                                                        <input type="text"
                                                            value="{{ date('d-m-y', strtotime($vendors->purchase_date)) }}"
                                                            placeholder="Date format :: dd-mm-yyyy"
                                                            required
                                                            id="purchase_date_{{$key+1}}"
                                                            name="vendors[{{$key+1}}][purchase_date]"
                                                            class="form-control datetimepicker-input pd_{{$vendors->id}}"
                                                            data-target=".search_datetimepicker_{{$key+1}}"
                                                            data-original="{{ date('d-m-Y', strtotime($vendors->purchase_date)) }}">
                                                        <div class="input-group-append" data-target=".search_datetimepicker_{{$key+1}}" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Quantity *</label>
                                                    <input type="number"
                                                        value="{{ $vendors->stock_quantity }}"
                                                        step="1"
                                                        required
                                                        name="vendors[{{$key+1}}][stock_quantity]"
                                                        class="form-control buyingpcCalc qt_{{$vendors->id}}"
                                                        placeholder="Enter Quantity *"
                                                        id="stock_quantity_{{$key+1}}"
                                                        unique-id="{{$key+1}}"
                                                        data-original="{{ $vendors->stock_quantity }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="parent_id ">Selected Unit *</label>
                                                    <select
                                                        class="form-control ut_{{$vendors->id}}"
                                                        data-original="{{ $vendors->unit->id }}"
                                                        data-original-name="{{ $vendors->unit->name }}"
                                                        name="vendors[{{$key+1}}][unit_id]"
                                                        id="vendors[{{$key+1}}][unit_id]">
                                                        <option value="">Select Unit</option>
                                                        @if (!empty($getInfo['unit']))
                                                        
                                                        @foreach ($getInfo['unit'] as $unit)
                                                            @if ($getInfo['product']->unit_category_id == $unit->unit_category_id)                                                           
                                                            <option value="{{$unit['id']}}" 
                                                                @if ( $unit['id'] == $vendors->unit->id)
                                                                        selected="selected"                                                                 
                                                                @endif

                                                            >{{$unit['name']}}</option>
                                                             @endif
                                                        @endforeach
                                                        @endif
                                                            
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>GST</label>
                                                    <input type="number"
                                                        value="{{ $vendors->gst }}"
                                                        step="0.01"
                                                        min="0"
                                                        unique-id="{{$key+1}}"
                                                        id="gst_{{$key+1}}"
                                                        name="vendors[{{$key+1}}][gst]"
                                                        class="form-control buyingpcCalc gst_{{$vendors->id}}"
                                                        placeholder="Enter GST"
                                                        data-original="{{ $vendors->gst }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Actual Price / Unit *</label>
                                                    <input type="number"
                                                        value="{{ $vendors->actual_price }}"
                                                        step="0.01"
                                                        min="0"
                                                        required
                                                        id="actual_price_{{$key+1}}"
                                                        unique-id="{{$key+1}}"
                                                        name="vendors[{{$key+1}}][actual_price]"
                                                        class="form-control buyingpcCalc ap_{{$vendors->id}}"
                                                        placeholder="Enter Actual Price *"
                                                        data-original="{{ $vendors->actual_price }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Gross Buying Price *</label>
                                                    <input type="number"
                                                        readonly="readonly"
                                                        value="{{ $vendors->buying_price }}"
                                                        id="buying_price_{{$key+1}}"
                                                        step="0.01"
                                                        min="0"
                                                        required
                                                        unique-id="{{$key+1}}"
                                                        name="vendors[{{$key+1}}][buying_price]"
                                                        class="form-control bp_{{$vendors->id}}"
                                                        placeholder="Enter Buying Price *"
                                                        data-original="{{ $vendors->buying_price }}">
                                                </div>
                                            </div>

                                            
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button"
                                                    id="vendor_prod_update_{{$key+1}}"
                                                    class="btn btn-info float-right vendor_prod_update" vendor-list-id="{{$vendors->id}}">Update</button>
                                            </div>
                                        </div>
</div>
                            @endforeach
                    @endif
                    
                </div>
            
                
              </div>
              <!-- /.card -->
  
        
            </div>

            
    
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>

</x-admininnerlayout>