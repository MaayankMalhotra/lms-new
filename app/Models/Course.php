<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'course_code_id',
        'logo',
        'duration',
        'placed_learner',
        'slug',
        'rating',
        'price',
    ];
    
    public function recordings()
    {
        return $this->hasMany(Recording::class, 'course_id');
    }
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
    public function trainers()
    {
        return TrainerDetail::whereJsonContains('course_ids', $this->id)->get();
    }
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function detail()
    {
        return $this->hasOne(CourseDetail::class, 'course_id');
    }
}
