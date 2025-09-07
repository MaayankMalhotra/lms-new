<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
       'batch_name', 'start_date', 'status', 'days', 'duration', 'time_slot', 
        'price','emi_price', 'discount_info', 'slots_available', 'slots_filled', 
        'course_id', 'teacher_id','emi_available',
        'emi_plans',
    ];

    protected $casts = [
        'emi_available' => 'boolean',
        'emi_plans' => 'array', // Cast emi_plans as array (stored as JSON in database)
        'start_date' => 'date',
    ];
    protected $dates = ['start_date'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    public function liveClasses()
    {
        return $this->hasMany(LiveClass::class);
    }

   
}
