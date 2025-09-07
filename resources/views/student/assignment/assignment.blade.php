@extends('admin.layouts.app')

@section('content')
<section class="mb-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Your Assignments</h1>
    
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($liveClasses as $batch)
            @if ($batch->assignments->isNotEmpty())
                @foreach ($batch->assignments as $assignment)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                        <h5 class="text-xl font-semibold text-gray-900 mb-3">{{ $assignment->title }}</h5>
                        <p class="text-sm text-gray-600 mb-2">
                            <span class="font-medium">Batch:</span> Batch #{{ $batch->id }}
                        </p>
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
                        <div class="flex flex-col space-y-4">
                            @if ($assignment->file_path)
                                <a href="{{ $assignment->file_url }}" target="_blank" 
                                   class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200 text-sm font-medium w-full text-center">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Assignment
                                </a>
                            @endif
                            
                            @if ($assignment->has_submission && $assignment->submission_file_url)
                                <a href="{{ $assignment->submission_file_url }}" target="_blank" 
                                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200 text-sm font-medium w-full text-center">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Submission
                                </a>
                            @elseif (!$assignment->has_submission)
                                <form action="{{ route('student.assignment.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col space-y-2">
                                    @csrf
                                    <div class="flex items-center space-x-2">
                                        <input type="file" name="submission_file" class="text-sm text-gray-600 w-full" accept=".pdf,.doc,.docx,.zip" required>
                                    </div>
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors duration-200 text-sm font-medium w-full">
                                        Submit Assignment
                                    </button>
                                    @error('submission_file')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </form>
                            @else
                                <span class="text-sm text-gray-600 font-medium text-center">No Submission Available</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        @empty
            <div class="col-span-full text-center">
                <p class="text-gray-500 text-lg">No assignments available.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection