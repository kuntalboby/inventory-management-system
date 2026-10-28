<?php
use App\Http\Controllers\manager\ManagerController;
use Illuminate\Support\Facades\Route;

  Route::get("/manager/", function () {
    return view("manager.login");
})->name("managerlogin");

Route::get("manager/signup",function(){
   // return view("admin.signup");
})->name("managersignup");


Route::get("manager/forgotPassword",function(){
   return view("manager.forgotpassword");
})->name("manager.forgotPassword");


Route::get("login/changepassword/{token}",function(string  $token){
   return view("manager.changepasswordform")->with('token',$token);
})->name("login.changepassword");


Route::get("manager/otpVerification/{token}",function(string  $token){
   return view("manager.verify-otp")->with('token',$token);
})->name("manager.otppage");

Route::post("manager/verifyuseremail",[ManagerController::class,"verifyuseremail"])->name('manager.verifyuseremail');
Route::post("managerlogin",[ManagerController::class,"login"]);
Route::post("manager/managerOtpVerification",[ManagerController::class,"managerOtpVerification"])->name('manager.verifyotp');

Route::middleware(['validAdmin:manager','precvenBack'])->group( function(){
   
            Route::get("manager/dashboard",function(){
               return view("manager.dashboard");
         })->name("manager.dashboard");
        

         Route::get("manager/logout",[ManagerController::class,'logout'])->name("manager.logout");

         Route::get("manager/profile",[ManagerController::class,'profile'])->name('manager.profile');
         Route::put("manager/updateprofile",[ManagerController::class,'updateprofile'])->name('manager.updateprofile');
         Route::put("manager/update-password",[ManagerController::class,'updatePassword'])->name('manager.update-password');

//  Route::any('{any}', function () {
//             return redirect('/');
//         })->where('any', '.*');

});



?>