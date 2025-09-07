<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuizSetAttempt extends Model
{
    protected $fillable = ['user_id', 'quiz_set_id', 'score'];
    // User relationship define karo
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // QuizSet relationship (yeh bhi ensure karo)
    public function quizSet()
    {
        return $this->belongsTo(QuizSet::class, 'quiz_set_id');
    }

    public function answers()
    {
        return $this->hasMany(StudentQuizAnswer::class, 'attempt_id');
    }
}
