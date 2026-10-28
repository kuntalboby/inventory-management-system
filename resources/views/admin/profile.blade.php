@section('currentPage', ' Profile Update')
@php
    $bgColor="dark"
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Profile')
    
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
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
              <!-- general form elements -->
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Personal Information</h3>
                </div>
            
                <form action="{{route('admin.updateprofile')}}" enctype="multipart/form-data" method="post">
                 @csrf
                 @method('put')
                  <div class="card-body">
                    <div class="avatar-upload">
                      <div class="avatar-edit">
                          <input type='file' id="profile_image" name="profile_image" accept=".png, .jpg, .jpeg" />
                          <label for="profile_image">
                            <i class="fas fa-pen uploadDp"></i>
                          </label>
                      </div>
                      <div class="avatar-preview">
                        @if ($personalInfo->userdetail->profile_image!='' && file_exists(public_path("storage/profile-pics/" . $personalInfo->userdetail->profile_image)))
                            <div id="imagePreview" style="background-image: url({{ asset('/storage/profile-pics/'.$personalInfo->userdetail->profile_image)}});">
                              @else
                            <div id="imagePreview" style="background-image: url({{ asset('/storage/website-image/no-image.png')}});">
                         @endif
                          
                          </div>
                      </div>
                  </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" value="{{ old('first_name', $personalInfo->first_name)}}" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name *">
                            </div>
        
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="last_name">Last name *</label>
                                <input type="text" value="{{ old('last_name', $personalInfo->last_name)}}"  name="last_name" class="form-control" id="last_name" placeholder="Enter Last Name">
                              </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Login Email *</label>
                                <input type="email" value="{{ old('email', $personalInfo->email)}}"  name="email" class="form-control" id="email" placeholder="Enter Personal Email">
                              </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone_number">Personal Phone Number *</label>
                                <input type="text" value="{{ old('phone_number', $personalInfo->userdetail->phone_number)}}" name="phone_number" class="form-control" id="phone_number" placeholder="Website Persoanl Phone No. *">
                            </div>
        
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Personal Email Address *</label>
                                <input type="email" value="{{ old('personal_email', $personalInfo->userdetail->personal_email)}}"  name="personal_email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                              </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="designation">Designastion *</label>
                                <input type="text" value="{{ old('designation', $personalInfo->userdetail->designation)}}"  name="designation" class="form-control" id="designation" placeholder="Enter Your Designation">
                              </div>
                        </div>
                    </div>
                        <div class="row">
                      
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status_otp_verification">Otp verification during login time *</label>
                                    <select class="form-control" name="status_otp_verification" id="status_otp_verification" >
                                        <option value="2" 
                                        @if ($personalInfo->userdetail->status_otp_verification == '2') 
                                          selected="selected"
                                        @endif>Yes</option>
                                        <option value="1"  @if ($personalInfo->userdetail->status_otp_verification == '1') 
                                          selected="selected"
                                        @endif>No</option>
                                    </select>
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