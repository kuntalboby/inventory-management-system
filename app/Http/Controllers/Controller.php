<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Order;
use App\Models\SiteInfo;
use App\Models\TrackActivity;
use App\Models\TrackLogin;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

abstract class Controller
{
    //
   public function randnoGenerator($length=15){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function trackActivity($params ){
        $create = TrackActivity::create(
            $params['update_fields']
        );

    }

     public function unitConversion($quantity, $unitId)
        {
            $unit = Unit::where('id', $unitId)
                ->whereNotNull('parent_id')
                ->select(['id', 'parent_id', 'conversion_factor','name'])
                ->first();

            if ($unit) {
                return [
                    'quantity' => $unit->conversion_factor * $quantity,
                    'unit_id' => $unit->parent_id,
                   // 'name' => $unit->name,
                ];
            }
           
            return [
                'quantity' => $quantity,
                'unit_id' => $unitId,
                //'name' => '',
            ];
        }

    public function handleSession($params){
        
       if($params['mode'] == 'W'){
             Session::put($params['key'], $params['value']);
             return true;
        }elseif($params['mode'] == 'D'){
             Session::forget($params['key']);
             return true;
        }else{
             return Session::get($params['key']);
             
        }
    }
     public function sitedata(){
        $siteData = SiteInfo::where('id','1')
            ->select(['site_name','site_logo','site_thum_logo',
            'site_email','site_contact','site_copyright',
            'pdf_recvr_email_one',
            'pdf_recvr_email_two'
            ])
            ->first();
            return $siteData;
    }
    
    
    public function menuList($pages, $currentPage)
{
    $menus = [];
    $staticPageSlug = ['product-details','thank-you','cart','check-out'];
    foreach ($pages as $key => $value) {
        if ($value->menu > 0) {
            $menus[$key]['title'] = $value->title;
            $menus[$key]['slug'] = $value->slug;
            $menus[$key]['position'] = $value->menu;

            // Default inactive
            $menus[$key]['active'] = '';

            // Normal match
            if ($value->slug == $currentPage) {
                $menus[$key]['active'] = 'current-menu-item';
            }

                if ( in_array( $currentPage ,$staticPageSlug) && $value->slug === 'product') {
                    $menus[$key]['active'] = 'current-menu-item';
            }
        }
    }

    return $menus;
    }   
    
    public function customerInvoice(string $ordernumber , string $mode , $adminEmails , $userEmail,$customerId=null){
           
        $getInfo = Order::where('order_number', $ordernumber)
                            ->where('customer_id',$customerId)
                            ->with([
                                'customer',
                                'orderDetails.product' => function ($query) {
                                    $query->select('id', 'name', 'slug','product_code');
                                }
                            ])
                            ->first();
                             $rject='Y';
                            
                            // if( !empty($getInfo) ){
                            //         $limitHours = (int) env('RETURN_REJECT_AFTER'); // Ensure it's an integer
                            //         $orderTime = Carbon::parse($getInfo->order_date);
                            //         $cutoffTime = $orderTime->addHours($limitHours);
                                   
                            //         if(Carbon::now()->lte($cutoffTime)){
                            //             $rject='N';
                            //         }
                                    
                                
                            // }
                                //return $mode;
                        if( $mode == 'download' || $mode == 'mail' )  {  

                            $pdf = Pdf::loadView('pdf.invoice', [
                                'getInfo' => $getInfo,
                                'ordernumber' => $ordernumber
                            ]);
                            $custname = ucwords(strtolower($getInfo->customer->first_name)) .' '.ucwords(strtolower($getInfo->customer->last_name));
                            $orderDate = date('d-m-Y',strtotime($getInfo->order_date));
                            $fileName = 'invoice_'.$custname.'_'.$orderDate.'.pdf';
                            if( $mode == 'download'){
                                return $pdf->download($fileName);
                            }elseif(  $mode =='mail'){

                                if($getInfo->status == 2){
                                    return 'ERROR-1';
                                
                                }else{
                                     $pdfContent = $pdf->output();
                                         if(!empty($userEmail)){
                                              array_push($adminEmails, $userEmail);
                                            }
                                             $mail = Mail::to($adminEmails);
                                              $mail->send(new InvoiceMail($pdfContent, $getInfo));
                                                return  'success';
                                                    
                                        
                                }

                            }

                        }
                   //
                 
    }
}
