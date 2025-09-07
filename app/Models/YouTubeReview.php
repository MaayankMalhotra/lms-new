<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YouTubeReview extends Model
{
    use HasFactory;
    protected $table = 'youtube_reviews';  

    protected $fillable = [
        'title',
        'description',
        'video_id',
        'thumbnail_url',
    ];
}
