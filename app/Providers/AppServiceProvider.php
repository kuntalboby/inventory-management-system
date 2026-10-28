<?php

namespace App\Providers;

use App\Models\ManagerDetail;
use App\Models\SiteInfo;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        View::composer('*', function ($view) {
            
            $userProfileImage = '';
            $siteInfo = array();
            $menuList =array();
            
            $siteInfo = SiteInfo::select('site_name', 'site_logo', 'site_thum_logo')->find(1);
            
            if(Auth::check() && Auth::user()->role == 'manager'){
                    $userProfileImage =  ManagerDetail::where("user_id", Auth::user()->id)->select(['profile_image'])->first();
                    $menuList = [
                    'Dashboard'=> ['manager.dashboard'],
                    'Profile'=> ['manager.profile'],
                    'Orders'=> ['manage.orders','manage.new-order','manage.order-confirmation','manage.invoice'],
                    
                    ];
                }elseif(Auth::check() && Auth::user()->role == 'admin'){
                    $userProfileImage =  UserDetail::where("user_id", Auth::user()->id)->select(['profile_image'])->first();
                    $menuList=[];
                }
            $view->with(['profileImage'=>$userProfileImage,'siteInfo'=>$siteInfo,'menuList'=>$menuList]);
        });
    }
}
