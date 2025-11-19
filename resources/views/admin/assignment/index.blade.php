@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">All Assignments</h1>
            <p class="text-gray-600">Listed with courses and submissions.</p>
        </div>
        <a href="{{ route('admin.assignments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Upload Assignment</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6">{{ session('success') }}</div>
    @endif

    <div class="space-y-6">
        @forelse($assignments as $assignment)
            <div class="bg-white shadow rounded-lg p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $assignment->title }}</h2>
                        <p class="text-sm text-gray-600">{{ $assignment->description ?? 'No description' }}</p>
                        <p class="text-sm text-gray-700 mt-2"><strong>Course:</strong> {{ $assignment->course_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-700"><strong>Due:</strong> {{ optional($assignment->due_date)->format('Y-m-d H:i') }}</p>
                        @if($assignment->file_path)
                            <a href="{{ route('admin.assignments.download', $assignment->id) }}" class="text-blue-600 hover:underline text-sm">Download assignment</a>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Submissions</h3>
                    @php
                        $submissions = $submissionsByAssignment[$assignment->id] ?? collect();
                    @endphp
                    @if($submissions->isEmpty())
                        <p class="text-sm text-gray-600">No submissions yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-gray-700">Student</th>
                                        <th class="px-3 py-2 text-left text-gray-700">Submitted At</th>
                                        <th class="px-3 py-2 text-left text-gray-700">File</th>
                                        <th class="px-3 py-2 text-left text-gray-700">Status</th>
                                        <th class="px-3 py-2 text-left text-gray-700">Marks</th>
                                        <th class="px-3 py-2 text-left text-gray-700">Feedback</th>
                                        <th class="px-3 py-2 text-left text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($submissions as $submission)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-800">{{ $submission->student_name ?? 'Student #'.$submission->user_id }}<br><span class="text-gray-500 text-xs">{{ $submission->student_email }}</span></td>
                                            <td class="px-3 py-2 text-gray-600">{{ \Carbon\Carbon::parse($submission->created_at)->format('Y-m-d H:i') }}</td>
                                            <td class="px-3 py-2 text-blue-600"><a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="hover:underline">View</a></td>
                                            <td class="px-3 py-2 text-gray-800">
                                                @php
                                                    $statusLabel = [
                                                        'approved' => 'Approved',
                                                        'needs_resubmission' => 'Needs Resubmission',
                                                        'expired' => 'Expired',
                                                        'submitted' => 'Under Review',
                                                    ][$submission->status ?? 'submitted'] ?? 'Under Review';
                                                    $statusColor = match($submission->status ?? 'submitted') {
                                                        'approved' => 'bg-green-100 text-green-800',
                                                        'needs_resubmission' => 'bg-yellow-100 text-yellow-800',
                                                        'expired' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColor }}">{{ $statusLabel }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-gray-800">{{ $submission->marks ?? '—' }}</td>
                                            <td class="px-3 py-2 text-gray-700 text-sm">{{ $submission->feedback ?? '—' }}</td>
                                            <td class="px-3 py-2">
                                                <form action="{{ route('admin.assignment_submissions.mark', $submission->id) }}" method="POST" class="flex flex-col space-y-2">
                                                    @csrf
                                                    <div class="flex items-center space-x-2">
                                                        <label class="text-xs text-gray-600">Status</label>
                                                        <select name="status" class="border rounded px-2 py-1 text-sm">
                                                            <option value="submitted" {{ ($submission->status ?? '') === 'submitted' ? 'selected' : '' }}>Under Review</option>
                                                            <option value="approved" {{ ($submission->status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                                                            <option value="needs_resubmission" {{ ($submission->status ?? '') === 'needs_resubmission' ? 'selected' : '' }}>Needs Resubmission</option>
                                                            <option value="expired" {{ ($submission->status ?? '') === 'expired' ? 'selected' : '' }}>Expired (0)</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <label class="text-xs text-gray-600">Marks</label>
                                                        <input type="number" name="marks" value="{{ $submission->marks }}" min="0" class="w-24 border rounded px-2 py-1 text-sm">
                                                    </div>
                                                    <textarea name="feedback" rows="2" class="w-full border rounded px-2 py-1 text-sm" placeholder="Feedback for the student">{{ $submission->feedback }}</textarea>
                                                    <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded text-xs hover:bg-gray-900 self-start">Save</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-600">No assignments yet.</div>
        @endforelse
    </div>
</div>
@endsection
