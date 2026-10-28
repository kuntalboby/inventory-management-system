<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\ParentController;
use App\Models\TrackForgetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends ParentController
{
    
    public function login(Request $request){
        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $isActiveOtpValidation = User::select(['id','email','first_name','last_name','password'])
                                ->where([
                                    'email'=>$request->email,
                                    'role'=>'admin',
                                    'status'=>'1'])->first();
        $userDetailsData = [];  
        
       // return $isActiveOtpValidation;
        if( !empty($isActiveOtpValidation) && Hash::check($request->password, $isActiveOtpValidation->password) ){
            $userDetailsData = $isActiveOtpValidation->load('userdetail');
        }
       
        if( isset($userDetailsData->userdetail->status_otp_verification) && $userDetailsData->userdetail->status_otp_verification == 2){
            // mail service
            $otpNumber = rand(1111,9999);
            $linktoken = $this->randnoGenerator();

            $generateOTP = [
                'login_otp_number'=>$otpNumber,
                'remember_token'=>$linktoken,
            ];

            $generateToken = User::updateOrCreate(
                ['email' => $request->email],
                $generateOTP
            );

            if($generateToken){
           
                $maildata = [
                    'to'=> $request->email,
                    'username'=> $userDetailsData->first_name.' '.$userDetailsData->last_name,
                    'mail_title'=> 'OTP Verification',
                    'subject'=>'Login OTP',
                    'message'=> 'OTP : '.$otpNumber. '.Please use this OTP to login your system. Do not share it with others.',
                    'link'=>''
                ];
                $this->sendmail($maildata);
                session()->flash('response','success');
                return redirect()->route('admin.otppage',['token'=>$linktoken])->with('message','OTP has been sent to your email.');
            }else{
                session()->flash('response','warning');
                return redirect()->back()->with('message','Something went wrong.');
            }

            //
        }else{

            //simple login with out OTP verification
                    $role = array('role'=>'admin','status'=>'1');
                    $credential = array_merge($credential , $role);
                    if(Auth::attempt($credential)){

                       
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'role_type'=>'admin',
                            'last_login_at'=> Carbon::now()->toDateTimeString(),
                            'last_login_ip_address'=> $request->getClientIp()
                        ];
                        $this->trackLoginDetails($params);


                         return redirect()->route('admin.dashboard');
                    }else{
                        return redirect()->back()->with('message','Please enter correct email & password.');
                    }

    }
        
        //return $request->all();
    }

    public function adminOtpVerification(Request $request){
        $credential = $request->validate([
            'login_otp_number' => 'required|max:4|min:4',
        ]);


        $role = array('role'=>'admin','login_otp_number'=> $request->login_otp_number,'remember_token'=>$request->rand_token);
        $user = User::where( $role)->first();
       if(!empty($user)){ 
            Auth::login($user);
            
            if(Auth::check()){
                User::findOrFail(Auth::user()->id)->update([
                    'login_otp_number' => null,
                    'remember_token' => null,
                ]);
            
                $params['update_fields'] = [
                    'user_id'=> Auth::user()->id,
                    'role_type'=>'admin',
                    'last_login_at'=> Carbon::now()->toDateTimeString(),
                    'last_login_ip_address'=> $request->getClientIp()
                ];
                $this->trackLoginDetails($params);


                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->back()->with('message','Please enter correct OTP.');
            }
     }else{
        return redirect()->back()->with('message','Please enter correct OTP.');
     }
    }


    public function signup(Request $request){

        $validated = $request->validate([
            'first_name' => 'required|max:15',
            'last_name' => 'required|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::create($validated);
        if($user){
            return redirect()->route('adminlogin')->with('message','Information has been sent to admin.');
        }
        //return $request->all();
    }
    public function logout(Request $request) : RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('adminlogin')->with('message','You have logged out successfully.');
    }
  
    public function verifyuseremail(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $users = User::where(['email'=>$request->email,'status'=> '1','role'=>'admin'])->first();
        
        $tokenNumber = $this->randnoGenerator();

       if(!empty($users)){
        $resetPassLink = [
            'role'=>$users['role'],
            'applicante_identifier_digit'=>$users['id'],
            'randnumber'=> $tokenNumber
        ];
        $generateToken = TrackForgetPassword::create($resetPassLink);
        if($generateToken){
           
            $maildata = [
                'to'=>$users['email'],
                'username'=>$users['first_name'].' '.$users['last_name'],
                'mail_title'=> 'Password Recovery',
                'subject'=>'Password recovery email',
                'message'=>'Please click the link below to update your login password. Please do not share it with others.',
                'link'=> route('login.changepassword',['token'=>$tokenNumber])
            ];
            $this->sendmail($maildata);
            session()->flash('response','success');
            return redirect()->route('adminlogin')->with('message','A link has been sent to your email.');
        }else{
            session()->flash('response','warning');
            return redirect()->back()->with('message','Something went wrong.');
        }
    }else{
        session()->flash('response','danger');
        return redirect()->back()->with('message','This email address is not available');
    }
    }

    public function updateloginpass(Request $request){

        Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6',
            'password_confirmation'=>'required',
        ])->validate();
        
        
        $user = TrackForgetPassword::select(['id','applicante_identifier_digit','role'])->where(['has_changed'=>'0','randnumber'=>$request->rand_token])->first();

                if(!empty($user)){
                
                    $response =  User::findOrFail($user->applicante_identifier_digit)->update([
                        'password' => Hash::make($request->password),
                    ]);

                    if( $response){
                            TrackForgetPassword::findOrFail($user->id)->update([
                                'has_changed' => 1,
                            ]);
                        
                                session()->flash('response','success');
                                if($user->role === 'admin') {
                                    $redirectTo = 'adminlogin';
                                }elseif($user->role === 'manager') {
                                    $redirectTo = 'managerlogin';
                                }
                    return redirect()->route($redirectTo)->with('message','Password has been updated successfully.');
                }else{
                    session()->flash('response','danger');
                    return redirect()->back()->with('message','Password has not been updated.Something went wrong.');
                }
          }else{
                  session()->flash('response','danger');
                  return redirect()->back()->with('message','This link is invalid');
          }

    }
}
