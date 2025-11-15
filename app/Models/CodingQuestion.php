<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class CodingQuestion extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'solutions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'solutions' => 'array', // Cast the solutions JSON column to an array
    ];
    public function submissions()
    {
        return $this->hasMany(CodingSubmission::class, 'coding_question_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
