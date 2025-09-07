@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Student Bookings</h1>

        <div class="overflow-x-auto shadow-md rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Slot 1</th>
                        <th class="px-6 py-3">Slot 2</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slots as $date => $slotData)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $date }}</td>
                            @for ($i = 1; $i <= 2; $i++)
                                <td class="px-6 py-4">
                                    @if (isset($slotData[$i]))
                                        <div>
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold @if($slotData[$i]['status'] === 'completed') bg-green-100 text-green-800 @elseif($slotData[$i]['status'] === 'pending') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                                {{ $slotData[$i]['status'] }}
                                            </span><br>
                                            Student: {{ $slotData[$i]['student'] }}<br>
                                            @if ($slotData[$i]['meeting_link'])
                                                <a href="{{ $slotData[$i]['meeting_link'] }}" target="_blank" class="text-blue-600 hover:underline">Join</a>
                                            @elseif ($slotData[$i]['booking_id'])
                                                <form method="POST" action="{{ route('teacher.bookings.upload-link', $slotData[$i]['booking_id']) }}" class="mt-2">
                                                    @csrf
                                                    <input type="url" name="meeting_link" placeholder="Meeting Link" class="border p-2 rounded w-full mb-2">
                                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
                                                </form>
                                            @else
                                                N/A (Not Booked)
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-500">N/A</span>
                                    @endif
                                </td>
                            @endfor
                            <td class="px-6 py-4">
                                @foreach($slotData as $slotNum => $data)
                                    <form method="POST" action="{{ route('teacher.update-slot-status', $data['slot_id'] ?? '') }}" class="mb-2">
                                        @csrf
                                        <select name="status" class="border p-2 rounded w-full mb-1">
                                            <option value="pending" {{ $data['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="completed" {{ $data['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="rescheduled" {{ $data['status'] == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                        </select>
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update</button>
                                    </form>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection