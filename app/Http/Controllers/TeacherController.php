<?php

namespace App\Http\Controllers;

use App\Models\AvailableSlot;
use App\Models\Batch;
use App\Models\Course;
use App\Models\InterviewBooking;
use App\Models\TrainerDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


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
        $data = $request->validate([
            'course_id'        => ['required', 'exists:courses,id'],
            'mock_type'        => ['required', 'string', 'max:100'],
            'batch_id'         => [
                'required',
                Rule::exists('batches', 'id')->where(function ($query) use ($request) {
                    if ($request->filled('course_id')) {
                        $query->where('course_id', $request->input('course_id'));
                    }
                }),
            ],
            'start_time'       => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:15'],
        ]);

        $start = Carbon::parse($data['start_time']);
        $slotNumber = (int) (AvailableSlot::where('teacher_id', Auth::id())
            ->whereDate('start_time', $start->toDateString())
            ->max('slot_number') ?? 0);

        AvailableSlot::create([
            'teacher_id'       => Auth::id(),
            'course_id'        => $data['course_id'],
            'mock_type'        => $data['mock_type'],
            'batch_id'         => $data['batch_id'],
            'start_time'       => $start,
            'duration_minutes' => (int) $data['duration_minutes'],
            'slot_number'      => $slotNumber + 1,
            'status'           => 'pending',
            'is_booked'        => false,
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

        InterviewBooking::where('slot_id', $booking->slot_id)
            ->update(['meeting_link' => $request->meeting_link, 'status' => 'confirmed']);

        return redirect()->route('teacher.bookings')->with('success', 'Link uploaded.');
    }

    public function saveBookingFeedback(Request $request, $bookingId)
    {
        $data = $request->validate([
            'marks' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'teacher_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $booking = InterviewBooking::findOrFail($bookingId);

        $booking->update([
            'marks' => $data['marks'] ?? null,
            'teacher_notes' => $data['teacher_notes'] ?? null,
        ]);

        return redirect()->route('teacher.bookings')->with('success', 'Marks and notes saved.');
    }

    public function viewBookings(Request $request)
    {
        $teacherId = Auth::id();
        $courseId = $request->query('course_id');
        $batchId = $request->query('batch_id');
        $status = $request->query('status');

        $trainerDetail = TrainerDetail::where('user_id', $teacherId)->first();
        $courseIds = $trainerDetail?->course_ids ?? [];
        if (!is_array($courseIds)) {
            $courseIds = $courseIds ? json_decode($courseIds, true) : [];
        }

        $coursesQuery = Course::orderBy('name');
        if (!empty($courseIds)) {
            $coursesQuery->whereIn('id', $courseIds);
        }
        $courses = $coursesQuery->get();

        $slotsByDate = AvailableSlot::with([
                'bookings.student',
                'course',
                'batch',
            ])
            ->where('teacher_id', $teacherId)
            ->when($courseId, fn ($query) => $query->where('course_id', $courseId))
            ->when($batchId, fn ($query) => $query->where('batch_id', $batchId))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($slot) => optional($slot->start_time)?->format('Y-m-d') ?? 'TBD')
            ->map(function ($slots) {
                return $slots->map(function ($slot) {
                    $bookings = $slot->bookings->sortBy('id')->values();
                    $totalBookings = max(1, $bookings->count());

                    $slotStart = $slot->start_time instanceof Carbon
                        ? $slot->start_time->copy()
                        : Carbon::parse($slot->start_time);
                    $totalSeconds = max(1, (int) $slot->duration_minutes) * 60;
                    $perStudentSeconds = max(1, intdiv($totalSeconds, $totalBookings));

                    $bookings->each(function ($booking, $index) use ($slotStart, $perStudentSeconds, $totalBookings) {
                        $joinStart = $slotStart->copy()->addSeconds($perStudentSeconds * $index);
                        $joinEnd = $joinStart->copy()->addSeconds($perStudentSeconds);
                        $booking->setAttribute('join_start', $joinStart);
                        $booking->setAttribute('join_end', $joinEnd);
                        $booking->setAttribute('join_position', $index + 1);
                        $booking->setAttribute('join_total', $totalBookings);
                        $booking->setAttribute('join_duration_seconds', $perStudentSeconds);
                    });

                    return $slot;
                });
            });

        $batches = collect();
        if ($courseId) {
            $batches = Batch::query()
                ->select('id', 'batch_name', 'start_date')
                ->where('course_id', $courseId)
                ->orderBy('start_date')
                ->get();
        }

        return view('teacher.bookings', compact(
            'slotsByDate',
            'courses',
            'courseId',
            'batchId',
            'batches',
            'status'
        ));
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

    public function attendees(Request $request)
    {
        $slotId = $request->query('slot');

        $bookings = InterviewBooking::with([
                'student',
                'slot.course',
                'slot.batch',
            ])
            ->when($slotId, fn ($q) => $q->where('slot_id', $slotId))
            ->whereNotNull('joined_at')
            ->orderByDesc('joined_at')
            ->paginate(20);

        return view('teacher.attendees', compact('bookings', 'slotId'));
    }

    public function getBatchesForCourse(Request $request, Course $course)
    {
        $trainerDetail = TrainerDetail::where('user_id', Auth::id())->first();
        $courseIds = $trainerDetail?->course_ids ?? [];
        if (!is_array($courseIds)) {
            $courseIds = $courseIds ? json_decode($courseIds, true) : [];
        }
        if (!empty($courseIds) && !in_array($course->id, $courseIds)) {
            abort(403);
        }

        $batches = Batch::query()
            ->select('id', 'batch_name', 'start_date')
            ->where('course_id', $course->id)
            ->orderBy('start_date')
            ->get()
            ->map(function ($batch) {
                return [
                    'id' => $batch->id,
                    'name' => $batch->batch_name,
                    'start_date' => optional($batch->start_date)?->format('d M Y'),
                ];
            });

        return response()->json($batches);
    }
    
}
