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

    .action-icon.details {
        background-color: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }

    .action-icon.details:hover {
        background-color: rgba(22, 163, 74, 0.2);
        color: #15803d;
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

        <div class="bg-white/95 backdrop-blur rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full listing-table">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3 text-left">Course</th>
                            <th class="px-6 py-3 text-left">Slug</th>
                            <th class="px-6 py-3 text-left">Code</th>
                            <th class="px-6 py-3 text-left">Duration</th>
                            <th class="px-6 py-3 text-right">Price</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200/70">
                        @forelse($courses as $course)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                                            @if($course->logo)
                                                <img src="{{ asset($course->logo) }}" alt="{{ $course->name }}" class="object-contain w-full h-full">
                                            @else
                                                <i class="fas fa-image text-gray-300 text-xl"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $course->name }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $course->placed_learner }} placed • Rating {{ $course->rating }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $course->slug }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $course->course_code_id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $course->duration }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right font-semibold">₹{{ number_format($course->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <button
                                            class="action-icon edit"
                                            title="Edit Course"
                                            data-id="{{ $course->id }}"
                                            data-name="{{ $course->name }}"
                                            data-slug="{{ $course->slug }}"
                                            data-code="{{ $course->course_code_id }}"
                                            data-duration="{{ $course->duration }}"
                                            data-price="{{ $course->price }}"
                                            data-update-url="{{ route('admin.course.update', $course->id) }}"
                                            onclick="openEditModal(this)"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @php $detailId = $course->course_detail_id ?? null; @endphp
                                        @if ($detailId)
                                            <a href="{{ route('course.edit', $detailId) }}" class="action-icon details" title="Edit Course Details">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>
                                        @endif

                                        <button type="button"
                                            class="action-icon delete"
                                            title="Delete Course"
                                            data-delete-url="{{ route('admin.course.delete', $course->id) }}"
                                            data-name="{{ $course->name }}"
                                            onclick="openDeleteModal(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i class="fas fa-inbox text-3xl"></i>
                                        <p>No courses found. Start by adding a new course!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
        <div class="mt-8">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Shared Edit Course Modal (Tailwind) -->
<div id="editCourseModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeEditModal()"></div>

    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Edit Course</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeEditModal()">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form id="editCourseForm" method="POST" action="#">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                    <input id="edit_name" name="name" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input id="edit_slug" name="slug" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
                    <input id="edit_code" name="course_code_id" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                    <input id="edit_duration" name="duration" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input id="edit_price" name="price" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:ring focus:outline-none">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                        onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Shared Delete Course Modal (Tailwind) -->
<div id="deleteCourseModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeDeleteModal()"></div>

    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-800">Delete Course</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <p class="text-sm text-gray-600">
            Are you sure you want to delete
            <span id="deleteCourseName" class="font-semibold"></span>?
            This action cannot be undone.
        </p>

        <form id="deleteCourseForm" method="POST" action="#" class="mt-6">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                        onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /* ---------- Edit Modal ---------- */
    const editModal = document.getElementById('editCourseModal');
    const editForm  = document.getElementById('editCourseForm');

    const f = {
        id:        document.getElementById('edit_id'),
        name:      document.getElementById('edit_name'),
        slug:      document.getElementById('edit_slug'),
        code:      document.getElementById('edit_code'),
        duration:  document.getElementById('edit_duration'),
        price:     document.getElementById('edit_price'),
    };

    function openEditModal(btn) {
        f.id.value       = btn.dataset.id || '';
        f.name.value     = btn.dataset.name || '';
        f.slug.value     = btn.dataset.slug || '';
        f.code.value     = btn.dataset.code || '';
        f.duration.value = btn.dataset.duration || '';
        f.price.value    = btn.dataset.price || '';

        // Use admin.course.update
        editForm.action  = btn.dataset.updateUrl || '#';

        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    /* ---------- Delete Modal ---------- */
    const deleteModal  = document.getElementById('deleteCourseModal');
    const deleteForm   = document.getElementById('deleteCourseForm');
    const deleteNameEl = document.getElementById('deleteCourseName');

    function openDeleteModal(btn) {
        deleteForm.action = btn.dataset.deleteUrl || '#';
        deleteNameEl.textContent = btn.dataset.name || '';
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Close modals on ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endsection
