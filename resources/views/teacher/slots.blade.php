@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manage Available Slots</h1>

        <!-- Create Slot -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Create New Slot</h2>
            <form method="POST" action="{{ route('teacher.slots') }}" class="space-y-4">
                @csrf

                <!-- Batch dropdown (shows start_date) -->
                <div>
                    <label for="batch_id" class="block text-sm font-medium text-gray-700">Batch</label>
                    <select
                        name="batch_id"
                        id="batch_id"
                        required
                        class="mt-1 block w-full border p-2 rounded bg-white"
                    >
                        <option value="" disabled selected>-- Select a batch --</option>
                        @forelse($batches as $batch)
                            @php $start = \Carbon\Carbon::parse($batch->start_date); @endphp
                            <option
                                value="{{ $batch->id }}"
                                data-start="{{ $start->toDateString() }}"
                                {{ old('batch_id') == $batch->id ? 'selected' : '' }}
                            >
                                {{ $batch->batch_name }} — starts {{ $start->format('d M Y') }}
                            </option>
                        @empty
                            <option value="" disabled>No batches found</option>
                        @endforelse
                    </select>

                    <p id="selectedBatchStartWrap" class="text-xs text-gray-500 mt-1 hidden">
                        Starts on: <span id="selectedBatchStart"></span>
                    </p>
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
                            <th class="px-6 py-3">Batch</th>
                            <th class="px-6 py-3">Slot Number</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Booked</th>
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
                                    @if(!empty($slot->batch_name))
                                        <div class="font-medium text-gray-900">{{ $slot->batch_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            Starts {{ \Carbon\Carbon::parse($slot->batch_start_date)->format('d M Y') }}
                                        </div>
                                    @else
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        const batchSelect = document.getElementById('batch_id');
        const wrap = document.getElementById('selectedBatchStartWrap');
        const out  = document.getElementById('selectedBatchStart');

        function updateBatchStart() {
            const opt = batchSelect?.options[batchSelect.selectedIndex];
            const start = opt ? opt.getAttribute('data-start') : '';
            if (start) {
                out.textContent = start;
                wrap.classList.remove('hidden');
            } else {
                wrap.classList.add('hidden');
                out.textContent = '';
            }
        }

        batchSelect?.addEventListener('change', updateBatchStart);
        document.addEventListener('DOMContentLoaded', updateBatchStart);
    </script>
@endsection
