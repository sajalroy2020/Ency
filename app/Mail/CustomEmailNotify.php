<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomEmailNotify extends Mailable
{
    use Queueable, SerializesModels;

    protected $typeData;
    protected $template;
    protected $userData;
    protected $link;
    protected $type;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type, $typeData, $template, $userData = null, $link = null)
    {
        $this->typeData = $typeData;
        $this->template = $template;
        $this->userData = $userData;
        $this->link = $link;
        $this->type = $type;
    }


    public function build()
    {
        $view = '';
        if ($this->type == 'ticket') {
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $link = '', $this->typeData, '');
            $view = 'mail.custom-ticket-email-notify';
        } else if ($this->type == 'quotation' || $this->type == 'invoice' || $this->type == 'reset-password') {
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $link = '', '', '');
            $view = 'mail.custom-email-notify';
        }else{
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $link = '', '', '');
            $view = 'mail.custom-email-notify';
        }

        return $this->from(getOption('MAIL_FROM_ADDRESS'), getOption('app_name'))
            ->subject($subject)
            ->markdown($view)
            ->with([
                'subject' => $subject,
                'type' => $this->type,
                'typeData' => $this->typeData,
                'template' => $this->template,
                'userData' => $this->userData,
                'link' => $this->link,
                'message' => $this->type->message,
            ]);
    }
}
