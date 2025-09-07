<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'internship_enrollment_id',
        'internship_content_id',
        'submission_file',
        'mark',
    ];

    public function enrollment()
    {
        return $this->belongsTo(InternshipEnrollment::class, 'internship_enrollment_id', 'id');
    }

    public function content()
    {
        return $this->belongsTo(InternshipContent::class, 'internship_content_id', 'id');
    }
}
