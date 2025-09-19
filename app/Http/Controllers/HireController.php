<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\JobRolesForHiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return redirect()->route('hire.index')->with('success', 'Mentor application submitted successfully. Awaiting review.');
    }

    public function index()
    {
        $jobRoles = JobRolesForHiring::latest()->paginate(10);
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
        'tech_name.*' => 'required|string|max:255',
        'tech_url.*' => 'required|url|max:255',
    ], [
        'tech_name.*.required' => 'Technology name is required.',
        'tech_url.*.required' => 'Technology URL is required.',
        'tech_url.*.url' => 'Technology URL must be valid.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $techNames = $request->input('tech_name', []);
        $techUrls = $request->input('tech_url', []);
        $technologiesArray = [];

        foreach ($techNames as $index => $name) {
            if (isset($techUrls[$index]) && $name && $techUrls[$index]) {
                $technologiesArray[] = [
                    'name' => $name,
                    'image_url' => $techUrls[$index],
                ];
            }
        }

        if (empty($technologiesArray)) {
            return redirect()->back()->withErrors(['technologies' => 'At least one valid technology with name and URL is required.'])->withInput();
        }

        JobRolesForHiring::create([
            'title' => $request->title,
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
    $request->validate([
        'title' => 'required|string|max:255',
        'technologies' => 'required|json',
    ]);

    $technologies = json_decode($request->technologies, true);

    if (!is_array($technologies)) {
        return redirect()->back()->withErrors(['technologies' => 'Technologies must be a valid JSON array.'])->withInput();
    }

    foreach ($technologies as $tech) {
        if (!isset($tech['name']) || !is_string($tech['name'])) {
            return redirect()->back()->withErrors(['technologies' => 'Each technology must have a valid name.'])->withInput();
        }
        if (!isset($tech['image_url']) || !filter_var($tech['image_url'], FILTER_VALIDATE_URL)) {
            return redirect()->back()->withErrors(['technologies' => 'Each technology must have a valid image URL.'])->withInput();
        }
    }

    $jobRole = JobRolesForHiring::findOrFail($id);

    $jobRole->update([
        'title' => $request->title,
        'technologies' => $technologies, 
    ]);

    return redirect()->route('admin.job-roles.index')->with('success', 'Job role updated successfully.');
}


    public function destroy($id)
    {
        $jobRole = JobRolesForHiring::findOrFail($id);
        $jobRole->delete();

        return redirect()->route('admin.job-roles.index')->with('success', 'Job role deleted successfully.');
    }
}
