<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipRecording extends Model
{
    protected $fillable = [
        'topic_id',
        'video_url',
        'locked',
    ];

    public function topic()
    {
        return $this->belongsTo(InternshipTopic::class);
    }
}