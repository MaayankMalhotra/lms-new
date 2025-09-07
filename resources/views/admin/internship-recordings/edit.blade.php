@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 mt-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Recording</h1>
    <form action="{{ route('admin.internship-recordings.update', $recording) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label for="recording_course_id" class="block text-sm font-medium text-gray-700 mb-1">Select Course</label>
            <select name="recording_course_id" id="recording_course_id" class="w-full border rounded p-2" required>
                <option value="" disabled>Select a course</option>
                @foreach ($courses as $course)
                <option value="{{ $course->id }}" {{ $recording->recording_course_id == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="topic" class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
            <input type="text" name="topic" id="topic" value="{{ $recording->topic }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ $recording->title }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Video Link</label>
            <input type="url" name="link" id="link" value="{{ $recording->link }}" class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Recording</button>
    </form>
</div>
@endsection