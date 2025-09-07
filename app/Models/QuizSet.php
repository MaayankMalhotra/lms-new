<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSet extends Model
{
    protected $fillable = ['teacher_id', 'title', 'total_quizzes','batch_id'];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'quiz_set_id');
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}