<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class CourseDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_name',
        'course_description',
        'course_rating',
        'course_rating_student_number',
        'course_learner_enrolled',
        'course_lecture_hours',
        'course_problem_counts',
        'course_banner',
        'course_overview_description',
        'learning_outcomes',
        'course_curriculum',
        'key_points',
        'instructor_ids',
        'faqs',
        'demo_syllabus',
        'key_features',
        'certifications',
        'certificate_image',
        'certificate_description',
        'course_id'
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

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
