<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentOrderController extends Controller
{
    public function index(): View
    {
        $orders = Enrollment::query()
            ->select([
                'enrollments.id',
                'enrollments.created_at as ordered_at',
                'enrollments.status as enrollment_status',
                'batches.batch_name',
                'batches.start_date',
                'batches.duration',
                'batches.price as batch_price',
                'courses.name as course_name',
                'payments.amount',
                'payments.status as payment_status',
                'payments.payment_method',
            ])
            ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
            ->join('courses', 'batches.course_id', '=', 'courses.id')
            ->leftJoin('payments', 'enrollments.id', '=', 'payments.enrollment_id')
            ->where('enrollments.user_id', Auth::id())
            ->orderByDesc('enrollments.created_at')
            ->get()
            ->map(function ($order) {
                $order->ordered_on = Carbon::parse($order->ordered_at)->format('d M Y');
                $order->start_date_formatted = $order->start_date
                    ? Carbon::parse($order->start_date)->format('d M Y')
                    : null;

                $durationMonths = $this->durationInMonths($order->duration);
                $order->duration_label = $order->duration ?? ($durationMonths ? $durationMonths . ' months' : null);

                $order->end_date_formatted = ($order->start_date && $durationMonths)
                    ? Carbon::parse($order->start_date)->copy()->addMonths($durationMonths)->format('d M Y')
                    : null;

                $order->display_amount = $order->amount ?? $order->batch_price ?? 0;

                return $order;
            });

        return view('student.orders', compact('orders'));
    }

    protected function durationInMonths($duration): ?int
    {
        if ($duration === null) {
            return null;
        }

        if (is_numeric($duration)) {
            return max(1, (int) $duration);
        }

        if (preg_match('/(\d+)/', (string) $duration, $matches)) {
            return max(1, (int) $matches[1]);
        }

        return null;
    }
}
