@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto p-4 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manage Available Slots</h1>
                <p class="text-sm text-gray-600">Create slots and track mock interview attendees.</p>
            </div>
            <a href="{{ route('teacher.attendees') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                <i class="fas fa-users"></i>
                View Attendees
            </a>
        </div>

        <!-- Create Slot -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Create New Slot</h2>
            <form method="POST" action="{{ route('teacher.slots') }}" class="space-y-4" id="slot-form">
                @csrf

                <!-- Batch dropdown (shows start_date) -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                    <select
                        name="course_id"
                        id="course_id"
                        required
                        class="mt-1 block w-full border p-2 rounded bg-white"
                    >
                        <option value="" disabled selected>-- Select a course --</option>
                        @forelse($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @empty
                            <option value="" disabled>No courses assigned yet</option>
                        @endforelse
                    </select>
                    @error('course_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="batch_id" class="block text-sm font-medium text-gray-700">Batch</label>
                    <select
                        name="batch_id"
                        id="batch_id"
                        class="mt-1 block w-full border p-2 rounded bg-white"
                        data-selected="{{ old('batch_id') }}"
                        required
                    >
                        <option value="">Select a course first</option>
                    </select>
                    @error('batch_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="datetime-local" name="start_time" id="start_time" required class="mt-1 block w-full border p-2 rounded">
                </div>

                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" required class="mt-1 block w-full border p-2 rounded" value="30" min="5">
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create Slot</button>
            </form>
        </div>

        <!-- Existing Slots -->
        @if ($slots->isEmpty())
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Info!</strong>
                <span class="block sm:inline"> No slots created yet.</span>
            </div>
        @else
            <div class="overflow-x-auto shadow-md rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Start Time</th>
                            <th class="px-6 py-3">Duration (min)</th>
                            <th class="px-6 py-3">Course / Batch</th>
                            <th class="px-6 py-3">Slot Number</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Booked</th>
                            <th class="px-6 py-3">Attendees</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slots as $slot)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{-- If your model casts start_time to datetime, this works; otherwise parse --}}
                                    {{ optional($slot->start_time)->format('Y-m-d h:i A') ?? \Carbon\Carbon::parse($slot->start_time)->format('Y-m-d h:i A') }}
                                </td>
                                <td class="px-6 py-4">{{ $slot->duration_minutes }}</td>

                                <!-- Uses join fields: batch_name + batch_start_date -->
                                <td class="px-6 py-4">
                                    @php
                                        $hasCourse = !empty($slot->course_name);
                                        $hasBatch = !empty($slot->batch_name);
                                    @endphp
                                    @if($hasCourse)
                                        <div class="font-medium text-gray-900">{{ $slot->course_name }}</div>
                                    @endif
                                    @if($hasBatch)
                                        <div class="text-sm text-gray-700">{{ $slot->batch_name }}</div>
                                        @if(!empty($slot->batch_start_date))
                                            <div class="text-xs text-gray-500">
                                                Starts {{ \Carbon\Carbon::parse($slot->batch_start_date)->format('d M Y') }}
                                            </div>
                                        @endif
                                    @endif
                                    @if(!$hasCourse && !$hasBatch)
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">{{ $slot->slot_number }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($slot->status === 'completed') bg-green-100 text-green-800
                                        @elseif($slot->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $slot->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($slot->is_booked) bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $slot->is_booked ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('teacher.attendees', ['slot' => $slot->id]) }}"
                                       class="inline-flex items-center gap-1 text-blue-600 hover:underline">
                                        <i class="fas fa-users"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const courseSelect = document.getElementById('course_id');
    const batchSelect = document.getElementById('batch_id');
    const selectedBatch = batchSelect.dataset.selected || '';
    const endpointBase = "{{ url('/teacher/courses') }}";

    const setPlaceholder = (message = 'Select a course first', disabled = true) => {
        batchSelect.innerHTML = `<option value="">${message}</option>`;
        batchSelect.disabled = disabled;
    };

    const fetchBatches = async (courseId, selectedId = '') => {
        if (!courseId) {
            setPlaceholder();
            return;
        }

        setPlaceholder('Loading batches...', true);

        try {
            const response = await fetch(`${endpointBase}/${courseId}/batches`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) {
                throw new Error('Unable to fetch batches');
            }
            const batches = await response.json();
            batchSelect.innerHTML = '';
            if (!Array.isArray(batches) || !batches.length) {
                setPlaceholder('No batches found for this course');
                return;
            }
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.disabled = true;
            placeholder.selected = selectedId ? false : true;
            placeholder.textContent = '-- Select a batch --';
            batchSelect.appendChild(placeholder);

            batches.forEach(batch => {
                const option = document.createElement('option');
                option.value = batch.id;
                option.textContent = batch.start_date
                    ? `${batch.name} (Starts ${batch.start_date})`
                    : batch.name;
                if (selectedId && String(selectedId) === String(batch.id)) {
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
        fetchBatches(courseSelect.value);
    });

    if (courseSelect.value) {
        fetchBatches(courseSelect.value, selectedBatch);
    } else {
        setPlaceholder();
    }
});
</script>
@endpush
