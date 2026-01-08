<?php

namespace App\Mail;

use App\Models\Webinar;
use App\Models\WebinarEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebinarRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Webinar $webinar;
    public WebinarEnrollment $enrollment;

    public function __construct(Webinar $webinar, WebinarEnrollment $enrollment)
    {
        $this->webinar = $webinar;
        $this->enrollment = $enrollment;
    }

    public function build()
    {
        return $this->subject('Webinar Registration Confirmed')
            ->view('emails.webinar-registration')
            ->with([
                'webinar' => $this->webinar,
                'enrollment' => $this->enrollment,
            ]);
    }
}
