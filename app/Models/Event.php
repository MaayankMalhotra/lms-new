<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'image', 'category_id', 'location', 'event_date', 'event_time', 'created_by'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function enrollments()
    {
        return $this->hasMany(EventEnrollment::class);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value) . '-' . uniqid();
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? $this->image : asset('images/placeholder.jpg');
    }
}