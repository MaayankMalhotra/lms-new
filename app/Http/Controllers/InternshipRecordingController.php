<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\InternshipRecording;
use App\Models\InternshipRecordingCourse;
use Illuminate\Http\Request;

class InternshipRecordingController extends Controller
{
    // public function getRecordingsByCourse($courseId)
    // {
    //     try {
    //         // Fetch recordings where recording_course_id matches the provided courseId
    //         $recordings = InternshipRecordingCourse::where('recording_course_id', $courseId)
    //             ->select('id', 'topic', 'title') // Only select the fields needed by the JavaScript
    //             ->get();

    //         // Return the recordings as JSON
    //         return response()->json($recordings);
    //     } catch (\Exception $e) {
    //         // Return an error response if something goes wrong
    //         return response()->json(['error' => 'Failed to fetch recordings'], 500);
    //     }
    // }
    public function index()
    {
        $courses = InternshipRecordingCourse::with('recordings')->get();
        return view('admin.internship-recording-courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate(['course_name' => 'required|string|max:255']);
        InternshipRecordingCourse::create($request->only('course_name'));
        return redirect()->route('admin.internship-recording-courses.index')->with('success', 'Course created.');
    }

    public function update(Request $request, InternshipRecordingCourse $recordingCourse)
    {
        $request->validate(['course_name' => 'required|string|max:255']);
        $recordingCourse->update($request->only('course_name'));
        return redirect()->route('admin.internship-recording-courses.index')->with('success', 'Course updated.');
    }

    public function destroy(InternshipRecordingCourse $recordingCourse)
    {
        $recordingCourse->delete();
        return redirect()->route('admin.internship-recording-courses.index')->with('success', 'Course deleted.');
    }

    // Recordings
    public function create()
    {
        $courses = InternshipRecordingCourse::all();
        return view('admin.internship-recordings.create', compact('courses'));
    }

    public function storeRecording(Request $request)
    {
        $request->validate([
            'recording_course_id' => 'required|exists:internship_recording_courses,id',
            'topic' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'link' => 'required|url',
        ]);

        InternshipRecording::create($request->all());
        return redirect()->route('admin.internship-recording-courses.index')->with('success', 'Recording created.');
    }

    public function edit(InternshipRecording $recording)
    {
        $courses = InternshipRecordingCourse::all();
        return view('admin.internship-recordings.edit', compact('recording', 'courses'));
    }

    public function updateRecording(Request $request, InternshipRecording $recording)
    {
        $request->validate([
            'recording_course_id' => 'required|exists:recording_courses,id',
            'topic' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'link' => 'required|url',
        ]);

        $recording->update($request->all());
        return redirect()->route('admin.internship-recording-courses.index')->with('success', 'Recording updated.');
    }

    public function destroyRecording(InternshipRecording $recording)
    {
        $recording->delete();
        return redirect()->route('admin.internship-recording-courses.index')->with('success', 'Recording deleted.');
    }

    public function getRecordingsByCourse($courseId)
{
    $recordings = InternshipRecording::where('recording_course_id', $courseId)->get();
    return response()->json($recordings);
}


}
