<x-frontend.customerlayout
                       :basicData="$sitedata"
                       :menu="$menu"
                       >
    @section('bg_image',$bg_image)
    @section('title',$title)

        <div class="column mcb-column three-fourth column_column">
                                            <div class="column_attr clearfix">
											    <div class="checkout-form">
												<!-- Form -->
                                                <div>
                                            @if (session('message'))
                                            
                                                <p class="text-center text text-{{session('response')}}">{{session('message')}}</p>
                                            
                                            @endif
                                            @if ($errors->any())
                                                
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li class="text-danger">{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                
                                            @endif
                                        </div>
                                    <form id="updatePassword" class="formhandlingClass" 
                                        action="{{route('users.password-update')}}"
                                        method="post">
                                          @csrf
                                          @method('put')
                                        <div class="mcb-wrap-inner">
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Old Password<span>*</span></label>
                                                    <input type="password" 
                                                        value=""
                                                        name="current_password" 
                                                        id="current_password"
                                                        placeholder="Enter current password"
                                                        required="required">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>New Password<span>*</span></label>
                                                    <input type="password" 
                                                        value=""
                                                        name="password" 
                                                        id="password"
                                                        placeholder="Password must be at least 6 characters long"
                                                        required="required">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Confirm Password<span>*</span></label>
                                                    <input type="text" name="password_confirmation" 
                                                        value=""
                                                        required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mcb-wrap-inner">
                                            <div class="column mcb-column one column_column">
                                                <div class="form-group">
                                                    <input type="submit" id="chnagePassword" value="Update" width="100%"/>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </form>
												<!--/ End Form -->
											</div>
                                            </div>
                                        </div>

</x-frontend.customerlayout>