<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebinarConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $enrollment;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @param object $enrollment
     * @return void
     */
    public function __construct($data, $enrollment)
    {
        $this->data = $data;
        $this->enrollment = $enrollment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->bcc('ashwani.rai@henryharvin.in')
                    ->subject('Your Webinar Confirmation Mail')
                    ->view('admin.webinar.webinar-email-confirmation')
                    ->with([
                        'data' => $this->data,
                        'enrollment' => $this->enrollment,
                        'webinar' => $this->enrollment->webinar,
                    ]);
    }
}
