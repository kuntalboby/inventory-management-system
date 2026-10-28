<?php
namespace App\Exports;

use App\Models\GoDownProduct;
use App\Models\MasterRecord;
use App\Models\Order;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Product;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;


class StockReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $type, $dateFrom, $dateTo , $paramsArray;

    public function __construct($type, $dateFrom, $dateTo , $paramsArray)
    {
        $this->type = $type;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->paramsArray = $paramsArray;
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
                //'name' => $unitName->name,
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

            }
        }
        public function registerEvents(): array
        {

            
            

            return [
                BeforeSheet::class => function (BeforeSheet $event) {
                    // Insert 2 rows before actual heading
                    // Set specific column widths
                    $event->sheet->getColumnDimension('A')->setWidth(15); // Serial Number
                    $event->sheet->getColumnDimension('B')->setWidth(30); // Product Name
                    $event->sheet->getColumnDimension('C')->setWidth(25); // Category Name
                    $event->sheet->getColumnDimension('D')->setWidth(15); // Quantity
                    $event->sheet->getDelegate()->insertNewRowBefore(1, 2);
                    
                    if( $this->type == 'ORD' ){
                        $heading = 'Order Records';
                        $headerColor = '4A90E2';
                    
                    

                        $event->sheet->getColumnDimension('A')->setWidth(15); // Serial Number
                        $event->sheet->getColumnDimension('B')->setWidth(15); // Product Name
                        $event->sheet->getColumnDimension('C')->setWidth(25); // Category Name
                        $event->sheet->getColumnDimension('D')->setWidth(25); 
                        $event->sheet->getColumnDimension('E')->setWidth(60); 
                        $event->sheet->getColumnDimension('F')->setWidth(10); 
                        $event->sheet->getColumnDimension('G')->setWidth(15); 
                        $event->sheet->getColumnDimension('H')->setWidth(10); 
                         $event->sheet->getColumnDimension('I')->setWidth(15); 
                        $event->sheet->getColumnDimension('J')->setWidth(15); 
                        $event->sheet->getColumnDimension('K')->setWidth(15); 
                        $event->sheet->getColumnDimension('L')->setWidth(20);
                        $event->sheet->getColumnDimension('M')->setWidth(20);
                        $event->sheet->getDelegate()->insertNewRowBefore(1, 2);
                    // Set cell A1 value

                    $headingUpto = 'L1';

                    }elseif( $this->type == 'DRPT' ){
                        $heading = 'Stocks Report';
                        $headerColor = '8A8A8A';
                    
                    

                        $event->sheet->getColumnDimension('A')->setWidth(15); // Product Name
                        $event->sheet->getColumnDimension('B')->setWidth(35); // Category Name
                        $event->sheet->getColumnDimension('C')->setWidth(25); // Quantity
                        $event->sheet->getColumnDimension('D')->setWidth(15); // Quantity
                        $event->sheet->getColumnDimension('E')->setWidth(15); 
                        $event->sheet->getColumnDimension('F')->setWidth(15); // Quantity
                        $event->sheet->getColumnDimension('G')->setWidth(15); // Quantity
                        $event->sheet->getColumnDimension('H')->setWidth(15); // Quantity
                        $event->sheet->getColumnDimension('I')->setWidth(10); // Quantity
                        //$event->sheet->getDelegate()->insertNewRowBefore(1, 2);
                    // Set cell A1 value
                        $headingUpto = 'I1';
                    }elseif( $this->type == 'SRPT' ){
                        $heading = 'Sales Report';
                        $headerColor = '453D5E';
                    
                    

                        $event->sheet->getColumnDimension('A')->setWidth(15); // Product Name
                        $event->sheet->getColumnDimension('B')->setWidth(20); // Category Name
                        $event->sheet->getColumnDimension('C')->setWidth(50); // Quantity
                        $event->sheet->getColumnDimension('D')->setWidth( 15); // Quantity
                        $event->sheet->getColumnDimension('E')->setWidth(18); 
                        $event->sheet->getColumnDimension('F')->setWidth(15); // Quantity
                        $event->sheet->getColumnDimension('G')->setWidth(10); // Quantity
                        $event->sheet->getColumnDimension('H')->setWidth(15);
 
                        //$event->sheet->getDelegate()->insertNewRowBefore(1, 2);
                    // Set cell A1 value
                        $headingUpto = 'G1';
                    }



                    if( !empty($this->dateFrom ) ){
                        $dateFm = Carbon::parse($this->dateFrom)->format('d-m-Y');
                    }else{
                        $dateFm= '';
                    }
                    if( !empty($this->dateTo ) ){
                        $dateTo = ' To '.Carbon::parse($this->dateTo)->format('d-m-Y');
                    }else{
                        $dateTo = 'Till Now';
                    }
                    $event->sheet->getDelegate()->setCellValue('A1',  $heading.'('
                        .$dateFm. 
                        $dateTo.' )'
                    );

                    // Merge A1:E1 (adjust range if more columns)
                    $event->sheet->getDelegate()->mergeCells('A1:'.$headingUpto);

                    // Optional: Style the merged title
                    $event->sheet->getDelegate()->getStyle('A1')
                    ->getFont()->setBold(true)->setSize(12);
                    $event->sheet->getDelegate()->getStyle('A1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 14,
                            'color' => ['rgb' => 'FFFFFF'], // white text
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => $headerColor // light blue background
                            ]
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal('center');
                },
            ];
        }

   
        public function collection()
        {
             $userType = Auth::user()->role;

             

            // if ($this->type == 'GS') {

            //     $query =  GoDownProduct::whereHas('productVendors', function ($q)  {
            //             $q->whereBetween('purchase_date', [$this->dateFrom, $this->dateTo]);
            //         })
            //         ->whereHas('goDownProductDetails')
            //         ->with([
            //             'category:id,name',
            //             'goDownProductDetails:id,go_down_product_id,unit_id,quantity',
            //             'productVendors:go_down_product_id,purchase_date',
            //             'goDownProductDetails.unit:id,name,unit_category_id',
            //             //'productVendors.vendor:id,name',
            //             'damagedProductDetail:id,go_down_product_id,quantity,unit_id'
            //         ])
            //         ->select(['name','id','unit_category_id'])
            //         ->orderBy('name');
                

            //     $products = $query->get();
            //       //return $products;      
            //        $productStat=[];     
            //     foreach ($products as $key => $product) {
            //         $productStat[$key]['name'] = $product->name;
            //             if(!empty($product->damagedProductDetail)){
            //                 $sumOfDamagedItem = 0;
            //                 foreach( $product->damagedProductDetail as $damagedItem ){
            //                     $sumOfDamagedItemArray =  $this->unitConversion($damagedItem->quantity,$damagedItem->unit_id );
            //                     $sumOfDamagedItem += $sumOfDamagedItemArray['quantity'];
            //                 }
            //                 $productStat[$key]['final_quantity'] = $product->goDownProductDetails->quantity-$sumOfDamagedItem;
            //             }else{
            //                 $productStat[$key]['final_quantity'] = $product->goDownProductDetails->quantity;
            //             }

            //            $productStat[$key]['unit'] = $product->goDownProductDetails->unit->name;

            //             $params=array(
            //                 'mode'=>'standard',
            //                 'unit_category_id'=>$product->unit_category_id,
            //                 'quantity'=>$productStat[$key]['final_quantity']
            //             );
            //             $productStat[$key]['standardForm'] = $this->standartUnitConversion($params);
            //     }
                  

            //     $data = [];

               
            //     foreach ($productStat as $k=>$product) {
                   
            //         $data[] = [
                     
            //             'Serial Number'=>$k+1,
            //             'Product Name'   => ucwords(strtolower($product['name'])) ,
            //             'Final Available Quantity( After if any damage )' => $product['final_quantity'].' '.$product['unit'].'/ '.$product['standardForm'] ?? '',
            //         ];
            //     }

            //     return collect($data);
            // }elseif ($this->type == 'SL') {

            //    $sales =  Order::whereBetween('order_date', [$this->dateFrom, $this->dateTo])
            //             ->select(['invoice_number','id','order_date','customer_id'])
            //             ->where('status', '1')
            //             ->with([
            //                 'orderDetails:id,order_id,product_id,quantity,unit',
            //                 'orderDetails.product' => function ($query) {
            //                     $query->select('id', 'name', 'category_id'); 
            //                 },
            //                 'orderDetails.product.category' => function ($query) {
            //                     $query->select('id', 'name');
            //                 }
            //             ])
            //             ->orderByDesc('order_date')
            //             ->get();
            //     $data = [];
            //     $l=1;
            //         foreach ($sales as $k => $product) {
                        
            //             foreach ($product->orderDetails as $detail) {
            //                 $data[] = [
            //                     'Serial Number' =>$l,
            //                     'Order Date' => date('d-m-Y',strtotime($product->order_date)),
            //                     'Product Name'  => ucwords(strtolower($detail->product->name ?? '')),
            //                     'Category Name' => ucwords(strtolower($detail->product->category->name ?? '')),
            //                     'Sold Quantity' => ($detail->quantity ?? '') . ' ' . ucwords(strtolower($detail->unit ?? '')),
            //                 ];
            //                 $l++;
            //             }
            //         }
            // }

             if ($this->type == 'ORD'){
                

                    
                        $query = Order::whereHas('orderDetails')
                        ->with('orderDetails:id,product_id,selling_price,quantity,unit,amount,
                            product_gst,discount_percent')
                        ->with('orderDetails.product:id,name')
                        ->with('customer')
                        //->whereNot('status','2')
                        ->select('orders.*');
                        if ($userType === 'manager') {
                                    $query->where('manager_id', Auth::id());
                                }

                        if (!empty($this->paramsArray['invoice_number'])) {
                            $query->where('invoice_number', 'like', '%' . $this->paramsArray['invoice_number'] . '%');
                         }
                         
                         if (!empty($this->paramsArray['ordered_from'])) {
                            $query->where('ordered_from', $this->paramsArray['ordered_from']);
                         }

                         
                        if (!empty($this->dateFrom) && empty($this->dataTo)) {
                            $dtfm = Carbon::parse($this->dateFrom)->startOfDay();
                            
                            //$dateFrom = Carbon::createFromFormat('d-m-Y', $this->dateFrom)->format('Y-m-d');
                            $query->where('order_date','>=', $dtfm);
                        }elseif(!empty($this->dateTo) && empty($this->dateFrom)) {

                             $dtto = Carbon::parse($this->dateTo)->endOfDay();
                            //$dateTo = Carbon::createFromFormat('d-m-Y', $this->dateTo)->format('Y-m-d');
                            $query->where('order_date','<=', $dtto);
                        }elseif(!empty($this->dateFrom) && !empty($this->dateTo)){
                            // $dateFrom = Carbon::createFromFormat('d-m-Y', $this->dateFrom)->format('Y-m-d');
                            // $dateTo = Carbon::createFromFormat('d-m-Y', $this->dateTo)->format('Y-m-d');
                             $dtfm = Carbon::parse($this->dateFrom)->startOfDay();
                             $dtto = Carbon::parse($this->dateTo)->endOfDay();
                            $query->whereBetween('order_date', [$dtfm, $dtto]);
                        }
                        $dataFltr = $this->paramsArray;

                        $query->whereHas('customer', function ($q) use ($dataFltr) {
                                if (!empty($this->paramsArray['name'])) {
                                    $q->where(function ($sub) use ($dataFltr) {
                                        $sub->where('first_name', 'like', '%' . $dataFltr['name'] . '%')
                                            ->orWhere('last_name', 'like', '%' . $dataFltr['name'] . '%');
                                    });
                                }

                                if (!empty($dataFltr['phone'])) {
                                    $q->where('phone', 'like', '%' . $dataFltr['phone'] . '%');
                                }

                                
                            });
                            $query->orderByDesc('order_date');

                            $data=[];
                            if( $query->count() > 0 ){
                            $orderStat = $query->get();
                                
                            foreach ($orderStat as $orders) {
                                if( $orders->ordered_from == '1' ){
                                    $orderedFrom = 'Inventory';
                                }elseif( $orders->ordered_from == '2' ){
                                    $orderedFrom = 'Online';
                                }
                                foreach ($orders->orderDetails as $detail) {
                                    $OrderStatus = 'Complete';
                                    if( $orders->status == '2' ){
                                        $OrderStatus = 'Cancelled';
                                    }elseif( $orders->status == '3' ){
                                        $OrderStatus = 'Partially Returned';
                                    }
                                    
                                    $discounted_amount = $detail->quantity * 
                                                        $detail->selling_price * 
                                                        $detail->discount_percent / 100;
                                    $data[] = [
                                        'Order Date'      => Carbon::parse($orders->order_date)->format('d-m-Y'),
                                        'Invoice Number'  => ucwords(strtolower($orders->invoice_number)),
                                        'Customer Name'   => ucwords(strtolower($orders->customer->first_name . ' ' . $orders->customer->last_name)),
                                        'Phone Number'        =>$orders->customer->phone,
                                        'Product Name'    => empty($detail->product_name) ? ucwords(strtolower($detail->product->name)) : ucwords(strtolower($detail->product_name)),
                                        'Quantity'        => $detail->quantity,
                                        'Selling Price'   => number_format($detail->selling_price, 2),
                                        'Discounted Amount'   => number_format($discounted_amount,2),
                                        'Discount Percent'   => number_format($detail->discount_percent,2),
                                        'GST'             => !empty($detail->product_gst) ? $detail->product_gst: number_format(0,2),
                                        'Amount'          => number_format($detail->amount, 2),
                                        'Order Status'    => $OrderStatus,
                                        'Order From'      => $orderedFrom
                                    ];
                                }
                            }
                        }


             }elseif($this->type == 'DRPT'){
                    $dtfm = Carbon::parse($this->dateFrom)->startOfDay();
                    $dtto = Carbon::parse($this->dateTo)->endOfDay();
                

                    $masterRecord = MasterRecord::whereBetween('record_date', [$dtfm, $dtto])
                    ->with('goDownProducts:id,name')
                    ->with('goDownProducts.category')
                    ->with('unit:id,name')
                    ->with('goDownProducts.unitCategory.standardUnit')
                    ->orderByDesc('record_date')->get();

                   

                    $data=[];
                    foreach ($masterRecord as $k=>$record) {
                        $conversionFactor = $record->goDownProducts->unitCategory->standardUnit->conversion_factor;

                        $openingStock = $record->opening_stock_quantity/$conversionFactor;
                        $addStock = $record->add_stock_quantity/$conversionFactor;
                        $soldStock = $record->sold_stock_quantity/$conversionFactor;
                        $damangedStock = $record->damaged_stock_quantity/$conversionFactor;
                        $closingStock = $record->closing_stock_quantity/$conversionFactor;
                    $data[] = [
                     
                        
                        'Date'=>date('d-m-Y',strtotime($record->record_date)),
                        'Product Name'   => ucwords(strtolower($record->goDownProducts->name)),
                        'Category' => ucwords(strtolower($record->goDownProducts->category->name)),
                        'Opening Stock'  =>  number_format($openingStock,2),
                        'Add Stock'      =>  number_format($addStock,2),
                        'Sell Stock'     =>  number_format($soldStock,2),
                        'Damaged Stock'  =>  number_format($damangedStock,2),
                        'Closing Stock'  =>  number_format($closingStock,2),
                        'Unit'=>$record->goDownProducts->unitCategory->standardUnit->name

                    ];
                }
             }elseif($this->type == 'SRPT'){
                    $dtfm = Carbon::parse($this->dateFrom)->startOfDay();
                    $dtto = Carbon::parse($this->dateTo)->endOfDay();
                  $masterRecord = Order::whereBetween('order_date', [$dtfm, $dtto])
                                ->select(['id', 'invoice_number', 'order_date', 'customer_id', 'total_amount','status'])
                                ->with(['orderDetails', 'customer:id,first_name,last_name,phone'])
                                ->orderByDesc('id')
                                ->get()
                                ->map(function($order) {
                                    $firstOrderId = Order::where('customer_id', $order->customer_id)
                                        ->orderBy('order_date', 'asc')
                                        ->orderBy('id', 'asc') 
                                        ->value('id');

                                    $order->is_new_customer = ($order->id == $firstOrderId);
                                    return $order;
                                });

                            $data=[];
                            foreach ($masterRecord as $k=>$record) {
                                $noOfPrd = $record->orderDetails->count();
                                $OrderStatus = 'Complete';
                                    if( $record->status == '2' ){
                                        $OrderStatus = 'Cancelled';
                                    }elseif( $record->status == '3' ){
                                        $OrderStatus = 'Partially Returned';
                                    }
                            $data[] = [
                            
                                
                                'Date'=>date('d-m-Y',strtotime($record->order_date)),
                                'Invoice Number'  => $record->invoice_number ?? '',
                                'Customer Name'   => ucwords(strtolower($record->customer->first_name.' '.$record->customer->last_name )),
                                'Mobile Number'  => $record->customer->phone,
                                'No Of Products' => $noOfPrd,
                                'Total Amount'     => $record->total_amount,
                                'Order Status'     => $OrderStatus,
                                'Customer'  => $record->is_new_customer ? 'New' : 'Old',
                                
                            ];
                        }

             }
            
            return collect([$data]);
        }

    public function headings(): array
    {
        if ($this->type == 'ORD') {
            return [
                    'Order Date',
                    'Invoice Number',
                    'Customer Name',
                    'Phone Number',
                    'Product Name',
                    'Quantity',
                    'Selling Price',
                    'Discounted Amount',
                    'Discount Percent',
                    'GST',
                    'Amount',
                    'Order Status',
                    'Ordered From'
                ];        
            }elseif ($this->type == 'DRPT') {
            return [
                    
                    'Date',
                    'Product Name',
                    'Category',
                    'Opening Stock',
                    'Add Stock',
                    'Sell Stock',
                    'Damaged Stock',
                    'Closing Stock',
                    'Unit'
                ];        
            }elseif ($this->type == 'SRPT') {
            return [
                    
                    'Date',
                    'Invoice Number',
                    'Customer Name',
                    'Mobile Number',
                    'No Of Products',
                    'Total Amount',
                    'Order Status',
                    'Customer',
                ];        
            }

            
            
        // if ($this->type == 'GS') {
        //     //return ['Date From', 'Date To','Product Name', 'Category Name','Available Quantity'];
        //     return ['Serial Number','Product Name','Final    Available Quantity( After if any damage )'];
        // } elseif ($this->type == 'SL') {
        //      return ['Serial Number','Order Date','Product Name', 'Category Name','Sold Quantity'];
        // }

        return [];
    }
}