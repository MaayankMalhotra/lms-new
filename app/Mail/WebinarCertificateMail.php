<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WebinarCertificateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $enrollment;
    public $certificateUrl;
    public $duration;
    public $certificateId;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $enrollment
     * @param  string $certificateUrl
     */
    public function __construct($enrollment, $certificateUrl,$duration,$certificateId)
    {
        $this->enrollment = $enrollment;
        $this->certificateUrl = $certificateUrl;
        $this->duration = $duration;
        $this->certificateId = $certificateId;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Webinar Certificate')
                    ->view('admin.webinar.webinar-certificate')
                    ->with([
                    'enrollment' => $this->enrollment,
                    'certificateUrl' => asset($this->certificateUrl),  // Build URL here
                    'certificateId' => $this->certificateId,
                    ]); 
                    // ->attach($this->certificatePath, [
                    //     'as' => 'webinar_certificate.jpg',
                    //     'mime' => 'image/jpeg',
                    // ]);
    }
}
