<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Student;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Event;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
       
        $userId = auth()->user()->id;
        
        $studentBatches = DB::table('users')
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
            ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
            ->where('users.role', 3)
            ->where('users.id', $userId) // 👈 filter for current student only
            ->select(
                'users.id as user_id',
                'users.name as student_name',
                'batches.id as batch_id',
                'batches.batch_name as batch_name',
                'enrollments.created_at as enrolled_on'
            )
            ->get();
            
$totalAssignments = DB::table('users')
    ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
    ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
    ->join('assignments', 'batches.id', '=', 'assignments.batch_id')
    ->where('users.id', $userId)
    ->distinct('assignments.id') // 👈 unique assignments only
    ->count('assignments.id');   // 👈 count unique ids



$quizSets = DB::table('users')
    ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
    ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
    ->join('quiz_sets', 'batches.id', '=', 'quiz_sets.batch_id')
    ->where('users.id', $userId)
    ->select('quiz_sets.*')
    ->distinct()
    ->get();

$payments = DB::table('payments')
    ->where('user_id', $userId)
    ->select(
        DB::raw("SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_completed"),
        DB::raw("SUM(CASE WHEN status != 'completed' THEN amount ELSE 0 END) as total_pending")
    )
    ->first();


       //dd($payments);

      
$events = DB::table('events')->get();

      
      //  $quizzes     = Quiz::where('batch_id', $student->batch_id)->where('status', 'pending')->count();
        //$exams       = Exam::where('batch_id', $student->batch_id)->orderBy('date', 'asc')->take(3)->get();
       // $fees        = Fee::where('student_id', $student->id)->latest()->first();
       // $events      = Event::orderBy('event_date', 'asc')->take(3)->get();

       return view('student.dashboard', [
    'studentBatches'   => $studentBatches,
    'totalAssignments' => $totalAssignments,
    'quizSets'         => $quizSets,
    'payments'         => $payments,
    'events'           => $events
]);
    }
}
