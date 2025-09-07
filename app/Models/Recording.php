<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    protected $fillable = ['topic_id', 'video_url'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
    public function batches()
{
    return $this->hasMany(Batch::class);
}

public function liveClasses()
    {
        return $this->belongsToMany(LiveClass::class, 'live_class_recording');
    }
}