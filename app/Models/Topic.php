<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['folder_id', 'name', 'discussion'];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }
}