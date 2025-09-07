@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Your Assignments</h1>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 text-green-800 p-4 rounded-lg mb-8 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Batch Selection -->
        <div class="mb-8">
            <label for="batch_select" class="block text-lg font-medium text-gray-700 mb-2">Select Batch</label>
            <select id="batch_select" class="w-full max-w-md border-gray-300 border-2 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                <option value="">Select a batch</option>
                @foreach ($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->course->name }} ({{ $batch->start_date->format('Y-m-d') }})</option>
                @endforeach
            </select>
        </div>

        <!-- Batch Details (Hidden until batch is selected) -->
        <div id="batch_details" class="hidden bg-white p-6 rounded-lg shadow-md mb-6 cursor-pointer hover:shadow-lg transition duration-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Batch Details</h2>
            <p class="text-gray-600"><strong>Course:</strong> <span id="batch_course"></span></p>
            <p class="text-gray-600"><strong>Start Date:</strong> <span id="batch_start_date"></span></p>
        </div>

        <!-- Assignments Section (Hidden until batch is selected) -->
        <div id="assignments_section" class="hidden">
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Assignments</h2>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Description</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Due Date</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Submission Status</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assignments_body" class="divide-y divide-gray-200"></tbody>
                    </table>
                </div>
            </div>

            <!-- Submissions Section -->
            <div id="submissions_section" class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Submissions</h2>
                <div id="submissions_body" class="grid gap-4"></div>
            </div>
        </div>
    </div>

    <script>
        const batchSelect = document.getElementById('batch_select');
        const batchDetails = document.getElementById('batch_details');
        const batchCourse = document.getElementById('batch_course');
        const batchStartDate = document.getElementById('batch_start_date');
        const assignmentsSection = document.getElementById('assignments_section');
        const assignmentsBody = document.getElementById('assignments_body');
        const submissionsBody = document.getElementById('submissions_body');

        // Toggle assignments visibility on batch details click
        batchDetails.addEventListener('click', () => {
            assignmentsSection.classList.toggle('hidden');
        });

        batchSelect.addEventListener('change', function () {
            const batchId = this.value;

            // Reset UI
            batchDetails.classList.add('hidden');
            assignmentsSection.classList.add('hidden');
            assignmentsBody.innerHTML = '';
            submissionsBody.innerHTML = '';

            if (!batchId) return;

            // Show batch details
            const selectedOption = this.options[this.selectedIndex];
            batchCourse.textContent = selectedOption.text.split(' (')[0];
            batchStartDate.textContent = selectedOption.text.match(/\d{4}-\d{2}-\d{2}/)[0];
            batchDetails.classList.remove('hidden');

            // Fetch assignments
            fetch(`assignments/batch/${batchId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate assignments table
                    data.forEach(assignment => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50';
                        const submissionStatus = assignment.submissions.length > 0
                            ? `${assignment.submissions.length} Submission${assignment.submissions.length > 1 ? 's' : ''}`
                            : 'Not Submitted';
                        const submissionLinks = assignment.submissions.length > 0
                            ? assignment.submissions.map(s => `<a href="/submissions/download/${s.id}" class="text-blue-600 hover:underline">View Submission (${new Date(s.submitted_at).toLocaleString()})</a>`).join('<br>')
                            : '';

                        row.innerHTML = `
                            <td class="px-6 py-4 text-gray-800">${assignment.title}</td>
                            <td class="px-6 py-4 text-gray-600">${assignment.description || 'N/A'}</td>
                            <td class="px-6 py-4 text-gray-600">${new Date(assignment.due_date).toLocaleString()}</td>
                            <td class="px-6 py-4 text-gray-600">${submissionStatus}</td>
                            <td class="px-6 py-4">
                                <a href="/assignments/download/${assignment.id}" class="text-blue-600 hover:underline">Download Assignment</a>
                                
                            </td>
                        `;
                        assignmentsBody.appendChild(row);

                        // Populate submissions section
                        if (assignment.submissions.length > 0) {
                            assignment.submissions.forEach(submission => {
                                const submissionCard = document.createElement('div');
                                submissionCard.className = 'p-4 bg-gray-50 rounded-lg shadow-sm';
                                submissionCard.innerHTML = `
                                    <p class="text-sm text-gray-600"><strong>Assignment:</strong> ${assignment.title}</p>
                                    <p class="text-sm text-gray-600"><strong>Submitted:</strong> ${new Date(submission.submitted_at).toLocaleString()}</p>
                                    <a href="/submissions/download/${submission.id}" class="text-blue-600 hover:underline text-sm">Download Submission</a>
                                `;
                                submissionsBody.appendChild(submissionCard);
                            });
                        }
                    });

                    // Show assignments section if there are assignments
                    if (data.length > 0) {
                        assignmentsSection.classList.remove('hidden');
                    }
                })
                .catch(error => console.error('Error fetching assignments:', error));
        });
    </script>
@endsection