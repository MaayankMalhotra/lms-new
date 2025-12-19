<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRoleApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_role_id',
        'user_id',
        'name',
        'email',
        'resume_path',
        'message',
        'status',
    ];

    public function jobRole()
    {
        return $this->belongsTo(JobRolesForHiring::class, 'job_role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
