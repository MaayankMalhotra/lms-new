<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
    use HasFactory;
    protected $table = 'live_classes';
    protected $fillable = ['batch_id', 'topic', 'google_meet_link', 'class_datetime', 'duration_minutes', 'status', 'folder_id','recording_id'];
    protected $dates = ['class_datetime'];
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function recordings()
    {
        return $this->belongsToMany(Recording::class, 'recordings');
    }

    // public function recording()
    // {
    //     return $this->hasOne(Recording::class);
    // }

    // public function getDynamicStatusAttribute()
    // {
    //     $endTime = Carbon::parse($this->class_datetime)->addMinutes($this->duration_minutes);
    //     if (now()->greaterThanOrEqualTo($endTime)) {
    //         return 'Ended';
    //     } elseif (now()->greaterThanOrEqualTo(Carbon::parse($this->class_datetime))) {
    //         return 'Live';
    //     }
    //     return 'Scheduled';
    // }

    // public function isRecordingVisible()
    // {
    //     $endTime = Carbon::parse($this->class_datetime)->addMinutes($this->duration_minutes);
    //     return $this->recording && now()->greaterThanOrEqualTo($endTime->addMinutes(30));
    // }
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'batch_id', 'batch_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'live_class_id', 'id');
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

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'live_class_id');
    }
}
