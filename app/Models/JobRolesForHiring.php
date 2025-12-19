<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRolesForHiring extends Model
{
    use HasFactory;
    protected $table = 'job_role_for_hirings';

    protected $fillable = [
        'title',
        'company_name',
        'salary_package',
        'location',
        'apply_link',
        'image_url',
        'last_date_to_apply',
        'suggestions',
        'technologies',
    ];

    protected $casts = [
        'technologies' => 'array', // Casts technologies JSON column to array for easy access
        'last_date_to_apply' => 'date',
    ];

    public function applications()
    {
        return $this->hasMany(JobRoleApplication::class, 'job_role_id')->with('user');
    }
}
