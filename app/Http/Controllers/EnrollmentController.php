<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnrollmentController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Fetch filter options
    //     $students = User::where('role', 3)->select('name', 'email')->distinct()->get();
    //     $courses = Course::select('name')->distinct()->get();
    //     $batches = Batch::select('batch_name')->distinct()->get();

    //     // Build the enrollments query
    //     $query = Enrollment::select(
    //         'enrollments.id as enrollment_id',
    //         'enrollments.email as enrollment_email',
    //         'enrollments.status as enrollment_status',
    //         'enrollments.created_at as enrollment_created_at',
    //         'users.name as student_name',
    //         'users.email as student_email',
    //         'students.phone',
    //         'payments.payment_id',
    //         'payments.amount',
    //         'payments.status as payment_status',
    //         'payments.payment_method',
    //         'payments.emi_installments',
    //         'payments.emi_amount',
    //         'payments.emi_schedule',
    //         'batches.start_date',
    //         'batches.time_slot',
    //         'batches.price as batch_price',
    //         'batches.slots_available',
    //         'batches.slots_filled',
    //         'batches.batch_name as batch_name',
    //         'courses.name as course_name',
    //         'courses.price as course_price',
    //         'teachers.name as instructor_name'
    //     )
    //     ->join('users', 'enrollments.user_id', '=', 'users.id')
    //     ->join('students', 'enrollments.user_id', '=', 'students.user_id')
    //     ->join('payments', 'enrollments.id', '=', 'payments.enrollment_id')
    //     ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
    //     ->join('courses', 'batches.course_id', '=', 'courses.id')
    //     ->leftJoin('users as teachers', 'batches.teacher_id', '=', 'teachers.id')
    //     ->where('users.role', 3) // Only students (role = 3)
    //     ->orderBy('enrollments.created_at', 'desc');

    //     // Apply filters
    //     if ($request->filled('student_name')) {
    //         $query->where('users.name', $request->student_name);
    //     }

    //     if ($request->filled('student_email')) {
    //         $query->where('users.email', $request->student_email);
    //     }

    //     if ($request->filled('course_name')) {
    //         $query->where('courses.name', $request->course_name);
    //     }

    //     if ($request->filled('batch_name')) {
    //         $query->where('batches.name', $request->batch_name);
    //     }

    //     if ($request->filled('from_date')) {
    //         $query->whereDate('enrollments.created_at', '>=', $request->from_date);
    //     }

    //     if ($request->filled('to_date')) {
    //         $query->whereDate('enrollments.created_at', '<=', $request->to_date);
    //     }

    //     if ($request->filled('payment_status')) {
    //         $query->where('payments.status', $request->payment_status);
    //     }

    //     // Fetch enrollments
    //     $enrollments = $query->get()->map(function ($enrollment) {
    //         // Parse EMI schedule and calculate next due EMI
    //         if ($enrollment->payment_method === 'emi' && !empty($enrollment->emi_schedule)) {
    //             $emi_schedule = json_decode($enrollment->emi_schedule, true);

    //             // Check if $emi_schedule is a valid array
    //             if (is_array($emi_schedule) && !empty($emi_schedule)) {
    //                 $enrollment->emi_schedule_array = $emi_schedule;
    //                 $enrollment->total_emi = count($emi_schedule);

    //                 // Find next due EMI
    //                 $current_date = Carbon::now();
    //                 $next_emi = collect($emi_schedule)->filter(function ($emi) use ($current_date) {
    //                     return isset($emi['due_date']) && Carbon::parse($emi['due_date'])->isFuture() && $emi['status'] === 'pending';
    //                 })->sortBy('due_date')->first();

    //                 $enrollment->next_emi = $next_emi ? $next_emi : null;
    //             } else {
    //                 // Handle invalid or empty EMI schedule
    //                 $enrollment->emi_schedule_array = [];
    //                 $enrollment->total_emi = 0;
    //                 $enrollment->next_emi = null;
    //             }
    //         } else {
    //             $enrollment->emi_schedule_array = [];
    //             $enrollment->total_emi = 0;
    //             $enrollment->next_emi = null;
    //         }
    //         return $enrollment;
    //     });

    //     return view('admin.enrollments.index', compact('enrollments', 'students', 'courses', 'batches'));
    // }



    public function index(Request $request)
    {
       
        try {
            // Fetch filter options
            $students = User::where('role', 3)
                ->select('name', 'email')
                ->distinct()
                ->get();

            $courses = Course::select('name')
                ->distinct()
                ->get();

            $batches = Batch::select('batch_name')
                ->distinct()
                ->get();

            // Build the enrollments query
            $query = Enrollment::select([
                'enrollments.id as enrollment_id',
                'enrollments.email as enrollment_email',
                'enrollments.status as enrollment_status',
                'enrollments.created_at as enrollment_created_at',
                'users.name as student_name',
                'users.email as student_email',
                'students.phone',
                'payments.payment_id',
                'payments.amount',
                'payments.status as payment_status',
                'payments.payment_method',
                'payments.emi_installments',
                'payments.emi_amount',
                'payments.emi_schedule',
                'batches.start_date',
                'batches.time_slot',
                'batches.price as batch_price',
                'batches.slots_available',
                'batches.slots_filled',
                'batches.batch_name as batch_name',
                'courses.name as course_name',
                'courses.price as course_price',
                'teachers.name as instructor_name',
            ])
                ->join('users', 'enrollments.user_id', '=', 'users.id')
                ->join('students', 'enrollments.user_id', '=', 'students.user_id')
                ->join('payments', 'enrollments.id', '=', 'payments.enrollment_id')
                ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
                ->join('courses', 'batches.course_id', '=', 'courses.id')
                ->leftJoin('users as teachers', 'batches.teacher_id', '=', 'teachers.id')
                ->where('users.role', 3)
                ->orderBy('enrollments.created_at', 'desc');
              //  dd($query->get());

            // Apply filters
            if ($request->filled('student_name')) {
                $query->where('users.name', $request->student_name);
            }

            if ($request->filled('student_email')) {
                $query->where('users.email', $request->student_email);
            }

            if ($request->filled('course_name')) {
                $query->where('courses.name', $request->course_name);
            }

            if ($request->filled('batch_name')) {
                // Fix: Use batch_name instead of name
                $query->where('batches.batch_name', $request->batch_name);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('enrollments.created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('enrollments.created_at', '<=', $request->to_date);
            }

            if ($request->filled('payment_status')) {
                $query->where('payments.status', $request->payment_status);
            }
            $enrollments = $query->get();
       
            // Fetch enrollments
            $enrollments = $query->get()->map(function ($enrollment) {
                try {
                    // Parse EMI schedule and calculate next due EMI
                    if ($enrollment->payment_method === 'emi' && !empty($enrollment->emi_schedule)) {
                        $emi_schedule = json_decode($enrollment->emi_schedule, true);

                        if (is_array($emi_schedule) && !empty($emi_schedule)) {
                            $enrollment->emi_schedule_array = $emi_schedule;
                            $enrollment->total_emi = count($emi_schedule);

                            // Find next due EMI
                            $current_date = Carbon::now();
                            $next_emi = collect($emi_schedule)
                                ->filter(function ($emi) use ($current_date) {
                                    return isset($emi['due_date']) &&
                                        Carbon::parse($emi['due_date'])->isFuture() &&
                                        ($emi['status'] ?? 'pending') === 'pending';
                                })
                                ->sortBy('due_date')
                                ->first();

                            $enrollment->next_emi = $next_emi;
                        } else {
                            Log::warning('Invalid EMI schedule format', [
                                'enrollment_id' => $enrollment->enrollment_id,
                                'emi_schedule' => $enrollment->emi_schedule,
                            ]);
                            $enrollment->emi_schedule_array = [];
                            $enrollment->total_emi = 0;
                            $enrollment->next_emi = null;
                        }
                    } else {
                        $enrollment->emi_schedule_array = [];
                        $enrollment->total_emi = 0;
                        $enrollment->next_emi = null;
                    }

                    return $enrollment;
                } catch (\Exception $e) {
                    Log::error('Error processing EMI schedule for enrollment', [
                        'enrollment_id' => $enrollment->enrollment_id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    // Set default values to prevent view errors
                    $enrollment->emi_schedule_array = [];
                    $enrollment->total_emi = 0;
                    $enrollment->next_emi = null;
                    return $enrollment;
                }
            });

            return view('admin.enrollments.index', compact('enrollments', 'students', 'courses', 'batches'));
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database query error in enrollment index', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'trace' => $e->getTraceAsString(),
            ]);
            return view('admin.enrollments.index', [
                'enrollments' => collect(),
                'students' => collect(),
                'courses' => collect(),
                'batches' => collect(),
                'error' => 'Failed to load enrollments due to a database error. Please try again later.',
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error in enrollment index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return view('admin.enrollments.index', [
                'enrollments' => collect(),
                'students' => collect(),
                'courses' => collect(),
                'batches' => collect(),
                'error' => 'An unexpected error occurred. Please try again later.',
            ]);
        }
    }

}