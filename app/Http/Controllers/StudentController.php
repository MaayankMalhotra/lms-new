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
        $start = $booking->slot->start_time;
        $end = $start->addMinutes($booking->slot->duration_minutes);
        if ($now->lt($start->subMinutes(15)) || $now->gt($end)) {
            return view('student.interview')->with('error', 'Not the right time to join.');
        }

        return redirect($booking->meeting_link); // Or display in view if needed.
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
        $now = now();

        // Booked slots for this student (upcoming) + batch info via JOIN
        $upcomingMeetings = AvailableSlot::query()
            ->select(
                'available_slots.*',
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
            ->where('available_slots.start_time', '>', $now)
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

        if ($slot->is_booked || $slot->start_time <= now()) {
            return redirect()->route('student.slots')->with('error', 'Slot is unavailable or expired.');
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
