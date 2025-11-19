@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Upload Assignment</h1>
            <a href="{{ route('admin.assignments.index') }}"
               class="inline-flex items-center justify-center bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors duration-200 text-sm font-medium">
                View All Assignments
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.assignments.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label for="course_id" class="block text-gray-700 font-medium mb-2">Select Course</label>
                <select name="course_id" id="course_id" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200">
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="batch_id" class="block text-gray-700 font-medium mb-2">Select Batch</label>
                <select name="batch_id" id="batch_id" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200" disabled>
                    <option value="">Select a course first</option>
                </select>
                @error('batch_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                <input type="text" name="title" id="title" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200" value="{{ old('title') }}">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">Description (Optional)</label>
                <textarea name="description" id="description" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-gray-700 font-medium mb-2">Due Date</label>
                <input type="datetime-local" name="due_date" id="due_date" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200" value="{{ old('due_date') }}">
                @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="file" class="block text-gray-700 font-medium mb-2">Upload File (Photo/PDF)</label>
                <input type="file" name="file" id="file" class="w-full border-gray-500 border-2 rounded-md p-2">
                @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">Upload Assignment</button>
        </form>

        <div id="existing_assignments" class="mt-10 hidden">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Existing Assignments</h2>
            <p class="text-sm text-gray-500 mb-4">Assignments filter automatically based on the selected course and batch.</p>
            <div id="assignment_cards" class="grid grid-cols-1 lg:grid-cols-2 gap-4"></div>
            <p id="assignment_placeholder" class="text-gray-500 text-sm">Select a course to view assignments.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const batchesByCourse = @json($batchesByCourse);
            const courseSelect = document.getElementById('course_id');
            const batchSelect = document.getElementById('batch_id');
            const preservedBatch = '{{ old('batch_id') }}';
            const assignmentsWrap = document.getElementById('existing_assignments');
            const assignmentCards = document.getElementById('assignment_cards');
            const assignmentPlaceholder = document.getElementById('assignment_placeholder');
            const assignmentsUrlTemplate = @json(route('admin.assignments.course', ['courseId' => '__course__']));

            function populateBatches(courseId, preserveSelection = false) {
                const batches = batchesByCourse[courseId] || [];
                const targetValue = preserveSelection ? preservedBatch : '';
                batchSelect.innerHTML = '';

                if (!courseId || batches.length === 0) {
                    batchSelect.disabled = true;
                    batchSelect.innerHTML = `<option value="">${courseId ? 'No batches available' : 'Select a course first'}</option>`;
                    refreshAssignments();
                    return;
                }

                batchSelect.disabled = false;
                batchSelect.appendChild(new Option('Select a batch', ''));

                batches.forEach(batch => {
                    const label = batch.start_date ? `${batch.label} — ${batch.start_date}` : batch.label;
                    const option = new Option(label, batch.id);
                    if (targetValue && Number(targetValue) === Number(batch.id)) {
                        option.selected = true;
                    }
                    batchSelect.appendChild(option);
                });

                refreshAssignments();
            }

            function refreshAssignments() {
                const courseId = courseSelect.value;
                if (!courseId) {
                    assignmentsWrap.classList.add('hidden');
                    assignmentCards.innerHTML = '';
                    assignmentPlaceholder.textContent = 'Select a course to view assignments.';
                    return;
                }

                const batchId = batchSelect.value;
                const url = assignmentsUrlTemplate.replace('__course__', courseId) + (batchId ? `?batch_id=${batchId}` : '');

                assignmentsWrap.classList.remove('hidden');
                assignmentPlaceholder.textContent = 'Loading assignments...';
                assignmentCards.innerHTML = '';

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => {
                        if (!response.ok) throw new Error();
                        return response.json();
                    })
                    .then(assignments => {
                        if (!assignments.length) {
                            assignmentCards.innerHTML = '';
                            assignmentPlaceholder.textContent = 'No assignments found for this selection.';
                            return;
                        }

                        assignmentPlaceholder.textContent = '';
                        assignmentCards.innerHTML = assignments.map(assignment => {
                            const dueDate = assignment.due_date
                                ? new Date(assignment.due_date).toLocaleString()
                                : 'No due date';
                            const batchLabel = assignment.batch
                                ? `${assignment.batch.name}${assignment.batch.start_date ? ' — ' + assignment.batch.start_date : ''}`
                                : 'Unassigned';
                            return `
                                <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">${assignment.title}</h3>
                                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Batch:</span> ${batchLabel}</p>
                                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Due:</span> ${dueDate}</p>
                                    <p class="text-sm text-gray-600 mb-3"><span class="font-medium">Description:</span> ${assignment.description || 'No description provided'}</p>
                                    ${assignment.file_url ? `<a href="${assignment.file_url}" target="_blank" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">Download Assignment</a>` : ''}
                                </div>
                            `;
                        }).join('');
                    })
                    .catch(() => {
                        assignmentCards.innerHTML = '';
                        assignmentPlaceholder.textContent = 'Unable to load assignments right now.';
                    });
            }

            courseSelect.addEventListener('change', event => populateBatches(event.target.value, false));
            batchSelect.addEventListener('change', refreshAssignments);

            if (courseSelect.value) {
                populateBatches(courseSelect.value, true);
            } else {
                refreshAssignments();
            }
        });
    </script>
@endsection
