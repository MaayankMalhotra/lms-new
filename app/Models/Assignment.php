<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'live_class_id', 'title', 'description', 'due_date', 'file_path', 'created_at', 'updated_at','batch_id'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function liveClass()
    {
        return $this->belongsTo(LiveClass::class, 'live_class_id');
    }

    // Helper to get the full file URL
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}