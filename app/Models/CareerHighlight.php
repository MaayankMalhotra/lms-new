<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerHighlight extends Model
{
    use HasFactory;
    protected $fillable=[
        'heading_line','heading_highlight','cta_text'
    ];
    public function stats()
{
    return $this->hasMany(CareerStat::class);
}
}

