<?php

namespace App\Http\Controllers;

use App\Models\AvailableSlot;
use App\Models\InterviewBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
   

    public function bookSlot__($slotId)
    {
        $slot = AvailableSlot::findOrFail($slotId);

        if ($slot->is_booked || $slot->start_time <= now()) {
            return redirect()->route('student.slots')->with('error', 'Slot unavailable.');
        }

        InterviewBooking::create([
            'student_id' => Auth::id(),
            'slot_id' => $slot->id,
        ]);

        $slot->update(['is_booked' => true]);

        return redirect()->route('student.slots')->with('success', 'Slot booked.');
    }

    public function joinInterview()
    {
        $booking = InterviewBooking::with('slot')
            ->where('student_id', Auth::id())
            ->where('status', 'confirmed')
            ->first();

        if (!$booking || $booking->meeting_link === null) {
            return view('student.interview')->with('error', 'No active interview or link not uploaded.');
        }

        // Check if current time is around the slot time (e.g., within 15 min before/after)
        $now = now();
        $start = $booking->slot->start_time instanceof \Carbon\Carbon
            ? $booking->slot->start_time->copy()
            : \Carbon\Carbon::parse($booking->slot->start_time);
        $end = $start->copy()->addMinutes($booking->slot->duration_minutes);
        if ($now->lt($start->copy()->subMinutes(15)) || $now->gt($end)) {
            return view('student.interview')->with('error', 'Not the right time to join.');
        }

        $booking->update([
            'status' => 'completed',
            'joined_at' => now(),
        ]);

        if ($booking->slot) {
            $booking->slot->update([
                'status' => 'completed',
                'is_booked' => true,
            ]);
        }

        return redirect($booking->meeting_link); // Or display in view if needed.
    }

    public function joinInterviewByBooking(InterviewBooking $booking)
    {
        if ($booking->student_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load('slot');

        if (!$booking->slot || !$booking->meeting_link) {
            return back()->with('error', 'No meeting link available.');
        }

        $now = now();
        $start = $booking->slot->start_time instanceof \Carbon\Carbon
            ? $booking->slot->start_time->copy()
            : \Carbon\Carbon::parse($booking->slot->start_time);
        $end = $start->copy()->addMinutes($booking->slot->duration_minutes);

        if ($now->lt($start->copy()->subMinutes(15)) || $now->gt($end)) {
            return back()->with('error', 'Not the right time to join.');
        }

        $booking->update([
            'status' => 'completed',
            'joined_at' => now(),
        ]);

        if ($booking->slot) {
            $booking->slot->update([
                'status' => 'completed',
                'is_booked' => true,
            ]);
        }

        return redirect($booking->meeting_link);
    }

//     public function viewAvailableSlots()
// {
//     $teacherId = 1; // Replace with dynamic teacher ID logic.
//     $availableSlots = AvailableSlot::where('status', 'pending')
//         ->where('is_booked', false)
//         ->where('start_time', '>', now())
//         ->get()
//         ->groupBy('start_time');
// //dd($availableSlots); // Debugging line, remove in production.
//     return view('student.slots', compact('availableSlots'));
// }

// public function viewAvailableSlots()
// {
//     $studentId = Auth::id();
//     $upcomingMeetings = AvailableSlot::with(['booking'])
//         ->whereHas('booking', function ($query) use ($studentId) {
//             $query->where('student_id', $studentId);
//         })
//         ->where('start_time', '>', now())
//         ->get()
//         ->groupBy('start_time');

//     return view('student.slots', compact('upcomingMeetings'));
// }


public function viewAvailableSlots__()
    {
        $studentId = Auth::id();
        $now = Carbon::now();

        $upcomingMeetings = AvailableSlot::with(['booking', 'teacher'])
            ->whereHas('booking', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->where('start_time', '>', $now)
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy(function ($slot) {
                return $slot->start_time->format('Y-m-d');
            })
            ->map(function ($slots) use ($now) {
                return $slots->map(function ($slot) use ($now) {
                    $startTime = $slot->start_time;
                    $durationMinutes = $slot->duration_minutes;
                    $availabilityStart = $startTime->subMinutes(15);
                    $availabilityEnd = $startTime->addMinutes($durationMinutes);
                    $isLive = $now->between($availabilityStart, $availabilityEnd);
                    $canCancel = $now->lt($startTime) && ($slot->booking->status !== 'completed');

                    return [
                        'id' => $slot->id,
                        'start_time' => $startTime->format('h:i A'),
                        'duration' => $durationMinutes . ' mins',
                        'interviewer' => optional($slot->teacher)->name ?? 'TBD',
                        'status' => $slot->booking ? ucfirst($slot->booking->status) : 'Pending',
                        'meeting_link' => $slot->booking->meeting_link ?? null,
                        'is_live' => $isLive,
                        'availability_time' => $availabilityStart->format('h:i A'),
                        'formatted_date' => $startTime->format('l, F d, Y'),
                        'countdown_target' => $availabilityStart->toIsoString(),
                        'can_cancel' => $canCancel,
                    ];
                });
            });

        return view('student.slots', compact('upcomingMeetings'));
    }
public function viewAvailableSlots()
    {
        $studentId = Auth::id();
        $user = Auth::user();
        $enrollments = $user
            ? $user->enrollments()->with('batch')->get()
            : collect();

        $studentBatchIds = $enrollments
            ->pluck('batch_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        $studentCourseIds = $enrollments
            ->map(fn ($enrollment) => optional($enrollment->batch)->course_id)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
        $now = now();
        $joinWindowLookback = $now->copy()->subMinutes(60); // allow showing meetings that started within the last hour for rejoin

        // Booked slots for this student (upcoming) + batch info via JOIN
        $upcomingMeetings = AvailableSlot::query()
            ->select(
                'available_slots.*',
                'interview_bookings.id as booking_id',
                'interview_bookings.status as booking_status',
                'interview_bookings.meeting_link',
                'courses.name as course_name',
                'batches.batch_name',
                'batches.start_date as batch_start_date'
            )
            ->join('interview_bookings', 'interview_bookings.slot_id', '=', 'available_slots.id')
            ->leftJoin('courses', 'courses.id', '=', 'available_slots.course_id')
            ->leftJoin('batches', 'batches.id', '=', 'available_slots.batch_id')
            ->where('interview_bookings.student_id', $studentId)
            ->where('available_slots.start_time', '>', $joinWindowLookback)
            ->orderBy('available_slots.start_time', 'asc')
            ->get()
            // keep your existing grouping style
            ->groupBy('start_time');

        // Unbooked future slots + batch info via JOIN
        $availableSlots = AvailableSlot::query()
            ->select(
                'available_slots.*',
                'courses.name as course_name',
                'batches.batch_name',
                'batches.start_date as batch_start_date'
            )
            ->leftJoin('interview_bookings', 'interview_bookings.slot_id', '=', 'available_slots.id')
            ->leftJoin('courses', 'courses.id', '=', 'available_slots.course_id')
            ->leftJoin('batches', 'batches.id', '=', 'available_slots.batch_id')
            ->whereNull('interview_bookings.id')
            ->where('available_slots.status', 'pending')
            ->where('available_slots.start_time', '>', $now)
            ->when($studentCourseIds->isNotEmpty(), function ($query) use ($studentCourseIds) {
                $query->whereIn('available_slots.course_id', $studentCourseIds);
            }, function ($query) {
                // If student has no course enrollment, return none
                $query->whereRaw('1 = 0');
            })
            ->orderBy('available_slots.start_time', 'asc')
            ->get()
            ->groupBy('start_time');

        return view('student.slots', compact('upcomingMeetings', 'availableSlots'));
    }


// public function viewAvailableSlots()
//     {
//         $studentId = Auth::id();

//         // Upcoming meetings (booked slots for the student)
//         $upcomingMeetings = AvailableSlot::with(['booking'])
//             ->whereHas('booking', function ($query) use ($studentId) {
//                 $query->where('student_id', $studentId);
//             })
//             ->where('start_time', '>', now())
//             ->get()
//             ->groupBy('start_time');

//         // Available slots (unbooked and upcoming)
//         $availableSlots = AvailableSlot::whereDoesntHave('booking')
//             ->where('start_time', '>', now())
//             ->where('status', 'pending')
//             ->get()
//             ->groupBy('start_time');

//         return view('student.slots', compact('upcomingMeetings', 'availableSlots'));
//     }

    public function bookSlot($slotId)
    {
        $slot = AvailableSlot::findOrFail($slotId);
        $studentId = Auth::id();
        $user = Auth::user();
        $studentBatchIds = $user
            ? $user->enrollments()->pluck('batch_id')->filter()->map(fn ($id) => (int) $id)->values()->toArray()
            : [];

        if ($slot->is_booked || $slot->start_time <= now()) {
            return redirect()->route('student.slots')->with('error', 'Slot is unavailable or expired.');
        }

        if ($slot->batch_id && !in_array($slot->batch_id, $studentBatchIds, true)) {
            return redirect()->route('student.slots')->with('error', 'This slot is not assigned to your batch.');
        }

        InterviewBooking::create([
            'student_id' => $studentId,
            'slot_id' => $slot->id,
            'status' => 'pending',
        ]);

        $slot->update(['is_booked' => true]);

        return redirect()->route('student.slots')->with('success', 'Slot booked successfully!');
    }
}
