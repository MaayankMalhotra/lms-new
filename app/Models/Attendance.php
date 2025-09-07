<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $fillable = ['user_id', 'live_class_id', 'date'];
    protected $dates = ['date'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }
}
