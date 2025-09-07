<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
  public function assignment()
    {
        // Get batch_id for the authenticated user
        $batch = DB::selectOne('SELECT batch_id FROM enrollments WHERE user_id = ? LIMIT 1', [Auth::id()]);
        
        if (!$batch) {
            return view('student.assignment.assignment', ['liveClasses' => []]);
        }

        // Fetch assignments with submission details for the authenticated user
        $assignments = DB::select("
            SELECT b.id AS batch_id,
                   a.id AS assignment_id,
                   a.title AS assignment_title,
                   a.description AS assignment_description,
                   a.due_date AS assignment_due_date,
                   a.file_path AS assignment_file_path,
                   asub.file_path AS submission_file_path,
                   asub.id AS submission_id
            FROM batches b
            LEFT JOIN assignments a ON b.id = a.batch_id
            LEFT JOIN assignment_submissions asub ON a.id = asub.assignment_id AND asub.user_id = ?
            WHERE b.id = ?
            ORDER BY a.due_date ASC
        ", [Auth::id(), $batch->batch_id]);

        // Group results to structure like Eloquent collections
        $liveClasses = collect($assignments)->groupBy('batch_id')->map(function ($items) {
            $first = $items->first();
            $batch = (object) [
                'id' => $first->batch_id,
                'assignments' => $items->filter(function ($item) {
                    return !is_null($item->assignment_id);
                })->map(function ($item) {
                    return (object) [
                        'id' => $item->assignment_id,
                        'title' => $item->assignment_title,
                        'description' => $item->assignment_description,
                        'due_date' => $item->assignment_due_date,
                        'file_path' => $item->assignment_file_path,
                        'file_url' => $item->assignment_file_path ? Storage::url($item->assignment_file_path) : null,
                        'submission_file_path' => $item->submission_file_path,
                        'submission_file_url' => $item->submission_file_path ? Storage::url($item->submission_file_path) : null,
                        'has_submission' => !is_null($item->submission_id),
                    ];
                })->values(),
            ];
            return $batch;
        })->values();

        return view('student.assignment.assignment', compact('liveClasses'));
    }

        public function assignmentInt()
    {
        // Get batch_id for the authenticated user
        $batch = DB::selectOne('SELECT batch_id FROM enrollments WHERE user_id = ? LIMIT 1', [Auth::id()]);
        
        if (!$batch) {
            return view('student.assignment.assignment', ['liveClasses' => []]);
        }

        // Fetch live classes with assignments and submission details for the authenticated user
        $liveClasses = DB::select("
            SELECT lc.id AS live_class_id, 
                   lc.batch_id, 
                   lc.topic, 
                   lc.class_datetime, 
                   a.id AS assignment_id, 
                   a.title AS assignment_title, 
                   a.description AS assignment_description, 
                   a.due_date AS assignment_due_date, 
                   a.file_path AS assignment_file_path,
                   asub.file_path AS submission_file_path,
                   asub.id AS submission_id
            FROM live_classes lc
            LEFT JOIN assignments a ON lc.id = a.live_class_id
            LEFT JOIN assignment_submissions asub ON a.id = asub.assignment_id AND asub.user_id = ?
            WHERE lc.batch_id = ?
            ORDER BY lc.class_datetime ASC
        ", [Auth::id(), $batch->batch_id]);
//dd($liveClasses);
        // Group results to structure like Eloquent collections
        $liveClasses = collect($liveClasses)->groupBy('live_class_id')->map(function ($classes) {
            $first = $classes->first();
            $liveClass = (object) [
                'id' => $first->live_class_id,
                'batch_id' => $first->batch_id,
                'topic' => $first->topic,
                'class_datetime' => $first->class_datetime,
                'assignments' => $classes->filter(function ($item) {
                    return !is_null($item->assignment_id);
                })->map(function ($item) {
                    return (object) [
                        'id' => $item->assignment_id,
                        'title' => $item->assignment_title,
                        'description' => $item->assignment_description,
                        'due_date' => $item->assignment_due_date,
                        'file_path' => $item->assignment_file_path,
                        'file_url' => $item->assignment_file_path ? Storage::url($item->assignment_file_path) : null,
                        'submission_file_path' => $item->submission_file_path,
                        'submission_file_url' => $item->submission_file_path ? Storage::url($item->submission_file_path) : null,
                        'has_submission' => !is_null($item->submission_id),
                    ];
                })->values(),
            ];
            return $liveClass;
        })->values();

        return view('student.assignment.assignment', compact('liveClasses'));
    }

    public function submitAssignment(Request $request, $assignmentId)
    {
        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,doc,docx,zip|max:20480', // 20MB max
        ]);

        // Verify assignment exists and get live_class_id
        $assignment = DB::selectOne('SELECT id, live_class_id FROM assignments WHERE id = ?', [$assignmentId]);
        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        // Store the file
        $file = $request->file('submission_file');
        $fileName = time() . '_' . Auth::id() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('assignment_submissions', $fileName, 'public');

        // Insert submission record
        DB::insert("
            INSERT INTO assignment_submissions (user_id, live_class_id, assignment_id, file_path, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ", [Auth::id(), $assignment->live_class_id, $assignmentId, $filePath]);

        return redirect()->back()->with('success', 'Assignment submitted successfully!');
    }


    public function viewClassAssignments_old($liveClassId)
    {
        // Verify the teacher has access to this live class (e.g., via batch ownership)
        $batch = DB::selectOne('SELECT batch_id FROM live_classes WHERE id = ?', [$liveClassId]);
        if (!$batch) {
            return redirect()->back()->with('error', 'Live class not found.');
        }

        // Fetch assignments and submissions for the live class
        $assignments = DB::select("
            SELECT a.id AS assignment_id, 
                   a.title AS assignment_title, 
                   a.description AS assignment_description, 
                   a.due_date AS assignment_due_date, 
                   a.file_path AS assignment_file_path,
                   asub.id AS submission_id,
                   asub.user_id,
                   asub.file_path AS submission_file_path,
                   u.name AS student_name
            FROM assignments a
            LEFT JOIN assignment_submissions asub ON a.id = asub.assignment_id
            LEFT JOIN users u ON asub.user_id = u.id
            WHERE a.live_class_id = ?
            ORDER BY a.due_date ASC
        ", [$liveClassId]);

        // Group results by assignment
        $assignments = collect($assignments)->groupBy('assignment_id')->map(function ($items) {
            $first = $items->first();
            return (object) [
                'id' => $first->assignment_id,
                'title' => $first->assignment_title,
                'description' => $first->assignment_description,
                'due_date' => $first->assignment_due_date,
                'file_path' => $first->assignment_file_path,
                'file_url' => $first->assignment_file_path ? Storage::url($first->assignment_file_path) : null,
                'submissions' => $items->filter(function ($item) {
                    return !is_null($item->submission_id);
                })->map(function ($item) {
                    return (object) [
                        'id' => $item->submission_id,
                        'user_id' => $item->user_id,
                        'student_name' => $item->student_name,
                        'file_path' => $item->submission_file_path,
                        'file_url' => $item->submission_file_path ? Storage::url($item->submission_file_path) : null,
                    ];
                })->values(),
            ];
        })->values();

        $liveClass = DB::selectOne('SELECT topic, class_datetime FROM live_classes WHERE id = ?', [$liveClassId]);

        return view('student.assignment.assubmission', compact('assignments', 'liveClass'));
    }
    public function viewClassAssignments($liveClassId)
    {
        // Verify the teacher has access to this live class (e.g., via batch ownership)
        $batch = DB::selectOne('SELECT batch_id FROM live_classes WHERE id = ?', [$liveClassId]);
        if (!$batch) {
            return redirect()->back()->with('error', 'Live class not found.');
        }

        // Fetch all assignments and their submissions for the live class
        $assignments = DB::select("
            SELECT a.id AS assignment_id, 
                   a.title AS assignment_title, 
                   a.description AS assignment_description, 
                   a.due_date AS assignment_due_date, 
                   a.file_path AS assignment_file_path,
                   asub.id AS submission_id,
                   asub.user_id,
                   asub.file_path AS submission_file_path,
                   u.name AS student_name
            FROM assignments a
            LEFT JOIN assignment_submissions asub ON a.id = asub.assignment_id
            LEFT JOIN users u ON asub.user_id = u.id
            WHERE a.live_class_id = ?
            ORDER BY a.due_date ASC, asub.user_id ASC
        ", [$liveClassId]);

        // Group results by assignment
        $assignments = collect($assignments)->groupBy('assignment_id')->map(function ($items) {
            $first = $items->first();
            return (object) [
                'id' => $first->assignment_id,
                'title' => $first->assignment_title,
                'description' => $first->assignment_description,
                'due_date' => $first->assignment_due_date,
                'file_path' => $first->assignment_file_path,
                'file_url' => $first->assignment_file_path ? Storage::url($first->assignment_file_path) : null,
                'submissions' => $items->filter(function ($item) {
                    return !is_null($item->submission_id);
                })->map(function ($item) {
                    return (object) [
                        'id' => $item->submission_id,
                        'user_id' => $item->user_id,
                        'student_name' => $item->student_name,
                        'file_path' => $item->submission_file_path,
                        'file_url' => $item->submission_file_path ? Storage::url($item->submission_file_path) : null,
                    ];
                })->values(),
            ];
        })->values();

        $liveClass = DB::selectOne('SELECT topic, class_datetime FROM live_classes WHERE id = ?', [$liveClassId]);

        return view('student.assignment.assubmission', compact('assignments', 'liveClass'));
    }
}