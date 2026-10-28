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
                                    <form id="customerProfile" class="formhandlingClass" 
                                        action="{{route('users.profile-update')}}"
                                        method="post">
                                          @csrf
                                          @method('put')
                                        <div class="mcb-wrap-inner">
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>First Name<span>*</span></label>
                                                    <input type="text" 
                                                        value="{{old('first_name',$customerDetails->first_name)}}"
                                                        name="first_name" 
                                                        required="required">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Last Name<span>*</span></label>
                                                    <input type="text" name="last_name" 
                                                        value="{{old('last_name',$customerDetails->last_name)}}"
                                                        required="required">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Email Address</label>
                                                    <input type="email" 
                                                        readonly="readonly"
                                                        value="{{old('email',$customerDetails->email)}}"
                                                        name="email">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Phone Number<span>*</span></label>
                                                    <input type="text" 
                                                        value="{{old('phone',$customerDetails->phone)}}"
                                                        name="phone" required="required">
                                                </div>
                                            </div>                                            
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Alternative Phone<span></span></label>
                                                    <input type="text" 
                                                        value="{{old('alternative_phone',$customerDetails->alternative_phone)}}"
                                                        name="alternative_phone">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Address Line 1<span>*</span></label>
                                                    <input type="text" 
                                                        value="{{old('address_line_one',$customerDetails->address_line_one)}}"
                                                        name="address_line_one" required="required">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Address Line 2</label>
                                                    <input type="text" 
                                                        value="{{old('address_line_two',$customerDetails->address_line_two)}}"
                                                        name="address_line_two">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>State<span>*</span></label>
                                                    <input type="text" 
                                                        value="{{old('state',$customerDetails->state)}}"
                                                        name="state" required="required">
                                                </div>
                                            </div>
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>City<span>*</span></label>
                                                    <input type="text" name="city" 
                                                        value="{{old('city',$customerDetails->city)}}"
                                                        required="required">
                                                </div>
                                            </div>
                                            
                                            <div class="column mcb-column one-second column_column">
                                                <div class="form-group">
                                                    <label>Postal Code<span>*</span></label>
                                                    <input type="text" name="pin" 
                                                        id="pin"
                                                        value="{{old('pin',$customerDetails->pin)}}"
                                                        required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mcb-wrap-inner">
                                            <div class="column mcb-column one column_column">
                                                <div class="form-group">
                                                    <input type="submit" id="signup" value="Update" width="100%"/>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </form>
												<!--/ End Form -->
											</div>
                                            </div>
                                        </div>

</x-frontend.customerlayout>