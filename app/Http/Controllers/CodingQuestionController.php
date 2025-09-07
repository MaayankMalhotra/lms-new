<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\CodingQuestion;
use Illuminate\Http\Request;

class CodingQuestionController extends Controller
{
    public function index()
    {
        $codingQuestions = CodingQuestion::paginate(10);
        return view('admin.coding_questions.index', compact('codingQuestions'));
    }

    public function create()
    {
        return view('admin.coding_questions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'solutions' => 'required|array|min:1',
            'solutions.*' => 'required|string', // Validate each solution
        ]);

        // Save the coding question with solutions as JSON
        CodingQuestion::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'solutions' => $validated['solutions'], // Automatically stored as JSON
        ]);

        return redirect()->route('admin.coding_questions.index')
            ->with('success', 'Coding question created successfully!');
    }

    public function edit($id)
    {
        $codingQuestion = CodingQuestion::findOrFail($id);
        return view('admin.coding_questions.edit', compact('codingQuestion'));
    }

    public function update(Request $request, $id)
    {
        $codingQuestion = CodingQuestion::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'solutions' => 'required|array|min:1',
            'solutions.*' => 'required|string',
        ]);

        $codingQuestion->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'solutions' => $validated['solutions'],
        ]);

        return redirect()->route('admin.coding_questions.index')
            ->with('success', 'Coding question updated successfully!');
    }

    public function destroy($id)
    {
        $codingQuestion = CodingQuestion::findOrFail($id);
        $codingQuestion->delete();

        return redirect()->route('admin.coding_questions.index')
            ->with('success', 'Coding question deleted successfully!');
    }
    public function showSubmissions($id)
{
    $codingQuestion = CodingQuestion::with('submissions.user')->findOrFail($id);
    return view('admin.coding_questions.show_submissions', compact('codingQuestion'));
}

public function deleteSolution(Request $request)
{
    $questionId = $request->query('question_id');
    $solutionValue = $request->query('solution');
    
    $codingQuestion = CodingQuestion::findOrFail($questionId);
    $solutions = $codingQuestion->solutions;
    
    $updatedSolutions = array_filter($solutions, function($value) use ($solutionValue) {
        return $value !== $solutionValue;
    });
    
    $updatedSolutions = array_values($updatedSolutions);
    $codingQuestion->solutions = $updatedSolutions;
    $codingQuestion->save();
    
    return response()->json(['message' => 'Solution deleted successfully']);
}
}
