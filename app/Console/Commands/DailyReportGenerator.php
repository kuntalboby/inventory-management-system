<?php

namespace App\Console\Commands;
use App\Models\DamagedProductsDetail;
use App\Models\GoDownProduct;
use App\Models\MasterRecord;
use App\Models\Order;
use App\Models\ProductVendor;
use App\Models\Unit;
use Carbon\Carbon;

use Illuminate\Console\Command;

class DailyReportGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:report-generator {source?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
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
    public function handle()
    {
         //master report
             $source = $this->argument('source') ?? 'cron';
            
              if( $source == 'Controller' ){
                    $yesterday = Carbon::today();
              }else{
                    $yesterday = Carbon::yesterday();
              }
        $finalArr =[];
        $godownPrds = GoDownProduct::with('goDownProductDetails')
                        ->get();

                if( $godownPrds->isNotEmpty() ){
                        foreach ($godownPrds as $key => $mainPrd) {
                                  $finalArr[$key]['date'] = $yesterday->toDateString();
                                  $finalArr[$key]['name'] = $mainPrd->name;
                                  $finalArr[$key]['id'] = $mainPrd->id;
                                  $finalArr[$key]['closing_stock'] = $mainPrd->goDownProductDetails->quantity;
                                  $finalArr[$key]['unit_id'] = $mainPrd->goDownProductDetails->unit_id;
                                  //add 
                        $added = ProductVendor::where('go_down_product_id',$mainPrd->id)
                                    ->whereDate('purchase_date',$yesterday->toDateString())
                                    ->select(['stock_quantity','unit_id'])
                                    ->get();
                        $addedCount=0;
                            if($added->isNotEmpty()){
                                    foreach ($added as $advl) {
                                       $addedCountArr =  $this->unitConversion($advl->stock_quantity,$advl->unit_id);
                                       $addedCount +=$addedCountArr['quantity'];
                                    }
                               $finalArr[$key]['added'] = $addedCount;
                            }else{
                                $finalArr[$key]['added'] = 0;
                            }

                         //damaged
                         $damaged = DamagedProductsDetail::where('go_down_product_id',$mainPrd->id)
                                    ->whereDate('damaged_date',$yesterday->toDateString())
                                    ->select(['quantity','damaged_in','unit_id','no_of_packet'])
                                    ->get();
                            $damagedCount=0;
                            if($damaged->isNotEmpty()){
                                
                                    foreach ($damaged as $damgd) {
                                        if( $damgd->damaged_in == '1'){
                                            $totalQuant = $damgd->quantity;
                                        }elseif($damgd->damaged_in == '2'){
                                            $totalQuant = $damgd->quantity * $damgd->no_of_packet;
                                        }
                                        $damagedCountArr =  $this->unitConversion($totalQuant,$damgd->unit_id);
                                       $damagedCount +=$damagedCountArr['quantity'];
                                    }
                               $finalArr[$key]['damaged'] = $damagedCount;
                            }else{
                                $finalArr[$key]['damaged'] = 0;
                              
                            }

                            //ordered/sale
                        $orderRecd = Order::whereDate('order_date', $yesterday->toDateString())
                        ->whereHas('orderDetails.product', function ($query) use ($mainPrd) {
                            $query->where('go_down_product_id', $mainPrd->id);
                        })
                        ->with(['orderDetails.product.goDownProducts'])
                        ->get();
                                
                       $saleCount = 0;

                        if ($orderRecd->isNotEmpty()) {
                            foreach ($orderRecd as $sale) {
                                foreach ($sale->orderDetails as $detail) {
                                    if ($detail->product && $detail->product->go_down_product_id == $mainPrd->id) {
                                        $ordrQnt = $detail->quantity * $detail->product->packet_size;
                                        $converted = $this->unitConversion($ordrQnt, $detail->unit_id);
                                        $saleCount += $converted['quantity'];
                                    }
                                }
                            }
                            $finalArr[$key]['sale'] = $saleCount;
                        } else {
                            $finalArr[$key]['sale'] = 0;
                        }
                        $openingQnt = $mainPrd->goDownProductDetails->quantity - $addedCount + $saleCount + $damagedCount;
                        $finalArr[$key]['opening_stock'] = $openingQnt;

                    } // main loop
                }

            if( !empty($finalArr) ){
               
                    MasterRecord::whereDate('record_date', $yesterday->toDateString())->delete();

                 foreach ($finalArr as $key => $dailyrecd) {

                    $savedInfo['record_date'] =  $yesterday;
                    $savedInfo['go_down_product_id'] =  $dailyrecd['id'];
                    $savedInfo['opening_stock_quantity'] =  $dailyrecd['opening_stock'];
                    $savedInfo['add_stock_quantity'] =  $dailyrecd['added'];
                    $savedInfo['sold_stock_quantity'] =  $dailyrecd['sale'];
                    $savedInfo['damaged_stock_quantity'] =  $dailyrecd['damaged'];
                    $savedInfo['closing_stock_quantity'] =  $dailyrecd['closing_stock'];
                    $savedInfo['unit_id'] =  $dailyrecd['unit_id'];
                    MasterRecord::create($savedInfo);
                 }
            }

        //end
    }
}
