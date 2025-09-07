@extends('admin.layouts.app')

@section('content')
<style>
    body {
        background: url("https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg") no-repeat center center fixed;
        background-size: cover;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .batch-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(6px);
        border-radius: 1rem;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        padding: 1.25rem;
    }

    .batch-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 18px rgba(0, 0, 0, 0.25);
    }

    .batch-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .batch-meta {
        font-size: 0.9rem;
        color: #4b5563;
        margin-bottom: 0.25rem;
    }

    .batch-price {
        margin-top: 0.75rem;
        font-size: 1.05rem;
        font-weight: 600;
        color: #2563eb;
    }

    .batch-actions {
        margin-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .batch-actions button,
    .batch-actions a {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1.1rem;
        transition: color 0.2s;
    }

    .batch-actions .edit { color: #3b82f6; }
    .batch-actions .edit:hover { color: #2563eb; }
    .batch-actions .delete { color: #ef4444; }
    .batch-actions .delete:hover { color: #b91c1c; }
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

    <!-- Batch Cards -->
    <div class="card-grid">
        @forelse ($batches as $batch)
            <div class="batch-card">
                <!-- ✅ Start - End Date in Title -->
                <div class="batch-title">
                    <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                    {{ $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('d M Y') : 'N/A' }}
                     – 
                    {{ $batch->end_date ? \Carbon\Carbon::parse($batch->end_date)->format('d M Y') : 'N/A' }}
                </div>

                <!-- Meta Info -->
                <div class="batch-meta"><i class="fas fa-info-circle mr-1 text-blue-400"></i>Status: {{ $batch->status ?? 'N/A' }}</div>
                <div class="batch-meta"><i class="fas fa-clock mr-1 text-blue-400"></i>Duration: {{ $batch->duration ?? 'N/A' }}</div>
                <div class="batch-meta"><i class="fas fa-calendar-week mr-1 text-blue-400"></i>Days: {{ $batch->days ?? 'N/A' }}</div>
                <div class="batch-meta"><i class="fas fa-hourglass-start mr-1 text-blue-400"></i>Time Slot: {{ $batch->time_slot ?? 'N/A' }}</div>
                <div class="batch-meta"><i class="fas fa-tag mr-1 text-blue-400"></i>Discount: {{ $batch->discount_info ?? 'N/A' }}</div>
                <div class="batch-meta"><i class="fas fa-users mr-1 text-blue-400"></i>Slots: {{ $batch->slots_available }}/{{ $batch->slots_filled }}</div>
                <div class="batch-meta"><i class="fas fa-book mr-1 text-blue-400"></i>Course: {{ $batch->course->name ?? 'N/A' }}</div>
                <div class="batch-meta"><i class="fas fa-chalkboard-teacher mr-1 text-blue-400"></i>Teacher: {{ $batch->teacher->name ?? 'N/A' }}</div>

                <!-- Price -->
                <div class="batch-price">₹{{ number_format($batch->price, 2) }}</div>

                <!-- Actions -->
                <div class="batch-actions">
                    <button onclick="openEditModal({{ $batch->id }})" class="edit" title="Edit Batch">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.batches.destroy', $batch->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this batch?')" title="Delete Batch">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-600">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>No batches found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination (if paginate() used in controller) -->
    @if($batches instanceof \Illuminate\Pagination\LengthAwarePaginator && $batches->hasPages())
        <div class="mt-8">
            {{ $batches->links() }}
        </div>
    @endif
</div>
@endsection
