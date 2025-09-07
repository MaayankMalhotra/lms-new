<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuizAnswer extends Model
{
    protected $fillable = ['attempt_id', 'quiz_id', 'student_answer', 'user_id'];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attempt()
    {
        return $this->belongsTo(StudentQuizSetAttempt::class);
    }
}
