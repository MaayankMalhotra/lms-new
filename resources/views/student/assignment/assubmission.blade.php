@extends('admin.layouts.app')

@section('content')
<section class="mb-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Assignments for {{ $liveClass->topic }}</h1>
    <p class="text-sm text-gray-600 mb-6">Class Date: {{ \Carbon\Carbon::parse($liveClass->class_datetime)->format('Y-m-d H:i') }}</p>

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @forelse ($assignments as $assignment)
        <div class="bg-white rounded-lg shadow-md mb-6 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ $assignment->title }}</h2>
            <p class="text-sm text-gray-600 mb-2">
                <span class="font-medium">Due Date:</span> 
                <span class="{{ \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'text-red-600' : 'text-green-600' }}">
                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d H:i') }}
                </span>
            </p>
            <p class="text-sm text-gray-600 mb-4">
                <span class="font-medium">Description:</span> 
                {{ $assignment->description ?? 'No description provided' }}
            </p>
            @if ($assignment->file_path)
                <a href="{{ $assignment->file_url }}" target="_blank" 
                   class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200 text-sm font-medium mb-4">
                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Assignment
                </a>
            @endif

            <h3 class="text-lg font-medium text-gray-800 mb-2">Submissions</h3>
            @if ($assignment->submissions->isNotEmpty())
                <div class="space-y-4">
                    @foreach ($assignment->submissions as $submission)
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-md">
                            <span class="text-sm text-gray-700">{{ $submission->student_name }}</span>
                            @if ($submission->file_url)
                                <a href="{{ $submission->file_url }}" target="_blank" 
                                   class="inline-block bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition-colors duration-200 text-sm">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download
                                </a>
                            @else
                                <span class="text-sm text-gray-500">No file submitted</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No submissions yet.</p>
            @endif
        </div>
    @empty
        <div class="text-center">
            <p class="text-gray-500 text-lg">No assignments available for this live class.</p>
        </div>
    @endforelse
</section>
@endsection