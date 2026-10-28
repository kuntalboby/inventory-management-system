@section('currentPage', ' Profile Update')
@php
    $bgColor="light"
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Profile')
    
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
                  <h3 class="card-title">Personal Information</h3>
                </div>
            
                <form action="{{route('manager.updateprofile')}}" enctype="multipart/form-data" method="post">
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
                        @if ($personalInfo->managerdetail->profile_image!='')
                            <div id="imagePreview" style="background-image: url({{ asset('/storage/profile-pics/'.$personalInfo->managerdetail->profile_image)}});">
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
                                <label for="personal_phone_one">Personal Phone Number *</label>
                                <input type="text" value="{{ old('personal_phone_one', $personalInfo->managerdetail->personal_phone_one)}}" name="personal_phone_one" class="form-control" id="personal_phone_one" placeholder="Persoanl Phone No. *">
                            </div>
        
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                              <label for="personal_phone_two">Alternative Phone Number </label>
                              <input type="text" value="{{ old('personal_phone_two', $personalInfo->managerdetail->personal_phone_two)}}" name="personal_phone_two" class="form-control" id="personal_phone_two" placeholder="Atlernative Phone No. *">
                          </div>
      
                      </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="designation">Designastion </label>
                                <input type="text" value="{{ old('designation', $personalInfo->managerdetail->designation)}}"  name="designation" class="form-control" id="designation" placeholder="Enter Your Designation">
                              </div>
                        </div>
                    </div>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal_email">Persoanl Email </label>
                                <input type="email" value="{{ old('personal_email', $personalInfo->managerdetail->personal_email)}}"  name="personal_email" class="form-control" id="personal_email" placeholder="Enter Perosnal Email">
                              </div>
                        </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                <label for="complete_address">Complete Address *</label>
                                <input type="text" value="{{ old('complete_address', $personalInfo->managerdetail->complete_address)}}"  name="complete_address" class="form-control" id="complete_address" placeholder="Enter Your Address">
                              </div>
                        </div>
                          
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status_otp_verification">Otp verification during login time *</label>
                                    <select class="form-control" name="status_otp_verification" id="status_otp_verification" >
                                        <option value="2" 
                                        @if ($personalInfo->managerdetail->status_otp_verification == '2')
                                          selected="selected"
                                        @endif>Yes</option>
                                        <option value="1"  @if ($personalInfo->managerdetail->status_otp_verification == '1')
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
            <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Update Login Password</h3>
                </div>
            
                <form action="{{route('manager.update-password')}}" enctype="multipart/form-data" method="post" id="managerPasswordSec">
                 @csrf
                 @method('put')
                  <div class="card-body">
                    
                   <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="current_password">Current Password *</label>
                                <input type="password" name="current_password" class="form-control" id="current_password" placeholder="Enter Current Password *">
                            </div>
        
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">New Password *</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter New Password *">
                              </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password_confirmation">Confirmed Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter Confirmed Password *">
                            </div>
                        </div>
                    </div>

                      
                     
                  <!-- /.card-body -->
  
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
        
            </div>
    
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>