<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class QuizSet extends Model
{
    protected $fillable = ['teacher_id', 'title', 'total_quizzes','course_id','batch_id'];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'quiz_set_id');
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
