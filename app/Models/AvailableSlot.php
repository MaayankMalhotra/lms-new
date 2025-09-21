<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableSlot extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'start_time', 'duration_minutes', 'slot_number', 'status', 'is_booked','batch_id'];

    protected $casts = [
        'start_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function booking()
    {
        return $this->hasOne(InterviewBooking::class, 'slot_id');
    }
}