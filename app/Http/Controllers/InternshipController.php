<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\InternshipDetail;
use App\Models\InternshipEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\InternshipClass;
class InternshipController extends Controller
{
    public function create()
    {
        $internships = Internship::withExists(['detail as has_details'])
            ->latest('created_at')
            ->paginate(12);

        return view('admin.add-internship', compact('internships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'duration' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'applicant' => 'required',
            'certified_button' => 'required',
            'price' => 'required|numeric|min:0', // Validate price
        ]);

        // Handle file upload
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('internships'), $imageName);

            // Store only relative path
            $validated['logo'] = 'internships/' . $imageName;
        }

        // Create the internship
        Internship::create($validated);

        return redirect()->route('admin.internship.add')->with('success', 'Internship created successfully!');
    }

    // public function internshipList()
    // {
    //     $internships = Internship::latest()->paginate(10);
    //     return view('admin.internship-list', compact('internships'));
    // }
public function internshipList()
{
    $internships = Internship::select(['internships.*', 'internship_details.id as internship_detail_id'])
        ->leftJoin('internship_details', 'internships.id', '=', 'internship_details.internship_id')
        ->addSelect(\DB::raw('CASE WHEN internship_details.id IS NOT NULL THEN 1 ELSE 0 END as has_details'))
        ->latest('internships.created_at')
        ->paginate(10);

    return view('admin.internship-list', compact('internships'));
}


// InternshipController.php

public function edit(Internship $internship)
{
    // Return JSON for the modal
    return response()->json([
        'id'               => $internship->id,
        'name'             => $internship->name,
        'duration'         => $internship->duration,
        'project'          => $internship->project,
        'applicant'        => $internship->applicant,
        'certified_button' => $internship->certified_button,
        'price'            => $internship->price,
        'logo'             => $internship->logo,
        'logo_url'         => $internship->logo ? asset($internship->logo) : null,
    ]);
}

public function update(Request $request, Internship $internship)
{
    // Light validation: only the fields visible on the card + optional logo
    $validated = $request->validate([
        'name'             => 'required|string|max:255',
        'duration'         => 'required|string|max:255',
        'project'          => 'required',
        'applicant'        => 'required',
        'certified_button' => 'required',
        'price'            => 'required|numeric|min:0',
        'logo'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle logo upload (public/internships)
    if ($request->hasFile('logo')) {
        if ($internship->logo && file_exists(public_path($internship->logo))) {
            @unlink(public_path($internship->logo));
        }
        $image = $request->file('logo');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        // ensure folder exists and is writable: public/internships
        if (!is_dir(public_path('internships'))) {
            @mkdir(public_path('internships'), 0755, true);
        }
        $image->move(public_path('internships'), $imageName);
        $validated['logo'] = 'internships/' . $imageName;
    }

    $internship->update($validated);

    // If AJAX request, return JSON (used by modal)
    if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
        $fresh = $internship->fresh();
        return response()->json([
            'success' => true,
            'internship' => [
                'id'               => $fresh->id,
                'name'             => $fresh->name,
                'duration'         => $fresh->duration,
                'project'          => $fresh->project,
                'applicant'        => $fresh->applicant,
                'certified_button' => $fresh->certified_button,
                'price'            => $fresh->price,
                'logo'             => $fresh->logo,
                'logo_url'         => $fresh->logo ? asset($fresh->logo) : null,
            ]
        ]);
    }

    // Non-AJAX fallback
    return redirect()->route('admin.internship.list')->with('success', 'Internship updated successfully!');
}

    // public function edit(Internship $internship)
    // {
    //     return response()->json($internship);
    // }

    // public function update(Request $request, Internship $internship)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'duration' => 'required|string|max:255',
    //         'project' => 'required|string|max:255',
    //         'applicant' => 'required',
    //         'certified_button' => 'required',
    //         'price' => 'required|numeric|min:0', // Validate price
    //     ]);

    //     // Handle file upload
    //     if ($request->hasFile('logo')) {
    //         // Delete old logo if it exists
    //         if ($internship->logo && file_exists(public_path($internship->logo))) {
    //             unlink(public_path($internship->logo));
    //         }

    //         $image = $request->file('logo');
    //         $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
    //         $image->move(public_path('internships'), $imageName);

    //         // Store only relative path
    //         $validated['logo'] = 'internships/' . $imageName;
    //     }

    //     // Update the internship
    //     $internship->update($validated);

    //     return redirect()->route('admin.internship.list')->with('success', 'Internship updated successfully!');
    // }

    public function destroy(Internship $internship)
    {
        // Delete the logo file if it exists
        if ($internship->logo && file_exists(public_path($internship->logo))) {
            unlink(public_path($internship->logo));
        }

        // Delete the internship record
        $internship->delete();

        return redirect()->route('admin.internship.list')->with('success', 'Internship deleted successfully!');
    }

    public function updateDemoVideo(Request $request, InternshipDetail $internshipDetail)
    {
        if (!Auth::check() || Auth::user()->role !== 1) {
            abort(403);
        }

        $data = $request->validate([
            'module_index' => 'required|integer|min:0',
            'video_url' => 'required|url',
        ]);

        $modules = $internshipDetail->demo_syllabus ?? [];
        if (!isset($modules[$data['module_index']])) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $modules[$data['module_index']]['video_url'] = trim($data['video_url']);
        $internshipDetail->update(['demo_syllabus' => $modules]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Demo video saved',
            'video_url' => $modules[$data['module_index']]['video_url'],
        ]);
    }

    public function contentCreate()
    {
        $internships = DB::table('internships')->select('id', 'name')->get();
        return view('admin.content_create', compact('internships'));
    }

    public function contentstore(Request $request)
    {
        // Validate input
        try {
            $request->validate([
                'internship_id' => 'required|exists:internships,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'nullable|file|mimes:pdf|max:5120', // Max 5MB
                'deadline' => 'nullable|date|after:today',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd('Validation Error:', $e->errors());
        }
    
        // Check if file is uploaded
        if (!$request->hasFile('file')) {
            dd('No file uploaded. Check form enctype or input name:', $request->all());
        }
    
        // Check if file is valid
        if (!$request->file('file')->isValid()) {
            dd('Invalid file. Check file type/size:', $request->file('file')->getErrorMessage());
        }
    
        // Prepare data
        $data = $request->only(['internship_id', 'title', 'description', 'deadline']);
        $data['created_at'] = now();
        $data['updated_at'] = now();
    
        // Try storing file
        try {
            $filePath = $request->file('file')->store('content', 'public');
            $data['file_path'] = $filePath;
        } catch (\Exception $e) {
            dd('File storage error:', $e->getMessage(), 'Storage path:', storage_path('app/public/content'));
        }
    
        // Try inserting into database
        try {
            DB::table('internship_contents')->insert($data);
        } catch (\Exception $e) {
            dd('Database insert error:', $e->getMessage(), 'Data:', $data);
        }
    
        return redirect()->route('admin.internship.content.create')->with('success', 'Content added.');
    }

    // public function showOnStudentDashboard(){
    //     // Get current user's ID
    //     $userId = Auth::id();

    //     // Fetch enrollments for the user with internship details
    //     $enrollments = DB::table('internship_enrollments')
    //         ->select('internship_enrollments.id', 'internship_enrollments.status', 'internship_enrollments.internship_id', 'internships.name')
    //         ->join('internships', 'internship_enrollments.internship_id', '=', 'internships.id')
    //         ->where('internship_enrollments.user_id', $userId)
    //         ->get()
    //         ->map(function ($enrollment) {
    //             // Count total content for this internship
    //             $total = DB::table('internship_contents')
    //                 ->where('internship_id', $enrollment->internship_id)
    //                 ->count();

    //             // Count completed submissions for this enrollment
    //             $completed = DB::table('internship_submissions')
    //                 ->where('internship_enrollment_id', $enrollment->id)
    //                 ->count();

    //             // Calculate progress
    //             $enrollment->progress = $total ? ($completed / $total) * 100 : 0;

    //             return $enrollment;
    //         });
    //         dd($enrollments); // Debugging line to check the enrollments data
    //     // Remove dd() for production; used for debugging

    //     return view('student.internshipdash', compact('enrollments'));
    // }
    public function showOnStudentDashboard()
{
    $userId = Auth::id();
        $enrollments = InternshipEnrollment::where('user_id', $userId)
            ->with('internship')
            ->get()
            ->map(function ($enrollment) {
                $total = $enrollment->internship ? $enrollment->internship->contents()->count() : 0;
                $completed = $enrollment->submissions()->count();
                $enrollment->progress = $total ? ($completed / $total) * 100 : 0;

                // Calculate average mark for completed submissions
                $averageMark = $enrollment->submissions()->avg('mark');
                $enrollment->average_mark = $averageMark ? number_format($averageMark, 2) : 'N/A';

                return $enrollment;
            });
           // dd($enrollments); // Debugging line to check the enrollments data

    return view('student.internshipdash', compact('enrollments'));
}

public function studentInternshipContent($enrollmentId)
    {
        $userId = Auth::id();
        $enrollment = DB::table('internship_enrollments')
        ->select('internship_enrollments.id', 'internship_enrollments.internship_id', 'internships.name')
        ->join('internships', 'internship_enrollments.internship_id', '=', 'internships.id')
        ->where('internship_enrollments.id', $enrollmentId)
        ->where('internship_enrollments.user_id', $userId)
        ->first();

        $contents = DB::table('internship_contents')
            ->select('internship_contents.id', 'internship_contents.title', 'internship_contents.description', 'internship_contents.file_path', 'internship_contents.deadline')
            ->where('internship_contents.internship_id', $enrollment->internship_id)
            ->get()
            ->map(function ($content) use ($enrollmentId) {
                // Check if submission exists for this content
                $submission = DB::table('internship_submissions')
                    ->select('submission_file')
                    ->where('internship_content_id', $content->id)
                    ->where('internship_enrollment_id', $enrollmentId)
                    ->first();

                $content->submission_file = $submission ? $submission->submission_file : null;
                return $content;
            });
        return view('student.internship.internship-content', compact('enrollment', 'contents'));
    }

    public function studentInternshipSubmit(Request $request, $contentId)
    {
        $userId = Auth::id();
     // Verify content exists and get enrollment
     $content = DB::table('internship_contents')
     ->select('internship_contents.id', 'internship_contents.internship_id')
     ->where('internship_contents.id', $contentId)
     ->first();

     $enrollment = DB::table('internship_enrollments')
            ->select('id')
            ->where('internship_id', $content->internship_id)
            ->where('user_id', $userId)
            ->first();

        // Store submission
        $filePath = $request->file('submission_file')->store('submissions', 'public');

        DB::table('internship_submissions')->insert([
            'internship_content_id' => $content->id,
            'user_id' => $userId,
            'internship_enrollment_id' => $enrollment->id,
            'submission_file' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('student.internship.content', $enrollment->id)
            ->with('success', 'Submission uploaded.');
    }

    public function getInternshipList(){
        $internships = Internship::all();
        return view('admin.internship-list-admin', compact('internships'));
        }


        public function internshipclasses()
{
    $internshipClasses = Internshipclass::with('recording.course')->get();
    return view('student.internshipclass', compact('internshipClasses'));
}
public function internshipDetails($id)
{
    // Slug directly parameter se mil gaya
    if (!$id || !is_string($id) || empty(trim($id))) {
        return view('website.internship_details')->with('error', 'Invalid or missing Internship Details!');
    }

    // Slug se course ki row database se fetch karo
    // $course = Course::where('slug', $slug)->first();
    // $course_details = CourseDetail::where('course_id', $course->id)->first();
    $course = Internship::where('id', $id)->first();
    $course_details = InternshipDetail::where('internship_id', $course->id)->first();
    $course_details?->loadMissing('internship');
    $instructorIds = $course_details->instructor_ids ?? [];
    $instructors = User::whereIn('id', array_filter($instructorIds))->get();
        // dd($course_details);
    if (!$course) {
        return view('website.internship_details')->with('error', 'internships not found!');
    }

    // Course details ke saath view pe bhejo
    return view('website.internship_details', [
        'course' => $course,
        'course_details' => $course_details,
        'instructors' => $instructors,
    ]);
}
}
