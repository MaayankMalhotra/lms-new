<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarEnrollment extends Model
{
    use HasFactory;
    protected $fillable = [
        'webinar_id',
        'name',
        'email',
        'phone',
        'comments',
        'attendance_code',
        'meeting_id',
        'meeting_link',
        'meeting_password',
        'attendance_status',
        'certificate_sent',
        'certificate_sent_at',
        'certificate_path'
    ];

    protected $appends = ['resolved_webinar_title'];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }

    public function getResolvedWebinarTitleAttribute(): string
    {
        $title = optional($this->webinar)->title;

        if ($title) {
            return $title;
        }

        return $this->webinar_id
            ? 'Webinar #' . $this->webinar_id
            : 'Webinar';
    }
}
