<?php

namespace App\Http\Controllers;

use App\Mail\WebinarCertificateMail;
use App\Mail\WebinarConfirmation;
use App\Models\Webinar;
use App\Models\WebinarEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WebinarController extends Controller
{
    // public function show()
    // {
    //     $webinars = Webinar::latest()->get();
    //     // Collect tags from all webinars, explode them, and remove duplicates
    //     $allTags = Webinar::pluck('tags')->implode(','); // Concatenate all tags into a single string
    //     $tagsArray = explode(',', $allTags); // Split the string into individual tags
    //     $uniqueTags = array_unique(array_map('trim', $tagsArray)); // Remove duplicates and trim spaces
    //     return view('website.webinars', compact('webinars','uniqueTags'));
    // }

    public function show(Request $request)
    {
        $selectedTag = $request->query('tag');

        // Filter webinars by selected tag if present
        $webinars = Webinar::when($selectedTag, function ($query) use ($selectedTag) {
            return $query->where('tags', 'LIKE', '%' . $selectedTag . '%');
        })->latest()->get();

        // Collect and clean all tags from all webinars
        $allTags = Webinar::pluck('tags')->implode(',');
        $tagsArray = array_filter(array_map('trim', explode(',', $allTags)));
        $uniqueTags = array_unique($tagsArray);

        return view('website.webinars', compact('webinars', 'uniqueTags', 'selectedTag'));
    }

    public function showWebinar($id)
    {
        $webinar = Webinar::findOrFail($id);
        return view('website.webinar.webinar_detail', compact('webinar'));
    }

    public function enroll(Request $request, $id)
    {
        $webinar = Webinar::where('id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'comments' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        WebinarEnrollment::create([
            'webinar_id' => $webinar->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'comments' => $request->comments,
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in the webinar!');
    }




    public function index(Request $request)
    {
        $query = Webinar::query();

        if ($tag = $request->query('tag')) {
            $query->where('tags', 'LIKE', '%' . $tag . '%');
        }
        $webinars = $query->latest()->paginate(10);

        $allTags = Webinar::pluck('tags')->implode(',');
        $tagsArray = array_filter(array_map('trim', explode(',', $allTags)));
        $uniqueTags = array_unique($tagsArray);
        return view('admin.webinar.index', compact('webinars', 'uniqueTags'));
    }

    public function create()
    {
        return view('admin.webinar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'start_time' => 'required|date',
            'registration_deadline' => 'required|date|after_or_equal:today',
            'entry_type' => 'required|string|max:255',
            'participants_count' => 'nullable|integer|min:0',
            'tags' => 'nullable|string|max:255',
        ]);

        Webinar::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'start_time' => $request->start_time,
            'registration_deadline' => $request->registration_deadline,
            'entry_type' => $request->entry_type,
            'participants_count' => $request->participants_count ?? 0,
            'tags' => $request->tags,
        ]);

        return redirect()->route('admin.webinar.index')->with('success', 'Webinar created successfully.');
    }
    public function edit($id)
    {
        $webinar = Webinar::findOrFail($id);
        return view('admin.webinar.edit', compact('webinar'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'start_time' => 'required|date',
            'registration_deadline' => 'required|date|after_or_equal:today',
            'entry_type' => 'required|string|max:255',
            'participants_count' => 'nullable|integer|min:0',
            'tags' => 'nullable|string|max:255',
        ]);

        $webinar = Webinar::findOrFail($id);
        $webinar->update($request->all());

        return redirect()->route('admin.webinar.index')->with('success', 'Webinar updated successfully!');
    }
    public function destroy($id)
    {
        $webinar = Webinar::findOrFail($id);
        $webinar->delete();

        return redirect()->route('admin.webinar.index')->with('success', 'Webinar deleted successfully!');
    }
    public function enrollments(Request $request)
    {
        // $enrollments = WebinarEnrollment::latest()->paginate(10);
        $webinars = Webinar::all(); // Fetch all webinars for the dropdown
        $query = WebinarEnrollment::query();
        if ($webinarId = $request->query('webinar_id')) {
            $query->where('webinar_id', $webinarId);
        }
        $enrollments = $query->with('webinar')->latest()->paginate(10);
        //dd($enrollments);
        return view('admin.webinar.webinar-enrollment', compact('enrollments', 'webinars'));
    }

    public function exportEnrollments(Request $request)
    {
        $filename = 'webinar_enrollments_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Webinar Title',
            'Webinar ID',
            'Name',
            'Email',
            'Phone',
            'Comments',
            'Attendance Status',
            'Certificate Sent',
            'Certificate Path',
            'Applied At',
        ];

        $webinarId = $request->query('webinar_id');

        $callback = function () use ($headers, $webinarId) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            WebinarEnrollment::with('webinar')
                ->when($webinarId, function ($query) use ($webinarId) {
                    $query->where('webinar_id', $webinarId);
                })
                ->orderBy('created_at', 'desc')
                ->chunk(200, function ($enrollments) use ($handle) {
                    foreach ($enrollments as $enrollment) {
                        fputcsv($handle, [
                            $enrollment->webinar->title ?? 'Untitled Webinar',
                            $enrollment->webinar_id,
                            $enrollment->name,
                            $enrollment->email,
                            $enrollment->phone,
                            $enrollment->comments,
                            ucfirst($enrollment->attendance_status ?? 'pending'),
                            $enrollment->certificate_sent ? 'Yes' : 'No',
                            $enrollment->certificate_path,
                            optional($enrollment->created_at)->toIso8601String(),
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
    public function sendConfirmation(Request $request)
    {
        $validated = $request->validate([
            'attendance_code' => 'required|string',
            'meeting_id' => 'required|string',
            'meeting_link' => 'required|url',
            'meeting_password' => 'required|string',
            'webinar_id' => 'nullable|exists:webinars,id',
        ]);
        // Fetch enrollments based on webinar_id
        $query = WebinarEnrollment::query();
        if ($validated['webinar_id']) {
            $query->where('webinar_id', $validated['webinar_id']);
        }
        $enrollments = $query->get();

        if ($enrollments->isEmpty()) {
            return response()->json(['message' => 'No enrollments found for the selected webinar'], 400);
        }

        // Update enrollments with confirmation data
        foreach ($enrollments as $enrollment) {
            $enrollment->update([
                'attendance_code' => $validated['attendance_code'],
                'meeting_id' => $validated['meeting_id'],
                'meeting_link' => $validated['meeting_link'],
                'meeting_password' => $validated['meeting_password'],
            ]);

            // Send email to each enrollee
            // Mail::to(['ashwani.rai@henryharvin.in', 'sandeep@henryharvin.in'])->send(new WebinarConfirmation($validated, $enrollment));
            Mail::to($enrollment->email)->send(new WebinarConfirmation($validated, $enrollment));
        }

        return response()->json(['message' => 'Confirmation emails sent and data saved successfully']);
    }

    public function verifyPresence(Request $request)
    {
        $email = $request->email;
        $webinar_title = $request->webinar;
        return view('verify_webinar_presence_form', compact('email', 'webinar_title'));
    }
    public function attendanceSubmitWebinar(Request $request)
    {
        // Optional but recommended validation
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'webinar_title' => 'required|string'
        ]);

        // Find the correct record
        $attendance_check = WebinarEnrollment::where('email', $request->email)
            ->where('attendance_code', $request->code)
            ->whereHas('webinar', function ($query) use ($request) {
                $query->where('title', $request->webinar_title);
            })
            ->first();

        if ($attendance_check) {
            $attendance_check->attendance_status = "present";
            $attendance_check->save();

            return view('webinar_submit_success_msg', [
                'name' => $attendance_check->name,
                'webinar_title' => $request->webinar_title
            ]);
        }

        // If not found, redirect back with error
        return back()->withErrors(['Invalid code or webinar title.']);
    }

    public function sendWebinarCertificate(Request $request, $enrollmentId)
    {


        $enrollment = WebinarEnrollment::with('webinar')->findOrFail($enrollmentId);

        if (!$enrollment->certificate_id) {
            $userId = $enrollment->id;
            $webinarId = $enrollment->webinar_id;
            // Get the webinar's start date from the related model
            $startDate = optional($enrollment->webinar)->start_time;

            // Format the date as YYYYMMDD, or use '00000000' if missing
            $formattedDate = $startDate ? $startDate->format('Ymd') : '00000000';

            // Generate a readable and stable certificate ID
            $certificateId = "CERT-{$userId}-{$webinarId}-{$formattedDate}";
            $enrollment->certificate_id = $certificateId;
            $enrollment->save();
        } else {
            $certificateId = $enrollment->certificate_id;
        }


        if ($enrollment->certificate_sent) {
            // Check if the certificate file still exists
            $existingPath = public_path(parse_url($enrollment->certificate_path, PHP_URL_PATH));
            if (file_exists($existingPath)) {
                return back()->with('success', 'Certificate already sent and available at: ' . $enrollment->certificate_path);
            } else {
                // Certificate was sent before but file is missing, regenerate
                $enrollment->certificate_sent = false;
            }
        }

        if ($enrollment->attendance_status !== 'present') {
            return back()->withErrors(['Certificate can only be sent to attendees marked as present.']);
        }

        $date = Carbon::now();
        $day = $date->format('d');
        $month = strtoupper($date->format('F'));
        $year = $date->format('Y');
        // $finalTextDate = "GIVEN ON THE $day DAY OF $month, $year";
        $finalTextDate = "$day - $month - $year";


        $img_url = public_path('images/certificate Final.jpg');
        $font = public_path('DejaVuSans-Bold.ttf');

        // Check font file exists
        if (!file_exists($font)) {
            return back()->withErrors(['Font not found at: ' . $font]);
        }

        // Check image file exists
        if (!file_exists($img_url)) {
            return back()->withErrors(['Certificate template image not found at: ' . $img_url]);
        }

        $img = imagecreatefromjpeg($img_url);
        if (!$img) {
            return back()->withErrors(['Failed to load certificate image.']);
        }

        $color = imagecolorallocate($img, 0, 0, 0);

        $name = ucwords(strtolower(trim($enrollment->name)));
        $webinar_title = $enrollment->webinar->title ?? 'Webinar Participant';
        // $duration = $enrollment->webinar->duration ?? 'N/A';
        // Convert duration (e.g., "2.5hr") to hours and minutes
        $durationRaw = $enrollment->webinar->duration ?? '0';
        $numericDuration = floatval($durationRaw); // convert "2.5hr" to 2.5
        $hours = floor($numericDuration);
        $minutes = round(($numericDuration - $hours) * 60);
        $duration = sprintf('%d hr%s %d min%s', $hours, $hours !== 1 ? 's' : '', $minutes, $minutes !== 1 ? 's' : '');

        // Draw text on image
        imagettftext($img, 30, 0, 2320, 1650, $color, $font, $certificateId);
        imagettftext($img, 40, 0, 2130, 1990, $color, $font, $name);
        imagettftext($img, 40, 0, 2215, 2075, $color, $font, $webinar_title);
        imagettftext($img, 40, 0, 1300, 3000, $color, $font, $finalTextDate);
        imagettftext($img, 40, 0, 1500, 3220, $color, $font, $duration);


        // // Get the width of the image
        // $imageWidth = imagesx($img);
        // $angle = 0;

        // // Calculate X for $name (centered)
        // $fontSizeName = 80;
        // $bboxName = imagettfbbox($fontSizeName, $angle, $font, $name);
        // $textWidthName = abs($bboxName[2] - $bboxName[0]);
        // $xName = ($imageWidth / 2) - ($textWidthName / 2);
        // imagettftext($img, $fontSizeName, $angle, $xName, 2000, $color, $font, $name);

        // // Calculate X for $webinar_title (centered)
        // $fontSizeTitle = 80;
        // $bboxTitle = imagettfbbox($fontSizeTitle, $angle, $font, $webinar_title);
        // $textWidthTitle = abs($bboxTitle[2] - $bboxTitle[0]);
        // $xTitle = ($imageWidth / 2) - ($textWidthTitle / 2);
        // imagettftext($img, $fontSizeTitle, $angle, $xTitle, 2100, $color, $font, $webinar_title);

        // // Calculate X for $finalTextDate (centered)
        // $fontSizeDate = 80;
        // $bboxDate = imagettfbbox($fontSizeDate, $angle, $font, $finalTextDate);
        // $textWidthDate = abs($bboxDate[2] - $bboxDate[0]);
        // $xDate = ($imageWidth / 2) - ($textWidthDate / 2);
        // imagettftext($img, $fontSizeDate, $angle, $xDate, 3000, $color, $font, $finalTextDate);



        // Always define $tempPath before use
        $fileName = uniqid('cert_') . '.jpg';
        $tempDir = public_path('certificate');
        $tempPath = $tempDir . '/' . $fileName;

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true); // Create directory if missing
        }

        // Save the image
        imagejpeg($img, $tempPath);
        imagedestroy($img);

        // Check file was written
        if (!file_exists($tempPath)) {
            return back()->withErrors(['Failed to generate certificate file.']);
        }

        // Generate the public URL for storing in DB or displaying
        $publicUrl = asset('certificate/' . $fileName);

        try {
            // Send the certificate by email
            Mail::to($enrollment->email)->send(new WebinarCertificateMail($enrollment, $publicUrl, $duration, $certificateId));
            $enrollment->update([
                'certificate_sent' => true,
                'certificate_sent_at' => now(),
                'certificate_path' => 'certificate/' . $fileName, // relative path to certificate in public folder
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['Failed to send email: ' . $e->getMessage()]);
        }

        // // Clean up temp file
        // @unlink($tempPath);

        return back()->with('success', 'Certificate sent successfully to ' . $enrollment->email);
    }
}
