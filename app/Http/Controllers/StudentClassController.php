<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Batch;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\LiveClass;
use App\Models\Recording;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentClassController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $enrollments = $student->enrollments()->where('status', 'active')->get();

        $upcomingClasses = collect();
        $ongoingClasses = collect();
        $endedClasses = collect();

        foreach ($enrollments as $enrollment) {
            $liveClasses = $enrollment->liveClasses()->where('status', 'Scheduled')->get();
           
            foreach ($liveClasses as $class) {
                if ($class->isUpcoming()) {
                    $upcomingClasses->push($class);
                } elseif ($class->isOngoing()) {
                    $ongoingClasses->push($class);
                } elseif ($class->isEnded()) {
                    $endedClasses->push($class);
                }
            }
        }
        return view('student.classes.index', compact('upcomingClasses', 'ongoingClasses', 'endedClasses'));
    }
      public function indexInt()
    {
        $student = Auth::user();
        $enrollments = $student->enrollmentsInt()->where('status', 'active')->get();

        $upcomingClasses = collect();
        $ongoingClasses = collect();
        $endedClasses = collect();

        foreach ($enrollments as $enrollment) {
            $liveClasses = $enrollment->liveClasses()->where('status', 'Scheduled')->get();
           
            foreach ($liveClasses as $class) {
                if ($class->isUpcoming()) {
                    $upcomingClasses->push($class);
                } elseif ($class->isOngoing()) {
                    $ongoingClasses->push($class);
                } elseif ($class->isEnded()) {
                    $endedClasses->push($class);
                }
            }
        }
        return view('student.classes.index', compact('upcomingClasses', 'ongoingClasses', 'endedClasses'));
    }

    public function joinClass($liveClassId)
    {
        $student = Auth::user();
        $liveClass = LiveClass::findOrFail($liveClassId);

        $now = Carbon::now();
        $classStart = Carbon::parse($liveClass->class_datetime);
        $classEnd = $classStart->copy()->addMinutes($liveClass->duration_minutes);

        if (!$liveClass->isOngoing()) {
            return redirect()->route('student.dashboard')->with('error', 'You can only join the class during its scheduled time.');
        }

        if (!$liveClass->hasAttended($student->id)) {
            Attendance::create([
                'user_id' => $student->id,
                'live_class_id' => $liveClass->id,
                'date' => $now->toDateString(),
            ]);
        }

        return redirect($liveClass->google_meet_link);
    }

    // Fetch recordings for the student's course or batch
    // public function recordings()
    // {
    //     // Assuming the authenticated student has a course_id or batch_id
    //     $batchId = Auth::user()->id; // Adjust this based on your User model
    //     $batchid = Enrollment::where('user_id', $batchId)->first();
    //     $recordings = Recording::whereHas('liveClass', function ($query) use ($batchid) {
    //         $query->where('batch_id', $batchid->batch_id);
    //     })->orderBy('created_at', 'desc')->get();
    //    // dd($recordings);
    //     return view('student.recordings.recoring', compact('recordings'));
    // }

        public function recordings()
    {
        // Get the authenticated student's ID
        $studentId = Auth::user()->id;

        // Get the student's batch ID from the Enrollment model
        $enrollment = Enrollment::where('user_id', $studentId)->first();
        if (!$enrollment) {
            return view('student.recordings.recording', ['recordings' => []])->with('error', 'No enrollment found');
        }
        $batchId = $enrollment->batch_id;

        // Get the course_id from the batch
        $batch = Batch::findOrFail($batchId);
        $courseId = $batch->course_id;

        // Fetch recordings where the folder's course_id matches, and both are unlocked
        $recordings = Recording::with(['topic.folder'])
            ->whereHas('topic.folder', function ($query) use ($courseId) {
                $query->where('course_id', $courseId)->where('locked', '0');
            })
            ->where('locked', '0')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.recordings.recording', compact('recordings'));
    }

    public function assignment(){
        $batchId = Enrollment::where('user_id', Auth::id())->first();
        $liveClasses = DB::table('batches')
            ->leftJoin('assignments', 'batches.id', '=', 'assignments.batch_id')
            ->where('batches.id', $batchId->batch_id)
            ->select('batches.*', 'assignments.*')
            ->orderBy('batches.id', 'asc')
            ->get();
             dd($liveClasses);
           return view('student.assignment.assignment', compact('liveClasses'));
    }
}
