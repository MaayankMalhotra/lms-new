<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipTopic extends Model
{
    protected $fillable = [
        'folder_id',
        'name',
        'discussion',
        'locked',
    ];

    public function folder()
    {
        return $this->belongsTo(InternshipFolder::class,'folder_id');
    }

    public function recordings()
    {
        return $this->hasMany(InternshipRecording::class,'topic_id');
    }
}