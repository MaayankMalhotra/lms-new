<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Student;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\QuizSet;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Event;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
       
        $userId = auth()->user()->id;
        
        $studentBatches = DB::table('users')
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
            ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
            ->where('users.role', 3)
            ->where('users.id', $userId) // 👈 filter for current student only
            ->select(
                'users.id as user_id',
                'users.name as student_name',
                'batches.id as batch_id',
                'batches.batch_name as batch_name',
                'enrollments.created_at as enrolled_on'
            )
            ->get();
            
$totalAssignments = DB::table('users')
    ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
    ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
    ->join('assignments', 'batches.id', '=', 'assignments.batch_id')
    ->where('users.id', $userId)
    ->distinct('assignments.id') // 👈 unique assignments only
    ->count('assignments.id');   // 👈 count unique ids



$quizSets = QuizSet::with(['batch.course'])
    ->whereIn('batch_id', auth()->user()->enrollments()->pluck('batch_id'))
    ->get();

$payments = DB::table('payments')
    ->where('user_id', $userId)
    ->select(
        DB::raw("SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_completed"),
        DB::raw("SUM(CASE WHEN status != 'completed' THEN amount ELSE 0 END) as total_pending")
    )
    ->first();


       //dd($payments);

      
$events = DB::table('events')->get();

// Next installment for this student
$nextInstallment = null;

$paymentSchedules = Payment::where('user_id', $userId)
    ->leftJoin('batches', 'payments.batch_id', '=', 'batches.id')
    ->leftJoin('courses', 'batches.course_id', '=', 'courses.id')
    ->select('payments.*', 'batches.batch_name', 'courses.name as course_name')
    ->get();

foreach ($paymentSchedules as $payment) {
    // Normalize schedule
    $schedule = $payment->emi_schedule;
    if (is_string($schedule)) {
        $decoded = json_decode($schedule, true);
        $schedule = $decoded ?: [];
    }

    // Rebuild a simple schedule if missing but installments exist
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
            'payment_id'   => $payment->id,
            'due'          => $due ? $due->format('d M Y') : 'Due soon',
            'raw_due'      => $due,
            'amount'       => $candidate['amount'] ?? 0,
            'installment'  => $candidate['installment_number'] ?? null,
            'course'       => $payment->course_name ?? 'Course',
            'batch'        => $payment->batch_name ?? 'Batch',
        ];
    }
}

      
      //  $quizzes     = Quiz::where('batch_id', $student->batch_id)->where('status', 'pending')->count();
        //$exams       = Exam::where('batch_id', $student->batch_id)->orderBy('date', 'asc')->take(3)->get();
       // $fees        = Fee::where('student_id', $student->id)->latest()->first();
       // $events      = Event::orderBy('event_date', 'asc')->take(3)->get();

       return view('student.dashboard', [
    'studentBatches'   => $studentBatches,
    'totalAssignments' => $totalAssignments,
    'quizSets'         => $quizSets,
    'payments'         => $payments,
    'events'           => $events,
    'nextInstallment'  => $nextInstallment,
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
