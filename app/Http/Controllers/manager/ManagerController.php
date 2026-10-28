<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\admin\ParentController;
use App\Models\ManagerDetail;
use App\Models\TrackForgetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ManagerController extends ParentController
{
    
    
    public function login(Request $request){
        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        

        $isActiveOtpValidation = User::select(['id','email','first_name','last_name','password'])
                                ->where([
                                    'email'=>$request->email,
                                    'role'=>'manager',
                                    'status'=>'1'])->first();

       if(!empty($isActiveOtpValidation)){
               
        $managerDetails = [];
        if( Hash::check($request->password, $isActiveOtpValidation->password) ){
            $managerDetails = $isActiveOtpValidation->load('managerdetail');
        }
       
        if( isset($managerDetails->managerdetail->status_otp_verification) && $managerDetails->managerdetail->status_otp_verification == 2){
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
                    'username'=> $managerDetails->first_name.' '.$managerDetails->last_name,
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
                                $role = array('role'=>'manager','status'=>'1');
                                $credential = array_merge($credential , $role);
                                if(Auth::attempt($credential)){

                                
                                    $params['update_fields'] = [
                                        'user_id'=> Auth::user()->id,
                                        'role_type'=>'manager',
                                        'last_login_at'=> Carbon::now()->toDateTimeString(),
                                        'last_login_ip_address'=> $request->getClientIp()
                                    ];
                                    $this->trackLoginDetails($params);


                                    return redirect()->route('manager.dashboard');
                                }else{
                                    return redirect()->back()->with('message','Please enter correct email & password.');
                                }

                }
    }else{
       return redirect()->back()->with('message','Please enter correct email & password.');

    }
   }

    public function managerOtpVerification(Request $request){
        $request->validate([
            'login_otp_number' => 'required|max:4|min:4',
        ]);


        $role = array('role'=>'manager','login_otp_number'=> $request->login_otp_number,'remember_token'=>$request->rand_token);
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
                    'role_type'=>'manager',
                    'last_login_at'=> Carbon::now()->toDateTimeString(),
                    'last_login_ip_address'=> $request->getClientIp()
                ];
                $this->trackLoginDetails($params);


                return redirect()->route('manager.dashboard');
            }else{
                return redirect()->back()->with('message','Please enter correct OTP.');
            }
        }else{
             return redirect()->back()->with('message','Please enter correct OTP.');
        }
    }
    public function verifyuseremail(Request $request){
         $request->validate([
            'email' => 'required|email',
        ]);

        $users = User::where(['email'=>$request->email,'status'=> '1','role'=>'manager'])->first();
        
       

       if(!empty($users)){
        $tokenNumber = $this->randnoGenerator();
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
            return redirect()->route('managerlogin')->with('message','A link has been sent to your email.');
        }else{
            session()->flash('response','warning');
            return redirect()->back()->with('message','Something went wrong.');
        }
    }else{
        session()->flash('response','danger');
        return redirect()->back()->with('message','This email address is not available');
    }
    }

    public function logout(Request $request) : RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('managerlogin')->with('message','You have logged out successfully.');
    }
  
    
 
    public function profile(){  
        //echo 'here'; exit;
        $personalInfo = User::with('managerdetail')->findOrFail(Auth::user()->id); // default id;
        //$personalInfo = ManagerDetail::with('user')->findOrFail(1);
        return view("manager.profile",[
            "personalInfo"=> $personalInfo
        ]);
    
      }

      public function updateprofile(Request $request){
        $info = User::with("managerdetail:user_id,profile_image")->findOrFail(Auth::user()->id, ["id","email"]);
        Validator::make($request->all(), [
            'first_name' => 'required|max:25',
            'last_name' => 'required|max:25',
            'email' => [
                'required',
                  Rule::unique('users')->ignore(Auth::user()->id)
            ],
            'personal_email' => [
                  Rule::unique('manager_details')->ignore(Auth::user()->id,'user_id')
            ],
            'personal_phone_one' => [
                  Rule::unique('manager_details')->ignore(Auth::user()->id,'user_id')
            ],
            'complete_address' => [
                  Rule::unique('manager_details')->ignore(Auth::user()->id,'user_id')
            ],
            'designation' => [
                  'required'
            ],
        ])->validate();

        if ($request->file('profile_image') != null){

            $site_thum_logo_path = $request->file('profile_image');
            $profileImagename = $site_thum_logo_path->hashName();
            $request->profile_image->storeAs('profile-pics',$profileImagename,'public');
      
            $profile_pic = public_path("storage")."/profile-pics/". $info->managerdetail->profile_image;
           
          if(file_exists($profile_pic)){
                @unlink($profile_pic);
          }
        }else{
            $profileImagename = $info->managerdetail->profile_image;
        }


            $managerDetails = ManagerDetail::updateOrCreate( ['user_id'=>Auth::user()->id],
            [
                'personal_email'=> $request->personal_email,
                'personal_phone_one'=> $request->personal_phone_one,
                'personal_phone_two'=> $request->personal_phone_two,
                'complete_address'=> $request->complete_address,
                'designation'=> $request->designation,
                'profile_image'=> $profileImagename,
            ]);
            $userData = array(
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'email'=> $request->email,
              );
            $updated = $managerDetails->user()->where(['id'=>Auth::user()->id])->update($userData);
            if($updated){
                Auth::user()->update($userData);
                session()->flash('response','success');
                return redirect()->back()->with('message','Information has been updated successfully');
            }else{
                session()->flash('response','danger');
                return redirect()->back()->with('message','Information has not been updated.');
            }
    } 
    
    

    public function updatePassword(Request $request){

        Validator::make($request->all(), [
          'current_password' => 'required',
          'password' => 'required|confirmed|min:6',
          'password_confirmation'=>'required',
      ])->validate();


      $currentPasswordStatus = hash::check($request->current_password, Auth::user()->password);
      if($currentPasswordStatus){

          User::findOrFail(Auth::user()->id)->update([
              'password' => Hash::make($request->password),
          ]);

                session()->flash('response','success');
                return redirect()->back()->with('message','Password has been updated successfully.');
        }else{
                session()->flash('response','danger');
                return redirect()->back()->with('message','Please enter correct current password.');
        }

      }
}
