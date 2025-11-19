<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function assignment()
    {
        $enrollments = DB::table('enrollments as e')
            ->leftJoin('batches as b', 'e.batch_id', '=', 'b.id')
            ->where('e.user_id', Auth::id())
            ->select('e.batch_id as batch_id', 'b.course_id')
            ->get();

        if ($enrollments->isEmpty()) {
            return view('student.assignment.assignment', ['liveClasses' => []]);
        }

        $batchIds = $enrollments->pluck('batch_id')->all();
        $courseIds = $enrollments->pluck('course_id')->filter()->unique()->all();

        $assignments = DB::table('assignments as a')
            ->where(function ($q) use ($batchIds, $courseIds) {
                $q->whereIn('a.batch_id', $batchIds);
                if (!empty($courseIds)) {
                    $q->orWhereIn('a.course_id', $courseIds);
                }
            })
            ->select(
                'a.id as assignment_id',
                'a.title as assignment_title',
                'a.description as assignment_description',
                'a.due_date as assignment_due_date',
                'a.file_path as assignment_file_path',
                'a.batch_id',
                'a.course_id'
            )
            ->orderBy('a.due_date', 'asc')
            ->get();

        if ($assignments->isEmpty()) {
            return view('student.assignment.assignment', ['liveClasses' => []]);
        }

        $assignmentIds = $assignments->pluck('assignment_id')->all();

        $submissions = DB::table('assignment_submissions')
            ->where('user_id', Auth::id())
            ->whereIn('assignment_id', $assignmentIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('assignment_id')
            ->map(function ($items) {
                return $items->first();
            });

        $courseToBatch = $enrollments
            ->filter(function ($item) {
                return !is_null($item->course_id);
            })
            ->groupBy('course_id')
            ->map(function ($items) {
                return $items->first()->batch_id;
            });

        $liveClasses = $assignments->map(function ($item) use ($courseToBatch, $batchIds, $submissions) {
            $displayBatchId = $item->batch_id ?? ($item->course_id ? ($courseToBatch[$item->course_id] ?? null) : null) ?? $batchIds[0];
            $submission = $submissions->get($item->assignment_id);
            $dueDate = $item->assignment_due_date ? Carbon::parse($item->assignment_due_date) : null;
            $isPastDue = $dueDate ? $dueDate->isPast() : false;
            $status = $submission->status ?? ($isPastDue ? 'expired' : 'not_submitted');
            $marks = $submission->marks ?? null;

            if ($status === 'expired' && !$submission) {
                $marks = 0;
            }

            return (object) [
                'batch_id' => $displayBatchId,
                'assignment' => (object) [
                    'id' => $item->assignment_id,
                    'title' => $item->assignment_title,
                    'description' => $item->assignment_description,
                    'due_date' => $item->assignment_due_date,
                    'file_path' => $item->assignment_file_path,
                    'file_url' => $item->assignment_file_path ? Storage::url($item->assignment_file_path) : null,
                    'submission_file_path' => $submission->file_path ?? null,
                    'submission_file_url' => ($submission->file_path ?? null) ? Storage::url($submission->file_path) : null,
                    'has_submission' => !is_null($submission),
                    'status' => $status,
                    'marks' => $marks,
                    'feedback' => $submission->feedback ?? null,
                    'submitted_at' => $submission->created_at ?? null,
                    'reviewed_at' => $submission->reviewed_at ?? null,
                    'can_resubmit' => !$isPastDue,
                ],
            ];
        })->groupBy('batch_id')->map(function ($items, $batchId) {
            $batch = (object) [
                'id' => $batchId,
                'assignments' => $items->map(function ($wrapped) {
                    return $wrapped->assignment;
                })->values(),
            ];

            return $batch;
        })->values();

        return view('student.assignment.assignment', compact('liveClasses'));
    }

    public function assignmentInt()
    {
        // Get all batches and courses for the authenticated user
        $enrollments = DB::table('enrollments as e')
            ->leftJoin('batches as b', 'e.batch_id', '=', 'b.id')
            ->where('e.user_id', Auth::id())
            ->select('e.batch_id as batch_id', 'b.course_id')
            ->get();

        if ($enrollments->isEmpty()) {
            return view('student.assignment.assignment', ['liveClasses' => []]);
        }

        $batchIds = $enrollments->pluck('batch_id')->all();

        // Fetch live classes with assignments and the latest submission details for the authenticated user
        $liveClasses = DB::table('live_classes as lc')
            ->whereIn('lc.batch_id', $batchIds)
            ->leftJoin('assignments as a', 'lc.id', '=', 'a.live_class_id')
            ->leftJoin(DB::raw('(SELECT * FROM assignment_submissions WHERE user_id = ' . (int) Auth::id() . ' ORDER BY created_at DESC) as asub'), function ($join) {
                $join->on('asub.assignment_id', '=', 'a.id');
            })
            ->select(
                'lc.id as live_class_id',
                'lc.batch_id',
                'lc.topic',
                'lc.class_datetime',
                'a.id as assignment_id',
                'a.title as assignment_title',
                'a.description as assignment_description',
                'a.due_date as assignment_due_date',
                'a.file_path as assignment_file_path',
                'asub.file_path as submission_file_path',
                'asub.id as submission_id',
                'asub.status as submission_status',
                'asub.marks as submission_marks',
                'asub.feedback as submission_feedback',
                'asub.created_at as submission_created_at',
                'asub.reviewed_at as submission_reviewed_at'
            )
            ->orderBy('lc.class_datetime', 'asc')
            ->get();

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
                    $dueDate = $item->assignment_due_date ? Carbon::parse($item->assignment_due_date) : null;
                    $isPastDue = $dueDate ? $dueDate->isPast() : false;
                    $status = $item->submission_status ?? ($isPastDue ? 'expired' : 'not_submitted');
                    $marks = $item->submission_marks;

                    if ($status === 'expired' && is_null($item->submission_id)) {
                        $marks = 0;
                    }

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
                        'status' => $status,
                        'marks' => $marks,
                        'feedback' => $item->submission_feedback,
                        'submitted_at' => $item->submission_created_at,
                        'reviewed_at' => $item->submission_reviewed_at,
                        'can_resubmit' => !$isPastDue,
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

        // Verify assignment exists and get live_class_id + due date
        $assignment = DB::selectOne('SELECT id, live_class_id, due_date FROM assignments WHERE id = ?', [$assignmentId]);
        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        $now = Carbon::now();
        $dueDate = $assignment->due_date ? Carbon::parse($assignment->due_date) : null;
        $isLate = $dueDate ? $now->gt($dueDate) : false;

        // Store the file
        $file = $request->file('submission_file');
        $fileName = time() . '_' . Auth::id() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('assignment_submissions', $fileName, 'public');

        $status = $isLate ? 'expired' : 'submitted';
        $marks = $isLate ? 0 : null;
        $feedback = $isLate ? 'Submission was after the deadline; marks expired to zero.' : null;
        $reviewedAt = $isLate ? $now : null;

        // Upsert submission record so students can re-upload until due date
        $existing = DB::table('assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->first();

        if ($existing) {
            DB::table('assignment_submissions')
                ->where('id', $existing->id)
                ->update([
                    'file_path' => $filePath,
                    'marks' => $marks,
                    'status' => $status,
                    'feedback' => $feedback,
                    'reviewed_at' => $reviewedAt,
                    'live_class_id' => $assignment->live_class_id,
                    'updated_at' => $now,
                ]);
        } else {
            DB::table('assignment_submissions')->insert([
                'user_id' => Auth::id(),
                'live_class_id' => $assignment->live_class_id,
                'assignment_id' => $assignmentId,
                'file_path' => $filePath,
                'marks' => $marks,
                'status' => $status,
                'feedback' => $feedback,
                'reviewed_at' => $reviewedAt,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $message = $isLate
            ? 'Uploaded, but the deadline has passed so this submission is marked expired with zero marks.'
            : 'Assignment submitted successfully!';

        return redirect()->back()->with('success', $message);
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
