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
    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }
}
