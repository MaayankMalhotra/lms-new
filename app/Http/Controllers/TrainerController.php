<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\InternshipBatch;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function myCourse()
    {
        $trainer = Batch::with([
            'course',
            'enrollments' => function ($query) {
                $query->with(['user', 'student'])->orderByDesc('id');
            },
        ])->where('teacher_id', auth()->id())->get();

        return view('trainer.my_course', compact('trainer'));
    }

    public function myInternships()
    {
        $internships = InternshipBatch::with('internship')
            ->where('teacher_id', auth()->id())
            ->get()
            ->pluck('internship')
            ->filter()
            ->unique('id')
            ->values();

        return view('trainer.my_internships', compact('internships'));
    }

    public function myLiveClasses()
    {
        $batches = Batch::with([
            'course',
            'liveClasses' => function ($query) {
                $query->orderBy('class_datetime', 'desc');
            },
        ])
            ->where('teacher_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->get();

        return view('trainer.live_classes', compact('batches'));
    }
}
