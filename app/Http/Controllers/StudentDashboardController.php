<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\QuizSet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        $enrollments = DB::table('enrollments as enrollments')
            ->leftJoin('batches', 'batches.id', '=', 'enrollments.batch_id')
            ->where('enrollments.user_id', $userId)
            ->select('enrollments.batch_id', 'batches.course_id')
            ->get();

        $batchIds = $enrollments
            ->pluck('batch_id')
            ->filter()
            ->unique()
            ->values();

        $courseIds = $enrollments
            ->pluck('course_id')
            ->filter()
            ->unique()
            ->values();

        $studentBatches = DB::table('users')
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
            ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
            ->where('users.role', 3)
            ->where('users.id', $userId)
            ->select(
                'users.id as user_id',
                'users.name as student_name',
                'batches.id as batch_id',
                'batches.batch_name as batch_name',
                'enrollments.created_at as enrolled_on'
            )
            ->get();

        $assignmentIds = collect();
        if ($batchIds->isNotEmpty() || $courseIds->isNotEmpty()) {
            $assignmentIds = DB::table('assignments')
                ->where(function ($query) use ($batchIds, $courseIds) {
                    if ($batchIds->isNotEmpty()) {
                        $query->whereIn('batch_id', $batchIds);
                    }
                    if ($courseIds->isNotEmpty()) {
                        if ($batchIds->isNotEmpty()) {
                            $query->orWhereIn('course_id', $courseIds);
                        } else {
                            $query->whereIn('course_id', $courseIds);
                        }
                    }
                })
                ->distinct()
                ->pluck('id');
        }

        $totalAssignments = $assignmentIds->count();

        $quizSets = QuizSet::with(['batch.course'])
            ->when($batchIds->isNotEmpty() || $courseIds->isNotEmpty(), function ($query) use ($batchIds, $courseIds) {
                $query->where(function ($subQuery) use ($batchIds, $courseIds) {
                    if ($batchIds->isNotEmpty()) {
                        $subQuery->whereIn('batch_id', $batchIds);
                    }
                    if ($courseIds->isNotEmpty()) {
                        if ($batchIds->isNotEmpty()) {
                            $subQuery->orWhereIn('course_id', $courseIds);
                        } else {
                            $subQuery->whereIn('course_id', $courseIds);
                        }
                    }
                });
            }, function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->get();

        $payments = DB::table('payments')
            ->where('user_id', $userId)
            ->select(
                DB::raw("SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_completed"),
                DB::raw("SUM(CASE WHEN status != 'completed' THEN amount ELSE 0 END) as total_pending")
            )
            ->first() ?? (object) ['total_completed' => 0, 'total_pending' => 0];

        $payments->total_completed = (float) ($payments->total_completed ?? 0);
        $payments->total_pending = (float) ($payments->total_pending ?? 0);

        $events = collect();
        if (Schema::hasTable('events')) {
            $events = DB::table('events')
                ->when(Schema::hasColumn('events', 'event_date'), function ($query) {
                    $query->whereDate('event_date', '>=', now()->toDateString())
                        ->orderBy('event_date', 'asc');
                }, function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->limit(8)
                ->get();
        }

        $nextInstallment = null;
        $paymentSchedules = Payment::where('user_id', $userId)
            ->leftJoin('batches', 'payments.batch_id', '=', 'batches.id')
            ->leftJoin('courses', 'batches.course_id', '=', 'courses.id')
            ->select('payments.*', 'batches.batch_name', 'courses.name as course_name')
            ->get();

        foreach ($paymentSchedules as $payment) {
            $schedule = $payment->emi_schedule;
            if (is_string($schedule)) {
                $decoded = json_decode($schedule, true);
                $schedule = $decoded ?: [];
            }

            if (!is_array($schedule) || empty($schedule)) {
                if ($payment->emi_installments && $payment->emi_amount) {
                    $startDate = $payment->created_at ? Carbon::parse($payment->created_at) : Carbon::now();
                    $schedule[] = [
                        'installment_number' => 1,
                        'amount' => (float) $payment->emi_amount,
                        'paid_date' => $startDate->toDateString(),
                        'status' => 'paid',
                    ];
                    for ($i = 1; $i < (int) $payment->emi_installments; $i++) {
                        $schedule[] = [
                            'installment_number' => $i + 1,
                            'amount' => (float) $payment->emi_amount,
                            'due_date' => $startDate->copy()->addMonths($i)->toDateString(),
                            'status' => 'pending',
                        ];
                    }
                } else {
                    continue;
                }
            }

            $pending = collect($schedule)->filter(function ($emi) {
                return ($emi['status'] ?? 'pending') !== 'paid';
            });

            if ($pending->isEmpty()) {
                continue;
            }

            $candidate = $pending->sortBy(function ($emi) {
                if (!empty($emi['due_date'])) {
                    return Carbon::parse($emi['due_date']);
                }

                return $emi['installment_number'] ?? PHP_INT_MAX;
            })->first();

            if (!$candidate) {
                continue;
            }

            $dueDate = $candidate['due_date'] ?? null;
            $due = $dueDate ? Carbon::parse($dueDate) : null;

            if (
                is_null($nextInstallment) ||
                ($due && isset($nextInstallment['raw_due']) && $due->lt($nextInstallment['raw_due']))
            ) {
                $nextInstallment = [
                    'payment_id' => $payment->id,
                    'due' => $due ? $due->format('d M Y') : 'Due soon',
                    'raw_due' => $due,
                    'amount' => $candidate['amount'] ?? 0,
                    'installment' => $candidate['installment_number'] ?? null,
                    'course' => $payment->course_name ?? 'Course',
                    'batch' => $payment->batch_name ?? 'Batch',
                ];
            }
        }

        $quizSetIds = $quizSets->pluck('id');

        $submittedAssignments = 0;
        if ($assignmentIds->isNotEmpty() && Schema::hasTable('assignment_submissions')) {
            $submittedAssignments = DB::table('assignment_submissions as submissions')
                ->where('submissions.user_id', $userId)
                ->whereIn('submissions.assignment_id', $assignmentIds)
                ->distinct('submissions.assignment_id')
                ->count('submissions.assignment_id');
        }

        $completedQuizSets = 0;
        if ($quizSetIds->isNotEmpty() && Schema::hasTable('student_quiz_set_attempts')) {
            $completedQuizSets = DB::table('student_quiz_set_attempts')
                ->where('user_id', $userId)
                ->whereIn('quiz_set_id', $quizSetIds)
                ->distinct('quiz_set_id')
                ->count('quiz_set_id');
        }

        $progressPercent = null;
        $trackableItems = $totalAssignments + $quizSets->count();
        if ($trackableItems > 0) {
            $completedItems = min($trackableItems, $submittedAssignments + $completedQuizSets);
            $progressPercent = (int) round(($completedItems / $trackableItems) * 100);
        }

        $nextLiveClass = null;
        if ($batchIds->isNotEmpty() && Schema::hasTable('live_classes')) {
            $upcomingLiveClass = DB::table('live_classes as live_classes')
                ->leftJoin('batches', 'batches.id', '=', 'live_classes.batch_id')
                ->leftJoin('courses', 'courses.id', '=', 'batches.course_id')
                ->whereIn('live_classes.batch_id', $batchIds)
                ->where('live_classes.class_datetime', '>=', now())
                ->orderBy('live_classes.class_datetime', 'asc')
                ->select(
                    'live_classes.topic',
                    'live_classes.class_datetime',
                    'batches.batch_name',
                    'courses.name as course_name'
                )
                ->first();

            if ($upcomingLiveClass) {
                $classAt = Carbon::parse($upcomingLiveClass->class_datetime);
                $nextLiveClass = [
                    'title' => $upcomingLiveClass->topic ?: 'Live Class',
                    'date' => $classAt->format('d M Y'),
                    'time' => $classAt->format('h:i A'),
                    'course' => $upcomingLiveClass->course_name ?? null,
                    'batch' => $upcomingLiveClass->batch_name ?? null,
                ];
            }
        }

        $nextCodingExam = null;
        if ($quizSetIds->isNotEmpty()) {
            $attemptedQuizSetIds = collect();
            if (Schema::hasTable('student_quiz_set_attempts')) {
                $attemptedQuizSetIds = DB::table('student_quiz_set_attempts')
                    ->where('user_id', $userId)
                    ->whereIn('quiz_set_id', $quizSetIds)
                    ->distinct()
                    ->pluck('quiz_set_id');
            }

            $nextQuizSet = QuizSet::query()
                ->whereIn('id', $quizSetIds)
                ->when($attemptedQuizSetIds->isNotEmpty(), function ($query) use ($attemptedQuizSetIds) {
                    $query->whereNotIn('id', $attemptedQuizSetIds);
                })
                ->orderBy('created_at', 'asc')
                ->first();

            if ($nextQuizSet) {
                $nextCodingExam = [
                    'title' => $nextQuizSet->title ?: 'Coding Test',
                    'date' => 'Available now',
                    'time' => $nextQuizSet->total_quizzes ? $nextQuizSet->total_quizzes . ' questions' : '',
                ];
            }
        }

        $nextMockInterview = null;
        if (Schema::hasTable('interview_bookings') && Schema::hasTable('available_slots')) {
            $upcomingMockInterview = DB::table('interview_bookings as interview_bookings')
                ->join('available_slots', 'available_slots.id', '=', 'interview_bookings.slot_id')
                ->leftJoin('courses', 'courses.id', '=', 'available_slots.course_id')
                ->where('interview_bookings.student_id', $userId)
                ->whereIn('interview_bookings.status', ['pending', 'confirmed'])
                ->where('available_slots.start_time', '>=', now())
                ->orderBy('available_slots.start_time', 'asc')
                ->select(
                    'available_slots.start_time',
                    'courses.name as course_name'
                );

            if (Schema::hasColumn('available_slots', 'mock_type')) {
                $upcomingMockInterview->addSelect('available_slots.mock_type');
            }

            $upcomingMockInterview = $upcomingMockInterview->first();

            if ($upcomingMockInterview) {
                $interviewAt = Carbon::parse($upcomingMockInterview->start_time);
                $mockType = $upcomingMockInterview->mock_type ?? 'Mock Interview';
                $courseName = $upcomingMockInterview->course_name ?? null;

                $nextMockInterview = [
                    'title' => $mockType,
                    'date' => $interviewAt->format('d M Y'),
                    'time' => $courseName
                        ? $interviewAt->format('h:i A') . ' | ' . $courseName
                        : $interviewAt->format('h:i A'),
                ];
            }
        }

        $placementUpdates = [];
        if (Schema::hasTable('home_placements')) {
            $placementUpdates = DB::table('home_placements')
                ->where('is_active', 1)
                ->orderByDesc('created_at')
                ->limit(3)
                ->get(['name', 'company', 'package', 'qualification'])
                ->map(function ($placement) {
                    $title = trim(($placement->name ?? 'Placement Update') . (!empty($placement->company) ? ' | ' . $placement->company : ''));

                    $summary = collect([
                        !empty($placement->qualification) ? $placement->qualification : null,
                        !empty($placement->package) ? 'Package: ₹' . $placement->package : null,
                    ])->filter()->implode(' | ');

                    return [
                        'title' => $title,
                        'description' => $summary,
                    ];
                })
                ->values()
                ->all();
        }

        return view('student.dashboard', [
            'studentBatches' => $studentBatches,
            'totalAssignments' => $totalAssignments,
            'quizSets' => $quizSets,
            'payments' => $payments,
            'events' => $events,
            'nextInstallment' => $nextInstallment,
            'progressPercent' => $progressPercent,
            'nextLiveClass' => $nextLiveClass,
            'nextCodingExam' => $nextCodingExam,
            'nextMockInterview' => $nextMockInterview,
            'placementUpdates' => $placementUpdates,
        ]);
    }

    public function payNextEmi(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $paymentSchedules = Payment::where('user_id', $user->id)->get();

        $targetPayment = null;
        $targetIndex = null;
        $targetEmi = null;

        foreach ($paymentSchedules as $payment) {
            $schedule = $payment->emi_schedule;
            if (is_string($schedule)) {
                $decoded = json_decode($schedule, true);
                $schedule = $decoded ?: [];
            }
            if (!is_array($schedule) || empty($schedule)) {
                if ($payment->emi_installments && $payment->emi_amount) {
                    $startDate = $payment->created_at ? Carbon::parse($payment->created_at) : Carbon::now();
                    $schedule[] = [
                        'installment_number' => 1,
                        'amount' => (float) $payment->emi_amount,
                        'paid_date' => $startDate->toDateString(),
                        'status' => 'paid',
                    ];
                    for ($i = 1; $i < (int) $payment->emi_installments; $i++) {
                        $schedule[] = [
                            'installment_number' => $i + 1,
                            'amount' => (float) $payment->emi_amount,
                            'due_date' => $startDate->copy()->addMonths($i)->toDateString(),
                            'status' => 'pending',
                        ];
                    }
                } else {
                    continue;
                }
            }

            foreach ($schedule as $idx => $emi) {
                if (($emi['status'] ?? 'pending') === 'paid') {
                    continue;
                }
                if ($targetEmi === null) {
                    $targetPayment = $payment;
                    $targetIndex = $idx;
                    $targetEmi = $emi;
                } else {
                    $currentDue = $emi['due_date'] ?? null;
                    $bestDue = $targetEmi['due_date'] ?? null;
                    if ($currentDue && $bestDue) {
                        if (Carbon::parse($currentDue)->lt(Carbon::parse($bestDue))) {
                            $targetPayment = $payment;
                            $targetIndex = $idx;
                            $targetEmi = $emi;
                        }
                    }
                }
            }
        }

        if (!$targetPayment || $targetIndex === null) {
            return response()->json(['message' => 'No pending EMI found'], 422);
        }

            $schedule = $targetPayment->emi_schedule;
            if (is_string($schedule)) {
                $decoded = json_decode($schedule, true);
                $schedule = $decoded ?: [];
            }

            $schedule[$targetIndex]['status'] = 'paid';
            $schedule[$targetIndex]['paid_date'] = Carbon::now()->toDateString();
            $targetPayment->emi_schedule = $schedule;
            $targetPayment->save();

        // Compute next pending after this payment
        $pending = collect($schedule)->filter(function ($emi) {
            return ($emi['status'] ?? 'pending') !== 'paid';
        });
        $next = $pending->sortBy(function ($emi) {
            if (!empty($emi['due_date'])) {
                return Carbon::parse($emi['due_date']);
            }
            return $emi['installment_number'] ?? PHP_INT_MAX;
        })->first();

        return response()->json([
            'message' => 'Next installment paid.',
            'paid_installment' => $targetEmi,
            'next_installment' => $next ? [
                'due_date' => $next['due_date'] ?? null,
                'amount' => $next['amount'] ?? 0,
                'installment_number' => $next['installment_number'] ?? null,
            ] : null,
        ]);
    }
}
