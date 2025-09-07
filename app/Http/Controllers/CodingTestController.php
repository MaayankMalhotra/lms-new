<?php
namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\CodingQuestion;
use App\Models\CodingSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CodingTestController extends Controller
{
    public function index()
    {
        $codingQuestions = CodingQuestion::all();
        return view('student.coding_tests.index', compact('codingQuestions'));
    }

    public function show($id)
    {
        $codingQuestion = CodingQuestion::findOrFail($id);
        $submission = CodingSubmission::where('user_id', Auth::id())
            ->where('coding_question_id', $id)
            ->latest()
            ->first(); // Get the latest submission for this question by the student
        return view('student.coding_tests.show', compact('codingQuestion', 'submission'));
    }

    public function submit(Request $request, $id)
    {
        $codingQuestion = CodingQuestion::findOrFail($id);

        $request->validate([
            'submitted_solution' => 'required|string',
        ]);

        $submittedSolution = trim($request->input('submitted_solution'));
        $isCorrect = in_array($submittedSolution, array_map('trim', $codingQuestion->solutions));

        $submission = CodingSubmission::create([
            'user_id' => Auth::id(),
            'coding_question_id' => $id,
            'submitted_solution' => $submittedSolution,
            'is_correct' => $isCorrect,
        ]);

        if ($isCorrect) {
            return redirect()->back()->with('success', 'Congratulations! Your solution is correct.');
        } else {
            return redirect()->back()->with('error', 'Sorry, your solution is incorrect. Please try again.');
        }
    }
}