<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\backendMailTemplate;
use App\Models\DamagedProductsDetail;
use App\Models\GoDownProduct;
use App\Models\GoDownProductDetail;
use App\Models\Product;
use App\Models\SiteInfo;
use App\Models\TrackActivity;
use App\Models\TrackLogin;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ParentController extends Controller
{
    public function randnoGenerator($length=15){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function trackRecords($params ){
        $create = TrackActivity::create(
            $params['update_fields']
        );

    }
    public function trackLoginDetails($param){

        $create = TrackLogin::create(
            $param['update_fields']
        );
        return $create;

    }

    public function getSiteInformation() {
        $site_info = SiteInfo::where(['id'=>'1'])->first();
       return $site_info;
    }

    public function sendmail($data) {

        $to = $data['to'];
        $details = [
            'username' => $data['username'],
            'mail_title'=>$data['mail_title'],
            'updatelink' => $data['link'],
            'message'=> $data['message'],
            'subject'=> $data['subject'],
        ];
        

        $sendMail = Mail::to($to)->send(new backendMailTemplate($details));
        return $sendMail;
        //dd( $sendMail);

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


        public function standartUnitConversion($params){
                $mode = $params['mode'];
                $unit_category_id = $params['unit_category_id'];
                $quantity = $params['quantity'];
                
            if( $mode == 'standard' ){
                    $unit = Unit::where('unit_category_id', $unit_category_id )
                        ->where('showing_unit','1')
                        ->select(['conversion_factor','name'])
                        ->first();
                 $convertoStandartUnt =  $quantity / $unit->conversion_factor .' '.$unit->name;
                 return $convertoStandartUnt;

            }elseif($mode == 'price_per_stnd_unit'){
                $totalPrice = $params['gross_buying_price'];

                $unit = Unit::where('unit_category_id', $unit_category_id )
                        ->where('showing_unit','1')
                        ->select(['conversion_factor','name'])
                        ->first();
                $convertoStandartUnt =  $quantity / $unit->conversion_factor;
                if($convertoStandartUnt > 0 ) {
                    return number_format($totalPrice / $convertoStandartUnt, 3 ).'/'.$unit->name;   
                }else{
                    return '0';
                }  
            }
        }

        public function goDownProdQuantityCheck($param){

            $quantity = $param['quantity']*$param['packet_size'];
            $unit_id= $param['unit_id'];
            $go_down_product_id= $param['go_down_product_id'];
            $slug= $param['slug'];
            $getProductQuantity = $this->unitConversion($quantity ,$unit_id);

            $alreadyInPacket = 0;
            $damagedProd = 0;

             $query = GoDownProductDetail::whereHas('goDownproduct')
                        ->where('go_down_product_id',$go_down_product_id)
                        ->where('unit_id', $getProductQuantity['unit_id'])
                        ->select('quantity')
                        ->first();

            $goDownQuantity =  $query->quantity;
                
            $queryProd = Product::whereHas('goDownProducts')
                        ->where('go_down_product_id',$go_down_product_id)
                        ->select('quantity','packet_size','unit_id');
           
                    if( !empty($slug) ){
                       $queryProd->whereNot('slug',$slug ); 
                    }
                   if($queryProd->count() >= 1 ){
                        $queryProdArr = $queryProd->get();
                        foreach( $queryProdArr as $prod ){
                                $quantityCal = $prod->quantity*$prod->packet_size;
                                $unit_id_prod = $prod->unit_id;
                                $alreadyInPacketArr = $this->unitConversion($quantityCal ,$unit_id_prod);
                                $alreadyInPacket += $alreadyInPacketArr['quantity'];
                        }
                   }  
                   
               // return $alreadyInPacket;   
            //   $queryProdDamaged = DamagedProductsDetail::whereHas('goDownproduct')
            //             ->where('go_down_product_id',$go_down_product_id)
            //             ->select('quantity','unit_id');
                        
            //       if($queryProdDamaged->count() >= 1 ){
            //             $damagedProdQury = $queryProdDamaged->get();
            //             foreach( $damagedProdQury as $DmgdProd ){
            //                     $alreadyDamagedArr = $this->unitConversion($DmgdProd->quantity ,$DmgdProd->unit_id);
            //                     $damagedProd += $alreadyDamagedArr['quantity'];
            //             }
            //        }       
                //$goDownQuantity = $goDownQuantity-$alreadyInPacket-$damagedProd;
                $goDownQuantity = $goDownQuantity-$alreadyInPacket;
                $summery['reject_request'] = 'N';
                if( $goDownQuantity < $getProductQuantity['quantity'] ){
                    $summery['reject_request'] = 'Y';
                }
                    $summery['in_stock'] = $goDownQuantity;
                    $summery['asking_for'] = $getProductQuantity['quantity'];
                

                return $summery;
                        
            
        }

        public function substractQuantity($params){

            
            
            $qunt = $params['quantity'];
            $unit_id = $params['unit_id'];
             $convertedData = $this->unitConversion($qunt,$unit_id);
            
                $go_down_product_id = $params['go_down_product_id'];
                $quantity = $convertedData['quantity'];
                $base_unit_id = $convertedData['unit_id'];

                    $updateProd = GoDownProductDetail::where('id', $go_down_product_id );
                    $selectedFld = $updateProd->select('quantity')->where('unit_id',$base_unit_id)->first();
                    if( !empty($selectedFld) ){
                             $oldQuantity = $params['previously_substracted_val']; // zero in the time of adding.
                             $oldQuantity_unit = $params['previously_substracted_val_unit'];
                       
                       if( $oldQuantity > 0 ){
                            $oldQuantity_converted = $this->unitConversion($oldQuantity,$oldQuantity_unit);
                            $oldQuantity_converted = $oldQuantity_converted['quantity'];
                        }else{
                            $oldQuantity_converted = 0;
                        } 

                            $newQuantity = $selectedFld->quantity + $oldQuantity_converted -  $quantity;
                            $updateProd->update([
                                        'quantity'=>$newQuantity,
                                    ]);

                         if($params['from'] == 'P' ){  // Packet subs

                            $updateProdTbleQuantity = Product::where('id', $params['product_id'] );
                            $selectedFldForproduct = $updateProdTbleQuantity->select('quantity')->where('go_down_product_id',$go_down_product_id)->first();
                         
                            if( !empty($selectedFldForproduct) ){
                            $newPacketQuantity = $selectedFldForproduct->quantity + $params['previously_packed'] - $params['no_of_packet'];
                            $updateProdTbleQuantity->update([
                                        'quantity'=>$newPacketQuantity,
                                    ]);
                            }
                        }


                         return 'Y';               
                    }else{
                        return 'E1';
                    }
            
           

        }
        function cleanCkeditorContent($html) {
            // Remove empty <p>
            $html = preg_replace('/<p>\s*(?:&nbsp;|\s)*<\/p>/i', '', $html);

            // Remove empty <i> (no text content)
            $html = preg_replace('/<i\b[^>]*>\s*<\/i>/i', '', $html);

            return $html;
        }
}
