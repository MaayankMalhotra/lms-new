<?php

namespace App\Http\Controllers;

use App\Models\MentorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        $previousStatus = $application->status;
        $application->update(['status' => $data['status']]);

        if ($previousStatus !== $data['status'] && !empty($application->email)) {
            $statusLabel = ucfirst($data['status']);
            $previousLabel = $previousStatus ? ucfirst($previousStatus) : 'Pending';
            $safeName = e($application->name ?: 'there');
            $safeEmail = e($application->email);

            $html = "
                <div style=\"font-family: Arial, sans-serif; color:#1f2937; background:#f8fafc; padding:20px;\">
                    <div style=\"background:#ffffff; border-radius:8px; padding:20px; border:1px solid #e5e7eb;\">
                        <h2 style=\"margin:0 0 12px; font-size:18px;\">Mentor application status update</h2>
                        <p style=\"margin:0 0 12px;\">Hi {$safeName},</p>
                        <p style=\"margin:0 0 12px;\">Your mentor application status has been updated.</p>
                        <table style=\"border-collapse: collapse; width:100%; margin:12px 0;\">
                            <tr>
                                <td style=\"padding:8px; border:1px solid #e5e7eb; width:140px;\"><strong>Previous</strong></td>
                                <td style=\"padding:8px; border:1px solid #e5e7eb;\">{$previousLabel}</td>
                            </tr>
                            <tr>
                                <td style=\"padding:8px; border:1px solid #e5e7eb;\"><strong>Current</strong></td>
                                <td style=\"padding:8px; border:1px solid #e5e7eb;\">{$statusLabel}</td>
                            </tr>
                            <tr>
                                <td style=\"padding:8px; border:1px solid #e5e7eb;\"><strong>Contact</strong></td>
                                <td style=\"padding:8px; border:1px solid #e5e7eb;\">{$safeEmail}</td>
                            </tr>
                        </table>
                        <p style=\"margin:12px 0 0;\">We will contact you if we need more information.</p>
                    </div>
                </div>
            ";

            try {
                Mail::html($html, function ($message) use ($application) {
                    $message->to($application->email)
                        ->subject('Mentor application status update');
                });
            } catch (\Throwable $e) {
                report($e);
                return back()->with('error', 'Status updated, but email could not be sent.');
            }
        }

        return back()->with('success', 'Application status updated.');
    }
}
