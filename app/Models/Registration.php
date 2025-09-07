<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id', 'batch_date', 'batch_status', 'mode', 'price', 'slots_available', 'slots_filled',
    ];
}