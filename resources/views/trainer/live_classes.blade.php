@extends('admin.layouts.app')

@section('content')
<div class="px-3 py-4">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Teacher Live Classes</h2>
            <span class="text-sm text-gray-500">
                Total Batches: {{ $batches->count() }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.live_classes.create') }}"
                class="inline-flex items-center px-3 py-2 rounded bg-[#ff9800] text-white text-sm font-medium hover:bg-[#e68900] transition">
                <i class="fas fa-plus-circle mr-2"></i> Create Live Class
            </a>
            <a href="{{ route('admin.live_classes.create.int') }}"
                class="inline-flex items-center px-3 py-2 rounded bg-[#2c1d56] text-white text-sm font-medium hover:bg-[#1f143f] transition">
                <i class="fas fa-plus-square mr-2"></i> Create Internship Class
            </a>
        </div>
    </div>

    @if($batches->isEmpty())
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            No batches are assigned to you.
        </div>
    @else
        <div class="space-y-6">
            @foreach($batches as $batch)
                <div class="bg-white rounded-xl shadow">
                    <div class="border-b px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ optional($batch->course)->name ?? 'Unnamed Course' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Batch: {{ $batch->batch_name ?? ('#' . $batch->id) }}
                                |
                                Start Date: {{ optional($batch->start_date)->format('d M Y') ?? 'N/A' }}
                            </p>
                        </div>
                        <span class="text-sm font-medium text-gray-600">
                            Live Classes: {{ $batch->liveClasses->count() }}
                        </span>
                    </div>

                    @if($batch->liveClasses->isEmpty())
                        <div class="px-5 py-4 text-sm text-gray-500">
                            No live classes are assigned to this batch yet.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold">Topic</th>
                                        <th class="px-4 py-3 text-left font-semibold">Date & Time</th>
                                        <th class="px-4 py-3 text-left font-semibold">Duration</th>
                                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                                        <th class="px-4 py-3 text-left font-semibold">Meet Link</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($batch->liveClasses as $class)
                                        @php
                                            $statusText = $class->isOngoing() ? 'Live' : ($class->isUpcoming() ? 'Upcoming' : 'Ended');
                                            $statusClass = $class->isOngoing()
                                                ? 'bg-green-100 text-green-700'
                                                : ($class->isUpcoming() ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700');
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3 text-gray-800">{{ $class->topic ?? 'Untitled Class' }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($class->class_datetime)->format('d M Y, h:i A') }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $class->duration_minutes ?? 0 }} mins</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if(!empty($class->google_meet_link))
                                                    <a href="{{ $class->google_meet_link }}" target="_blank" rel="noopener"
                                                        class="text-blue-600 hover:underline font-medium">
                                                        Join
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">Not set</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
