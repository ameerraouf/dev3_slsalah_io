<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailTemplates;

class PurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user ;
    public $settings ;
    public $template ;

    
    
    
    /**
     * Create a new message instance.
     */
    public function __construct( $user,  $settings,  $template)
    {
        $this->user =$user ;
        $this->settings = $settings ; 
        $this->template = $template ;
    }

   
  
   
    // /**
    //  * Get the message envelope.
    //  */
    
    public function envelope(): Envelope
    {
        $template = $this->template;
        return new Envelope(
            subject: $template->subject,
        );
    }

    // /**
    //  * Get the message content definition.
    //  */
    
    public function content(): Content
    {
        return new Content(
            view: 'mail.purchase',
        );
    }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    
    public function attachments(): array
    {
        return [];
    }
}
