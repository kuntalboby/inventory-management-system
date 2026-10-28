<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $getInfo;
    protected $pdfContent;

    public function __construct($pdfContent, $getInfo)
    {
        $this->pdfContent = $pdfContent;
        $this->getInfo = $getInfo;
    }

    public function build()
    {
        return $this->view('mailtemplate.invoice_text')
                    ->subject('Invoice for Order #' . $this->getInfo->order_number)
                    ->attachData($this->pdfContent, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
