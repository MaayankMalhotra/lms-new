<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Internship;

class InternshipDetail extends Model
{
    use HasFactory;
       protected $fillable = [
        'internship_id',
        'course_description',
        'course_rating',
        'course_rating_student_number',
        'course_learner_enrolled',
        'course_lecture_hours',
        'course_problem_counts',
        'course_banner',
        'key_points',
        'course_overview_description',
        'learning_outcomes',
        'course_curriculum',
        'demo_syllabus',
        'instructor_info',
        'created_at',
        'updated_at',
        'instructor_ids',
        'faqs',
        'key_features',
        'certifications',
        'certificate_image',
        'certificate_description',
        'course_name'
    ];
     protected $casts = [
        'key_points' => 'array',
        'learning_outcomes' => 'array',
        'course_curriculum' => 'array',
        'instructor_ids' => 'array',
        'faqs' => 'array',
        'demo_syllabus' => 'array',
        'key_features' => 'array',
        'certifications' => 'array',
        'certificate_description' => 'array',
        
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class, 'internship_id');
    }
}
