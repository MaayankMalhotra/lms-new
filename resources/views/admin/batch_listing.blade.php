@extends('admin.layouts.app')

@section('content')
<style>
    body {
        background: url("https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg") no-repeat center center fixed;
        background-size: cover;
    }

    .listing-table thead th {
        font-size: 0.75rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7280;
    }

    .listing-table tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }

    .listing-table td {
        vertical-align: middle;
    }

    .action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        transition: background-color 0.2s, color 0.2s;
    }

    .action-icon.edit {
        background-color: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .action-icon.edit:hover {
        background-color: rgba(37, 99, 235, 0.2);
        color: #1d4ed8;
    }

    .action-icon.delete {
        background-color: rgba(239, 68, 68, 0.12);
        color: #dc2626;
    }

    .action-icon.delete:hover {
        background-color: rgba(239, 68, 68, 0.2);
        color: #b91c1c;
    }
</style>

<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8 text-center">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-list mr-2 text-blue-500"></i>Batch Listing
        </h1>
        <p class="text-gray-500 text-sm sm:text-base">View and manage all batch programs</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg" id="success-message">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    <div id="error-message" class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg hidden"></div>

    <!-- Add New Batch Button -->
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.batches.add') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all">
            <i class="fas fa-plus-circle mr-2"></i>Add New Batch
        </a>
    </div>

    <div class="bg-white/95 backdrop-blur rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full listing-table">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3 text-left">Schedule</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Duration</th>
                        <th class="px-6 py-3 text-left">Time Slot</th>
                        <th class="px-6 py-3 text-left">Slots</th>
                        <th class="px-6 py-3 text-left">Course</th>
                        <th class="px-6 py-3 text-left">Teacher</th>
                        <th class="px-6 py-3 text-right">Price</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200/70">
                    @forelse ($batches as $batch)
                        @php
                            $start = $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('d M Y') : 'N/A';
                            $end = $batch->end_date ? \Carbon\Carbon::parse($batch->end_date)->format('d M Y') : 'N/A';
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-semibold">{{ $start }} – {{ $end }}</div>
                                <div class="text-xs text-gray-500 mt-1">Days: {{ $batch->days ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->status ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->duration ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->time_slot ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->slots_available }}/{{ $batch->slots_filled }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->course->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->teacher->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-right text-gray-900 font-semibold">₹{{ number_format($batch->price, 2) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick="openEditModal({{ $batch->id }})" class="action-icon edit" title="Edit Batch">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.batches.destroy', $batch->id) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon delete" onclick="return confirm('Are you sure you want to delete this batch?')" title="Delete Batch">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i class="fas fa-inbox text-3xl"></i>
                                    <p>No batches found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination (if paginate() used in controller) -->
    @if($batches instanceof \Illuminate\Pagination\LengthAwarePaginator && $batches->hasPages())
        <div class="mt-8">
            {{ $batches->links() }}
        </div>
    @endif
</div>
@endsection
