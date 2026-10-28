@section('currentPage', ' Edit Damaged Packet')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Damaged Packet')
    
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <div>
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
                  <h3 class="card-title">Edit Damaged Packet</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.products.damaged-packet')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.products.damagePacketUpdate',$getInfo['id'])}}" enctype="multipart/form-data" method="post" id="damagedPacketProducts">
                 @csrf
                 @method('PUT')
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-3">
                            <div class="form-group">
                                <label for="damaged_date">Date *</label>
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                    <input type="text"
                                                    value="{{ old('damaged_date', date('d-m-Y',strtotime($getInfo['damaged']->damaged_date)))}}"
                                                    name="damaged_date" class="form-control datetimepicker-input"
                                                           data-target="#datetimepicker1" placeholder="DD-MM-YYYY"
                                                        >
                                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="parent_id ">Select Product *</label>
                                <select class="form-control fetchProdDetails selectPicker"  name="product_id" id="product_id">
                                    <option value="">Packaged Products</option>
                                    @if (!empty($getInfo['products']))
                                       
                                    @foreach ($getInfo['products'] as $products)
                                    
                                        <option unit-cat="{{ $products->unit->unit_category_id}}" 
                                            
                                            @if ( $getInfo['damaged']->packet_prodid == $products['id'] )
                                             selected="selected"
                                            @endif
                                            value="{{$products->id}}" >{{ucwords(strtolower($products->name))}}</option>
                                     @endforeach
                                    @endif
                                        
                                </select>
                            </div>
                        </div>
                        
                      <div class="col-md-3">
                            <div class="form-group">
                                <label for="packet_size">Packet Size * </label>
                                <input type="text"
                                readonly 
                                name="packet_size"
                                 class="form-control" id="packet_size" 
                                 value="{{$getInfo['damaged']->product->packet_size .' '.$getInfo['damaged']->product->unit->name}}"
                                 placeholder="Enter Packet Size*">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="quantity">Quantity * </label>
                                <input type="number" value="{{$getInfo['damaged']->no_of_packet}}" name="quantity" class="form-control" id="quantity" placeholder="Number of packets *">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="comment">Remarks (if any ) </label>
                                <textarea name="comment" class="ckeditor form-control" id="comment">{{old('comment', $getInfo['damaged']->comment)}}</textarea>
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