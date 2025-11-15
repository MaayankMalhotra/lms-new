@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center p-8" style="background-image: url('{{ asset('images/bg-pattern.jpg') }}');">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 bg-white/70 backdrop-blur-md p-4 rounded-xl shadow">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-users text-blue-500 mr-2"></i> Webinar Enrollments
                </h2>
                <p class="text-gray-600 mt-1">Manage all webinar enrollments from the panel below.</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $exportParams = array_filter(['webinar_id' => request('webinar_id')]);
                @endphp
                <a href="{{ route('admin.webinar.enrollments.export', $exportParams) }}"
                   class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg shadow text-sm flex items-center gap-2">
                    <i class="fas fa-file-csv"></i>
                    Export CSV
                </a>
                <button id="send-confirmation-btn"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
                    Send Confirmation Mail
                </button>
            </div>
        </div>

        <!-- Filter -->
        <div class="mb-6 bg-white/70 backdrop-blur-md p-4 rounded-xl shadow">
            <form action="{{ route('admin.webinar.enrollments') }}" method="GET" class="flex items-center space-x-4">
                <div class="relative w-full max-w-sm">
                    <select name="webinar_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Webinar Title</option>
                        @foreach ($webinars as $webinar)
                            <option value="{{ $webinar->id }}" {{ request('webinar_id') == $webinar->id ? 'selected' : '' }}>
                                {{ $webinar->title ?? 'Untitled Webinar (ID: ' . $webinar->id . ')' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-search"></i>
                </button>
                @if (request('webinar_id'))
                    <a href="{{ route('admin.webinar.enrollments') }}" class="text-sm text-blue-500 hover:text-blue-700">Clear</a>
                @endif
            </form>
        </div>

        <!-- Enrollments as Cards -->
        @if($enrollments->isEmpty())
            <div class="text-center text-gray-100 py-12">
                <i class="fas fa-user-times text-4xl mb-3"></i>
                <p>No enrollments found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrollments as $enrollment)
                    <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg hover:shadow-2xl transition duration-300 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ $enrollment->webinar->title ?? 'Untitled Webinar' }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-user mr-2 text-blue-500"></i>{{ $enrollment->name }}</p>
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-envelope mr-2 text-blue-500"></i>{{ $enrollment->email }}</p>
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-phone mr-2 text-blue-500"></i>{{ $enrollment->phone ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-comment mr-2 text-blue-500"></i>{{ $enrollment->comments ?? 'N/A' }}</p>
                        
                        <p class="mt-3">
                            @if ($enrollment->attendance_status === 'present')
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">Present</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                            @endif
                        </p>

                        <div class="mt-4 flex justify-between items-center">
                            @if ($enrollment->certificate_sent)
                                <a href="{{ $enrollment->certificate_path }}" target="_blank"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    View Certificate
                                </a>
                            @else
                                @if ($enrollment->attendance_status === 'present')
                                    <form action="{{ route('admin.webinar.send-certificate', $enrollment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                            Send Certificate
                                        </button>
                                    </form>
                                @else
                                    <button class="bg-gray-300 text-gray-600 px-3 py-1 rounded text-sm cursor-not-allowed" disabled>
                                        Send Certificate
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $enrollments->appends(['webinar_id' => request('webinar_id')])->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Send Confirmation Mail</h3>
        <form id="confirmation-form">
            @csrf
            <input type="hidden" id="webinar-id" name="webinar_id" value="{{ request('webinar_id') }}">
            <div class="mb-3">
                <label class="block text-sm font-medium">Attendance Code</label>
                <input type="text" id="attendance-code" name="attendance_code" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Meeting ID</label>
                <input type="text" id="meeting-id" name="meeting_id" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Meeting Link</label>
                <input type="url" id="meeting-link" name="meeting_link" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Meeting Password</label>
                <input type="text" id="meeting-password" name="meeting_password" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="close-modal-btn" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Send</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('confirmation-modal');
    const openBtn = document.getElementById('send-confirmation-btn');
    const closeBtn = document.getElementById('close-modal-btn');
    const form = document.getElementById('confirmation-form');

    openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        fetch('{{ route('admin.webinar.send-confirmation') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({
                attendance_code: document.getElementById('attendance-code').value,
                meeting_id: document.getElementById('meeting-id').value,
                meeting_link: document.getElementById('meeting-link').value,
                meeting_password: document.getElementById('meeting-password').value,
                webinar_id: document.getElementById('webinar-id').value
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Confirmation mail sent!');
            modal.classList.add('hidden');
        })
        .catch(err => {
            console.error(err);
            alert('Failed to send mail');
        });
    });
});
</script>
@endsection
