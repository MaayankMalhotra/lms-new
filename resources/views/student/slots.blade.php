@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"> {{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"> {{ session('error') }}</span>
            </div>
        @endif

        <!-- Upcoming Meetings Section -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Your Upcoming Meetings</h1>
        @if ($upcomingMeetings->isEmpty())
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Info!</strong>
                <span class="block sm:inline"> No upcoming meetings scheduled.</span>
            </div>
        @else
            <div class="overflow-x-auto shadow-md rounded-lg mb-6">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Date & Time</th>
                            <th class="px-6 py-3">Course / Batch</th> <!-- NEW -->
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingMeetings as $dateTime => $slots)
                            @foreach($slots as $slot)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $slot->start_time->format('Y-m-d h:i A') }}
                                    </td>

                                    <!-- NEW: Batch -->
                                    <td class="px-6 py-4">
                                        @if(!empty($slot->course_name))
                                            <div class="font-medium text-gray-900">{{ $slot->course_name }}</div>
                                        @elseif(!empty($slot->batch_name))
                                            <div class="font-medium text-gray-900">{{ $slot->batch_name }}</div>
                                            <div class="text-xs text-gray-500">
                                                Starts {{ \Carbon\Carbon::parse($slot->batch_start_date)->format('d M Y') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @php $status = $slot->booking_status ?? 'pending'; @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $windowStart = (clone $slot->start_time)->subMinutes(15);
                                            $windowEnd   = (clone $slot->start_time)->addMinutes($slot->duration_minutes);
                                        @endphp

                                        @if ($slot->meeting_link && now()->between($windowStart, $windowEnd))
                                            <a href="{{ $slot->meeting_link }}" target="_blank" class="inline-block px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                                                Join Meeting
                                            </a>
                                        @else
                                            <span class="text-gray-500">Not Available Yet</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Available Slots for Booking Section -->
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Available Slots for Booking</h2>
        @if ($availableSlots->isEmpty())
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Info!</strong>
                <span class="block sm:inline"> No available slots at the moment.</span>
            </div>
        @else
            <div class="overflow-x-auto shadow-md rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Date & Time</th>
                            <th class="px-6 py-3">Course / Batch</th> <!-- NEW -->
                            <th class="px-6 py-3">Duration (min)</th>
                            <th class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableSlots as $dateTime => $slots)
                            @foreach($slots as $slot)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $slot->start_time->format('Y-m-d h:i A') }}
                                    </td>

                                    <!-- NEW: Batch -->
                                    <td class="px-6 py-4">
                                        @if(!empty($slot->course_name))
                                            <div class="font-medium text-gray-900">{{ $slot->course_name }}</div>
                                        @elseif(!empty($slot->batch_name))
                                            <div class="font-medium text-gray-900">{{ $slot->batch_name }}</div>
                                            <div class="text-xs text-gray-500">
                                                Starts {{ \Carbon\Carbon::parse($slot->batch_start_date)->format('d M Y') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">{{ (int) $slot->duration_minutes }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('student.book', $slot->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                                                Book Slot
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
