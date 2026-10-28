@section('currentPage', ' Edit Manager')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Edit Manager')
    
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
              <div class="card card-warning">
                <div class="card-header">
                  <h3 class="card-title">Edit Manager</h3>
                  <div class="card-tools">
                    
                    <a href="{{route('admin.managers.index')}}" class="btn btn-light">
                      Back
                    </a>
                    
                  </div>
                </div>
            
                <form action="{{route('admin.managers.managerUpdate',[$personalInfo->id])}}" enctype="multipart/form-data" method="post">
                 @csrf
                 @method('PUT')
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" value="{{ old('first_name', $personalInfo->first_name)}}" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name *">
                            </div>
                        </div>
                        
                       

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" value="{{ old('last_name', $personalInfo->last_name)}}" name="last_name" class="form-control" id="last_name" placeholder="Enter Last Name *">
                            </div>
                        </div>
                       
                      <div class="col-md-6">
                            <div class="form-group">
                                <label for="personal_phone_one">Phone No. 1*</label>
                                <input type="text" value="{{ old('personal_phone_one', $personalInfo->managerdetail->personal_phone_one)}}" name="personal_phone_one" class="form-control" id="personal_phone_one" placeholder="Enter Phone 1 *">
                            </div>
                        </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="personal_phone_two">Phone No. ( Alternative) </label>
                                <input type="text" value="{{ old('personal_phone_two', $personalInfo->managerdetail->personal_phone_two)}}" name="personal_phone_two" class="form-control" id="personal_phone_two" placeholder="Enter Phone Alternative">
                            </div>
                        </div>

                        
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="personal_email">Persoal Email </label>
                                <input type="text" value="{{ old('personal_email', $personalInfo->managerdetail->personal_email)}}" name="personal_email" class="form-control" id="personal_email" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation">Designation </label>
                                <input type="text" value="{{ old('designation', $personalInfo->managerdetail->designation)}}" readonly name="designation" class="form-control" id="designation" placeholder="Enter Designation">
                            </div>
                        </div>

                         <div class="col-md-12">
                            <div class="form-group">
                                <label for="complete_address">Address </label>
                                <textarea id="complete_address" name="complete_address" class="form-control ckeditor" placeholder="Enter Address if required">{{ old('complete_address', $personalInfo->managerdetail->complete_address)}}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                             <div class="card-header" style="background: #202d36">
                            <h3 class="card-title">Login Credential</h3></div>
                        </div>
                       

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">Login Email *</label>
                                <input type="email" value="{{ old('email', $personalInfo->email)}}" name="email" class="form-control" id="email" placeholder="Enter Personal Email *">
                              </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password"  name="password" class="form-control" id="password" placeholder="Enter Password *">
                              </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter Confirm Password *">
                              </div>
                        </div>

                     
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select class="form-control" name="status" id="status">
                                @php
                                    $status[1]='Active';
                                    $status[2]='Hold';
                                    $status[0]='Inactive';
                                @endphp
                                    @foreach ($status as $ks=>$stats)
                                        @if ($personalInfo->status == $ks )
                                            <option value="{{$ks}}" selected>{{$stats}}</option>
                                        @else
                                            <option value="{{$ks}}">{{$stats}}</option>
                                        @endif
                                    @endforeach
                                
                            </select>
                            </div>
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