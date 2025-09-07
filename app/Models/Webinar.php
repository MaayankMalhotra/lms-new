<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'start_time',
        'registration_deadline',
        'entry_type',
        'participants_count',
        'tags',
        'end_time',
        'duration',
        'topic',
        'speaker_name',
        'speaker_designation',
        'speaker_bio',
        'learning_points',
        'event_date_display',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'registration_deadline' => 'datetime',
        'end_time' => 'datetime',
    ];
}
