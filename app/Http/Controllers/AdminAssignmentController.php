<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAssignmentController extends Controller
{
    public function create()
    {
        $liveClasses = Batch::all(); // Fetch all live classes for dropdown
        return view('admin.assignment.assignment', compact('liveClasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'live_class_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        Assignment::create([
            'batch_id' => $request->live_class_id,
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

    public function index()
    {
        // Fetch batches that the authenticated student is enrolled in, with course details
        $batches = DB::select('
            SELECT b.id, b.course_id, b.start_date, c.name as course_name
            FROM batches b
            INNER JOIN courses c ON b.course_id = c.id
            
            
        ',);

        // Convert batches to a collection for easier handling in the view
        $batches = collect($batches)->map(function ($batch) {
            return (object) [
                'id' => $batch->id,
                'course' => (object) ['name' => $batch->course_name],
                'start_date' => \Carbon\Carbon::parse($batch->start_date),
            ];
        });

        return view('admin.assignment.assignment_all', compact('batches'));
    }

    public function getAssignmentsByBatch($batchId)
    {
        
        // Fetch assignments for the given batch_id, with submission details for the authenticated student
        $assignments = DB::select('
            SELECT 
                a.id, 
                a.title, 
                a.description, 
                a.due_date, 
                a.created_at, 
                a.file_path,
                s.id as submission_id,
                s.file_path as submission_file_path,
                s.created_at as submitted_at
            FROM assignments a
            INNER JOIN batches b ON a.batch_id = b.id
            LEFT JOIN assignment_submissions s ON a.id = s.assignment_id 
            WHERE a.batch_id = ?
        ', [ $batchId]);

          $groupedAssignments = collect($assignments)->groupBy('id')->map(function ($assignmentGroup) {
            $first = $assignmentGroup->first();
            return (object) [
                'id' => $first->id,
                'title' => $first->title,
                'description' => $first->description,
                'due_date' => $first->due_date,
                'created_at' => $first->created_at,
                'file_path' => $first->file_path,
                'submissions' => $assignmentGroup->filter(function ($item) {
                    return $item->submission_id !== null;
                })->map(function ($item) {
                    return (object) [
                        'id' => $item->submission_id,
                        'file_path' => $item->submission_file_path,
                        'submitted_at' => $item->submitted_at,
                    ];
                })->values()->all(),
            ];
        })->values();

        return response()->json($groupedAssignments);
    }

    public function download($assignmentId)
    {
        // Fetch the assignment's file_path
        $assignment = DB::selectOne('
            SELECT file_path
            FROM assignments
            WHERE id = ?
        ', [$assignmentId]);

        if (!$assignment || !$assignment->file_path) {
            abort(404, 'File not found');
        }

        // Get the full path to the file
        $filePath = storage_path('app/public/' . $assignment->file_path);

        if (!Storage::exists('public/' . $assignment->file_path)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath);
    }
}
