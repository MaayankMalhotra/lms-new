<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = ['user_id', 'leave_date', 'reason', 'status', 'approved_by', 'approved_at'];
    protected $dates = ['leave_date', 'approved_at'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
