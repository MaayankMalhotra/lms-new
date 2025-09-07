<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipBatch extends Model
{
    use HasFactory;
   protected $fillable = [
       'batch_name', 'start_date', 'status', 'days', 'duration', 'time_slot', 
        'price','emi_price', 'discount_info', 'slots_available', 'slots_filled', 
        'internship_id', 'teacher_id','emi_available',
        'emi_plans',
    ];
    
  protected $casts = [
        'emi_available' => 'boolean',
        'emi_plans' => 'array', // Cast emi_plans as array (stored as JSON in database)
        'start_date' => 'date',
    ];
    protected $dates = ['start_date'];

    // public function internship()
    // {
    //     return $this->belongsTo(Internship::class, 'internship_id');
    // }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function students()
{
    return $this->belongsToMany(InternshipEnrollment::class, 'internship_batch_student');
}

public function classes()
{
    return $this->hasMany(InternshipClass::class,'batch_id');
}

public function internship()
{
    return $this->belongsTo(Internship::class, 'internship_id');
}
public function liveClasses()
    {
        return $this->hasMany(InternshipClass::class,'batch_id');
    }
}