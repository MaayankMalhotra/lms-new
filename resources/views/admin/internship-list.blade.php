@extends('admin.layouts.app')

@section('content')
<style>
    body {
        /* ✅ Better clean background */
        background: url("https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg") no-repeat center center fixed;
        background-size: cover;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .internship-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border-radius: 1rem;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .internship-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
    }

    .internship-logo {
        width: 100%;
        height: 160px;
        object-fit: contain;
        background: #f9fafb;
        padding: 1rem;
    }

    .internship-body {
        padding: 1rem 1.2rem;
    }

    .internship-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
    }

    .internship-meta {
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .internship-price {
        margin-top: 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: #2563eb;
    }

    .internship-actions {
        margin-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .internship-actions button,
    .internship-actions a {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1.1rem;
        transition: color 0.2s;
    }

    .internship-actions .edit { color: #3b82f6; }
    .internship-actions .edit:hover { color: #2563eb; }
    .internship-actions .detail { color: #16a34a; }
    .internship-actions .detail:hover { color: #15803d; }
    .internship-actions .delete { color: #ef4444; }
    .internship-actions .delete:hover { color: #b91c1c; }
</style>

<div class="min-h-screen bg-gray-100/60 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-briefcase mr-2 text-blue-600"></i>Internship Programs
                </h1>
                <p class="text-gray-600 mt-1">Manage all internship programs in the system</p>
            </div>
            <a href="{{ route('admin.internship.add') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md transition-all">
                <i class="fas fa-plus-circle mr-2"></i>Add New Internship
            </a>
        </div>

        <!-- Internship Cards -->
        <div class="card-grid">
            @forelse($internships as $internship)
            <div class="internship-card">
                @if($internship->logo)
                    <img src="{{ asset($internship->logo) }}" alt="{{ $internship->name }}" class="internship-logo">
                @else
                    {{-- ✅ Default logo image --}}
                    <img src="{{ asset('images/default-internship.png') }}" alt="Default Internship Logo" class="internship-logo">
                @endif

                <div class="internship-body">
                    <div class="internship-title">{{ $internship->name }}</div>
                    <div class="internship-meta">
                        <p><i class="fas fa-certificate mr-1 text-blue-400"></i> {{ $internship->certified_button }}</p>
                        <p><i class="fas fa-clock mr-1 text-blue-400"></i> {{ $internship->duration }}</p>
                        <p><i class="fas fa-project-diagram mr-1 text-blue-400"></i> {{ $internship->project }} Projects</p>
                        <p><i class="fas fa-users mr-1 text-blue-400"></i> {{ $internship->applicant }} Applicants</p>
                    </div>
                    <div class="internship-price">₹{{ number_format($internship->price, 2) }}</div>

                    <div class="internship-actions">
                        <button onclick="openEditModal(`{{ route('admin.internship.edit', $internship->id) }}`)" class="edit" title="Edit Internship">
                            <i class="fas fa-edit"></i>
                        </button>

                        @if($internship->has_details)
                        <a href="{{ route('course.edit.int', $internship->internship_detail_id) }}" class="detail" title="Edit Internship Details">
                            <i class="fas fa-book"></i>
                        </a>
                        @endif

                        <form action="{{ route('admin.internship.destroy', $internship->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this internship?')" title="Delete Internship">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-600">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>No internships found. Start by adding a new internship program!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($internships->hasPages())
        <div class="mt-8">
            {{ $internships->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
