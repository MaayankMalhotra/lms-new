<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipContent extends Model
{
    use HasFactory;

    public function internship()
    {
        return $this->belongsTo(Internship::class, 'internship_id', 'id');
    }
}
