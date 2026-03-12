<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'experience',
        'teaching_hours',
        'course_ids',
    ];

    protected $casts = [
        'course_ids' => 'array', // Automatically decode JSON to array
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCourseNamesAttribute()
    {
        if (empty($this->course_ids)) {
            return 'None';
        }

        $courseIds = $this->course_ids;

        if (is_string($courseIds)) {
            $decoded = json_decode($courseIds, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $courseIds = $decoded;
            } else {
                $courseIds = explode(',', $courseIds);
            }
        }

        if (!is_array($courseIds)) {
            return 'None';
        }

        if (count($courseIds) === 1 && is_string($courseIds[0]) && str_contains($courseIds[0], ',')) {
            $courseIds = explode(',', $courseIds[0]);
        }

        $courseIds = array_values(array_filter(array_map(static function ($value) {
            if (is_numeric($value)) {
                return (int) $value;
            }

            if (is_string($value) && ctype_digit(trim($value))) {
                return (int) trim($value);
            }

            return null;
        }, $courseIds), static fn ($id) => $id !== null && $id > 0));

        if (empty($courseIds)) {
            return 'None';
        }

        $courses = Course::whereIn('id', $courseIds)->pluck('name')->toArray();

        return !empty($courses) ? implode(', ', $courses) : 'None';
    }
}
