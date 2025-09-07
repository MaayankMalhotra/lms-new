<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipFolder extends Model
{
    protected $fillable = [
        'internship_id',
        'name',
        'locked',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class,'internship_id'); // Assuming a Course model exists
    }

    public function topics()
    {
        return $this->hasMany(InternshipTopic::class, 'folder_id');
    }
}