<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventCategory extends Model
{
    use HasFactory;

    protected $table = 'event_categories';

    protected $fillable = ['name'];

    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }
}