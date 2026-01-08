@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto p-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Mock Interview Bookings</h1>
                <p class="text-sm text-gray-600">See booked students, upload meeting links, and record marks.</p>
            </div>
            <a href="{{ route('teacher.slots') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                <i class="fas fa-arrow-left"></i>
                Back to Slots
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4">
            <form method="GET" action="{{ route('teacher.bookings') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="filter-course" class="block text-sm font-medium text-gray-700">Course</label>
                    <select
                        id="filter-course"
                        name="course_id"
                        class="mt-1 block w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-indigo-200"
                    >
                        <option value="">All courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ (string) ($courseId ?? '') === (string) $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter-batch" class="block text-sm font-medium text-gray-700">Batch</label>
                    <select
                        id="filter-batch"
                        name="batch_id"
                        data-selected="{{ $batchId ?? '' }}"
                        class="mt-1 block w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-indigo-200"
                        {{ $courseId ? '' : 'disabled' }}
                    >
                        @if($courseId)
                            <option value="">All batches</option>
                            @forelse($batches as $batch)
                                <option value="{{ $batch->id }}" {{ (string) ($batchId ?? '') === (string) $batch->id ? 'selected' : '' }}>
                                    {{ $batch->batch_name }}
                                    @if($batch->start_date)
                                        (Starts {{ optional($batch->start_date)->format('d M Y') }})
                                    @endif
                                </option>
                            @empty
                                <option value="" disabled>No batches found</option>
                            @endforelse
                        @else
                            <option value="">Select a course first</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label for="filter-status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select
                        id="filter-status"
                        name="status"
                        class="mt-1 block w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-indigo-200"
                    >
                        <option value="">All statuses</option>
                        @foreach(['pending' => 'Pending', 'completed' => 'Completed', 'rescheduled' => 'Rescheduled'] as $value => $label)
                            <option value="{{ $value }}" {{ ($status ?? '') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap gap-3 md:col-span-2">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Apply filters
                    </button>
                    <a href="{{ route('teacher.bookings') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @if($slotsByDate->isEmpty())
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">No bookings found.</strong>
                <span class="block sm:inline">
                    @if($courseId || $batchId)
                        Try adjusting or clearing the filters.
                    @else
                        Create slots and students will appear here once they book.
                    @endif
                </span>
            </div>
        @endif

        @foreach($slotsByDate as $date => $slots)
            @php
                $dateLabel = $date !== 'TBD'
                    ? \Carbon\Carbon::parse($date)->format('d M Y')
                    : 'Date not set';
            @endphp
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <div class="font-semibold text-gray-800">{{ $dateLabel }}</div>
                    <div class="text-sm text-gray-500">{{ $slots->count() }} {{ $slots->count() === 1 ? 'slot' : 'slots' }}</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                            <tr>
                                <th class="px-4 py-3">Time</th>
                                <th class="px-4 py-3">Mock Type</th>
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Meeting Link</th>
                                <th class="px-4 py-3">Marks / Notes</th>
                                <th class="px-4 py-3">Slot Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slots as $slot)
                                @php $booking = $slot->booking; @endphp
                                <tr class="border-b last:border-0 hover:bg-gray-50 align-top">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900">
                                            {{ optional($slot->start_time)->format('h:i A') ?? '—' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ optional($slot->start_time)->format('d M Y, h:i A') ?? '' }}
                                            @if($slot->duration_minutes)
                                                <span class="ml-1">({{ (int) $slot->duration_minutes }} mins)</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Slot #{{ $slot->slot_number ?? '—' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $slot->course->name ?? 'Course N/A' }}
                                            @if($slot->batch)
                                                <span class="block text-gray-600">{{ $slot->batch->batch_name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                            {{ $slot->mock_type ?? 'Mock Test' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($booking)
                                            <div class="font-semibold text-gray-900">{{ $booking->student->name ?? 'Student' }}</div>
                                            <div class="text-xs text-gray-600">{{ $booking->student->email ?? '—' }}</div>
                                        @else
                                            <span class="text-gray-500">Not booked</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($booking)
                                            @if($booking->meeting_link)
                                                <a href="{{ $booking->meeting_link }}" target="_blank" rel="noreferrer" class="text-blue-600 hover:underline break-all">Join link</a>
                                            @else
                                                <form method="POST" action="{{ route('teacher.bookings.upload-link', $booking->id) }}" class="space-y-2">
                                                    @csrf
                                                    <input
                                                        type="url"
                                                        name="meeting_link"
                                                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                                                        placeholder="https://meeting-link.com"
                                                        required
                                                    >
                                                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">
                                                        Upload
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($booking)
                                            <form method="POST" action="{{ route('teacher.bookings.feedback', $booking->id) }}" class="space-y-2">
                                                @csrf
                                                <div>
                                                    <label class="block text-xs text-gray-600 mb-1" for="marks-{{ $booking->id }}">Marks</label>
                                                    <input
                                                        id="marks-{{ $booking->id }}"
                                                        type="number"
                                                        name="marks"
                                                        value="{{ old('marks', $booking->marks) }}"
                                                        min="0"
                                                        max="1000"
                                                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-purple-200"
                                                        placeholder="Enter marks"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-600 mb-1" for="notes-{{ $booking->id }}">Notes</label>
                                                    <textarea
                                                        id="notes-{{ $booking->id }}"
                                                        name="teacher_notes"
                                                        rows="2"
                                                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-purple-200"
                                                        placeholder="Any remarks"
                                                    >{{ old('teacher_notes', $booking->teacher_notes) }}</textarea>
                                                </div>
                                                <button type="submit" class="px-3 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-xs">
                                                    Save
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">Awaiting booking</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('teacher.update-slot-status', $slot->id) }}" class="space-y-2">
                                            @csrf
                                            <select name="status" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-green-200">
                                                <option value="pending" {{ $slot->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="completed" {{ $slot->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="rescheduled" {{ $slot->status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                            </select>
                                            <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-xs">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const courseSelect = document.getElementById('filter-course');
    const batchSelect = document.getElementById('filter-batch');

    if (!courseSelect || !batchSelect) {
        return;
    }

    let selectedBatch = batchSelect.dataset.selected || '';
    const endpointBase = "{{ url('/teacher/courses') }}";

    const setPlaceholder = (message = 'Select a course first', disabled = true) => {
        batchSelect.innerHTML = `<option value="">${message}</option>`;
        batchSelect.disabled = disabled;
    };

    const fetchBatches = async (courseId) => {
        if (!courseId) {
            setPlaceholder();
            return;
        }

        setPlaceholder('Loading batches...', true);

        try {
            const response = await fetch(`${endpointBase}/${courseId}/batches`, {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) {
                throw new Error('Unable to fetch batches');
            }

            const batches = await response.json();
            batchSelect.innerHTML = '';

            if (!Array.isArray(batches) || !batches.length) {
                setPlaceholder('No batches found', false);
                return;
            }

            const allOption = document.createElement('option');
            allOption.value = '';
            allOption.textContent = 'All batches';
            batchSelect.appendChild(allOption);

            batches.forEach(batch => {
                const option = document.createElement('option');
                option.value = batch.id;
                option.textContent = batch.start_date
                    ? `${batch.name} (Starts ${batch.start_date})`
                    : batch.name;
                if (selectedBatch && String(selectedBatch) === String(batch.id)) {
                    option.selected = true;
                }
                batchSelect.appendChild(option);
            });

            batchSelect.disabled = false;
        } catch (error) {
            console.error(error);
            setPlaceholder('Unable to load batches');
        }
    };

    courseSelect.addEventListener('change', () => {
        selectedBatch = '';
        fetchBatches(courseSelect.value);
    });

    if (courseSelect.value && !batchSelect.options.length) {
        fetchBatches(courseSelect.value);
    } else if (!courseSelect.value) {
        setPlaceholder();
    }
});
</script>
@endpush
