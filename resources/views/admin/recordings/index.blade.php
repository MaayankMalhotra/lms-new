@extends('admin.layouts.app')

@section('content')
    <style>
        body {
            background: url("https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg") no-repeat center center fixed;
            background-size: cover;
        }
        .collapsed { display: none; }
        .expanded { display: block; }
    </style>

    <h1 class="text-3xl font-bold mb-6">View Internship Recordings</h1>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded shadow mb-6">
        <div id="courses" class="space-y-4">
            @forelse ($courses as $course)
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <h2 class="text-xl font-semibold text-blue-600 cursor-pointer"
                        onclick="toggleSection(this, 'batches-{{ $course->id }}')">
                        {{ $course->name }}
                        <span class="text-gray-500">({{ $course->batches->count() }} batches)</span>
                    </h2>
                    <div id="batches-{{ $course->id }}" class="ml-4 mt-2 collapsed">
                        <h3 class="text-md font-medium text-gray-700 mb-2">Batches</h3>
                        @forelse ($course->batches as $batch)
                            <div class="bg-gray-50 p-2 rounded mt-2">
                                <h4 class="text-md font-medium text-green-600 cursor-pointer"
                                    onclick="toggleSection(this, 'folders-{{ $batch->id }}')">
                                    {{ $batch->batch_name }}
                                </h4>
                                <button onclick="openAddModal('folder', {{ $course->id }}, {{ $batch->id }})"
                                    class="bg-green-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-green-600">Add
                                    Folder</button>
                                <div id="folders-{{ $batch->id }}" class="ml-4 mt-2 collapsed">
                                    @php
                                        $batchFolders = isset($batch->folders) ? $batch->folders : $course->folders;
                                    @endphp
                                    @if ($batchFolders->isEmpty())
                                        <p class="text-sm text-gray-500 ml-4 mt-2">No folders</p>
                                    @else
                                        <h5 class="text-md font-medium text-gray-700 mb-2">Folders</h5>
                                        @foreach ($batchFolders as $folder)
                                            <div
                                                class="bg-gray-100 p-3 rounded mt-2 flex items-center justify-between">
                                                <h6 class="text-md font-medium text-purple-600 cursor-pointer"
                                                    onclick="toggleSection(this, 'topics-{{ $folder->id }}-{{ $batch->id }}')">
                                                    {{ $folder->name }}
                                                </h6>
                                                <div>
                                                    <span
                                                        class="text-sm mr-2 {{ $folder->locked ? 'text-red-500' : 'text-green-500' }}">
                                                        {{ $folder->locked ? 'Locked' : 'Unlocked' }}
                                                    </span>
                                                    <button onclick="toggleLock('folder', {{ $folder->id }})"
                                                        class="bg-{{ $folder->locked ? 'green' : 'red' }}-500 text-white px-2 py-1 rounded text-sm hover:bg-{{ $folder->locked ? 'green' : 'red' }}-600">
                                                        {{ $folder->locked ? 'Unlock' : 'Lock' }}
                                                    </button>
                                                    <button
                                                        onclick="openEditModal('folder', {{ $folder->id }}, '{{ $folder->name }}', {{ $course->id }})"
                                                        class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">Edit</button>
                                                </div>
                                            </div>
                                            <div id="topics-{{ $folder->id }}-{{ $batch->id }}" class="ml-4 mt-2 collapsed">
                                                @if ($folder->topics->isEmpty())
                                                    <p class="text-sm text-gray-500">No topics</p>
                                                @else
                                                    <h6 class="text-md font-medium text-gray-700 mb-2">Topics</h6>
                                                    @foreach ($folder->topics as $topic)
                                                        <div class="bg-white p-2 rounded mt-2">
                                                            <h7
                                                                class="text-sm font-medium text-indigo-600">{{ $topic->name }}</h7>
                                                            <button
                                                                onclick="openEditModal('topic', {{ $topic->id }}, '{{ $topic->name }}', {{ $course->id }})"
                                                                class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">Edit</button>
                                                            <div class="ml-4 mt-1">
                                                                @if ($topic->recordings->isEmpty())
                                                                    <p class="text-sm text-gray-500">No recordings</p>
                                                                @else
                                                                    @foreach ($topic->recordings as $recording)
                                                                        <div
                                                                            class="flex items-center justify-between mt-1">
                                                                            <a href="{{ $recording->video_url }}"
                                                                                target="_blank"
                                                                                class="text-sm text-blue-500 hover:underline">
                                                                                📹 Recording {{ $recording->id }}
                                                                            </a>
                                                                            <div>
                                                                                <span
                                                                                    class="text-sm mr-2 {{ $recording->locked ? 'text-red-500' : 'text-green-500' }}">
                                                                                    {{ $recording->locked ? 'Locked' : 'Unlocked' }}
                                                                                </span>
                                                                                <button
                                                                                    onclick="toggleLock('recording', {{ $recording->id }})"
                                                                                    class="bg-{{ $recording->locked ? 'green' : 'red' }}-500 text-white px-2 py-1 rounded text-sm hover:bg-{{ $recording->locked ? 'green' : 'red' }}-600">
                                                                                    {{ $recording->locked ? 'Unlock' : 'Lock' }}
                                                                                </button>
                                                                                <button
                                                                                    onclick="openEditModal('recording', {{ $recording->id }}, '{{ $recording->video_url }}', {{ $course->id }})"
                                                                                    class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">Edit</button>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                <button
                                                    onclick="openAddTopicAndRecordingModal('{{ $folder->id }}', {{ $course->id }})"
                                                    class="bg-green-500 text-white px-2 py-1 rounded text-sm mt-2 hover:bg-green-600">Add
                                                    Topic & Recording</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 ml-4 mt-2">No batches</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No courses available</p>
            @endforelse
        </div>

        {{-- Add/Edit Modals and JS same as before --}}
    </div>

    <script>
        function toggleSection(element, targetId) {
            const target = document.getElementById(targetId);
            if (target.classList.contains('collapsed')) {
                target.classList.remove('collapsed');
                target.classList.add('expanded');
            } else {
                target.classList.remove('expanded');
                target.classList.add('collapsed');
            }
        }

        function toggleLock(type, id) {
            fetch(`/admin/${type}/${id}/toggle-lock`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id, type }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // other modal functions unchanged ...
    </script>
@endsection
