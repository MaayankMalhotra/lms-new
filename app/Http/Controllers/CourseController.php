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

   
    // Controller top (if not already there)
// use Illuminate\Support\Str;

// public function update(Request $request, $id)
// {
//     $course = Course::findOrFail($id);

//     // Only accept fields you actually allow from the modal
//     $data = $request->only([
//         'name',
//         'course_code_id',
//         'slug',
//         'duration',
//         'price',
//         // 'rating', 'placed_learner'  // add if you ever send them
//     ]);

//     // Handle logo (optional)
//     if ($request->hasFile('logo')) {
//         // delete old file if exists
//         if ($course->logo && file_exists(public_path($course->logo))) {
//             @unlink(public_path($course->logo));
//         }

//         // ensure folder exists
//         $destPath = public_path('courses');
//         if (!is_dir($destPath)) {
//             @mkdir($destPath, 0755, true);
//         }

//         $image = $request->file('logo');
//         $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
//         $image->move($destPath, $imageName);

//         // store relative path (your Blade uses asset($course->logo))
//         $data['logo'] = 'courses/' . $imageName;
//     }

//     // Save without validation
//     $course->fill($data)->save();

//     return redirect()
//         ->route('admin.course.list')
//         ->with('success', 'Course updated successfully!');
// }



public function update(Request $request, $id)
{
    $course = Course::findOrFail($id);

    // Only accept fields you actually allow from the modal
    $data = $request->only([
        'name',
        'course_code_id',
        'slug',
        'duration',
        'price',
        // 'rating', 'placed_learner'  // add if you ever send them
    ]);

    // Handle logo (optional)
    if ($request->hasFile('logo')) {
        // delete old file if exists
        if ($course->logo && file_exists(public_path($course->logo))) {
            @unlink(public_path($course->logo));
        }

        // ensure folder exists
        $destPath = public_path('courses');
        if (!is_dir($destPath)) {
            @mkdir($destPath, 0755, true);
        }

        $image = $request->file('logo');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($destPath, $imageName);

        // store relative path (your Blade uses asset($course->logo))
        $data['logo'] = 'courses/' . $imageName;
    }

    // Save without validation
    $course->fill($data)->save();

    return redirect()
        ->route('admin.course.list')
        ->with('success', 'Course updated successfully!');
}



public function destroy(Request $request, $id)
{
    try {
        $course = Course::findOrFail($id);

        // Delete logo file (relative path like 'courses/xyz.jpg')
        if ($course->logo) {
            $path = public_path($course->logo);
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $course->delete();

        // Redirect (HTML) or JSON (AJAX)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.course.list')
            ->with('success', 'Course deleted successfully!');

    } catch (QueryException $e) {
        Log::error('Course delete failed (FK/QueryException)', ['id' => $id, 'error' => $e->getMessage()]);

        $msg = 'Could not delete the course. Remove related records first.';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $msg], 422);
        }
        return redirect()->back()->with('error', $msg);

    } catch (\Throwable $e) {
        Log::error('Course delete failed', ['id' => $id, 'error' => $e->getMessage()]);

        $msg = 'Unexpected error while deleting the course.';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $msg], 500);
        }
        return redirect()->back()->with('error', $msg);
    }
}


// public function destroy($id)
// {
//     try {
//         $course = Course::findOrFail($id);
        
//         // Delete logo if exists
//         // if ($course->logo) {
//         //     $path = str_replace('/storage', 'public', $course->logo);
//         //     if (Storage::exists($path)) {
//         //         Storage::delete($path);
//         //     }
//         // }
//          if ($course->logo && file_exists(public_path($course->logo))) {
//                 unlink(public_path($course->logo));
//             }
        
//         $course->delete();

//         return response()->json([
//             'success' => true,
//             'message' => 'Course deleted successfully'
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Error deleting course: ' . $e->getMessage()
//         ], 500);
//     }
// }

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
