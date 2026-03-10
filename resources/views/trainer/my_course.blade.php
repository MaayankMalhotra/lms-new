@extends('admin.layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($trainer as $batch)
        @php
            $logoPath = optional($batch->course)->logo;
            $logoUrl = $logoPath
                ? (filter_var($logoPath, FILTER_VALIDATE_URL) ? $logoPath : asset($logoPath))
                : asset('images/default-course.png');
        @endphp
        <div class="bg-white shadow-md rounded-2xl overflow-hidden">
            <img src="{{ $logoUrl }}" alt="{{ optional($batch->course)->name ?? 'Course' }}" class="w-full h-48 object-cover">
            
            <div class="p-4">
                <h2 class="text-xl font-semibold text-gray-800">{{ optional($batch->course)->name ?? 'Unnamed Course' }}</h2>
                
                <div class="mt-4 text-sm text-gray-700">
                    <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($batch->start_date)->format('d M, Y') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($batch->status) }}</p>
                    <p><strong>Duration:</strong> {{ $batch->duration }}</p>
                    <p><strong>Total Students:</strong> {{ $batch->enrollments->count() }}</p>
                </div>

                <div class="mt-4 border-t pt-3">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Students In This Batch</h3>
                    @if ($batch->enrollments->isEmpty())
                        <p class="text-xs text-gray-500">No students enrolled yet.</p>
                    @else
                        <div class="max-h-56 overflow-y-auto border rounded-lg">
                            <table class="min-w-full text-xs">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-2 py-2 text-left font-semibold text-gray-600">Name</th>
                                        <th class="px-2 py-2 text-left font-semibold text-gray-600">Email</th>
                                        <th class="px-2 py-2 text-left font-semibold text-gray-600">Phone</th>
                                        <th class="px-2 py-2 text-left font-semibold text-gray-600">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($batch->enrollments as $enrollment)
                                        @php
                                            $studentUser = $enrollment->user;
                                            $phone = optional($studentUser)->phone ?: optional($enrollment->student)->phone;
                                        @endphp
                                        <tr>
                                            <td class="px-2 py-2 text-gray-800">{{ optional($studentUser)->name ?? '-' }}</td>
                                            <td class="px-2 py-2 text-gray-700">{{ optional($studentUser)->email ?? $enrollment->email ?? '-' }}</td>
                                            <td class="px-2 py-2 text-gray-700">{{ $phone ?: '-' }}</td>
                                            <td class="px-2 py-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $enrollment->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                    {{ ucfirst($enrollment->status ?? 'pending') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection
