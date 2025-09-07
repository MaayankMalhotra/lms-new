<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodingSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coding_question_id',
        'submitted_solution',
        'is_correct',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function codingQuestion()
    {
        return $this->belongsTo(CodingQuestion::class);
    }
}
