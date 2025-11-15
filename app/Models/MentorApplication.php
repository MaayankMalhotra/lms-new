<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'teaching_hours',
        'specialization',
        'experience_years',
        'linkedin_url',
        'portfolio_url',
        'message',
        'status',
    ];

    protected $casts = [
        'teaching_hours'   => 'integer',
        'experience_years' => 'integer',
    ];
}
