@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mock Interview Attendees</h1>
            <p class="text-sm text-gray-600">Students who have joined a mock interview.</p>
        </div>
        <a href="{{ route('teacher.slots') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Back to Slots
            @if(!empty($slotId))
                <span class="text-gray-500 ml-2">Slot #{{ $slotId }}</span>
            @endif
        </a>
    </div>

    @if ($bookings->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            No attendees yet.
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Student</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Joined At</th>
                        <th class="px-4 py-3">Mock Interview</th>
                        <th class="px-4 py-3">Slot Time</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Resume</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr class="border-b last:border-0">
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $booking->student->name ?? 'Student' }}</td>
                            <td class="px-4 py-3">{{ $booking->student->email ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ optional($booking->joined_at)->format('d M Y, h:i A') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $booking->slot->course->name ?? 'Mock Interview' }}</div>
                                @if(!empty($booking->slot->batch->batch_name))
                                    <div class="text-xs text-gray-600">Batch: {{ $booking->slot->batch->batch_name }}</div>
                                @endif
                                @if($booking->slot && $booking->slot->start_time)
                                    <div class="text-xs text-gray-500">Date: {{ $booking->slot->start_time->format('d M Y, h:i A') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                {{ optional($booking->slot->start_time)->format('d M Y, h:i A') }}
                                <span class="text-gray-500 text-xs block">Duration: {{ $booking->slot->duration_minutes }} mins</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($booking->meeting_link)
                                    <a href="{{ $booking->meeting_link }}" target="_blank" class="text-blue-600 hover:underline">Join link</a>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
