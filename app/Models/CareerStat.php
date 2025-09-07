<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerStat extends Model
{
    use HasFactory;
    protected $fillable = [
        'career_highlight_id',
        'icon',
        'value',
        'label',
    ];
    public function highlight()
{
    return $this->belongsTo(CareerHighlight::class);
}
}
