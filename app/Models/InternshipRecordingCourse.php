<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipRecordingCourse extends Model
{
    use HasFactory;
    protected $table = 'internship_recording_courses';
    protected $fillable = ['course_name'];

    public function recordings()
    {
        return $this->hasMany(InternshipRecording::class,'recording_course_id');
    }
}
