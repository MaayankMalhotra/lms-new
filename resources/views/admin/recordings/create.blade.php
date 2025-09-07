@extends('admin.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Manage Recordings</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Course Selection -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">Select Course</h2>
        <form action="{{ route('recordings.storeFolder') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Course</label>
                <select name="course_id" class="w-full p-2 border rounded" required>
                    <option value="">Choose a course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Folder Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded" placeholder="e.g., HTML" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Folder</button>
        </form>
    </div>

    <!-- Topic Creation -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">Add Topic</h2>
        <form action="{{ route('recordings.storeTopic') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Select Folder</label>
                <select name="folder_id" class="w-full p-2 border rounded" required>
                    <option value="">Choose a folder</option>
                    @foreach ($folders as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->course->name }} - {{ $folder->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Topic Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded" placeholder="e.g., Python Basics" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Discussion</label>
                <textarea name="discussion" class="w-full p-2 border rounded" placeholder="Enter topic discussion"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Topic</button>
        </form>
    </div>

    <!-- Recording Addition -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Add Recording</h2>
        <form action="{{ route('recordings.storeRecording') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Select Topic</label>
                <select name="topic_id" class="w-full p-2 border rounded" required>
                    <option value="">Choose a topic</option>
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->folder->course->name }} - {{ $topic->folder->name }} - {{ $topic->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Recording Link</label>
                <input type="url" name="video_url" class="w-full p-2 border rounded" placeholder="e.g., https://drive.google.com/file/..." required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Recording</button>
        </form>
    </div>
@endsection