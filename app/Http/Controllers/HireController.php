<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\JobRoleApplication;
use App\Models\JobRolesForHiring;
use App\Models\MentorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HireController extends Controller
{
    public function show()
    {
        $jobRoles= JobRolesForHiring::all();
        $instructors=Instructor::where('is_active',1)->get();
        return view('website.hire_with_us',compact('jobRoles','instructors'));
    }

    public function storeMentor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
            'teaching_hours' => 'required|integer|min:0',
            'specialization' => 'required|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'phone_number' => 'required|string|digits:10',
            'email' => 'nullable|email|max:255',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'message' => 'nullable|string',
        ]);

        
        Instructor::create([
            'name' => $request->name,
            'image' => null, // Explicitly set to null since images are not stored
            'teaching_hours' => $request->teaching_hours,
            'specialization' => $request->specialization,
            'linkedin_url' => $request->linkedin_url,
            'facebook_url' => $request->facebook_url,
            'phone_number' => $request->phone_number,
            'is_active' => 0, // Set to inactive pending review
        ]);

        MentorApplication::create([
            'name' => $request->name,
            'email' => $request->input('email'),
            'phone' => $request->phone_number,
            'teaching_hours' => $request->teaching_hours,
            'specialization' => $request->specialization,
            'experience_years' => $request->input('experience_years'),
            'linkedin_url' => $request->linkedin_url,
            'portfolio_url' => $request->facebook_url,
            'message' => $request->input('message'),
            'status' => 'pending',
        ]);

        return redirect()->route('hire.show')->with('success', 'Mentor application submitted successfully. Awaiting review.');
    }

    public function seedSample()
    {
        $name = 'Demo Mentor ' . now()->format('His');
        $phone = (string) rand(6000000000, 9999999999);

        Instructor::create([
            'name' => $name,
            'image' => null,
            'teaching_hours' => rand(100, 1500),
            'specialization' => collect(['Web Development', 'Data Science', 'AI/ML', 'Cybersecurity'])->random(),
            'linkedin_url' => 'https://linkedin.com/in/demo-mentor',
            'facebook_url' => 'https://portfolio.example.com/demo',
            'phone_number' => $phone,
            'is_active' => 0,
        ]);

        MentorApplication::create([
            'name' => $name,
            'email' => 'demo.' . Str::lower(Str::random(4)) . '@example.com',
            'phone' => $phone,
            'teaching_hours' => rand(100, 1500),
            'specialization' => 'Full Stack Engineering',
            'experience_years' => rand(1, 10),
            'linkedin_url' => 'https://linkedin.com/in/demo-mentor',
            'portfolio_url' => 'https://portfolio.example.com/demo',
            'message' => 'Auto-generated demo submission for testing the mentor flow.',
            'status' => 'pending',
        ]);

        return back()->with('success', 'Sample mentor submission created successfully.');
    }

    public function studentJobRoles()
    {
        $jobRoles = JobRolesForHiring::latest()->get();
        $user = auth()->user();

        $applications = collect();
        $applicationsByJobRole = collect();

        if ($user) {
            $applications = JobRoleApplication::with('jobRole')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            $applicationsByJobRole = $applications->keyBy('job_role_id');
        }

        $applicationStatusOptions = $this->applicationStatusOptions();

        return view('student.job-roles.index', compact('jobRoles', 'applications', 'applicationsByJobRole', 'applicationStatusOptions'));
    }

    public function externalApply(Request $request, $jobRoleId)
    {
        $user = $request->user();

        if (!$user || $user->role !== 3) {
            abort(403, 'Only students can apply to job roles.');
        }

        $jobRole = JobRolesForHiring::findOrFail($jobRoleId);

        $existing = JobRoleApplication::where('job_role_id', $jobRole->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$existing) {
            $placeholderPath = 'job-role-applications/external-link.txt';
            if (!Storage::disk('public')->exists($placeholderPath)) {
                Storage::disk('public')->put($placeholderPath, 'Applied via external link');
            }

            JobRoleApplication::create([
                'job_role_id' => $jobRole->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'resume_path' => $placeholderPath,
                'message' => null,
                'status' => 'applied',
            ]);
        }

        return redirect()->away($jobRole->apply_link);
    }

    public function apply(Request $request, $jobRoleId)
    {
        $user = $request->user();

        if (!$user || $user->role !== 3) {
            abort(403, 'Only students can apply to job roles.');
        }

        $jobRole = JobRolesForHiring::findOrFail($jobRoleId);

        $alreadyApplied = JobRoleApplication::where('job_role_id', $jobRole->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyApplied) {
            return redirect()
                ->back()
                ->with('success', 'You have already applied for "' . $jobRole->title . '".');
        }

        $validated = $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'message' => 'nullable|string|max:2000',
        ]);

        $resumePath = $request->file('resume')->store('job-role-applications', 'public');

        JobRoleApplication::create([
            'job_role_id' => $jobRole->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'resume_path' => $resumePath,
            'message' => $validated['message'] ?? null,
            'status' => 'applied',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Application submitted successfully for "' . $jobRole->title . '".');
    }

    public function updateApplicationStatus(Request $request, JobRoleApplication $application)
    {
        $user = $request->user();

        if (!$user || $application->user_id !== $user->id) {
            abort(403, 'You are not allowed to update this application.');
        }

        $statuses = array_keys($this->applicationStatusOptions());

        $data = $request->validate([
            'status' => 'required|in:' . implode(',', $statuses),
        ]);

        $application->update([
            'status' => $data['status'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Application status updated.');
    }

    public function applications(Request $request)
    {
        abort_if(auth()->user()->role !== 1, 403);

        $jobRoleId = $request->query('job_role_id');
        $search = trim((string) $request->query('search'));

        $applications = JobRoleApplication::with(['jobRole', 'user'])
            ->when($jobRoleId, fn ($q) => $q->where('job_role_id', $jobRoleId))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        $jobRoles = JobRolesForHiring::orderBy('title')->get(['id', 'title']);

        return view('admin.hire-with-us.applications', compact('applications', 'jobRoles', 'jobRoleId', 'search'));
    }

    public function index()
    {
        $jobRoles = JobRolesForHiring::with(['applications' => function ($q) {
            $q->latest();
        }, 'applications.user'])->latest()->paginate(10);
        $instructors = Instructor::where('is_active', 1)->get();
        return view('admin.hire-with-us.index', compact('jobRoles', 'instructors'));
    }

    public function create()
    {
        return view('admin.hire-with-us.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'salary_package' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'apply_link' => 'required|url|max:2048',
            'image_url' => 'required|url|max:2048',
            'last_date_to_apply' => 'required|date',
            'suggestions' => 'nullable|string',
            'tech_name.*' => 'required|string|max:255',
        ], [
            'tech_name.*.required' => 'Technology name is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $technologiesArray = $this->buildTechnologiesPayload($request);

            if (empty($technologiesArray)) {
                return redirect()->back()->withErrors(['technologies' => 'Add at least one technology with a name and logo URL.'])->withInput();
            }

            JobRolesForHiring::create([
                'title' => $request->title,
                'company_name' => $request->company_name,
                'salary_package' => $request->salary_package,
                'location' => $request->location,
                'apply_link' => $request->apply_link,
                'image_url' => $request->image_url,
                'last_date_to_apply' => $request->last_date_to_apply,
                'suggestions' => $request->suggestions,
                'technologies' => $technologiesArray,
            ]);
            
            return redirect()->route('admin.job-roles.index')->with('success', 'Job role created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['technologies' => 'Error: ' . $e->getMessage()])->withInput();
        }
    }


    public function edit($id)
    {
        $jobRole = JobRolesForHiring::findOrFail($id);
        return view('admin.hire-with-us.edit', compact('jobRole'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'salary_package' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'apply_link' => 'required|url|max:2048',
            'image_url' => 'required|url|max:2048',
            'last_date_to_apply' => 'required|date',
            'suggestions' => 'nullable|string',
            'tech_name.*' => 'required|string|max:255',
        ], [
            'tech_name.*.required' => 'Technology name is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $technologies = $this->buildTechnologiesPayload($request);

        if (empty($technologies)) {
            return redirect()->back()->withErrors(['technologies' => 'Add at least one technology with a name and logo URL.'])->withInput();
        }

        $jobRole = JobRolesForHiring::findOrFail($id);

        $jobRole->update([
            'title' => $request->title,
            'company_name' => $request->company_name,
            'salary_package' => $request->salary_package,
            'location' => $request->location,
            'apply_link' => $request->apply_link,
            'image_url' => $request->image_url,
            'last_date_to_apply' => $request->last_date_to_apply,
            'suggestions' => $request->suggestions,
            'technologies' => $technologies, 
        ]);

        return redirect()->route('admin.job-roles.index')->with('success', 'Job role updated successfully.');
    }

    private function buildTechnologiesPayload(Request $request): array
    {
        $techNames = $request->input('tech_name', []);
        $defaultTechImageUrl = 'https://dummyimage.com/100x100/edf2f7/1f2937&text=Tech';
        $technologiesArray = [];

        foreach ($techNames as $index => $name) {
            $trimmedName = trim($name ?? '');

            if ($trimmedName) {
                $technologiesArray[] = [
                    'name' => $trimmedName,
                    'image_url' => $defaultTechImageUrl,
                ];
            }
        }

        return $technologiesArray;
    }

    private function applicationStatusOptions(): array
    {
        return [
            'applied' => 'Applied',
            'got_email' => 'Got email',
            'interview_scheduled' => 'Interview scheduled',
            'offer_received' => 'Offer received',
            'rejected' => 'Rejected',
            'no_response' => 'No response yet',
        ];
    }


    public function destroy($id)
    {
        $jobRole = JobRolesForHiring::findOrFail($id);
        $jobRole->delete();

        return redirect()->route('admin.job-roles.index')->with('success', 'Job role deleted successfully.');
    }
}
