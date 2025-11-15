<?php

namespace App\Http\Controllers;

use App\Models\AvailableSlot;
use App\Models\Course;
use App\Models\InterviewBooking;
use App\Models\TrainerDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TeacherController extends Controller
{
    // public function index()
    // {
    //     $slots = AvailableSlot::where('teacher_id', Auth::id())->get();
    //     return view('teacher.slots', compact('slots'));
    // }

    public function index()
    {
        $slots = AvailableSlot::query()
            ->select(
                'available_slots.*',
                'courses.name as course_name',
                'batches.batch_name',
                'batches.start_date as batch_start_date'
            )
            ->leftJoin('courses', 'courses.id', '=', 'available_slots.course_id')
            ->leftJoin('batches', 'batches.id', '=', 'available_slots.batch_id')
            ->where('available_slots.teacher_id', Auth::id())
            ->orderByDesc('available_slots.start_time')
            ->get();

        $trainerDetail = TrainerDetail::where('user_id', Auth::id())->first();
        $courseIds = $trainerDetail?->course_ids ?? [];

        $coursesQuery = Course::orderBy('name');
        if (!empty($courseIds)) {
            $coursesQuery->whereIn('id', $courseIds);
        }
        $courses = $coursesQuery->get();

        return view('teacher.slots', compact('slots', 'courses'));
    }

    // public function createSlot(Request $request)
    // {
    //     $request->validate([
    //         'start_time' => 'required|date',
    //         'duration_minutes' => 'required|integer|min:15',
    //     ]);

    //     AvailableSlot::create([
    //         'teacher_id' => Auth::id(),
    //         'start_time' => $request->start_time,
    //         'duration_minutes' => $request->duration_minutes,
    //     ]);

    //     return redirect()->route('teacher.slots')->with('success', 'Slot created.');
    // }

    

    public function createSlot(Request $request)
    {
        $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'start_time'       => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
        ]);

        $start = Carbon::parse($request->start_time);

        AvailableSlot::create([
            'teacher_id'       => Auth::id(),
            'course_id'        => $request->course_id,
            'batch_id'         => null,
            'start_time'       => $start,
            'duration_minutes' => (int) $request->duration_minutes,
        ]);

        return redirect()->route('teacher.slots')->with('success', 'Slot created.');
    }

    // public function viewBookings()
    // {
    //     $bookings = InterviewBooking::with(['student', 'slot'])
    //         ->whereHas('slot', function ($query) {
    //             $query->where('teacher_id', Auth::id());
    //         })->get();

    //     return view('teacher.bookings', compact('bookings'));
    // }

    public function uploadLink(Request $request, $id)
    {
       //  dd($request->all());
        $request->validate(['meeting_link' => 'required|url']);

        $booking = InterviewBooking::findOrFail($id);
      //  dd($booking);
        // if ($booking->slot->teacher_id !== Auth::id()) {
        //     abort(403);
        // }

        $booking->update(['meeting_link' => $request->meeting_link, 'status' => 'confirmed']);

        return redirect()->route('teacher.bookings')->with('success', 'Link uploaded.');
    }

public function viewBookings()
{
    $teacherId = Auth::id();
    $slots = AvailableSlot::with(['booking.student'])
       // ->where('teacher_id', $teacherId)
        ->get()
        ->groupBy('start_time')
        ->map(function ($group) {
            return $group->mapWithKeys(function ($slot) {
                $bookingData = $slot->booking ? [
                    'student' => optional($slot->booking->student)->name ?? 'N/A',
                    'meeting_link' => $slot->booking->meeting_link ?? null,
                    'id' => $slot->booking->id ?? null,
                    'slot_id' => $slot->booking->slot_id ?? null,
                ] : [
                    'student' => 'N/A',
                    'meeting_link' => null,
                    'id' => null,
                    'slot_id' => $slot->id,
                ];

                return [
                    $slot->slot_number => [
                        'status' => $slot->status ?? 'pending', // Default to 'pending' if null
                        'student' => $bookingData['student'],
                        'meeting_link' => $bookingData['meeting_link'],
                        'booking_id' => $bookingData['id'],
                        'slot_id' => $bookingData['slot_id'],
                    ],
                ];
            });
        });
       // dd($slots); // Debugging line, remove in production.

    return view('teacher.bookings', compact('slots'));
}

// public function updateSlotStatus(Request $request, $slotId)
// {
//     $request->validate(['status' => 'required|in:pending,completed,rescheduled']);
//     $slot = AvailableSlot::findOrFail($slotId);
//     if ($slot->teacher_id !== Auth::id()) {
//         abort(403);
//     }
//     $slot->update(['status' => $request->status]);
//     return redirect()->route('teacher.bookings')->with('success', 'Status updated.');
// }

public function updateSlotStatus(Request $request, $slotId)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,rescheduled',
        ]);

        $slot = AvailableSlot::findOrFail($slotId);
        // if ($slot->teacher_id !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $slot->update(['status' => $request->status]);

        return redirect()->route('teacher.bookings')->with('success', 'Status updated successfully.');
    }
    
}
