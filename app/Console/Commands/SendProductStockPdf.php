<?php
namespace App\Console\Commands;

use App\Models\SiteInfo;
use App\Models\Unit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ProductStockReport;
use App\Models\MasterRecord;
use Carbon\Carbon;

class SendProductStockPdf extends Command
{
    protected $signature = 'report:send-stock-pdf';
    protected $description = 'Send daily stock report as PDF to email';

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

        public function handle(){
            $yesterday = Carbon::yesterday();
            $query = MasterRecord::whereDate('record_date', $yesterday->toDateString())
                ->with('goDownProducts:id,name')
                    ->with('goDownProducts.category')
                    ->with('unit:id,name')
                    ->with('goDownProducts.unitCategory.standardUnit');
            $products = $query->get();
                
        $filename = 'Stock_Report_' . Carbon::yesterday()->format('Y_m_d') . '.pdf';
        $site_info = SiteInfo::select(['pdf_recvr_email_one','pdf_recvr_email_two'])->findOrFail(1);
        $pdf = Pdf::loadView('pdf.daily_stock_report', compact('products'));
        
        $recipients = [
            //'kuntal.on9@gmail.com',
             $site_info->pdf_recvr_email_one,
             $site_info->pdf_recvr_email_two,
           
        ];
        Mail::to($recipients)->send(new ProductStockReport($pdf->output(),$filename));

        }
    // public function handle(): void
    // {


       
    //     $yesterday = Carbon::yesterday()->toDateString();

    //     $site_info = SiteInfo::select(['pdf_recvr_email_one','pdf_recvr_email_two'])->findOrFail(1);

         
    //    $query = Product::whereHas('goDownProducts')
    //         ->whereHas('goDownProducts.goDownProductDetails')
    //         ->with([
    //             'goDownProducts:id,name,slug,category_id',
    //             'goDownProducts.goDownProductDetails:go_down_product_id,quantity,unit_id',
    //             'goDownProducts.goDownProductDetails.unit:id,name',
    //             'unit:id,name'
    //             ]);
           
    //         $products = $query->get();
    //         $filename = 'Stock_Report_' . Carbon::yesterday()->format('Y_m_d') . '.pdf';

    //     $pdf = Pdf::loadView('pdf.stock_report', compact('products'));
    //     $recipients = [
    //         //'kuntal.on9@gmail.com',
    //          $site_info->pdf_recvr_email_one,
    //          $site_info->pdf_recvr_email_two,
           
    //     ];
    //     Mail::to($recipients)->send(new ProductStockReport($pdf->output(),$filename));

    //     $this->info('Stock PDF sent successfully.');
    // }
}
