<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipEnrollment extends Model
{
    // protected $fillable = [
    //     'user_id', 'internship_id', 'email', 'name', 'phone',
    //     'payment_id', 'amount', 'status','batch_id', 'payment_status',
    // ];

    protected $fillable = [
  'user_id','email','name','batch_id','course_id',
  'payment_id','amount','status','free_internship_after_course'
];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function submissions()
    {
        return $this->hasMany(InternshipSubmission::class);
    }

    public function batches()
{
    return $this->belongsToMany(InternshipBatch::class, 'internship_batch_student');
}
    public function liveClasses()
    {
        return $this->hasMany(InternshipClass::class, 'batch_id', 'batch_id');
    }
}