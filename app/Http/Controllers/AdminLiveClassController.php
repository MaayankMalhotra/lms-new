<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use App\Models\LiveClass;
use App\Models\Internship;
use App\Models\InternshipBatch;
use App\Models\InternshipFolder;
use App\Models\InternshipClass;
use App\Models\Recording;
use Illuminate\Http\Request;
use App\Models\Folder;

class AdminLiveClassController extends Controller
{
    // public function index()
    // {
    //     $batches = Batch::with('course', 'liveClasses')->get();
    //     return view('admin.live_classes.index', compact('batches'));
    // }
    public function index()
    {
        $batchesQuery = Batch::with('course', 'liveClasses');

        if (auth()->check() && (int) auth()->user()->role === 2) {
            $batchesQuery->where('teacher_id', auth()->id());
        }

        $batches = $batchesQuery->get();

        return view('admin.live_classes.index', compact('batches'));
    }

     public function indexInt()
    {
        $batchesQuery = InternshipBatch::with('internship', 'liveClasses');

        if (auth()->check() && (int) auth()->user()->role === 2) {
            $batchesQuery->where('teacher_id', auth()->id());
        }

        $batches = $batchesQuery->get();

        return view('admin.live_classes.index-int', compact('batches'));
    }
    public function create()
    {
        if (auth()->check() && (int) auth()->user()->role === 2) {
            $courseIds = Batch::where('teacher_id', auth()->id())
                ->pluck('course_id')
                ->filter()
                ->unique()
                ->values();

            $courses = Course::whereIn('id', $courseIds)->orderBy('name')->get();
        } else {
            $courses = Course::orderBy('name')->get();
        }

        return view('admin.live_classes.create', compact('courses'));
    }

    public function createInt()
    {
        if (auth()->check() && (int) auth()->user()->role === 2) {
            $internshipIds = InternshipBatch::where('teacher_id', auth()->id())
                ->pluck('internship_id')
                ->filter()
                ->unique()
                ->values();

            $internships = Internship::whereIn('id', $internshipIds)->orderBy('name')->get();
        } else {
            $internships = Internship::orderBy('name')->get();
        }

        return view('admin.live_classes.create-int', compact('internships'));
    }

    public function getRecordings($batchId)
    {
        $batch = Batch::findOrFail($batchId);
        $courseId = $batch->course_id;
        $recordings = Recording::where('course_id', $courseId)
            ->get(['id', 'topic']);
        return response()->json($recordings);
    }
        public function getRecordingsByBatch($batchId)
    {
        $batch = Batch::with('course')->findOrFail($batchId);
        $folders = Folder::where('course_id', $batch->course_id)->with(['topics.recordings'])->get();

        $recordingsData = $folders->map(function ($folder) {
            $topics = $folder->topics->map(function ($topic) {
                $recordings = $topic->recordings->map(function ($recording) {
                    return [
                        'id' => $recording->id,
                        'name' => "Recording {$recording->id}", // Custom label
                    ];
                });
                return [
                    'name' => $topic->name,
                    'recordings' => $recordings,
                ];
            });
            return [
                'name' => $folder->name,
                'topics' => $topics,
            ];
        })->filter(function ($folder) {
            return $folder['topics']->isNotEmpty();
        });

        return response()->json($recordingsData);
    }
    public function getFoldersByCourse($courseId)
    {
        $folders = Folder::where('course_id', $courseId)->get()->map(function ($folder) {
            return [
                'id' => $folder->id,
                'name' => $folder->name,
            ];
        });

        return response()->json($folders);
    }

     public function getFoldersByBatchInt($batchId)
    {
        $batch = InternshipBatch::with('internship')->findOrFail($batchId);
 
        
        $folders = InternshipFolder::where('internship_id', $batch->internship_id)->get()->map(function ($folder) {
            return [
                'id' => $folder->id,
                'name' => $folder->name,
            ];
        });
       

        return response()->json($folders);
    }

          public function getRecordingsByFolderInt($folderId)
    {
        $folder = InternshipFolder::with(['topics.recordings'])->findOrFail($folderId);
        $recordingsData = $folder->topics->map(function ($topic) {
            $recordings = $topic->recordings->map(function ($recording) {
                return [
                    'id' => $recording->id,
                    'name' => "Recording {$recording->id}",
                ];
            });
            return [
                'name' => $topic->name,
                'recordings' => $recordings,
            ];
        })->filter(function ($topic) {
            return $topic['recordings']->isNotEmpty();
        });

        return response()->json($recordingsData);
    }
        public function getRecordingsByFolder($folderId)
    {
        $folder = Folder::with(['topics.recordings'])->findOrFail($folderId);
        $recordingsData = $folder->topics->map(function ($topic) {
            $recordings = $topic->recordings->map(function ($recording) {
                return [
                    'id' => $recording->id,
                    'name' => "Recording {$recording->id}",
                ];
            });
            return [
                'name' => $topic->name,
                'recordings' => $recordings,
            ];
        })->filter(function ($topic) {
            return $topic['recordings']->isNotEmpty();
        });

        return response()->json($recordingsData);
    }

    public function store_old(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'google_meet_link' => 'required|url',
            'class_datetime' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'recording_id' => 'nullable|exists:recordings,id',
        ]);

        $recording = $request->recording_id ? Recording::find($request->recording_id) : null;

        $liveClass = LiveClass::create([
            'batch_id' => $request->batch_id,
            'topic' => $recording ? $recording->topic : 'Untitled Live Class',
            'google_meet_link' => $request->google_meet_link,
            'class_datetime' => $request->class_datetime,
            'duration_minutes' => $request->duration_minutes,
            'status' => 'Scheduled',
        ]);

        if ($recording) {
            $recording->update(['live_class_id' => $liveClass->id]);
        }

        return redirect()->route('admin.live_classes.index')->with('success', 'Live class created successfully');
    }

    public function edit($id)
    {
        $liveClass = LiveClass::findOrFail($id);
        $batches = Batch::with('course')->get();
        return view('admin.live_classes.index', compact('liveClass', 'batches')); // Load into index for modal
    }

    public function update(Request $request, $id)
    {
        $liveClass = LiveClass::findOrFail($id);    

        $recording = $request->recording_id ? Recording::find($request->recording_id) : null;
        $liveClass->update([
            'course_id' => $request->course_id,
            'topic' => $recording ? $recording->topic : 'Untitled Live Class',
            'google_meet_link' => $request->google_meet_link,
            'class_datetime' => $request->class_datetime,
            'duration_minutes' => $request->duration_minutes,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.live_classes.index')->with('success', 'Live class updated successfully');
    }

    public function destroy($id)
    {
        $liveClass = LiveClass::findOrFail($id);
        $liveClass->delete();
        return redirect()->route('admin.live_classes.index')->with('success', 'Live class deleted successfully');
    }


    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_id' => 'required|exists:batches,id',
            'folder_id' => 'nullable|exists:folders,id',
            'google_meet_link' => 'required|url',
            'class_datetime' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'recording_id' => 'nullable|array',
            'recording_id.*' => 'exists:recordings,id',
        ]);

        $selectedBatch = Batch::findOrFail($request->batch_id);

        if (auth()->check() && (int) auth()->user()->role === 2) {
            if ((int) $selectedBatch->teacher_id !== (int) auth()->id()) {
                return back()->withErrors(['batch_id' => 'You can only create live classes for your own batches.'])->withInput();
            }
        }

        $topicName = 'Untitled Live Class';
        if ($request->has('folder_id') && $request->folder_id) {
            $folder = Folder::find($request->folder_id);
            if ($folder) {
                $firstTopic = $folder->topics->first();
                $topicName = $firstTopic ? $firstTopic->name : $topicName;
            }
        }

        $recordingIds = $request->has('recording_id') && !empty($request->recording_id) ? implode(',', $request->recording_id) : null;

        LiveClass::create([
            'course_id' => $selectedBatch->course_id,
            'batch_id' => $request->batch_id,
            'folder_id' => $request->folder_id,
            'topic' => $topicName,
            'google_meet_link' => $request->google_meet_link,
            'class_datetime' => $request->class_datetime,
            'duration_minutes' => $request->duration_minutes,
            'status' => 'Scheduled',
            'recording_id' => $recordingIds,
        ]);

        $redirectRoute = auth()->check() && (int) auth()->user()->role === 2
            ? 'teacher.live_classes'
            : 'admin.live_classes.index';

        return redirect()->route($redirectRoute)->with('success', 'Live class created successfully');
    }

  public function storeInt(Request $request)
    {
        try {
            $request->validate([
                'batch_id' => 'required|exists:internship_batches,id',
                'folder_id' => 'nullable',
                'google_meet_link' => 'required|url',
                'class_datetime' => 'required|date',
                'duration_minutes' => 'required|integer|min:1',
                'recording_id' => 'nullable|array',
               // 'recording_id.*' => 'exists:recordings,id',
            ]);

            $selectedBatch = InternshipBatch::findOrFail($request->batch_id);

            if (auth()->check() && (int) auth()->user()->role === 2) {
                if ((int) $selectedBatch->teacher_id !== (int) auth()->id()) {
                    return back()->withErrors(['batch_id' => 'You can only create live classes for your own batches.'])->withInput();
                }
            }

            $topicName = 'Untitled Live Class';
            if ($request->has('folder_id') && $request->folder_id) {
                $folder = InternshipFolder::find($request->folder_id);
                if ($folder) {
                    $firstTopic = $folder->topics->first();
                    $topicName = $firstTopic ? $firstTopic->name : $topicName;
                }
            }

            $recordingIds = $request->has('recording_id') && !empty($request->recording_id) ? implode(',', $request->recording_id) : null;

            InternshipClass::create([
                'batch_id' => $request->batch_id,
                'folder_id' => $request->folder_id,
                'topic' => $topicName,
                'google_meet_link' => $request->google_meet_link,
                'class_datetime' => $request->class_datetime,
                'duration_minutes' => $request->duration_minutes,
                'status' => 'Scheduled',
                'recording_id' => $recordingIds,
            ]);

            $redirectRoute = auth()->check() && (int) auth()->user()->role === 2
                ? 'teacher.live_classes'
                : 'admin.live_classes.index';

            return redirect()->route($redirectRoute)->with('success', 'Live class created successfully');
        } catch (\ValidationException $e) {
            dd('Validation Error:', $e->errors());
        } catch (\Exception $e) {
           
            dd('Error:', $e->getMessage(), $e->getTraceAsString());
        }
    }
}
