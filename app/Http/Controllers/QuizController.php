<?php

namespace App\Http\Controllers;

use App\Models\QuizSet;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
       // dd(Auth::id());
        $quizSets = QuizSet::where('teacher_id', Auth::id())->get();
       // dd($quizSets);
        return view('admin.quiz_sets.index', compact('quizSets'));
    }

    public function createSet()
    {
        $batches = \App\Models\Batch::with('course')->orderBy('id')->get();
        
        // Array banayo: batch_id => "Course Name - Batch Name (Start Date)"
        $batchOptions = [];
        foreach ($batches as $batch) {
            $label = "{$batch->course->name} - {$batch->name} ({$batch->start_date})";
            $batchOptions[$batch->id] = $label;
        }
    
        return view('admin.quiz_sets.create', compact('batchOptions'));
    }

    public function storeSet(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'total_quizzes' => 'required|integer|min:1',
            'batch_id' => 'required|exists:batches,id', // Batch ID validate karo
        ]);

        QuizSet::create([
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'total_quizzes' => $request->total_quizzes,
            'batch_id' => $request->batch_id, // Batch ID save karo
        ]);

        return redirect()->route('admin.quiz_sets')->with('success', 'Quiz Set created!');
    }

    public function addQuizzes($id)
    {
        $quizSet = QuizSet::findOrFail($id);
        return view('admin.quiz_sets.add_quizzes', compact('quizSet'));
    }

    public function storeQuizzes(Request $request, $id)
    {
        $quizSet = QuizSet::findOrFail($id);
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|string',
            'options' => 'required|array',
            'options.*' => 'required|array|size:4',
            'correct_options' => 'required|array',
            'correct_options.*' => 'required|integer|between:1,4',
        ]);

        foreach ($request->questions as $index => $question) {
            Quiz::create([
                'quiz_set_id' => $quizSet->id,
                'question' => $question,
                'option_1' => $request->options[$index][0],
                'option_2' => $request->options[$index][1],
                'option_3' => $request->options[$index][2],
                'option_4' => $request->options[$index][3],
                'correct_option' => $request->correct_options[$index],
            ]);
        }

        return redirect()->route('admin.quiz_sets')->with('success', 'Quizzes added to set!');
    }
    public function showQuizzes($id)
    {
        $quizSet = QuizSet::with(['quizzes', 'batch.course'])->findOrFail($id);
       
        return view('admin.quiz_sets.show_quizzes', compact('quizSet'));
    }
public function editSet($id)
{
    $quizSet = QuizSet::findOrFail($id);
    

    $batches = \App\Models\Batch::with('course')->orderBy('start_date')->get(); // Start_date se sort kiya, name nahi hai
    $batchOptions = [];
    foreach ($batches as $batch) {
        $label = "{$batch->course->name} - Batch ({$batch->start_date})"; // Name nahi hai toh "Batch" static rakha
        $batchOptions[$batch->id] = $label;
    }

    return view('admin.quiz_sets.edit', compact('quizSet', 'batchOptions'));
}

// Update Quiz Set
public function updateSet(Request $request, $id)
{
    $quizSet = QuizSet::findOrFail($id);
   
    $request->validate([
        'title' => 'required|string|max:255',
        'total_quizzes' => 'required|integer|min:1',
        'batch_id' => 'required|exists:batches,id', // Batch ID validate karo
    ]);

    $quizSet->update([
        'title' => $request->title,
        'total_quizzes' => $request->total_quizzes,
        'batch_id' => $request->batch_id, // Batch ID update karo
    ]);

    return redirect()->route('admin.quiz_sets')->with('success', 'Quiz Set updated successfully!');
}

// Delete Quiz Set
public function deleteSet($id)
{
    $quizSet = QuizSet::findOrFail($id);
   
    $quizSet->delete();
    return redirect()->route('admin.quiz_sets')->with('success', 'Quiz Set deleted successfully!');
}

// Edit Quiz
public function editQuiz($id)
{
    $quiz = Quiz::with('quizSet')->findOrFail($id);
   
    return view('admin.quiz_sets.edit_quiz', compact('quiz'));
}

// Update Quiz
public function updateQuiz(Request $request, $id)
{
    $quiz = Quiz::with('quizSet')->findOrFail($id);
    
    $request->validate([
        'question' => 'required|string',
        'option_1' => 'required|string',
        'option_2' => 'required|string',
        'option_3' => 'required|string',
        'option_4' => 'required|string',
        'correct_option' => 'required|integer|between:1,4',
    ]);

    $quiz->update([
        'question' => $request->question,
        'option_1' => $request->option_1,
        'option_2' => $request->option_2,
        'option_3' => $request->option_3,
        'option_4' => $request->option_4,
        'correct_option' => $request->correct_option,
    ]);

    return redirect()->route('admin.quiz_sets.show_quizzes', $quiz->quiz_set_id)
        ->with('success', 'Quiz updated successfully!');
}

// Delete Quiz
public function deleteQuiz($id)
{
    $quiz = Quiz::with('quizSet')->findOrFail($id);
    
    $quizSetId = $quiz->quiz_set_id;
    $quiz->delete();
    return redirect()->route('admin.quiz_sets.show_quizzes', $quizSetId)
        ->with('success', 'Quiz deleted successfully!');
}
public function bulkUpload(Request $request, $quizSetId)
{
    // Validate the request
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:2048', // Max 2MB
    ]);

    // Find the quiz set
    $quizSet = QuizSet::findOrFail($quizSetId);

    // Check if the user is authorized (optional)
    if ($quizSet->teacher_id !== Auth::id()) {
        return redirect()->route('admin.quiz_sets')->with('error', 'Unauthorized!');
    }

    // Process the CSV file
    if ($request->hasFile('csv_file')) {
        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Open and read the CSV file
        $csvData = array_map('str_getcsv', file($path));

        // Skip the header row
        $header = array_shift($csvData);

        // Expected header
        $expectedHeader = ['question', 'option_1', 'option_2', 'option_3', 'option_4', 'correct_option'];

        // Validate header
        if ($header !== $expectedHeader) {
            return redirect()->back()->with('error', 'Invalid CSV format. Expected columns: question, option_1, option_2, option_3, option_4, correct_option');
        }

        // Process each row
        $errors = [];
        foreach ($csvData as $index => $row) {
            // Ensure the row has the correct number of columns
            if (count($row) !== 6) {
                $errors[] = "Row " . ($index + 2) . ": Invalid number of columns.";
                continue;
            }

            // Extract data
            $question = $row[0];
            $option1 = $row[1];
            $option2 = $row[2];
            $option3 = $row[3];
            $option4 = $row[4];
            $correctOption = (int) $row[5];

            // Validate data
            if (empty($question) || empty($option1) || empty($option2) || empty($option3) || empty($option4)) {
                $errors[] = "Row " . ($index + 2) . ": All fields are required.";
                continue;
            }

            if ($correctOption < 1 || $correctOption > 4) {
                $errors[] = "Row " . ($index + 2) . ": Correct option must be between 1 and 4.";
                continue;
            }

            // Create the quiz
            Quiz::create([
                'quiz_set_id' => $quizSet->id,
                'question' => $question,
                'option_1' => $option1,
                'option_2' => $option2,
                'option_3' => $option3,
                'option_4' => $option4,
                'correct_option' => $correctOption,
            ]);
        }

        // If there are errors, return them
        if (!empty($errors)) {
            return redirect()->back()->with('error', implode('<br>', $errors));
        }

        return redirect()->route('admin.quiz_sets')->with('success', 'Quizzes uploaded successfully!');
    }

    return redirect()->back()->with('error', 'No file uploaded.');
}
}