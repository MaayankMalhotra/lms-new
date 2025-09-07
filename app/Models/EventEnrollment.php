<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'name', 'email', 'phone', 'comments'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}