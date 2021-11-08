<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailPdf extends Mailable
{
    use SerializesModels;
    public $data;
    public $pdf;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$pdf)
    {
        $this->data = $data;
        $this->pdf=$pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


         return $this->from('results@mmcisch.com')
        ->subject($this->data['subject'])
        ->view('Mail.email')
        ->with('data', $this->data)
        ->attachData($this->pdf->output(),'results.pdf');

    }

    }


?>
