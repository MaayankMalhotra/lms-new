<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Internship;
use App\Models\InternshipDetail;
use App\Models\CourseDetail;
use App\Models\Payment;
use App\Models\TrainerDetail;
use App\Models\User; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Illuminate\Support\Str;
class AdminController extends Controller
{
    public function trainer_management(){
        $trainers = User::where('role', 2)
                ->with('trainerDetail')
                ->get()
                ->map(function ($user) {
            $trainer = $user->trainerDetail;
            $courses = [];

            if ($trainer && $trainer->course_ids) {
                $decoded = json_decode($trainer->course_ids, true); // could be ["18,16,17"] or [18,16,17]
                $ids = [];

                // ✅ Handle both old (["18,16,17"]) and new ([18,16,17]) formats
                if (is_array($decoded)) {
                    if (count($decoded) === 1 && is_string($decoded[0]) && str_contains($decoded[0], ',')) {
                        $ids = explode(',', $decoded[0]);
                    } else {
                        $ids = $decoded;
                    }

                    $ids = array_map('intval', $ids); // ensure integers
                    $courses = Course::whereIn('id', $ids)->pluck('name')->toArray();
                }
            }
            $user->course_names = $courses ? implode(', ', $courses) : 'None';

            return $user;  // <-- Important: return the modified user!
        });
        $courses = Course::select('id', 'name')->get();
        $availableTrainers = User::where('role', 2)
            ->whereDoesntHave('trainerDetail')
            ->select('id', 'name', 'email')
            ->get();
        return view('admin.trainermanagement', compact('trainers', 'courses', 'availableTrainers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id,role,2',
            'experience' => 'required|string|max:255',
            'teaching_hours' => 'required|integer|min:0',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        // Prevent duplicate TrainerDetail for the same user
        if (TrainerDetail::where('user_id', $validated['user_id'])->exists()) {
            return redirect()->back()->withErrors(['user_id' => 'This user is already a trainer.']);
        }

        TrainerDetail::create([
            'user_id' => $validated['user_id'],
            'experience' => $validated['experience'],
            'teaching_hours' => $validated['teaching_hours'],
            'course_ids' => !empty($validated['course_ids']) ? json_encode($validated['course_ids']) : null,
        ]);

        return redirect()->route('trainer-management')->with('success', 'Trainer created successfully!');
    }

    public function edit($id)
    {
        $trainerDetail = TrainerDetail::findOrFail($id);
        $courseIds = $trainerDetail->course_ids ? json_decode($trainerDetail->course_ids, true) : [];
        $courseIds = array_map('intval', $courseIds); // Convert to integers
        return response()->json([
            'id' => $trainerDetail->id,
            'user_id' => $trainerDetail->user_id,
            'experience' => $trainerDetail->experience,
            'teaching_hours' => $trainerDetail->teaching_hours,
            'course_ids' => $courseIds,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id,role,2',
            'experience' => 'required|string|max:255',
            'teaching_hours' => 'required|integer|min:0',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $trainerDetail = TrainerDetail::findOrFail($id);

        // Check if another trainer detail exists for the same user_id (excluding current record)
        if (TrainerDetail::where('user_id', $validated['user_id'])->where('id', '!=', $id)->exists()) {
            return response()->json(['errors' => ['user_id' => ['This user is already a trainer.']]], 422);
        }

        $trainerDetail->update([
            'user_id' => $validated['user_id'],
            'experience' => $validated['experience'],
            'teaching_hours' => $validated['teaching_hours'],
            'course_ids' => !empty($validated['course_ids']) ? json_encode($validated['course_ids']) : null,
        ]);

        return response()->json(['message' => 'Trainer updated successfully!']);
    }

    public function destroy($id)
    {
        $trainerDetail = TrainerDetail::findOrFail($id);
        $trainerDetail->delete();
        if (request()->ajax()) {
        return response()->json(['success' => true, 'message' => 'Trainer deleted successfully']);
        }
        return redirect()->route('trainer-management')->with('success', 'Trainer deleted successfully!');
    }


public function student_management()
    {
        $students = User::where('role', 3)->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone ?? 'N/A',
                'created_at' => $student->created_at,
            ];
        });

        return view('admin.studentmanagement', compact('students'));
    }

    public function demoVideoUploader()
    {
        $courseDetails = CourseDetail::with('course')->get();
        $internshipDetails = InternshipDetail::with('internship')->get();

        return view('admin.demo-video-uploader', compact('courseDetails', 'internshipDetails'));
    }

    public function editStudent($id)
    {
        $student = User::findOrFail($id);

        return response()->json([
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'phone' => $student->phone ?? '',
        ]);
    }

    public function updateStudent(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
            ]);

            $student = User::findOrFail($id);
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            return response()->json(['message' => 'Student updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Update Student Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update student'], 500);
        }
    }

    public function deleteStudent($id)
    {
        try {
            $student = User::findOrFail($id);
            $student->delete();
            return response()->json(['message' => 'Student deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Delete Student Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete student'], 500);
        }
    }









    public function home()
    {
        $placements = DB::select("SELECT * FROM home_placements");
        $courses = DB::select("SELECT * FROM home_courses");
        $upcomingCourses = DB::select("SELECT * FROM home_upcoming_courses");
        $internships = DB::select("SELECT * FROM home_internships");
        $instructors = DB::select("SELECT * FROM home_instructors");
        $testimonials = DB::select("SELECT * FROM home_testimonials");
        $faqs = DB::select("SELECT * FROM home_faqs");

        return view('admin.home', compact(
            'placements', 'courses', 'upcomingCourses', 'internships',
            'instructors', 'testimonials', 'faqs'
        ));
    }

    public function pendingSummary(Request $request)
    {
        $courseId = $request->query('course_id');
        $batchId = $request->query('batch_id');

        $baseQuery = Payment::query()->where('status', 'pending');

        if ($courseId) {
            $baseQuery->whereHas('batch', fn($q) => $q->where('course_id', $courseId));
        }
        if ($batchId) {
            $baseQuery->where('batch_id', $batchId);
        }

        $totalPendingAmount = (clone $baseQuery)->sum('amount');
        $pendingCount = (clone $baseQuery)->count();
        $nextDueRaw = (clone $baseQuery)->orderBy('created_at')->value('created_at');
        $nextDueDate = $nextDueRaw ? Carbon::parse($nextDueRaw)->format('d M, Y') : null;

        $filteredBatches = Batch::when($courseId, function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->orderBy('batch_name')
            ->get(['id', 'batch_name', 'course_id']);

        $topBatchAggregates = Payment::query()
            ->selectRaw('batch_id, SUM(amount) as total_amount, COUNT(*) as count')
            ->where('status', 'pending')
            ->when($courseId, fn($q) => $q->whereHas('batch', fn($q) => $q->where('course_id', $courseId)))
            ->when($batchId, fn($q) => $q->where('batch_id', $batchId))
            ->groupBy('batch_id')
            ->orderByDesc('total_amount')
            ->limit(3)
            ->get();

        $topBatches = $topBatchAggregates->map(function ($row) {
            $batch = Batch::find($row->batch_id);
            return [
                'name'   => $batch->batch_name ?? "Batch #{$row->batch_id}",
                'amount' => (float) $row->total_amount,
                'count'  => (int) $row->count,
            ];
        });

        return response()->json([
            'total_amount' => (float) $totalPendingAmount,
            'count'        => $pendingCount,
            'next_due'     => $nextDueDate,
            'batches'      => $filteredBatches,
            'top_batches'  => $topBatches,
        ]);
    }

    // Placements
//     public function storePlacement(Request $request)
//     {
//         dd($request->all());
//         // $request->validate([
//         //     'name' => 'required|string|max:255',
//         //     'qualification' => 'required|string|max:255',
//         //     'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
//         //     'tags' => 'nullable|string',
//         //     'company' => 'required|string|max:255',
//         //     'package' => 'required|string|max:255',
//         //     'is_active' => 'boolean',
//         // ]);
// //   dd($request->all());
//         $imagePath = $request->file('image')->store('images', 'public');
//         $imagePath='';
//         DB::insert("
//             INSERT INTO home_placements (name, qualification, image, tags, company, package, is_active, created_at, updated_at)
//             VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
//         ", [
//             $request->name,
//             $request->qualification,
//             $imagePath,
//             $request->tags,
//             $request->company,
//             $request->package,
//             $request->is_active ?? 1,
//         ]);

//         return redirect()->route('admin.home')->with('success', 'Placement added successfully.');
//     }

public function storePlacement(Request $request)
{
    // Validate the request
    // $request->validate([
    //     'name' => 'required|string|max:255',
    //     'qualification' => 'required|string|max:255',
    //     'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     'tags' => 'nullable|string',
    //     'company' => 'required|string|max:255',
    //     'package' => 'required|string|max:255',
    //     'is_active' => 'boolean',
    // ]);

    try {
        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('images', 'public');
           //sss dd($request->hasFile('image')&& $request->file('image')->isValid());
        } else {
            throw new \Exception('Image upload failed.');
        }

        // Insert into database
        $isActive = ($request->is_active ?? 1) ? 1 : 0;

        DB::insert("
            INSERT INTO home_placements (name, qualification, image, tags, company, package, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $request->name,
            $request->qualification,
            $imagePath,
            $request->tags,
            $request->company,
            $request->package,
            $isActive,
        ]);

        return redirect()->route('admin.home')->with('success', 'Placement added successfully.');
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Failed to store placement: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to add placement: ' . $e->getMessage())->withInput();
    }
}

    public function updatePlacement(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'company' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $placement = DB::select("SELECT * FROM home_placements WHERE id = ?", [$id])[0] ?? null;
        if (!$placement) {
            return redirect()->route('admin.home')->with('error', 'Placement not found.');
        }

        $imagePath = $placement->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('images', 'public');
        }

        DB::update("
            UPDATE home_placements
            SET name = ?, qualification = ?, image = ?, tags = ?, company = ?, package = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $request->name,
            $request->qualification,
            $imagePath,
            $request->tags,
            $request->company,
            $request->package,
            $request->is_active ?? 1,
            $id,
        ]);

        return redirect()->route('admin.home')->with('success', 'Placement updated successfully.');
    }

    public function deletePlacement($id)
    {
        $placement = DB::select("SELECT * FROM home_placements WHERE id = ?", [$id])[0] ?? null;
        if ($placement) {
            Storage::disk('public')->delete($placement->image);
            DB::delete("DELETE FROM home_placements WHERE id = ?", [$id]);
            return redirect()->route('admin.home')->with('success', 'Placement deleted successfully.');
        }
        return redirect()->route('admin.home')->with('error', 'Placement not found.');
    }

    // Courses
    public function storeCourse(Request $request)
    {
        if (!($request->hasFile('image') && $request->file('image')->isValid())) {
            return redirect()->back()->with('error', 'Please upload a valid course image.')->withInput();
        }

        $imagePath = $request->file('image')->store('home/courses', 'public');

        DB::insert("
            INSERT INTO home_courses (title, image, duration, placed_count, rating, student_count, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $request->title,
            $imagePath,
            $request->duration,
            $request->placed_count,
            $request->rating,
            $request->student_count,
            $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.home')->with('success', 'Course added successfully.');
    }

    public function updateCourse(Request $request, $id)
    {
        $course = DB::table('home_courses')->where('id', $id)->first();
        if (!$course) {
            return redirect()->route('admin.home')->with('error', 'Course not found.');
        }

        $imagePath = $course->image;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($imagePath && !Str::startsWith($imagePath, ['http://', 'https://'])) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('home/courses', 'public');
        } elseif ($request->filled('image') && Str::startsWith($request->image, ['http://', 'https://'])) {
            $imagePath = $request->image;
        }

        DB::update("
            UPDATE home_courses
            SET title = ?, image = ?, duration = ?, placed_count = ?, rating = ?, student_count = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $request->title,
            $imagePath,
            $request->duration,
            $request->placed_count,
            $request->rating,
            $request->student_count,
            $request->is_active ?? 1,
            $id,
        ]);

        return redirect()->route('admin.home')->with('success', 'Course updated successfully.');
    }

    public function deleteCourse($id)
    {
        $course = DB::table('home_courses')->where('id', $id)->first();
        if ($course && $course->image && !Str::startsWith($course->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($course->image);
        }

        DB::delete("DELETE FROM home_courses WHERE id = ?", [$id]);
        return redirect()->route('admin.home')->with('success', 'Course deleted successfully.');
    }

    // Upcoming Courses
    public function storeUpcomingCourse(Request $request)
    {
        if (!($request->hasFile('image') && $request->file('image')->isValid())) {
            return redirect()->back()->with('error', 'Please upload a valid upcoming course image.')->withInput();
        }

        $imagePath = $request->file('image')->store('home/upcoming-courses', 'public');

        DB::insert("
            INSERT INTO home_upcoming_courses (title, image, start_date, slots_open, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $request->title,
            $imagePath,
            $request->start_date,
            $request->slots_open ?? 1,
            $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.home')->with('success', 'Upcoming Course added successfully.');
    }

    public function updateUpcomingCourse(Request $request, $id)
    {
        $course = DB::table('home_upcoming_courses')->where('id', $id)->first();
        if (!$course) {
            return redirect()->route('admin.home')->with('error', 'Upcoming course not found.');
        }

        $imagePath = $course->image;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($imagePath && !Str::startsWith($imagePath, ['http://', 'https://'])) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('home/upcoming-courses', 'public');
        } elseif ($request->filled('image') && Str::startsWith($request->image, ['http://', 'https://'])) {
            $imagePath = $request->image;
        }

        DB::update("
            UPDATE home_upcoming_courses
            SET title = ?, image = ?, start_date = ?, slots_open = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $request->title,
            $imagePath,
            $request->start_date,
            $request->slots_open ?? 1,
            $request->is_active ?? 1,
            $id,
        ]);

        return redirect()->route('admin.home')->with('success', 'Upcoming Course updated successfully.');
    }

    public function deleteUpcomingCourse($id)
    {
        $course = DB::table('home_upcoming_courses')->where('id', $id)->first();
        if ($course && $course->image && !Str::startsWith($course->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($course->image);
        }

        DB::delete("DELETE FROM home_upcoming_courses WHERE id = ?", [$id]);
        return redirect()->route('admin.home')->with('success', 'Upcoming Course deleted successfully.');
    }

    // Internships
    public function storeInternship(Request $request)
    {
        if (!($request->hasFile('image') && $request->file('image')->isValid())) {
            return redirect()->back()->with('error', 'Please upload a valid internship image.')->withInput();
        }

        $imagePath = $request->file('image')->store('home/internships', 'public');

        DB::insert("
            INSERT INTO home_internships (title, image, duration, project_count, rating, applicant_count, certification, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $request->title,
            $imagePath,
            $request->duration,
            $request->project_count,
            $request->rating ?? 0,
            $request->applicant_count ?? 0,
            $request->certification ?? '',
            $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.home')->with('success', 'Internship added successfully.');
    }

    public function updateInternship(Request $request, $request_id)
    {
        $internship = DB::table('home_internships')->where('id', $request_id)->first();
        if (!$internship) {
            return redirect()->route('admin.home')->with('error', 'Internship not found.');
        }

        $imagePath = $internship->image;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($imagePath && !Str::startsWith($imagePath, ['http://', 'https://'])) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('home/internships', 'public');
        } elseif ($request->filled('image') && Str::startsWith($request->image, ['http://', 'https://'])) {
            $imagePath = $request->image;
        }

        DB::update("
            UPDATE home_internships
            SET title = ?, image = ?, duration = ?, project_count = ?, rating = ?, applicant_count = ?, certification = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $request->title,
            $imagePath,
            $request->duration,
            $request->project_count,
            $request->rating ?? $internship->rating,
            $request->applicant_count ?? $internship->applicant_count,
            $request->certification ?? $internship->certification,
            $request->is_active ?? 1,
            $request_id,
        ]);

        return redirect()->route('admin.home')->with('success', 'Internship updated successfully.');
    }

    public function deleteInternship($id)
    {
        $internship = DB::table('home_internships')->where('id', $id)->first();
        if ($internship && $internship->image && !Str::startsWith($internship->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($internship->image);
        }

        DB::delete("DELETE FROM home_internships WHERE id = ?", [$id]);
        return redirect()->route('admin.home')->with('success', 'Internship deleted successfully.');
    }

    // Instructors
    public function storeInstructor(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'teaching_hours' => 'required|integer|min:0',
        //     'specialization' => 'required|string|max:255',
        //     'linkedin_url' => 'nullable|url|max:255',
        //     'facebook_url' => 'nullable|url|max:255',
        //     'is_active' => 'boolean',
        // ]);

        $imagePath = $request->file('image')->store('instructors', 'public');

        DB::insert("
            INSERT INTO home_instructors (name, image, teaching_hours, specialization, linkedin_url, facebook_url, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $request->name,
            $imagePath,
            $request->teaching_hours,
            $request->specialization,
            $request->linkedin_url,
            $request->facebook_url,
            $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.home')->with('success', 'Instructor added successfully!');
    }

    public function updateInstructor(Request $request, $request_id)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'teaching_hours' => 'required|integer|min:0',
        //     'specialization' => 'required|string|max:255',
        //     'linkedin_url' => 'nullable|url|max:255',
        //     'facebook_url' => 'nullable|url|max:255',
        //     'is_active' => 'boolean',
        // ]);

        $instructor = DB::select("SELECT * FROM home_instructors WHERE id = ?", [$request_id])[0] ?? null;
        if (!$instructor) {
            return redirect()->route('admin.home')->with('error', 'Instructor not found.');
        }

        $imagePath = $instructor->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('instructors', 'public');
        }

        DB::update("
            UPDATE home_instructors
            SET name = ?, image = ?, teaching_hours = ?, specialization = ?, linkedin_url = ?, facebook_url = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $request->name,
            $imagePath,
            $request->teaching_hours,
            $request->specialization,
            $request->linkedin_url,
            $request->facebook_url,
            $request->is_active ?? 1,
            $request_id,
        ]);

        return redirect()->route('admin.home')->with('success', 'Instructor updated successfully.');
    }

    public function deleteInstructor($id)
    {
        $instructor = DB::select("SELECT * FROM home_instructors WHERE id = ?", [$id])[0] ?? null;
        if ($instructor) {
            Storage::disk('public')->delete($instructor->image);
            DB::delete("DELETE FROM home_instructors WHERE id = ?", [$id]);
            return redirect()->home()->with('success', 'Instructor deleted successfully.');
        }
        return redirect()->home()->with('error', 'Instructor not found.');
    }

    // Testimonials
    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required|string',
            'designation' => 'required|string|max:255',
            'rating' => 'required|numeric|min:0|max:5',
            'is_active' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('testimonials', 'public');
        }

        DB::insert("
            INSERT INTO home_testimonials (name, image, content, designation, rating, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $validated['name'],
            $imagePath,
            $validated['content'],
            $validated['designation'],
            $validated['rating'],
            $validated['is_active'] ?? 1,
        ]);

        return redirect()->route('admin.home')->with('success', 'Testimonial added successfully.');
    }

    public function updateTestimonial(Request $request, $request_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required|string',
            'designation' => 'required|string|max:255',
            'rating' => 'required|numeric|min:0|max:5',
            'is_active' => 'boolean',
        ]);

        $testimonial = DB::select("SELECT * FROM home_testimonials WHERE id = ?", [$request_id])[0] ?? null;
        if (!$testimonial) {
            return redirect()->route('admin.home')->with('error', 'Testimonial not found.');
        }

        $imagePath = $testimonial->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('testimonials', 'public');
        }

        DB::update("
            UPDATE home_testimonials
            SET name = ?, image = ?, content = ?, designation = ?, rating = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $request->name,
            $imagePath,
            $request->content,
            $request->designation,
            $request->rating,
            $request->is_active ?? 1,
            $request_id,
        ]);

        return redirect()->route('admin.home')->with('success', 'Testimonial updated successfully.');
    }

    public function deleteTestimonial($id)
    {
        $testimonial = DB::select("SELECT * FROM home_testimonials WHERE id = ?", [$id])[0] ?? null;
        if ($testimonial) {
            Storage::disk('public')->delete($testimonial->image);
            DB::delete("DELETE FROM home_testimonials WHERE id = ?", [$id]);
            return redirect()->route('admin.home')->with('success', 'Testimonial deleted successfully.');
        }
        return redirect()->route('admin.home')->with('error', 'Testimonial not found.');
    }

    // FAQs
    public function storeFaq(Request $request)
    {
        // $request->validate([
        //     'question' => 'required|string|max:255',
        //     'answer' => 'required|string',
        //     'is_active' => 'boolean',
        // ]);

        DB::insert("
            INSERT INTO home_faqs (question, answer, is_active, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW())
        ", [
            $request->question,
            $request->answer,
            $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.home')->with('success', 'FAQ added successfully.');
    }

    public function updateFaq(Request $request, $request_id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'boolean',
        ]);

        DB::update("
            UPDATE home_faqs
            SET question = ?, answer = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $request->question,
            $request->answer,
            $request->is_active ?? 1,
            $request_id,
        ]);

        return redirect()->route('admin.home')->with('success', 'FAQ updated successfully.');
    }

    public function deleteFaq($id)
    {
        DB::delete("DELETE FROM home_faqs WHERE id = ?", [$id]);
        return redirect()->route('admin.home')->with('success', 'FAQ deleted successfully.');
    }
}
