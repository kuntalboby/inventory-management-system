<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ManagerDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ManagerController extends Controller
{
    //
    public function index(){
        $managers = User::with('managerdetail')->where(['role' => 'manager'])->orderBy('first_name')->get(); // Get all Managers
      // return $managers;
        return view("admin.managers.index",[
        "managers"=> $managers
    ]);
    }
    public function managerAdd(Request $request){

        //return $request;

        Validator::make($request->all(), [
            'first_name' => 'required|max:25',
            'last_name' => 'required|max:25',
            'email' => [
                'required',
                  Rule::unique('users')
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'personal_email' => [
                  'nullable', Rule::unique('manager_details')
            ],
            'personal_phone_one' => [
                  'nullable', Rule::unique('manager_details')
            ],
             'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ])->validate();
        
        $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'status' => $request->status,
        'password' => Hash::make($request->password),
        'role' => 'manager',
    ]);

   if ($request->file('profile_image') != null){

            $site_thum_logo_path = $request->file('profile_image');
            $profileImagename = $site_thum_logo_path->hashName();
            $request->profile_image->storeAs('profile-pics',$profileImagename,'public');
           
        }else{
            $profileImagename = '';
        }

        $updateRes = $user->managerDetail()->create([
        'complete_address' => $request->complete_address,
        'personal_email' => $request->personal_email,
        'personal_phone_one' => $request->personal_phone_one,
        'personal_phone_two' => $request->personal_phone_two,
        'profile_image' => $profileImagename,
        'designation'=>$request->designation
    ]);

    if ($updateRes) {
        session()->flash('response', 'success');
        return redirect()->route('admin.managers.index')->with('message', 'Information has been updated successfully.');
    } else {
        session()->flash('response', 'danger');
        return redirect()->back()->with('message', 'Information has not been updated.');
    }

    }

    public function managerEdit(Request $request, int $id){
            $managerD = User::with('managerdetail')->where('id', $id)->firstOrFail();
            //return $managerD;
             return view("admin.managers.manager-edit",[
                "personalInfo"=> $managerD
             ]);
    }

     public function managerUpdate(Request $request, int $id){
        $info = User::with("managerdetail:user_id,profile_image")->findOrFail($id, ["id","email"]);
        Validator::make($request->all(), [
            'first_name' => 'required|max:25',
            'last_name' => 'required|max:25',
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'email' => [
                'required',
                  Rule::unique('users')->ignore($id)
            ],
            'personal_email' => [
                  'nullable' , Rule::unique('manager_details')->ignore($id,'user_id')
            ],
            'personal_phone_one' => [
                  'nullable' , Rule::unique('manager_details')->ignore($id,'user_id')
            ],
            'designation' => [
                  'nullable'
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

    $user = User::findOrFail($id);
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'status' => $request->status,
            'role' => 'manager',
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }
     $user->updateOrFail($data);

     $updateRes = $user->managerDetail()->updateOrCreate(
    ['user_id' => $user->id], // match condition
    [
        'complete_address' => $request->complete_address,
        'personal_email' => $request->personal_email,
        'personal_phone_one' => $request->personal_phone_one,
        'personal_phone_two' => $request->personal_phone_two,
        'profile_image' => $profileImagename,
        'designation' => $request->designation
    ]
);

            if($updateRes){
                session()->flash('response','success');
                return redirect()->back()->with('message','Information has been updated successfully');
            }else{
                session()->flash('response','danger');
                return redirect()->back()->with('message','Information has not been updated.');
            }
    } 
}
