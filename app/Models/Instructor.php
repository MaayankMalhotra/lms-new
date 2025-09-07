<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;
    protected $table = 'home_instructors';

    protected $fillable = [
        'name',
        'image',
        'teaching_hours',
        'specialization',
        'linkedin_url',
        'facebook_url',
        'phone_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
