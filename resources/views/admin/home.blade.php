@extends('admin.layouts.app')

@section('title', 'Manage Homepage')

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
    .record-card {
        background: rgba(255,255,255,0.95);
        border-radius: 1rem;
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .record-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.25);
    }
    .record-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .record-body {
        padding: 1rem;
    }
    .record-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c0b57;
    }
    .record-meta {
        font-size: 0.9rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }
    .record-actions {
        margin-top: 1rem;
        display: flex;
        justify-content: space-between;
    }
    .record-actions button,
    .record-actions a {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1.2rem;
        transition: color 0.2s;
    }
    .edit { color: #3b82f6; }
    .edit:hover { color: #2563eb; }
    .delete { color: #ef4444; }
    .delete:hover { color: #b91c1c; }
</style>

<div class="bg-[#f8eeea]/80 p-8 rounded-3xl shadow-lg">
    <h1 class="text-4xl font-bold text-center text-[#2c0b57] mb-8">
        Manage Homepage Content
    </h1>

    <!-- Tabs Navigation -->
    <div class="mt-8 overflow-x-auto hide-scrollbar">
        <ul class="flex space-x-4 whitespace-nowrap justify-center">
            @foreach(['Placements', 'Courses', 'Upcoming Courses', 'Internships', 'Instructors', 'Testimonials', 'FAQs'] as $tab)
                <li>
                    <button class="nav-tab text-lg font-bold hover:text-[#ff7300] transition-all duration-300 px-4 py-2 rounded-xl {{ $loop->first ? 'text-white bg-[#ff7300]' : '' }}" data-tab="tab-{{ str_replace(' ', '-', strtolower($tab)) }}">
                        {{ $tab }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="mt-12">

        <!-- Placements -->
        <div id="tab-placements" class="tab-pane">
            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Add Placement</h2>
            <form action="{{ route('admin.placements.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" placeholder="Name" required class="p-3 border rounded-lg">
                    <input type="text" name="qualification" placeholder="Qualification" required class="p-3 border rounded-lg">
                    <input type="file" name="image" required class="p-3 border rounded-lg">
                    <input type="text" name="tags" placeholder="Tags (comma separated)" class="p-3 border rounded-lg">
                    <input type="text" name="company" placeholder="Company" required class="p-3 border rounded-lg">
                    <input type="text" name="package" placeholder="Package" required class="p-3 border rounded-lg">
                </div>
                <button type="submit" class="mt-4 w-full bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white py-3 rounded-lg font-bold">Add Placement</button>
            </form>

            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Existing Placements</h2>
            <div class="card-grid">
                @forelse($placements as $placement)
                    <div class="record-card">
                        @if($placement->image)
                            <img src="{{ asset('storage/' . ltrim($placement->image, '/')) }}" alt="{{ $placement->name }}">
                        @endif
                        <div class="record-body">
                            <div class="record-title">{{ $placement->name }}</div>
                            <div class="record-meta">{{ $placement->qualification }} <br> {{ $placement->company }} – {{ $placement->package }}</div>
                            <div class="record-actions">
                                <button type="button" class="edit toggle-edit" data-target="placement-edit-{{ $placement->id }}" aria-label="Edit {{ $placement->name }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.placements.delete', $placement->id) }}" method="POST" onsubmit="return confirm('Delete this?')">
                                    @csrf @method('DELETE')
                                    <button class="delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <form id="placement-edit-{{ $placement->id }}"
                                  class="edit-form mt-4 border-t border-gray-200 pt-4 grid gap-3"
                                  style="display: none;"
                                  data-display="grid"
                                  action="{{ route('admin.placements.update', $placement->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $placement->name }}" placeholder="Name" required class="p-2 border rounded-lg">
                                <input type="text" name="qualification" value="{{ $placement->qualification }}" placeholder="Qualification" required class="p-2 border rounded-lg">
                                <label class="block text-sm font-semibold text-gray-600">
                                    Update Image
                                    <input type="file" name="image" accept="image/*" class="mt-1 p-2 border rounded-lg w-full">
                                </label>
                                <input type="text" name="tags" value="{{ $placement->tags }}" placeholder="Tags" class="p-2 border rounded-lg">
                                <input type="text" name="company" value="{{ $placement->company }}" placeholder="Company" required class="p-2 border rounded-lg">
                                <input type="text" name="package" value="{{ $placement->package }}" placeholder="Package" required class="p-2 border rounded-lg">
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $placement->is_active ? 'checked' : '' }} class="h-4 w-4">
                                    <span class="text-sm text-gray-600">Active</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#ff7300] text-white rounded-lg font-semibold">Save</button>
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold cancel-edit" data-target="placement-edit-{{ $placement->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty <p>No Placements yet.</p> @endforelse
            </div>
        </div>

        <!-- Courses -->
        <div id="tab-courses" class="tab-pane hidden">
            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Add Course</h2>
            <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="title" placeholder="Title" required class="p-3 border rounded-lg">
                    <input type="file" name="image" accept="image/*" required class="p-3 border rounded-lg">
                    <input type="text" name="duration" placeholder="Duration" required class="p-3 border rounded-lg">
                    <input type="number" name="placed_count" placeholder="Placed Count" class="p-3 border rounded-lg">
                    <input type="number" name="rating" placeholder="Rating" step="0.1" class="p-3 border rounded-lg">
                    <input type="number" name="student_count" placeholder="Student Count" class="p-3 border rounded-lg">
                </div>
                <button type="submit" class="mt-4 w-full bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white py-3 rounded-lg font-bold">Add Course</button>
            </form>

            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Existing Courses</h2>
            <div class="card-grid">
                @forelse($courses as $course)
                    <div class="record-card">
                        @php
                            $courseImage = $course->image ? (\Illuminate\Support\Str::startsWith($course->image, ['http://', 'https://']) ? $course->image : asset('storage/' . ltrim($course->image, '/'))) : null;
                        @endphp
                        @if($courseImage)
                            <img src="{{ $courseImage }}" alt="{{ $course->title }}">
                        @endif
                        <div class="record-body">
                            <div class="record-title">{{ $course->title }}</div>
                            <div class="record-meta">Duration: {{ $course->duration }} <br> Rating: {{ $course->rating }} ⭐</div>
                            <div class="record-actions">
                                <button type="button" class="edit toggle-edit" data-target="course-edit-{{ $course->id }}" aria-label="Edit {{ $course->title }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.courses.delete', $course->id) }}" method="POST" onsubmit="return confirm('Delete this?')">
                                    @csrf @method('DELETE')
                                    <button class="delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <form id="course-edit-{{ $course->id }}"
                                  class="edit-form mt-4 border-t border-gray-200 pt-4 grid gap-3"
                                  style="display: none;"
                                  data-display="grid"
                                  action="{{ route('admin.courses.update', $course->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="text" name="title" value="{{ $course->title }}" placeholder="Title" required class="p-2 border rounded-lg">
                                <label class="block text-sm font-semibold text-gray-600">
                                    Update Image
                                    <input type="file" name="image" accept="image/*" class="mt-1 p-2 border rounded-lg w-full">
                                </label>
                                <input type="text" name="duration" value="{{ $course->duration }}" placeholder="Duration" required class="p-2 border rounded-lg">
                                <input type="number" name="placed_count" value="{{ $course->placed_count }}" placeholder="Placed Count" class="p-2 border rounded-lg">
                                <input type="number" step="0.1" name="rating" value="{{ $course->rating }}" placeholder="Rating" class="p-2 border rounded-lg">
                                <input type="number" name="student_count" value="{{ $course->student_count }}" placeholder="Student Count" class="p-2 border rounded-lg">
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $course->is_active ? 'checked' : '' }} class="h-4 w-4">
                                    <span class="text-sm text-gray-600">Active</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#ff7300] text-white rounded-lg font-semibold">Save</button>
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold cancel-edit" data-target="course-edit-{{ $course->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty <p>No Courses yet.</p> @endforelse
            </div>
        </div>

        <!-- Upcoming Courses -->
        <div id="tab-upcoming-courses" class="tab-pane hidden">
            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Add Upcoming Course</h2>
            <form action="{{ route('admin.upcoming_courses.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="title" placeholder="Title" required class="p-3 border rounded-lg">
                    <input type="file" name="image" accept="image/*" required class="p-3 border rounded-lg">
                    <input type="date" name="start_date" required class="p-3 border rounded-lg">
                </div>
                <button type="submit" class="mt-4 w-full bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white py-3 rounded-lg font-bold">Add Upcoming Course</button>
            </form>

            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Existing Upcoming Courses</h2>
            <div class="card-grid">
                @forelse($upcomingCourses as $course)
                    <div class="record-card">
                        @php
                            $upcomingImage = $course->image ? (\Illuminate\Support\Str::startsWith($course->image, ['http://', 'https://']) ? $course->image : asset('storage/' . ltrim($course->image, '/'))) : null;
                        @endphp
                        @if($upcomingImage)
                            <img src="{{ $upcomingImage }}" alt="{{ $course->title }}">
                        @endif
                        <div class="record-body">
                            <div class="record-title">{{ $course->title }}</div>
                            <div class="record-meta">Starts: {{ $course->start_date }} <br> Slots Open: {{ $course->slots_open }}</div>
                            <div class="record-actions">
                                <button type="button" class="edit toggle-edit" data-target="upcoming-course-edit-{{ $course->id }}" aria-label="Edit {{ $course->title }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.upcoming_courses.delete', $course->id) }}" method="POST" onsubmit="return confirm('Delete this?')">
                                    @csrf @method('DELETE')
                                    <button class="delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <form id="upcoming-course-edit-{{ $course->id }}"
                                  class="edit-form mt-4 border-t border-gray-200 pt-4 grid gap-3"
                                  style="display: none;"
                                  data-display="grid"
                                  action="{{ route('admin.upcoming_courses.update', $course->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="text" name="title" value="{{ $course->title }}" placeholder="Title" required class="p-2 border rounded-lg">
                                <label class="block text-sm font-semibold text-gray-600">
                                    Update Image
                                    <input type="file" name="image" accept="image/*" class="mt-1 p-2 border rounded-lg w-full">
                                </label>
                                <input type="date" name="start_date" value="{{ $course->start_date }}" required class="p-2 border rounded-lg">
                                <input type="number" name="slots_open" value="{{ $course->slots_open }}" placeholder="Slots Open" class="p-2 border rounded-lg">
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $course->is_active ? 'checked' : '' }} class="h-4 w-4">
                                    <span class="text-sm text-gray-600">Active</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#ff7300] text-white rounded-lg font-semibold">Save</button>
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold cancel-edit" data-target="upcoming-course-edit-{{ $course->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty <p>No Upcoming Courses yet.</p> @endforelse
            </div>
        </div>

        <!-- Internships -->
        <div id="tab-internships" class="tab-pane hidden">
            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Add Internship</h2>
            <form action="{{ route('admin.internships.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="title" placeholder="Title" required class="p-3 border rounded-lg">
                    <input type="file" name="image" accept="image/*" required class="p-3 border rounded-lg">
                    <input type="text" name="duration" placeholder="Duration" required class="p-3 border rounded-lg">
                    <input type="number" name="project_count" placeholder="Project Count" class="p-3 border rounded-lg">
                    <input type="number" name="rating" placeholder="Rating" step="0.1" class="p-3 border rounded-lg">
                    <input type="number" name="applicant_count" placeholder="Applicant Count" class="p-3 border rounded-lg">
                    <input type="text" name="certification" placeholder="Certification" class="p-3 border rounded-lg">
                </div>
                <button type="submit" class="mt-4 w-full bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white py-3 rounded-lg font-bold">Add Internship</button>
            </form>

            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Existing Internships</h2>
            <div class="card-grid">
                @forelse($internships as $internship)
                    <div class="record-card">
                        @php
                            $internshipImage = $internship->image ? (\Illuminate\Support\Str::startsWith($internship->image, ['http://', 'https://']) ? $internship->image : asset('storage/' . ltrim($internship->image, '/'))) : null;
                        @endphp
                        @if($internshipImage)
                            <img src="{{ $internshipImage }}" alt="{{ $internship->title }}">
                        @endif
                        <div class="record-body">
                            <div class="record-title">{{ $internship->title }}</div>
                            <div class="record-meta">Duration: {{ $internship->duration }} <br> Projects: {{ $internship->project_count }}</div>
                            <div class="record-actions">
                                <button type="button" class="edit toggle-edit" data-target="internship-edit-{{ $internship->id }}" aria-label="Edit {{ $internship->title }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.internships.delete', $internship->id) }}" method="POST" onsubmit="return confirm('Delete this?')">
                                    @csrf @method('DELETE')
                                    <button class="delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <form id="internship-edit-{{ $internship->id }}"
                                  class="edit-form mt-4 border-t border-gray-200 pt-4 grid gap-3"
                                  style="display: none;"
                                  data-display="grid"
                                  action="{{ route('admin.internships.update', $internship->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="text" name="title" value="{{ $internship->title }}" placeholder="Title" required class="p-2 border rounded-lg">
                                <label class="block text-sm font-semibold text-gray-600">
                                    Update Image
                                    <input type="file" name="image" accept="image/*" class="mt-1 p-2 border rounded-lg w-full">
                                </label>
                                <input type="text" name="duration" value="{{ $internship->duration }}" placeholder="Duration" required class="p-2 border rounded-lg">
                                <input type="number" name="project_count" value="{{ $internship->project_count }}" placeholder="Project Count" class="p-2 border rounded-lg">
                                <input type="number" step="0.1" name="rating" value="{{ $internship->rating }}" placeholder="Rating" class="p-2 border rounded-lg">
                                <input type="number" name="applicant_count" value="{{ $internship->applicant_count }}" placeholder="Applicant Count" class="p-2 border rounded-lg">
                                <input type="text" name="certification" value="{{ $internship->certification }}" placeholder="Certification" class="p-2 border rounded-lg">
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $internship->is_active ? 'checked' : '' }} class="h-4 w-4">
                                    <span class="text-sm text-gray-600">Active</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#ff7300] text-white rounded-lg font-semibold">Save</button>
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold cancel-edit" data-target="internship-edit-{{ $internship->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty <p>No Internships yet.</p> @endforelse
            </div>
        </div>

        <!-- Instructors -->
        <div id="tab-instructors" class="tab-pane hidden">
            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Add Instructor</h2>
            <form action="{{ route('admin.instructors.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" placeholder="Name" required class="p-3 border rounded-lg">
                    <input type="file" name="image" required class="p-3 border rounded-lg">
                    <input type="text" name="specialization" placeholder="Specialization" required class="p-3 border rounded-lg">
                    <input type="number" name="teaching_hours" placeholder="Teaching Hours" min="0" required class="p-3 border rounded-lg">
                    <input type="url" name="linkedin_url" placeholder="LinkedIn URL" class="p-3 border rounded-lg">
                    <input type="url" name="facebook_url" placeholder="Facebook URL" class="p-3 border rounded-lg">
                </div>
                <button type="submit" class="mt-4 w-full bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white py-3 rounded-lg font-bold">Add Instructor</button>
            </form>

            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Existing Instructors</h2>
            <div class="card-grid">
                @forelse($instructors as $instructor)
                    <div class="record-card">
                        @php
                            $instructorImage = $instructor->image ? (\Illuminate\Support\Str::startsWith($instructor->image, ['http://', 'https://']) ? $instructor->image : asset('storage/' . ltrim($instructor->image, '/'))) : null;
                        @endphp
                        @if($instructorImage)
                            <img src="{{ $instructorImage }}" alt="{{ $instructor->name }}">
                        @endif
                        <div class="record-body">
                            <div class="record-title">{{ $instructor->name }}</div>
                            <div class="record-meta">{{ $instructor->specialization }} <br> Teaching Hours: {{ $instructor->teaching_hours }}</div>
                            <div class="record-actions">
                                <button type="button" class="edit toggle-edit" data-target="instructor-edit-{{ $instructor->id }}" aria-label="Edit {{ $instructor->name }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.instructors.delete', $instructor->id) }}" method="POST" onsubmit="return confirm('Delete this?')">
                                    @csrf @method('DELETE')
                                    <button class="delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <form id="instructor-edit-{{ $instructor->id }}"
                                  class="edit-form mt-4 border-t border-gray-200 pt-4 grid gap-3"
                                  style="display: none;"
                                  data-display="grid"
                                  action="{{ route('admin.instructors.update', $instructor->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $instructor->name }}" placeholder="Name" required class="p-2 border rounded-lg">
                                <label class="block text-sm font-semibold text-gray-600">
                                    Update Image
                                    <input type="file" name="image" accept="image/*" class="mt-1 p-2 border rounded-lg w-full">
                                </label>
                                <input type="text" name="specialization" value="{{ $instructor->specialization }}" placeholder="Specialization" required class="p-2 border rounded-lg">
                                <input type="number" name="teaching_hours" value="{{ $instructor->teaching_hours }}" placeholder="Teaching Hours" min="0" required class="p-2 border rounded-lg">
                                <input type="url" name="linkedin_url" value="{{ $instructor->linkedin_url }}" placeholder="LinkedIn URL" class="p-2 border rounded-lg">
                                <input type="url" name="facebook_url" value="{{ $instructor->facebook_url }}" placeholder="Facebook URL" class="p-2 border rounded-lg">
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $instructor->is_active ? 'checked' : '' }} class="h-4 w-4">
                                    <span class="text-sm text-gray-600">Active</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#ff7300] text-white rounded-lg font-semibold">Save</button>
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold cancel-edit" data-target="instructor-edit-{{ $instructor->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty <p>No Instructors yet.</p> @endforelse
            </div>
        </div>

        <!-- Testimonials -->
        <div id="tab-testimonials" class="tab-pane hidden">
            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Add Testimonial</h2>
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" placeholder="Name" required class="p-3 border rounded-lg">
                    <input type="file" name="image" required class="p-3 border rounded-lg">
                    <input type="text" name="designation" placeholder="Designation" required class="p-3 border rounded-lg">
                </div>
                <textarea name="content" placeholder="Content" required class="p-3 border rounded-lg w-full"></textarea>
                <button type="submit" class="mt-4 w-full bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white py-3 rounded-lg font-bold">Add Testimonial</button>
            </form>

            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Existing Testimonials</h2>
            <div class="card-grid">
                @forelse($testimonials as $testimonial)
                    <div class="record-card">
                        @php
                            $testimonialImage = $testimonial->image ? (\Illuminate\Support\Str::startsWith($testimonial->image, ['http://', 'https://']) ? $testimonial->image : asset('storage/' . ltrim($testimonial->image, '/'))) : null;
                        @endphp
                        @if($testimonialImage)
                            <img src="{{ $testimonialImage }}" alt="{{ $testimonial->name }}">
                        @endif
                        <div class="record-body">
                            <div class="record-title">{{ $testimonial->name }}</div>
                            <div class="record-meta">{{ $testimonial->designation }} <br> Rating: {{ $testimonial->rating }}</div>
                            <p class="text-gray-600">{{ $testimonial->content }}</p>
                            <div class="record-actions">
                                <button type="button" class="edit toggle-edit" data-target="testimonial-edit-{{ $testimonial->id }}" aria-label="Edit {{ $testimonial->name }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.testimonials.delete', $testimonial->id) }}" method="POST" onsubmit="return confirm('Delete this?')">
                                    @csrf @method('DELETE')
                                    <button class="delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <form id="testimonial-edit-{{ $testimonial->id }}"
                                  class="edit-form mt-4 border-t border-gray-200 pt-4 grid gap-3"
                                  style="display: none;"
                                  data-display="grid"
                                  action="{{ route('admin.testimonials.update', $testimonial->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $testimonial->name }}" placeholder="Name" required class="p-2 border rounded-lg">
                                <label class="block text-sm font-semibold text-gray-600">
                                    Update Image
                                    <input type="file" name="image" accept="image/*" class="mt-1 p-2 border rounded-lg w-full">
                                </label>
                                <input type="text" name="designation" value="{{ $testimonial->designation }}" placeholder="Designation" required class="p-2 border rounded-lg">
                                <textarea name="content" placeholder="Content" required class="p-2 border rounded-lg">{{ $testimonial->content }}</textarea>
                                <input type="number" step="0.1" name="rating" value="{{ $testimonial->rating }}" placeholder="Rating" min="0" max="5" required class="p-2 border rounded-lg">
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $testimonial->is_active ? 'checked' : '' }} class="h-4 w-4">
                                    <span class="text-sm text-gray-600">Active</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#ff7300] text-white rounded-lg font-semibold">Save</button>
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold cancel-edit" data-target="testimonial-edit-{{ $testimonial->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty <p>No Testimonials yet.</p> @endforelse
            </div>
        </div>

        <!-- FAQs -->
        <div id="tab-faqs" class="tab-pane hidden">
            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Add FAQ</h2>
            <form action="{{ route('admin.faqs.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow-lg mb-8">
                @csrf
                <input type="text" name="question" placeholder="Question" required class="p-3 border rounded-lg w-full mb-2">
                <textarea name="answer" placeholder="Answer" required class="p-3 border rounded-lg w-full"></textarea>
                <button type="submit" class="mt-4 w-full bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white py-3 rounded-lg font-bold">Add FAQ</button>
            </form>

            <h2 class="text-2xl font-bold text-[#2c0b57] mb-4">Existing FAQs</h2>
            <div class="card-grid">
                @forelse($faqs as $faq)
                    <div class="record-card">
                        <div class="record-body">
                            <div class="record-title">{{ $faq->question }}</div>
                            <p class="text-gray-600">{{ $faq->answer }}</p>
                            <div class="record-actions">
                                <button type="button" class="edit toggle-edit" data-target="faq-edit-{{ $faq->id }}" aria-label="Edit FAQ {{ $faq->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.faqs.delete', $faq->id) }}" method="POST" onsubmit="return confirm('Delete this?')">
                                    @csrf @method('DELETE')
                                    <button class="delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <form id="faq-edit-{{ $faq->id }}"
                                  class="edit-form mt-4 border-t border-gray-200 pt-4 grid gap-3"
                                  style="display: none;"
                                  data-display="grid"
                                  action="{{ route('admin.faqs.update', $faq->id) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')
                                <input type="text" name="question" value="{{ $faq->question }}" placeholder="Question" required class="p-2 border rounded-lg">
                                <textarea name="answer" placeholder="Answer" required class="p-2 border rounded-lg">{{ $faq->answer }}</textarea>
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $faq->is_active ? 'checked' : '' }} class="h-4 w-4">
                                    <span class="text-sm text-gray-600">Active</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#ff7300] text-white rounded-lg font-semibold">Save</button>
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold cancel-edit" data-target="faq-edit-{{ $faq->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty <p>No FAQs yet.</p> @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll(".nav-tab");
        const panes = document.querySelectorAll(".tab-pane");
        function activateTab(tab){
            tabs.forEach(t=>t.classList.remove("text-white","bg-[#ff7300]"));
            panes.forEach(p=>p.classList.add("hidden"));
            tab.classList.add("text-white","bg-[#ff7300]");
            document.getElementById(tab.dataset.tab).classList.remove("hidden");
        }
        tabs.forEach(tab=>tab.addEventListener("click", ()=>activateTab(tab)));
        if(tabs.length>0) activateTab(tabs[0]);

        const editForms = document.querySelectorAll(".edit-form");
        function hideAllEditForms() {
            editForms.forEach(form => {
                form.style.display = "none";
            });
        }

        hideAllEditForms();

        document.querySelectorAll(".toggle-edit").forEach(button => {
            button.addEventListener("click", function () {
                const targetId = this.getAttribute("data-target");
                if (!targetId) {
                    return;
                }
                const form = document.getElementById(targetId);
                if (!form) {
                    return;
                }
                const wasVisible = form.style.display !== "none";
                hideAllEditForms();
                if (!wasVisible) {
                    const desiredDisplay = form.dataset.display || "block";
                    form.style.display = desiredDisplay;
                }
            });
        });

        document.querySelectorAll(".cancel-edit").forEach(button => {
            button.addEventListener("click", function () {
                const targetId = this.getAttribute("data-target");
                if (!targetId) {
                    return;
                }
                const form = document.getElementById(targetId);
                if (form) {
                    form.style.display = "none";
                }
            });
        });
    });
</script>
@endsection
