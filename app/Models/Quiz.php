<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['quiz_set_id', 'question', 'option_1', 'option_2', 'option_3', 'option_4', 'correct_option'];

    public function quizSet()
    {
        return $this->belongsTo(QuizSet::class, 'quiz_set_id');
    }
}