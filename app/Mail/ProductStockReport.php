<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductStockReport extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent; 
    public $filename;


    public function __construct($pdfContent  , $filename)
    {
        $this->pdfContent = $pdfContent;
        $this->filename = $filename;
    }

    public function build(): self
    {
        return $this->subject('Daily Product Stock Report')
                    ->view('mailtemplate.stock_report') 
                    ->attachData($this->pdfContent, $this->filename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}