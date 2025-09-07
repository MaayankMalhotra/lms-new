@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 mt-8 bg-white rounded-lg shadow-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manage Recording Courses</h1>
        <a href="{{ route('admin.internship-recordings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
            Add New Recording
        </a>
    </div>

    <!-- Create Course Form -->
    <form action="{{ route('admin.internship-recording-courses.store') }}" method="POST" class="mb-8 bg-gray-50 p-4 rounded-lg">
        @csrf
        <div class="flex items-center space-x-4">
            <div class="flex-1">
                <label for="course_name" class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                <input type="text" name="course_name" id="course_name" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter course name" required>
                @error('course_name')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 mt-6">
                Add Course
            </button>
        </div>
    </form>

    <!-- Course List -->
    @if ($courses->isEmpty())
        <div class="text-center text-gray-600 py-6">
            <p>No recording courses available. Create one to get started!</p>
        </div>
    @else
        @foreach ($courses as $course)
        <div class="mb-6 border border-gray-200 rounded-lg bg-white shadow-sm">
            <!-- Course Edit Form -->
            <div class="p-4">
                <form action="{{ route('admin.internship-recording-courses.update', $course) }}" method="POST" class="flex items-center space-x-4">
                    @csrf @method('PUT')
                    <div class="flex-1">
                        <label for="course_name_{{ $course->id }}" class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                        <input type="text" name="course_name" id="course_name_{{ $course->id }}" value="{{ $course->course_name }}" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('course_name')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex space-x-2 mt-6">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                            Update
                        </button>
                        <form action="{{ route('admin.internship-recording-courses.destroy', $course) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200" onclick="return confirm('Are you sure you want to delete this course?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </form>
            </div>

            <!-- Recordings Section -->
            <div class="p-4 bg-gray-100 rounded-b-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recordings</h3>
                    <a href="{{ route('admin.internship-recordings.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Recording
                    </a>
                </div>
                
                @if ($course->recordings->isEmpty())
                    <div class="text-center py-4 text-gray-500">
                        <p>No recordings available for this course.</p>
                        <a href="{{ route('admin.internship-recordings.create') }}" class="text-blue-600 hover:underline">Add one now</a>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach ($course->recordings as $recording)
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center space-x-3">
                                <!-- Play Icon -->
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.2A1 1 0 0010 9.768v4.464a1 1 0 001.555.832l3.197-2.2a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $recording->topic }}</p>
                                    <p class="text-sm text-gray-600">{{ $recording->title }}</p>
                                    <a href="{{ $recording->link }}" target="_blank" class="text-blue-600 text-sm hover:underline" title="Open video link">
                                        View Recording
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.internship-recordings.edit', $recording) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center" title="Edit recording">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.internship-recordings.destroy', $recording) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium flex items-center" title="Delete recording" onclick="return confirm('Are you sure you want to delete this recording?')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0H6a1 1 0 00-1 1v1h14V4a1 1 0 00-1-1h-4z"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection