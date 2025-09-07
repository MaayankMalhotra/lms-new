<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function addCourse()
    {
        return view('admin.add-course');
    }

  public function storeCourse(Request $request)
{
    // Dump the request data for debugging
   // dd($request->all());

    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'course_code_id' => 'required|unique:courses|max:255',
            'logo' => 'nullable|image',
            'duration' => 'required',
            'placed_learner' => 'required',
            'slug' => 'required|unique:courses|max:255',
            'rating' => 'required',
            'price' => 'required|numeric',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Dump the validation errors and stop execution
        dd($e->errors());
    }

    if ($request->hasFile('logo')) {
        $image = $request->file('logo');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('courses'), $imageName);

        // Store only relative path
        $validated['logo'] = 'courses/' . $imageName;
    }

    // Create the course using mass assignment
    $data = Course::create($validated);
    Log::info('Course created successfully', ['course' => $data]);

    return redirect()->route('admin.course.add')->with('success', 'Course created successfully!');
}

//     public function courseList()
// {
//     $courses = Course::latest()->paginate(10);
//     return view('admin.courses-list', compact('courses'));
// }
public function courseList()
{
    $courses = Course::select('courses.*', 'course_details.id as course_details_id')
        ->leftJoin('course_details', 'course_details.course_id', '=', 'courses.id')
        ->latest('courses.created_at')
        ->paginate(100);
    return view('admin.courses-list', compact('courses'));
}
public function edit($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

   
    public function update(Request $request, $id)
{
    $course = Course::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'course_code_id' => 'required|unique:courses,course_code_id,'.$course->id,
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'duration' => 'required|string|max:255',
        'placed_learner' => 'required|integer',
        'slug' => 'required|unique:courses,slug,'.$course->id,
        'rating' => 'required',
        'price' => 'required|numeric',
    ]);

    // if ($request->hasFile('logo')) {
    //     // Delete old logo
    //     if ($course->logo) {
    //         Storage::delete(str_replace('/storage', 'public', $course->logo));
    //     }
        
    //     $path = $request->file('logo')->store('public/courses');
    //     $validated['logo'] = Storage::url($path);
    // }
 if ($request->hasFile('logo')) {
            // Delete old logo
            if ($course->logo && file_exists(public_path($course->logo))) {
                unlink(public_path($course->logo));
            }

            $image = $request->file('logo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('courses'), $imageName);

            // Store only relative path
            $validated['logo'] = 'courses/' . $imageName;
        }
    $course->update($validated);

      return redirect()->route('admin.course.list')->with('success', 'Course updated successfully!');
}

public function destroy($id)
{
    try {
        $course = Course::findOrFail($id);
        
        // Delete logo if exists
        // if ($course->logo) {
        //     $path = str_replace('/storage', 'public', $course->logo);
        //     if (Storage::exists($path)) {
        //         Storage::delete($path);
        //     }
        // }
         if ($course->logo && file_exists(public_path($course->logo))) {
                unlink(public_path($course->logo));
            }
        
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error deleting course: ' . $e->getMessage()
        ], 500);
    }
}

public function courseDetails($slug)
{
    // Slug directly parameter se mil gaya
    if (!$slug || !is_string($slug) || empty(trim($slug))) {
        return view('website.course_details')->with('error', 'Invalid or missing course slug!');
    }

    // Slug se course ki row database se fetch karo
    // $course = Course::where('slug', $slug)->first();
    // $course_details = CourseDetail::where('course_id', $course->id)->first();
    $course = Course::where('slug', $slug)->first();
    $course_details = CourseDetail::where('course_id', $course->id)
        ->join('courses', 'courses.id', '=', 'course_details.course_id')
        ->select('course_details.*', 'courses.name')
        ->first();
        // dd($course_details);
    if (!$course) {
        return view('website.course_details')->with('error', 'Course not found!');
    }

    // Course details ke saath view pe bhejo
    return view('website.course_details', ['course' => $course,'course_details' => $course_details]);
}
}
