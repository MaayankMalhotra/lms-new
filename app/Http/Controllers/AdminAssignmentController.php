<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminAssignmentController extends Controller
{
    public function create()
    {
        $courses = Course::orderBy('name')->get();
        $batchesByCourse = Batch::select('id', 'course_id', 'batch_name', 'start_date')
            ->orderBy('start_date', 'desc')
            ->get()
            ->groupBy('course_id')
            ->map(function ($collection) {
                return $collection->map(function ($batch) {
                    return [
                        'id' => $batch->id,
                        'label' => $batch->batch_name ?? 'Batch #' . $batch->id,
                        'start_date' => optional($batch->start_date)->format('d M Y'),
                    ];
                })->values();
            })->toArray();

        return view('admin.assignment.assignment', [
            'courses' => $courses,
            'batchesByCourse' => $batchesByCourse,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_id' => 'required|exists:batches,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        $batch = Batch::where('id', $request->batch_id)
            ->where('course_id', $request->course_id)
            ->first();

        if (!$batch) {
            return back()
                ->withErrors(['batch_id' => 'Batch does not belong to the selected course.'])
                ->withInput();
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        Assignment::create([
            'course_id' => $request->course_id,
            'batch_id' => $batch->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'file_path' => $filePath,
        ]);

        return redirect()->route('admin.assignments.create')->with('success', 'Assignment uploaded successfully!');
    }

//   public function index()
//     {
//         // Fetch batches that the authenticated student is enrolled in, with course details
//         $batches = DB::select('
//             SELECT b.id, b.course_id, b.start_date, c.name as course_name
//             FROM batches b
//             INNER JOIN courses c ON b.course_id = c.id
            
            
//         ');

//         // Convert batches to a collection for easier handling in the view
//         $batches = collect($batches)->map(function ($batch) {
//             return (object) [
//                 'id' => $batch->id,
//                 'course' => (object) ['name' => $batch->course_name],
//                 'start_date' => \Carbon\Carbon::parse($batch->start_date),
//             ];
//         });

//         return view('admin.assignment.assignment_all', compact('batches'));
//     }

    // public function getAssignmentsByBatch($batchId)
    // {
    //     // Fetch assignments for the given batch_id, accessible to the authenticated student
    //     $assignments = DB::select('
    //         SELECT a.id, a.title, a.description, a.due_date, a.created_at, a.file_path
    //         FROM assignments a
    //         INNER JOIN batches b ON a.batch_id = b.id
            
    //         WHERE a.batch_id = ? 
    //     ', [$batchId]);

    //     // Convert assignments to a collection for consistent JSON response
    //     $assignments = collect($assignments)->map(function ($assignment) {
    //         return (object) [
    //             'id' => $assignment->id,
    //             'title' => $assignment->title,
    //             'description' => $assignment->description,
    //             'due_date' => $assignment->due_date,
    //             'created_at' => $assignment->created_at,
    //                'file_path' => $assignment->file_path,
    //         ];
    //     });

    //     return response()->json($assignments);
    // }

    //  public function download($assignmentId)
    // {
    //     // Fetch the assignment's file_path
    //     $assignment = DB::selectOne('
    //         SELECT file_path
    //         FROM assignments
    //         WHERE id = ?
    //     ', [$assignmentId]);

    //     if (!$assignment || !$assignment->file_path) {
    //         abort(404, 'File not found');
    //     }

    //     // Get the full path to the file
    //     $filePath = storage_path('app/public/' . $assignment->file_path);

    //     if (!Storage::exists('public/' . $assignment->file_path)) {
    //         abort(404, 'File not found');
    //     }

    //     return response()->download($filePath);
    // }

    public function adminIndex()
    {
        $assignments = DB::table('assignments as a')
            ->leftJoin('courses as c', 'a.course_id', '=', 'c.id')
            ->select('a.id', 'a.title', 'a.description', 'a.due_date', 'a.file_path', 'c.name as course_name')
            ->orderBy('a.due_date', 'desc')
            ->get();

        $submissionsByAssignment = DB::table('assignment_submissions as s')
            ->leftJoin('users as u', 's.user_id', '=', 'u.id')
            ->select('s.*', 'u.name as student_name', 'u.email as student_email')
            ->orderBy('s.created_at', 'desc')
            ->get()
            ->groupBy('assignment_id');

        return view('admin.assignment.index', compact('assignments', 'submissionsByAssignment'));
    }

    public function getAssignmentsByCourse(Request $request, $courseId)
    {
        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
        ]);

        $query = Assignment::with(['batch:id,batch_name,start_date', 'course:id,name'])
            ->where('course_id', $courseId)
            ->orderBy('due_date', 'desc');

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $assignments = $query->get()->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'due_date' => optional($assignment->due_date)->toIso8601String(),
                'file_url' => $assignment->file_url,
                'batch' => $assignment->batch ? [
                    'id' => $assignment->batch->id,
                    'name' => $assignment->batch->batch_name ?? ('Batch #' . $assignment->batch->id),
                    'start_date' => optional($assignment->batch->start_date)->format('d M Y'),
                ] : null,
                'course' => $assignment->course ? [
                    'id' => $assignment->course->id,
                    'name' => $assignment->course->name,
                ] : null,
            ];
        });

        return response()->json($assignments);
    }

    public function setSubmissionMark(Request $request, $submissionId)
    {
        $validated = $request->validate([
            'marks' => 'nullable|integer|min:0',
            'status' => ['required', Rule::in(['submitted', 'approved', 'needs_resubmission', 'expired'])],
            'feedback' => 'nullable|string|max:2000',
        ]);

        $update = [
            'marks' => $validated['marks'],
            'status' => $validated['status'],
            'feedback' => $validated['feedback'],
            'reviewed_at' => now(),
            'updated_at' => now(),
        ];

        if ($validated['status'] === 'needs_resubmission') {
            $update['marks'] = null; // reset marks until the student re-uploads
        }

        if ($validated['status'] === 'expired') {
            $update['marks'] = 0; // ensure expiry marks are zeroed out
        }

        DB::table('assignment_submissions')
            ->where('id', $submissionId)
            ->update($update);

        return redirect()->back()->with('success', 'Submission updated.');
    }
}
