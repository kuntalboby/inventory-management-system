<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $subjectText;
    public $messageContent;
    public $siteName;
    public $siteLogo;
    public $mailFor;
    

    public function __construct($name, $email, $subjectText, $messageContent,$siteName, $siteLogo, $mailFor)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subjectText = $subjectText;
        $this->messageContent = $messageContent;
        $this->siteName = $siteName;
        $this->siteLogo = asset('storage/website-image/' . $siteLogo);

        $this->mailFor = $mailFor;
    }

    public function build()
    {

        if( $this->mailFor == 'contact' ){

            return $this->subject('Contact Form ::'.$this->subjectText)
                        ->view('mailtemplate.contact-form')
                        ->with([
                            'name' => $this->name,
                            'email' => $this->email,
                            'subject' => $this->subjectText,
                            'messageContent' => $this->messageContent,
                        ]);
        }elseif( $this->mailFor == 'newsletter' ){

            return $this->subject($this->subjectText)
                        ->view('mailtemplate.newsletter')
                        ->with([
                            'name' => $this->name,
                            'email' => $this->email,
                            'subject' => $this->subjectText,
                            'messageContent' => $this->messageContent,
                        ]);
        }elseif( $this->mailFor == 'guestPassword' ){

            return $this->subject($this->siteName.':: Login credentials')
                        ->view('mailtemplate.guest-password')
                        ->with([
                            'name' => $this->name,
                            'email' => $this->email,
                            'subject' => $this->subjectText,
                            'messageContent' => $this->messageContent,
                        ]);
        }elseif( $this->mailFor == 'forgetPassLink' ){

            return $this->subject($this->siteName.':'.$this->subjectText)
                        ->view('mailtemplate.customer-password-link')
                        ->with([
                            'name' => $this->name,
                            'email' => $this->email,
                            'subject' => $this->subjectText,
                            'messageContent' => $this->messageContent,
                        ]);
        }elseif( $this->mailFor == 'cancelRequest' ){

            return $this->subject($this->siteName.'::'.$this->subjectText)
                        ->view('mailtemplate.order-cancel-request')
                        ->with([
                            'name' => $this->name,
                            'email' => $this->email,
                            'subject' => $this->subjectText,
                            'messageContent' => $this->messageContent,
                        ]);
        }
    }
}
