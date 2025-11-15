<?php

namespace App\Http\Controllers;

use App\Models\QuizSet;
use App\Models\StudentQuizSetAttempt;
use App\Models\StudentQuizAnswer;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentQuizController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $enrolledBatches = $student->enrollments()->pluck('batch_id');

        $quizSets = QuizSet::with(['batch.course'])
            ->whereIn('batch_id', $enrolledBatches)
            ->get();

        $attempts = $student->studentQuizSetAttempts()
            ->with('answers')
            ->whereIn('quiz_set_id', $quizSets->pluck('id'))
            ->get()
            ->keyBy('quiz_set_id');

        return view('student.quiz_sets.index', compact('quizSets', 'student', 'attempts'));
    }

    public function takeQuiz($id)
    {
        $student = Auth::user();
        $quizSet = QuizSet::with('quizzes')->findOrFail($id);
        // Check enrollment
        $enrolledBatches = $student->enrollments()->pluck('batch_id');
        if (!$enrolledBatches->contains($quizSet->batch_id)) {
            return redirect()->route('student.quiz_sets')->with('error', 'You are not enrolled in this batch!');
        }
        // Check if already attempted
        if ($student->studentQuizSetAttempts()->where('quiz_set_id', $id)->exists()) {
            return redirect()->route('student.quiz_sets')->with('error', 'You have already taken this quiz set!');
        }
        return view('student.quiz_sets.take', compact('quizSet'));
    }
    public function batchQuizRanking(Request $request)
    {
        // Get the selected batch ID and quiz set ID from the request
        $batchId = $request->input('batch_id');
        $selectedQuizSetId = $request->input('quiz_set_id');
        $showNonAttempted = $request->input('show_non_attempted', false); // New filter
    
        // Get all batches for the dropdown
        $batches = \App\Models\Batch::with('course')->get();
    
        // If no batch is selected, set defaults
        if (!$batchId) {
            $quizSets = collect(); // Empty collection
            $studentResults = [];
            $batch = null;
            $selectedQuizSetId = null;
        } else {
            // Get all quiz sets for the selected batch
            $quizSets = QuizSet::where('batch_id', $batchId)->get();
    
            // If the selected quiz set ID doesn't belong to the current batch, reset it
            if ($selectedQuizSetId && !$quizSets->contains('id', $selectedQuizSetId)) {
                $selectedQuizSetId = null;
            }
    
            // Get enrolled students for the batch
            $enrolledStudentIds = \App\Models\Enrollment::where('batch_id', $batchId)
                ->pluck('user_id');
    
            // If "Show only non-attempted" is selected
            if ($showNonAttempted) {
                // Get students who haven't attempted any quiz in this batch
                $attemptedStudentIds = StudentQuizSetAttempt::whereIn('quiz_set_id', $quizSets->pluck('id'))
                    ->pluck('user_id')
                    ->unique();
    
                // Students who are enrolled but haven't attempted
                $nonAttemptedStudentIds = $enrolledStudentIds->diff($attemptedStudentIds);
    
                // Fetch details of non-attempted students
                $studentResults = \App\Models\User::whereIn('id', $nonAttemptedStudentIds)
                    ->get()
                    ->map(function ($student) use ($quizSets, $selectedQuizSetId) {
                        return (object) [
                            'student_name' => $student->name,
                            'quiz_set_title' => $selectedQuizSetId ? $quizSets->firstWhere('id', $selectedQuizSetId)->title : 'N/A',
                            'score' => 0,
                            'total_quizzes' => $selectedQuizSetId ? $quizSets->firstWhere('id', $selectedQuizSetId)->total_quizzes : $quizSets->sum('total_quizzes'),
                            'percentage' => 0.00,
                        ];
                    })->sortByDesc('percentage')->values();
            } else {
                // Original logic for showing all students (with attempts)
                $query = "
                    SELECT 
                        users.name AS student_name,
                        quiz_sets.title AS quiz_set_title,
                        student_quiz_set_attempts.score,
                        quiz_sets.total_quizzes,
                        (student_quiz_set_attempts.score / quiz_sets.total_quizzes * 100) AS percentage
                    FROM 
                        student_quiz_set_attempts
                    JOIN 
                        users ON student_quiz_set_attempts.user_id = users.id
                    JOIN 
                        quiz_sets ON student_quiz_set_attempts.quiz_set_id = quiz_sets.id
                    WHERE 
                        quiz_sets.batch_id = ?
                ";
    
                $params = [$batchId];
                if ($selectedQuizSetId) {
                    $query .= " AND student_quiz_set_attempts.quiz_set_id = ?";
                    $params[] = $selectedQuizSetId;
                }
    
                $query .= " ORDER BY percentage DESC";
    
                $studentResults = DB::select($query, $params);
                $studentResults = array_map(function ($result) {
                    return (object) $result;
                }, $studentResults);
            }
    
            $batch = \App\Models\Batch::with('course')->findOrFail($batchId);
        }
    
        return view('student.quiz_sets.batch_ranking', compact('studentResults', 'quizSets', 'batch', 'selectedQuizSetId', 'batches', 'batchId', 'showNonAttempted'));
    }
    // public function batchQuizRanking(Request $request)
    // {
    //     // Get the selected batch ID and quiz set ID from the request
    //     $batchId = $request->input('batch_id');
    //     $selectedQuizSetId = $request->input('quiz_set_id');
    
    //     // Get all batches for the dropdown
    //     $batches = \App\Models\Batch::with('course')->get();
    
    //     // If no batch is selected, set defaults
    //     if (!$batchId) {
    //         $quizSets = collect(); // Empty collection
    //         $studentResults = [];
    //         $batch = null;
    //         $selectedQuizSetId = null;
    //     } else {
    //         // Get all quiz sets for the selected batch
    //         $quizSets = QuizSet::where('batch_id', $batchId)->get();
    
    //         // If the selected quiz set ID doesn't belong to the current batch, reset it
    //         if ($selectedQuizSetId && !$quizSets->contains('id', $selectedQuizSetId)) {
    //             $selectedQuizSetId = null;
    //         }
    
    //         // Build the query for rankings
    //         $query = "
    //             SELECT 
    //                 users.name AS student_name,
    //                 quiz_sets.title AS quiz_set_title,
    //                 student_quiz_set_attempts.score,
    //                 quiz_sets.total_quizzes,
    //                 (student_quiz_set_attempts.score / quiz_sets.total_quizzes * 100) AS percentage
    //             FROM 
    //                 student_quiz_set_attempts
    //             JOIN 
    //                 users ON student_quiz_set_attempts.user_id = users.id
    //             JOIN 
    //                 quiz_sets ON student_quiz_set_attempts.quiz_set_id = quiz_sets.id
    //             WHERE 
    //                 quiz_sets.batch_id = ?
    //         ";
    
    //         $params = [$batchId];
    //         if ($selectedQuizSetId) {
    //             $query .= " AND student_quiz_set_attempts.quiz_set_id = ?";
    //             $params[] = $selectedQuizSetId;
    //         }
    
    //         $query .= " ORDER BY percentage DESC";
    
    //         // Execute the query
    //         $studentResults = DB::select($query, $params);
    
    //         // Convert array of arrays to array of objects
    //         $studentResults = array_map(function ($result) {
    //             return (object) $result;
    //         }, $studentResults);
    
    //         $batch = \App\Models\Batch::with('course')->findOrFail($batchId);
    //     }
    
    //     return view('student.quiz_sets.batch_ranking', compact('studentResults', 'quizSets', 'batch', 'selectedQuizSetId', 'batches', 'batchId'));
    // }
    public function batchQuizRanking_old($batchId)
{
    $quizSets = QuizSet::where('batch_id', $batchId)->pluck('id');
    $quizIds = Quiz::whereIn('quiz_set_id', $quizSets)->pluck('id');

    $studentResults = StudentQuizAnswer::whereIn('quiz_id', $quizIds)
        ->with(['quiz.quizSet'])
        ->get()
        ->groupBy('user_id') // Ab user_id se group karo
        ->map(function ($answers) {
            $score = $answers->sum(function ($answer) {
                return $answer->student_answer == $answer->quiz->correct_option ? 1 : 0;
            });
            $totalQuizzes = $answers->first()->quiz->quizSet->total_quizzes;
            $student = User::find($answers->first()->user_id);

            return [
                'student_name' => $student->name,
                'quiz_set_title' => $answers->first()->quiz->quizSet->title,
                'score' => $score,
                'total_quizzes' => $totalQuizzes,
                'percentage' => ($score / $totalQuizzes) * 100
            ];
        })
        ->sortByDesc('percentage')
        ->values();

    $batch = \App\Models\Batch::with('course')->findOrFail($batchId);

    return view('student.quiz_sets.batch_ranking', compact('studentResults', 'batch'));
}
public function submitQuiz(Request $request, $id)
{
    $student = Auth::user();
    $quizSet = QuizSet::with('quizzes')->findOrFail($id);

    $enrolledBatches = $student->enrollments()->pluck('batch_id');
    if (!$enrolledBatches->contains($quizSet->batch_id)) {
        return redirect()->route('student.quiz_sets')->with('error', 'Unauthorized!');
    }
    if ($student->studentQuizSetAttempts()->where('quiz_set_id', $id)->exists()) {
        return redirect()->route('student.quiz_sets')->with('error', 'You have already taken this quiz set!');
    }

    $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'required|integer|between:1,4',
    ]);

    $answers = $request->input('answers');
    $correctCount = 0;

    $attempt = StudentQuizSetAttempt::create([
        'user_id' => $student->id,
        'quiz_set_id' => $quizSet->id,
        'score' => 0,
    ]);

    foreach ($quizSet->quizzes as $quiz) {
        $studentAnswer = $answers[$quiz->id] ?? null;
        if ($studentAnswer) {
            StudentQuizAnswer::create([
                'attempt_id' => $attempt->id,
                'quiz_id' => $quiz->id,
                'student_answer' => $studentAnswer,
                'user_id' => $student->id,
            ]);
            if ($studentAnswer == $quiz->correct_option) {
                $correctCount++;
            }
        }
    }

    $attempt->update(['score' => $correctCount]);
    return redirect()->route('student.quiz_attempt', $attempt->id)
        ->with('success', "Quiz submitted! You scored $correctCount out of {$quizSet->total_quizzes}.");
}

           // extra code
            public function viewAttempt($attemptId)
            {
                $student = Auth::user();
                
                // Attempt fetch karo with quiz set aur answers
                $attempt = StudentQuizSetAttempt::with([
                    'quizSet.quizzes',
                    'answers.quiz'
                ])->where('user_id', $student->id)
                ->findOrFail($attemptId);

                // Check karo ki yeh attempt student ka hai
                if ($attempt->user_id !== $student->id) {
                    return redirect()->route('student.quiz_sets')->with('error', 'Bhai, tu is attempt ko nahi dekh sakta!');
                }

                // Data ko Blade page pe bhejo
                return view('student.quiz_attempt', compact('attempt'));
            }
   }
