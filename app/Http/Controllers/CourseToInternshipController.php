<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Models\InternshipEnrollment;
use App\Mail\OfferLetter;

class CourseToInternshipController extends Controller
{
    public function index()
    {
        $enrollments = DB::table('enrollments')
            ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->where('batches.start_date', '<', now())
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.phone',
                'users.internship'
            )
            ->distinct('enrollments.user_id')
            ->get();

        // NEW: fetch batches & courses for the dropdowns
        $batches = DB::table('batches')
            ->select('id', 'batch_name', 'start_date')
            ->orderByDesc('start_date')
            ->get();

        $courses = DB::table('courses')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('enrollments.report', compact('enrollments', 'batches', 'courses'));
    }

    public function sendOfferLetter(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id'  => 'required|exists:users,id',
                'email'    => 'required|email',
                'name'     => 'required|string|max:255',
                'batch_id' => 'required|exists:batches,id',   // ✅ NEW
                'course_id'=> 'required|exists:courses,id',   // ✅ NEW
            ]);

            $user = DB::table('users')->where('id', $validated['user_id'])->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                    'error_code' => 'USER_NOT_FOUND'
                ], 404);
            }

            if ($user->internship) {
                return response()->json([
                    'success' => false,
                    'message' => 'Internship offer already sent to this user.',
                    'error_code' => 'OFFER_ALREADY_SENT'
                ], 400);
            }

            // Prevent duplicate enrollment for same user+batch+course
            $existingEnrollment = InternshipEnrollment::where('user_id', $validated['user_id'])
                ->where('batch_id', $validated['batch_id'])
                ->where('course_id', $validated['course_id'])
                ->first();

            if ($existingEnrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enrollment already exists for this user for the selected batch & course.',
                    'error_code' => 'ENROLLMENT_EXISTS'
                ], 400);
            }

            // Create enrollment with batch & course
            $enrollment = InternshipEnrollment::create([
                'user_id'      => $validated['user_id'],
                'email'        => $validated['email'],
                'name'         => $validated['name'],
                'batch_id'     => $validated['batch_id'],   // ✅ NEW
                'course_id'    => $validated['course_id'],  // ✅ NEW
                'payment_id'   => null,
                'amount'       => 0,
                'status'       => 'pending',
                'free_internship_after_course' => 1,
            ]);

            DB::table('users')->where('id', $validated['user_id'])->update(['internship' => 1]);

            // Mail::to($validated['email'])->send(new OfferLetter($validated['name']));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Offer letter sent successfully.',
                    'enrollment_id' => $enrollment->id
                ]);
            }

            return redirect()->route('enrollment.report')->with('success', 'Offer letter sent successfully.');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'error_code' => 'VALIDATION_ERROR',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Offer letter sending failed: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send offer letter: ' . $e->getMessage(),
                    'error_code' => 'SERVER_ERROR'
                ], 500);
            }
            return back()->with('error', 'Failed to send offer letter.');
        }
    }
}
