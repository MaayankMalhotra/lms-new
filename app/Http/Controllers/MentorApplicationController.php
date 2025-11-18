<?php

namespace App\Http\Controllers;

use App\Models\MentorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MentorApplicationController extends Controller
{
    public function index()
    {
        $applications = MentorApplication::latest()->paginate(15);

        return view('admin.mentor_applications.index', compact('applications'));
    }

    public function export()
    {
        $filename = 'mentor_applications_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Name',
            'Email',
            'Phone',
            'Specialization',
            'Teaching Hours',
            'Experience Years',
            'LinkedIn URL',
            'Portfolio URL',
            'Message',
            'Status',
            'Applied At',
        ];

        $callback = function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            MentorApplication::latest()->chunk(200, function ($applications) use ($handle) {
                foreach ($applications as $application) {
                    fputcsv($handle, [
                        $application->name,
                        $application->email,
                        $application->phone,
                        $application->specialization,
                        $application->teaching_hours,
                        $application->experience_years,
                        $application->linkedin_url,
                        $application->portfolio_url,
                        $application->message,
                        ucfirst($application->status ?? 'pending'),
                        optional($application->created_at)->toIso8601String(),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['nullable', 'email', 'max:255'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'teaching_hours'   => ['nullable', 'integer', 'min:0'],
            'specialization'   => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'linkedin_url'     => ['nullable', 'url', 'max:255'],
            'portfolio_url'    => ['nullable', 'url', 'max:255'],
            'message'          => ['nullable', 'string'],
        ]);

        MentorApplication::create($data);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok', 'message' => 'Application submitted'], 201);
        }

        return back()->with('success', 'Thanks for applying! Our team will get in touch soon.');
    }

    public function seedSample()
    {
        MentorApplication::create([
            'name' => 'Sample Mentor ' . now()->format('His'),
            'email' => 'mentor+' . Str::random(4) . '@example.com',
            'phone' => '+91' . rand(6000000000, 9999999999),
            'teaching_hours' => rand(100, 1200),
            'specialization' => collect(['AI/ML', 'Data Science', 'Cloud Computing', 'Cybersecurity'])->random(),
            'experience_years' => rand(1, 12),
            'linkedin_url' => 'https://linkedin.com/in/sample-mentor',
            'portfolio_url' => 'https://portfolio.example.com/sample',
            'message' => 'Demo data inserted from admin panel for preview.',
            'status' => 'pending',
        ]);

        return back()->with('success', 'Sample mentor application added.');
    }

    public function updateStatus(Request $request, MentorApplication $application)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $application->update(['status' => $data['status']]);

        return back()->with('success', 'Application status updated.');
    }
}
