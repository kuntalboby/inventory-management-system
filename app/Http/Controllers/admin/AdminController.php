<?php

namespace App\Http\Controllers\admin;


use App\Models\Category;
use App\Models\Order;
use App\Models\SiteInfo;
use App\Models\TrackActivity;
use App\Models\TrackLogin;
use App\Models\UserDetail;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\admin\ParentController;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
class AdminController extends ParentController
{
    //
    //public $siteInformation;
    
    public function __construct() {
      //$this->siteInformation = $this->getSiteInformation();
    }
    public function dashboard(){
      $info['category'] = Category::where(['is_deleted'=>'0','parent_id'=>null])->select('name')->count();
      $info['product'] = Product::whereHas('goDownProducts')->select(['name','id'])->count();
      $info['vendor'] = Vendor::where('is_deleted','0')->select('name')->count();
      $info['order'] = Order::whereHas('orderDetails')->count();
      $info['activity'] = TrackActivity::where('status','0')->select(['note','created_at'])->orderByDesc('created_at')->limit(10)->get();
     // return $info; 
      return view("admin.dashboard",['info'=>$info]);
    }
     public function siteinfo() {
        $info = SiteInfo::findOrFail(1); // default id;
        return view("admin.siteinfo",[
            "siteinfo"=> $info
        ]);
}
  public function updateinfo(Request $request){
    $info = SiteInfo::findOrFail(1);
                $rules = [
                    'site_name' => 'required|max:25',
                    //'site_contact' => 'required',
                //    'site_email' => 'email',
                    'pdf_recvr_email_one' => 'required|email',
                    'pdf_recvr_email_two' => 'required|email',
                  //  'site_logo' =>'required|mimes:png,jpg,png,jpeg|max:3000',
                    'site_thum_logo' =>'mimes:png,jpg,png,jpeg|max:3000',
                 //   'site_addressline_1' => 'required|min:6',
                ];
            if($request->site_logo !=''){
                $rules['site_logo'] = 'mimes:png,jpg,png,jpeg|max:3000';
            }else{
                if($info->site_logo == ''){
                    $rules['site_logo'] = 'required|mimes:png,jpg,png,jpeg|max:3000';
                }else{
                    $rules['site_logo']='';
                }
               
            }
        
            $validated = $request->validate($rules);
           // dd($validated); 
            if ($request->file('site_logo') != null){

                $site_logo_path = $request->file('site_logo');
                $site_logo_file_name = $site_logo_path->hashName();
                $request->site_logo->storeAs('website-image',$site_logo_file_name,'public');
                //delete previous image
              $imagePath = public_path("storage")."/website-image/".$info->site_logo;
            //  echo $imagePath; exit;
              if(file_exists($imagePath)){
                    @unlink($imagePath);
              }
            }else{
                $site_logo_file_name = $info->site_logo;
            }

            if ($request->file('site_thum_logo') != null){

                $site_thum_logo_path = $request->file('site_thum_logo');
                $site_thum_logo_file_name = $site_thum_logo_path->hashName();
                $request->site_thum_logo->storeAs('website-image',$site_thum_logo_file_name,'public');

                $imagePath_for_thumb = public_path("storage")."/website-image/". $info->site_thum_logo;
               
              if(file_exists($imagePath_for_thumb)){
                    @unlink($imagePath_for_thumb);
              }

            }else{
                $site_thum_logo_file_name = $info->site_thum_logo;
            }

                $savedInfo = array_merge($request->all(), ['site_logo' =>  $site_logo_file_name, 'site_thum_logo' =>  $site_thum_logo_file_name]);
                $info = SiteInfo::updateOrCreate(
                ['id' => '1'],
                $savedInfo
            );
            if($info){
                
                session()->flash('response','success');
                return redirect()->back()->with('message','Information has been saved successfully.');
            }else{
                session()->flash('response','danger');
                return redirect()->back()->with('message','Information has not been saved.');
            }
  }  
    

  public function profile(){  
    //echo 'here'; exit;
    $personalInfo = User::with('userdetail')->findOrFail(Auth::user()->id); // default id;
    //return $personalInfo;
    return view("admin.profile",[
        "personalInfo"=> $personalInfo
    ]);

  }
 public function updateprofile(Request $request){
    //dd($request->all());

    $info = User::with("userdetail:user_id,personal_email,profile_image")->findOrFail(Auth::user()->id, ["id","email"]);
//return $info;
   // dd($info);    
      Validator::make($request->all(), [
          'first_name' => 'required|max:25',
          'last_name' => 'required|max:25',
          'phone_number'=>'required|max:20',
          'email' => [
              'required',
                Rule::unique('users')->ignore(Auth::user()->id)
          ],
          'personal_email' => [
                Rule::unique('user_details')->ignore(Auth::user()->id,'user_id')
          ],
      ])->validate();


      $userData = array(
        'first_name'=> $request->first_name,
        'last_name'=> $request->last_name,
        'email'=> $request->email,
      );

     User::updateOrCreate(
        ['id' => Auth::user()->id],
        $userData
    ); 

    
    $userDetailsData = array(
      'personal_email'=> $request->personal_email,
      'phone_number'=> $request->phone_number,
      'designation'=> $request->designation,
      'status_otp_verification'=> $request->status_otp_verification,
    ); 

    if ($request->file('profile_image') != null){

      $site_thum_logo_path = $request->file('profile_image');
      $profileImagename = $site_thum_logo_path->hashName();
      $request->profile_image->storeAs('profile-pics',$profileImagename,'public');

      $profile_pic = public_path("storage")."/profile-pics/". $info->userdetail->profile_image;
     
    if(file_exists($profile_pic)){
          @unlink($profile_pic);
    }

    }else{
        $profileImagename = $info->userdetail->profile_image;
    }

    //return $profileImagename;
    $savedInfo = array_merge($userDetailsData, ['profile_image' =>  $profileImagename]);
   // return Auth::user()->id;
    $savedInfoResponse = UserDetail::updateOrCreate(
      ['user_id' => Auth::user()->id],
      $savedInfo
  );
      if($savedInfoResponse){
        Auth::user()->update($userData);
        
        $note = Auth::user()->first_name.' has updated his profile';
                        $params['update_fields'] = [
                            'user_id'=> Auth::user()->id,
                            'note'=> $note,
                        ];
                        $this->trackRecords($params);
                        
                session()->flash('response','success');
                return redirect()->back()->with('message','Information has been saved successfully.');
      }else{
                session()->flash('response','danger');
                return redirect()->back()->with('message','Information has not been saved.');
      }


  }


  public function updatepassword(Request $request){

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
     
      // public function ipAdressLocation($ip){
      //   $response = file_get_contents("http://ip-api.com/json/$ip");
      //   return json_decode($response, true);

      // }
      public function trackLogins( Request $request){
        $records = TrackLogin::with('user:id,first_name,last_name,role')
                              ->orderByDesc('id')
                              ->limit(10)
                              ->get();
        // $data=[];
        // foreach ($records as $key => $value) {
        //     $data[$key]['location']  = $this->ipAdressLocation($value->last_login_ip_address);
        // }

        //                     return  $data;
           return view("admin.track-logins",compact('records'));
      }
   }
