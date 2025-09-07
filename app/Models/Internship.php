<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'logo',
        'duration',
        'project',
        'applicant',
        'price',
        'certified_button'
    ];

    public function contents()
    {
        return $this->hasMany(InternshipContent::class, 'internship_id', 'id');
    }
      public function batches()
    {
        return $this->hasMany(InternshipBatch::class,'internship_id');
    }
    public function folders()
    {
        return $this->hasMany(InternshipFolder::class,'internship_id');
    }
}
