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
                                                    onclick="openAddTopicAndRecordingModal('{{ $folder->id }}', {{ $course->id }}, {{ $batch->id }})"
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
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const CREATE_FOLDER_URL = "{{ route('create.folder') }}";
        const CREATE_TOPIC_RECORDING_URL = "{{ route('create.topic.and.recording') }}";
        const UPDATE_FOLDER_URL = "{{ route('update.folder', ['id' => '__ID__']) }}";
        const UPDATE_TOPIC_URL = "{{ route('update.topic', ['id' => '__ID__']) }}";
        const UPDATE_RECORDING_URL = "{{ route('update.recording', ['id' => '__ID__']) }}";

        function endpointWithId(template, id) {
            return template.replace('__ID__', String(id));
        }

        async function requestJson(url, method, payload) {
            const response = await fetch(url, {
                method,
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json().catch(() => ({}));
            return { ok: response.ok, data };
        }

        function firstErrorMessage(payload, fallback = 'Something went wrong. Please try again.') {
            if (payload?.errors) {
                const first = Object.values(payload.errors)[0];
                if (Array.isArray(first) && first.length) {
                    return first[0];
                }
            }
            if (typeof payload?.message === 'string' && payload.message) {
                return payload.message;
            }
            return fallback;
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function ensureFoldersHeading(container) {
            const heading = container.querySelector('h5');
            if (!heading) {
                const h5 = document.createElement('h5');
                h5.className = 'text-md font-medium text-gray-700 mb-2';
                h5.textContent = 'Folders';
                container.appendChild(h5);
            }
        }

        function removeNoFoldersMessage(container) {
            const messages = container.querySelectorAll('p.text-sm.text-gray-500');
            messages.forEach((p) => {
                if (p.textContent && p.textContent.toLowerCase().includes('no folders')) {
                    p.remove();
                }
            });
        }

        function appendFolderToBatch(folderId, folderName, courseId, batchId) {
            const container = document.getElementById(`folders-${batchId}`);
            if (!container) {
                location.reload();
                return;
            }

            ensureFoldersHeading(container);
            removeNoFoldersMessage(container);

            const safeFolderName = escapeHtml(folderName);
            const folderBlock = `
                <div class="bg-gray-100 p-3 rounded mt-2 flex items-center justify-between">
                    <h6 class="text-md font-medium text-purple-600 cursor-pointer"
                        onclick="toggleSection(this, 'topics-${folderId}-${batchId}')">
                        ${safeFolderName}
                    </h6>
                    <div>
                        <span class="text-sm mr-2 text-green-500">Unlocked</span>
                        <button onclick="toggleLock('folder', ${folderId})"
                            class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">
                            Lock
                        </button>
                        <button onclick="openEditModal('folder', ${folderId}, '${safeFolderName.replace(/'/g, "\\'")}', ${courseId})"
                            class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">
                            Edit
                        </button>
                    </div>
                </div>
                <div id="topics-${folderId}-${batchId}" class="ml-4 mt-2 collapsed">
                    <p class="text-sm text-gray-500">No topics</p>
                    <button
                        onclick="openAddTopicAndRecordingModal('${folderId}', ${courseId}, ${batchId})"
                        class="bg-green-500 text-white px-2 py-1 rounded text-sm mt-2 hover:bg-green-600">
                        Add Topic & Recording
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', folderBlock);
        }

        function removeNoTopicsMessage(topicsContainer) {
            const messages = topicsContainer.querySelectorAll('p.text-sm.text-gray-500');
            messages.forEach((p) => {
                if (p.textContent && p.textContent.toLowerCase().includes('no topics')) {
                    p.remove();
                }
            });
        }

        function ensureTopicsHeading(topicsContainer) {
            const heading = topicsContainer.querySelector('h6.text-md.font-medium.text-gray-700.mb-2');
            if (!heading) {
                const h6 = document.createElement('h6');
                h6.className = 'text-md font-medium text-gray-700 mb-2';
                h6.textContent = 'Topics';
                topicsContainer.prepend(h6);
            }
        }

        function appendTopicAndRecording(folderId, batchId, courseId, topicId, topicName, recordingId, recordingLink) {
            const topicsContainer = document.getElementById(`topics-${folderId}-${batchId}`);
            if (!topicsContainer) {
                location.reload();
                return;
            }

            topicsContainer.classList.remove('collapsed');
            topicsContainer.classList.add('expanded');
            ensureTopicsHeading(topicsContainer);
            removeNoTopicsMessage(topicsContainer);

            const safeTopicName = escapeHtml(topicName);
            const safeRecordingLink = escapeHtml(recordingLink);
            const topicHtml = `
                <div class="bg-white p-2 rounded mt-2">
                    <h7 class="text-sm font-medium text-indigo-600">${safeTopicName}</h7>
                    <button onclick="openEditModal('topic', ${topicId}, '${safeTopicName.replace(/'/g, "\\'")}', ${courseId})"
                        class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">
                        Edit
                    </button>
                    <div class="ml-4 mt-1">
                        <div class="flex items-center justify-between mt-1">
                            <a href="${safeRecordingLink}" target="_blank" class="text-sm text-blue-500 hover:underline">
                                📹 Recording ${recordingId}
                            </a>
                            <div>
                                <span class="text-sm mr-2 text-green-500">Unlocked</span>
                                <button onclick="toggleLock('recording', ${recordingId})"
                                    class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">
                                    Lock
                                </button>
                                <button onclick="openEditModal('recording', ${recordingId}, '${safeRecordingLink.replace(/'/g, "\\'")}', ${courseId})"
                                    class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const addButton = topicsContainer.querySelector('button[onclick*="openAddTopicAndRecordingModal"]');
            if (addButton) {
                addButton.insertAdjacentHTML('beforebegin', topicHtml);
            } else {
                topicsContainer.insertAdjacentHTML('beforeend', topicHtml);
            }
        }

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

        async function openAddModal(type, courseId, batchId) {
            if (type !== 'folder') {
                return;
            }

            const folderName = prompt('Enter folder name');
            if (!folderName || !folderName.trim()) {
                return;
            }

            const { ok, data } = await requestJson(CREATE_FOLDER_URL, 'POST', {
                name: folderName.trim(),
                course_id: courseId,
            });

            if (!ok || !data?.success) {
                alert(firstErrorMessage(data, 'Unable to create folder.'));
                return;
            }

            appendFolderToBatch(data.id, folderName.trim(), courseId, batchId);
        }

        async function openAddTopicAndRecordingModal(folderId, courseId, batchId) {
            const topicName = prompt('Enter topic name');
            if (!topicName || !topicName.trim()) {
                return;
            }

            const recordingLink = prompt('Enter recording link (URL)');
            if (!recordingLink || !recordingLink.trim()) {
                return;
            }

            const { ok, data } = await requestJson(CREATE_TOPIC_RECORDING_URL, 'POST', {
                topic_name: topicName.trim(),
                recording_link: recordingLink.trim(),
                folder_id: folderId,
                course_id: courseId,
            });

            if (!ok || !data?.success) {
                alert(firstErrorMessage(data, 'Unable to add topic and recording.'));
                return;
            }

            appendTopicAndRecording(folderId, batchId, courseId, data.topic_id, topicName.trim(), data.recording_id, recordingLink.trim());
        }

        async function openEditModal(type, id, currentValue, courseId) {
            const newValue = prompt(`Update ${type}`, currentValue || '');
            if (newValue === null || !newValue.trim()) {
                return;
            }

            let url = '';
            let payload = {};

            if (type === 'folder') {
                url = endpointWithId(UPDATE_FOLDER_URL, id);
                payload = { name: newValue.trim(), course_id: courseId };
            } else if (type === 'topic') {
                url = endpointWithId(UPDATE_TOPIC_URL, id);
                payload = { name: newValue.trim() };
            } else if (type === 'recording') {
                url = endpointWithId(UPDATE_RECORDING_URL, id);
                payload = { name: newValue.trim() };
            } else {
                return;
            }

            const { ok, data } = await requestJson(url, 'PUT', payload);
            if (!ok || (data && data.success === false)) {
                alert(firstErrorMessage(data, `Unable to update ${type}.`));
                return;
            }

            location.reload();
        }

        // other modal functions unchanged ...
    </script>
@endsection
