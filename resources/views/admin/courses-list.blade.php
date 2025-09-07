@extends('admin.layouts.app')

@section('content')
<style>
    body {
        background: url("https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg") no-repeat center center fixed;
        background-size: cover;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .course-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border-radius: 1rem;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .course-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
    }

    .course-logo {
        width: 100%;
        height: 160px;
        object-fit: contain;
        background: #f9fafb;
        padding: 1rem;
    }

    .course-body {
        padding: 1rem 1.2rem;
    }

    .course-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
    }

    .course-meta {
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .course-price {
        margin-top: 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: #2563eb;
    }

    .course-actions {
        margin-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .course-actions button,
    .course-actions a {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1.1rem;
        transition: color 0.2s;
    }

    .course-actions .edit { color: #3b82f6; }
    .course-actions .edit:hover { color: #2563eb; }
    .course-actions .detail { color: #16a34a; }
    .course-actions .detail:hover { color: #15803d; }
    .course-actions .delete { color: #ef4444; }
    .course-actions .delete:hover { color: #b91c1c; }
</style>

<div class="min-h-screen bg-gray-100/60 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-chalkboard-teacher mr-2 text-blue-600"></i>Course List
                </h1>
                <p class="text-gray-600 mt-1">Manage and explore all available courses</p>
            </div>
            <a href="{{ route('admin.course.add') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md transition-all">
                <i class="fas fa-plus-circle mr-2"></i>Add New Course
            </a>
        </div>

        <!-- Course Cards -->
        <div class="card-grid">
            @forelse($courses as $course)
            <div class="course-card">
                @if($course->logo)
                    <img src="{{ asset($course->logo) }}" alt="{{ $course->name }}" class="course-logo">
                @else
                    <div class="course-logo flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-3xl"></i>
                    </div>
                @endif

                <div class="course-body">
                    <div class="course-title">{{ $course->name }}</div>
                    <div class="course-meta">
                        <p><i class="fas fa-link mr-1"></i> {{ $course->slug }}</p>
                        <p><i class="fas fa-hashtag mr-1"></i> {{ $course->course_code_id }}</p>
                        <p><i class="fas fa-clock mr-1"></i> {{ $course->duration }}</p>
                    </div>
                    <div class="course-price">₹{{ number_format($course->price, 2) }}</div>

                    <div class="course-actions">
                        <button onclick="openEditModal(`{{ route('admin.course.edit', $course->id) }}`)" class="edit" title="Edit Course">
                            <i class="fas fa-edit"></i>
                        </button>

                        @if($course->course_details_id)
                        <a href="{{ route('course.edit', $course->course_details_id) }}" class="detail" title="Edit Course Detail">
                            <i class="fas fa-book-open"></i>
                        </a>
                        @endif

                        <button type="button" onclick="openDeleteModal(`{{ route('admin.course.delete', $course->id) }}`)" class="delete" title="Delete Course">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-600">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>No courses found. Start by adding a new course!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
        <div class="mt-8">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
