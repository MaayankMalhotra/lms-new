<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRolesForHiring extends Model
{
    use HasFactory;
    protected $table = 'job_role_for_hirings';

    protected $fillable = ['title', 'technologies'];

    protected $casts = [
        'technologies' => 'array', // Casts technologies JSON column to array for easy access
    ];
}
