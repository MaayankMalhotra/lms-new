<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewBooking extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'slot_id', 'meeting_link', 'status'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function slot()
    {
        return $this->belongsTo(AvailableSlot::class);
    }
}