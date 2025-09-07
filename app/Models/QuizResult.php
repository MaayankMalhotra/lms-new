<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = ['student_id', 'quiz_set_id', 'marks', 'completed'];
}
