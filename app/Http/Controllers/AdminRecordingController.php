<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use App\Models\LiveClass;
use App\Models\Recording;
use App\Models\Internship;
use App\Models\InternshipFolder;
use App\Models\InternshipTopic;
use App\Models\InternshipRecording;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Folder;
use App\Models\Topic;

use Illuminate\Http\Request;

class AdminRecordingController extends Controller
{
    public function index()
    {
        $recordings = Recording::all(); // Fetch recordings with courses
        $courses = Course::all(); // Fetch all courses for the edit modal
        return view('admin.recordings.index', compact('recordings', 'courses'));
    }

    //  public function indexInt()
    // {
    //     $recordings = InternshipRecording::all(); // Fetch recordings with courses
    //     $courses = Internship::all(); // Fetch all courses for the edit modal
    //      dd($recordings,$courses);
        
    //     return view('admin.recordings.index-int', compact('recordings', 'courses'));
    // }
    public function indexInt()
    {
        // Fetch courses with related batches, internship_folders, internship_topics, and internship_recordings
        $courses = Internship::with(['batches', 'folders.topics.recordings'])->get();
        $recordings = InternshipRecording::all(); // Optional, if you need all recordings separately
       //// dd($courses, $recordings);

        return view('admin.recordings.index-int', compact('courses', 'recordings'));
    }
    public function view()
{
    $courses = Course::with(['batches', 'folders.topics.recordings'])->get();
    return view('admin.recordings.view', compact('courses'));
}

    // public function create()
    // {
    //     $courses = Course::all(); // Fetch all courses for dropdown
    //     return view('admin.recordings.create', compact('courses'));
    // }

    public function store_old(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'topic' => 'required|string|max:255',
            'video_url' => 'required|url',
        ]);

        Recording::create([
            'course_id' => $request->course_id,
            'topic' => $request->topic,
            'video_url' => $request->video_url,
            'uploaded_at' => now(),
        ]);

        return redirect()->route('admin.recordings.index')->with('success', 'Recording added successfully');
    }

    public function edit($id)
    {
        $recording = Recording::findOrFail($id);
        $courses = Course::all();
        $recordings = Recording::with('course')->get(); // Pass recordings for index consistency
        return view('admin.recordings.index', compact('recording', 'courses', 'recordings')); // Load into index for modal
    }

    public function _old(Request $request, $id)
    {
        $recording = Recording::findOrFail($id);

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'topic' => 'required|string|max:255',
            'video_url' => 'required|url',
        ]);

        $recording->update([
            'course_id' => $request->course_id,
            'topic' => $request->topic,
            'video_url' => $request->video_url,
        ]);

        return redirect()->route('admin.recordings.index')->with('success', 'Recording updated successfully');
    }

    public function destroy($id)
    {
        $recording = Recording::findOrFail($id);
        if ($recording->live_class_id) {
            return redirect()->route('admin.recordings.index')->with('error', 'Cannot delete recording linked to a live class.');
        }
        $recording->delete();
        return redirect()->route('admin.recordings.index')->with('success', 'Recording deleted successfully');
    }

//     public function view()
// {
//     $courses = Course::all();
//     return view('admin.recordings.view', compact('courses'));
// }

public function storeView(Request $request)
{
    $validatedData = $request->validate([
        'course_id' => 'required|exists:courses,id',
        'folder_name' => 'required',
        'topic_name' => 'required',
        'topic_discussion' => 'required',
        'recording_link' => 'required|url',
    ]);

    Recording::create($validatedData);

    return redirect()->back()->with('success', 'Recording added successfully');
}

public function getFolders($courseId)
{
    $folders = Folder::where('course_id', $courseId)->pluck('name')->toArray();
    return response()->json(['folders' => $folders]);
}

public function addFolder(Request $request, $courseId)
{
    $folderName = $request->input('folder_name');
    Folder::create(['course_id' => $courseId, 'name' => $folderName]);
    return response()->json(['success' => true]);
}




 public function create()
    {
        $courses = Course::all();
        $folders = Folder::with('course')->get();
        $topics = Topic::with('folder')->get();
        return view('admin.recordings.create', compact('courses', 'folders', 'topics'));
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
        ]);

        Folder::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.recordings.create')->with('success', 'Folder created successfully.');
    }

    public function storeTopic(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id',
            'name' => 'required|string|max:255',
            'discussion' => 'nullable|string',
        ]);

        Topic::create([
            'folder_id' => $request->folder_id,
            'name' => $request->name,
            'discussion' => $request->discussion,
        ]);

        return redirect()->route('admin.recordings.create')->with('success', 'Topic created successfully.');
    }

    public function storeRecording(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'video_url' => 'required|url',
        ]);

        Recording::create([
            'topic_id' => $request->topic_id,
            'video_url' => $request->video_url,
        ]);

        return redirect()->route('admin.recordings.create')->with('success', 'Recording added successfully.');
    }

public function createFolder(Request $request)
{
    $folder = Folder::create([
        'name' => $request->name,
        'course_id' => $request->course_id,
        'locked' => false,
    ]);
    return response()->json(['success' => true, 'id' => $folder->id]);
}

  public function createTopic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:folders,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $topic = Topic::create([
            'name' => $request->name,
            'folder_id' => $request->parent_id,
            'locked' => false,
        ]);
        return response()->json(['success' => true, 'id' => $topic->id]);
    }

    public function createRecording(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|url',
            'parent_id' => 'required|exists:topics,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $recording = Recording::create([
            'video_url' => $request->name,
            'topic_id' => $request->parent_id,
            'locked' => false,
        ]);
        return response()->json(['success' => true, 'id' => $recording->id]);
    }

    public function createTopicAndRecording(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_name' => 'required|string|max:255',
            'recording_link' => 'required|url',
            'folder_id' => 'required|exists:folders,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        // Create the topic
        $topic = Topic::create([
            'name' => $request->topic_name,
            'folder_id' => $request->folder_id,
            'locked' => false,
        ]);

        // Create the recording
        $recording = Recording::create([
            'video_url' => $request->recording_link,
            'topic_id' => $topic->id,
            'locked' => false,
        ]);

        return response()->json(['success' => true, 'topic_id' => $topic->id, 'recording_id' => $recording->id]);
    }

   public function updateFolder(Request $request, $id)
       {
        try {
            Log::info('Update Folder Request: ', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'course_id' => 'required|exists:courses,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator], 422);
            }

            $folder = Folder::findOrFail($id);
            Log::info('Folder found: ', $folder->toArray());

            $folder->name = $request->input('name');
            $folder->course_id = $request->input('course_id');
            $folder->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Update Folder Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function updateTopic(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $topic = Topic::findOrFail($id);
        $topic->update($request->only(['name']));
        return response()->json(['success' => true]);
    }

    public function updateRecording(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $recording = Recording::findOrFail($id);
        $recording->update($request->only(['video_url']));
        return response()->json(['success' => true]);
    }
public function toggleLock($type, $id)
{
    if ($type === 'folder') {
        $item = Folder::findOrFail($id);
    } elseif ($type === 'recording') {
        $item = Recording::findOrFail($id);
    }
    $item->locked = !$item->locked;
    $item->save();
    return response()->json(['success' => true]);
}

  

     public function updateItem(Request $request, $id)
    {
        $type = $request->input('type');
        Log::info('Update Item Request: ', ['type' => $type, 'id' => $id, 'data' => $request->all()]);

        if ($type === 'topic') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $item = Topic::findOrFail($id);
            $item->update($request->only(['name']));
        } elseif ($type === 'recording') {
            $validator = Validator::make($request->all(), [
                'video_url' => 'required|url',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $item = Recording::findOrFail($id);
            $item->update($request->only(['video_url']));
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
        }

        return response()->json(['success' => true]);
    }



     // New Methods with -int Postfix
    public function toggleLockInt($type, $id)
    {
        if ($type === 'folder') {
            $item = InternshipFolder::findOrFail($id);
        } elseif ($type === 'recording') {
            $item = InternshipRecording::findOrFail($id);
        }
        $item->locked = !$item->locked;
        $item->save();
        return response()->json(['success' => true]);
    }

     public function createFolderInt(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'course_id' => 'required', // Added exists validation for course_id
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $folder = InternshipFolder::create([
                'name' => $request->name,
                'internship_id' => $request->course_id, // Assuming internship_id is intended as course_id
                'locked' => '0',
            ]);

            return response()->json(['success' => true, 'id' => $folder->id]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database Error in createFolderInt: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred',
                'errors' => ['general' => ['An error occurred while creating the folder. Please try again.']],
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error in createFolderInt: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'errors' => ['general' => ['Something went wrong. Please try again later.']],
            ], 500);
        }
    }

    public function createTopicInt(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'parent_id' => 'required|exists:internship_folders,id',
        // ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $topic = InternshipTopic::create([
            'name' => $request->name,
            'folder_id' => $request->parent_id,
            'locked' => false,
        ]);
        return response()->json(['success' => true, 'id' => $topic->id]);
    }

    public function createRecordingInt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|url',
            'parent_id' => 'required|exists:internship_topics,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $recording = InternshipRecording::create([
            'video_url' => $request->name,
            'topic_id' => $request->parent_id,
            'locked' => false,
        ]);
        return response()->json(['success' => true, 'id' => $recording->id]);
    }

    public function createTopicAndRecordingInt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_name' => 'required|string|max:255',
            'recording_link' => 'required|url',
            'folder_id' => 'required|exists:internship_folders,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $topic = InternshipTopic::create([
            'name' => $request->topic_name,
            'folder_id' => $request->folder_id,
            'locked' => false,
        ]);

        $recording = InternshipRecording::create([
            'video_url' => $request->recording_link,
            'topic_id' => $topic->id,
            'locked' => false,
        ]);

        return response()->json(['success' => true, 'topic_id' => $topic->id, 'recording_id' => $recording->id]);
    }

    public function updateFolderInt(Request $request, $id)
    {
        try {
            Log::info('Update FolderInt Request: ', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'course_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $folder = InternshipFolder::findOrFail($id);
            Log::info('FolderInt found: ', $folder->toArray());

            $folder->name = $request->input('name');
            $folder->internship_id = $request->input('course_id');
            $folder->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Update FolderInt Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

public function updateItemInt(Request $request, $id)
{
    $type = $request->input('type');
    Log::info('Update ItemInt Request: ', ['type' => $type, 'id' => $id, 'data' => $request->all()]);

    if ($type === 'topic') {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $item = InternshipTopic::findOrFail($id);
        $item->update($request->only(['name']));
    } elseif ($type === 'recording') {
        $validator = Validator::make($request->all(), [
            'name' => 'required|url', // Changed from 'video_url' to 'name'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $item = InternshipRecording::findOrFail($id);
        // Map the 'name' input to the 'video_url' database field
        $item->update(['video_url' => $request->input('name')]);
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
    }

    return response()->json(['success' => true]);
}
}
