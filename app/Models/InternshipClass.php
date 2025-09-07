<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class InternshipClass extends Model
{
    protected $fillable = [
        'batch_id',
        'folder_id',
        'google_meet_link',
        'class_datetime',
        'duration_minutes',
        'recording_id',
        'status',
        'topic',
    ];

    protected $casts = [
        'class_datetime' => 'datetime',
        'duration_minutes' => 'integer',
        'status' => 'string',
    ];
  public function recordings()
    {
        return $this->belongsToMany(InternshipRecording::class, 'recordings');
    }
    public function batch()
    {
        return $this->belongsTo(InternshipBatch::class,'batch_id');
    }

    public function folder()
    {
        return $this->belongsTo(InternshipFolder::class); // Assuming Folder model exists
    }
     public function enrollment()
    {
        return $this->belongsTo(InternshipEnrollment::class, 'batch_id', 'batch_id');
    }

    public function hasAttended($userId)
    {
        return $this->attendance()->where('user_id', $userId)->whereDate('date', now()->toDateString())->exists();
    }

    public function isUpcoming()
    {
        return now()->lt(Carbon::parse($this->class_datetime)); // Before class start time
    }

    public function isEnded()
    {
        $endTime = Carbon::parse($this->class_datetime)->addMinutes($this->duration_minutes);
        return now()->gte($endTime); // After class end time
    }

    public function isOngoing()
    {
        $startTime = Carbon::parse($this->class_datetime);
        $endTime = $startTime->copy()->addMinutes($this->duration_minutes);
        return now()->gte($startTime) && now()->lte($endTime); // During class time
    }

}