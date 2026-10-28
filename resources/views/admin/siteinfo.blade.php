@php
    $bgColor="dark"
@endphp
<x-admininnerlayout :$bgColor>
    @section('currentPage', ' Site Information')
    @section('title','Website Information')
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
                  <h3 class="card-title">Information for frontend</h3>
                </div>
            
                <form action="{{route('admin.updateinfo')}}" enctype="multipart/form-data" method="post">
                 @csrf
                 @method('put')
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="site_name">Site Name *</label>
                                <input type="text" value="{{ old('site_name', $siteinfo->site_name)}}" name="site_name" class="form-control" id="site_name" placeholder="Website Name *">
                            </div>
        
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pdf_recvr_email_one">Email to recieve PDF</label>
                                <input type="email" value="{{ old('pdf_recvr_email_one', $siteinfo->pdf_recvr_email_one)}}"  name="pdf_recvr_email_one" class="form-control" id="pdf_recvr_email_one" placeholder="Enter email*">
                              </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pdf_recvr_email_two">Email to recieve PDF</label>
                                <input type="email" value="{{ old('pdf_recvr_email_two', $siteinfo->pdf_recvr_email_two)}}"  name="pdf_recvr_email_two" class="form-control" id="pdf_recvr_email_two" placeholder="Enter email*">
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address</label>
                                <input type="email" value="{{ old('site_email', $siteinfo->site_email)}}"  name="site_email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site_contact">Contact *</label>
                                <input type="text" value="{{ old('site_contact', $siteinfo->site_contact)}}"  name="site_contact" class="form-control" id="site_contact" placeholder="Contact *">
                            </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="site_copyright">Copyright</label>
                            <input type="text" value="{{ old('site_copyright', $siteinfo->site_copyright)}}"  name="site_copyright"  class="form-control" id="site_copyright" placeholder="Copyright *">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site_logo">Logo</label>
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" name="site_logo" class="custom-file-input" id="site_logo" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                  </div>
                                  
                                </div>
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site_thum_logo">Thumb Logo</label>
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" name="site_thum_logo" class="custom-file-input" id="site_thum_logo" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                  </div>
                                 
                                </div>
                              </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                          @if ($siteinfo->site_logo!='')
                              <img src="{{ asset('/storage/website-image/'.$siteinfo->site_logo)}}" width="50%" />
                              @else
                              <img src="{{ asset('/storage/website-image/no-image.png')}}"  width="60px"/>
                          @endif
                        </div>
                        <div class="col-md-6">
                          @if ($siteinfo->site_thum_logo!='')
                          <img src="{{ asset('/storage/website-image/'.$siteinfo->site_thum_logo)}}" width="50%" />
                          @else
                          <img src="{{ asset('/storage/website-image/no-image.png')}}"  width="60px" />
                        @endif
                        </div>
                    </div>
                    {{-- <div class="row">
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="site_phone">Phone</label>
                                <input type="text" value="{{ old('site_phone', $siteinfo->site_phone)}}"  name="site_phone" class="form-control" id="site_phone" placeholder="Phone">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="site_fax">Fax</label>
                                <input type="text" value="{{ old('site_fax', $siteinfo->site_fax)}}"  name="site_fax" class="form-control" id="site_fax" placeholder="Fax">
                            </div> 
                        </div>
                    </div> --}}
                   
                    
                    {{-- <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="site_state">State</label>
                                <input type="text" value="{{ old('site_state', $siteinfo->site_state)}}"  name="site_state"  class="form-control" id="site_state" placeholder="State">
                              </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="site_city">City</label>
                                <input name="site_city"  value="{{ old('site_city', $siteinfo->site_city)}}"  type="text"  class="form-control" id="site_city" placeholder="City">
                              </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="site_suburb">Suburb</label>
                                <input type="text" value="{{ old('site_suburb', $siteinfo->site_suburb)}}"  name="site_suburb"  class="form-control" id="site_suburb" placeholder="Suburb">
                              </div>
                        </div>
                    </div> --}}

                    {{-- <div class="form-group">
                        <label for="site_addressline_1">Address Line 1 *</label>
                        <input type="text" value="{{ old('site_addressline_1', $siteinfo->site_addressline_1)}}"  name="site_addressline_1"  class="form-control" id="site_addressline_1" placeholder="Address Line 1 *">
                      </div>
                      <div class="form-group">
                        <label for="site_addressline_2">Address Line 2</label>
                        <input type="text" value="{{ old('site_addressline_2', $siteinfo->site_addressline_2)}}"  name="site_addressline_2"  class="form-control" id="site_addressline_2" placeholder="Address Line 2">
                      </div>


                    <div class="form-group">
                      <label for="site_copyright">Copyright</label>
                      <input type="text" value="{{ old('site_copyright', $siteinfo->site_copyright)}}"  name="site_copyright"  class="form-control" id="site_copyright" placeholder="Copyright *">
                    </div>
                    --}}
                      
                {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site_logo">Logo</label>
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" name="site_logo" class="custom-file-input" id="site_logo" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                  </div>
                                  
                                </div>
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site_thum_logo">Thumb Logo</label>
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" name="site_thum_logo" class="custom-file-input" id="site_thum_logo" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                  </div>
                                 
                                </div>
                              </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                          @if ($siteinfo->site_logo!='')
                              <img src="{{ asset('/storage/website-image/'.$siteinfo->site_logo)}}" width="50%" />
                              @else
                              <img src="{{ asset('/storage/website-image/no-image.png')}}"  width="60px"/>
                          @endif
                        </div>
                        <div class="col-md-6">
                          @if ($siteinfo->site_thum_logo!='')
                          <img src="{{ asset('/storage/website-image/'.$siteinfo->site_thum_logo)}}" width="50%" />
                          @else
                          <img src="{{ asset('/storage/website-image/no-image.png')}}"  width="60px" />
                        @endif
                        </div>
                    </div> --}}
                      
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