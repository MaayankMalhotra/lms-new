@extends('admin.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">View Internship Recordings</h1>

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

    <div class="bg-white p-6 rounded shadow mb-6">
        <div id="courses" class="space-y-4">
            @foreach ($courses as $course)
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <h2 class="text-xl font-semibold text-blue-600 cursor-pointer" onclick="toggleSection(this, 'batches-{{ $course->id }}')">
                        {{ $course->name }} <span class="text-gray-500">({{ $course->batches->count() }} batches)</span>
                    </h2>
                    <div id="batches-{{ $course->id }}" class="ml-4 mt-2 collapsed">
                        <h3 class="text-md font-medium text-gray-700 mb-2">Batches</h3>
                        @foreach ($course->batches as $batch)
                            <div class="bg-gray-50 p-2 rounded mt-2">
                                <h4 class="text-md font-medium text-green-600 cursor-pointer" onclick="toggleSection(this, 'folders-{{ $batch->id }}')">
                                    {{ $batch->batch_name }}
                                </h4>
                                <button onclick="openAddModal('folder', {{ $course->id }}, {{ $batch->id }})" class="bg-green-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-green-600">Add Folder</button>
                                <div id="folders-{{ $batch->id }}" class="ml-4 mt-2 collapsed">
                                    @if ($course->folders->isEmpty())
                                        <p class="text-sm text-gray-500 ml-4 mt-2">No folders</p>
                                    @else
                                        <h5 class="text-md font-medium text-gray-700 mb-2">Folders</h5>
                                        @foreach ($course->folders as $folder)
                                            @if ($folder->internship_id == $course->id)
                                                <div class="bg-gray-100 p-3 rounded mt-2 flex items-center justify-between">
                                                    <h6 class="text-md font-medium text-purple-600 cursor-pointer" onclick="toggleSection(this, 'topics-{{ $folder->id }}-{{ $batch->id }}')">
                                                        {{ $folder->name }}
                                                    </h6>
                                                    <div>
                                                        <span class="text-sm mr-2 {{ $folder->locked ? 'text-red-500' : 'text-green-500' }}">
                                                            {{ $folder->locked ? 'Locked' : 'Unlocked' }}
                                                        </span>
                                                        <button onclick="toggleLock('folder', {{ $folder->id }})" class="bg-{{ $folder->locked ? 'green' : 'red' }}-500 text-white px-2 py-1 rounded text-sm hover:bg-{{ $folder->locked ? 'green' : 'red' }}-600">
                                                            {{ $folder->locked ? 'Unlock' : 'Lock' }}
                                                        </button>
                                                        <button onclick="openEditModal('folder', {{ $folder->id }}, '{{ $folder->name }}', {{ $course->id }})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">Edit</button>
                                                    </div>
                                                </div>
                                                <div id="topics-{{ $folder->id }}-{{ $batch->id }}" class="ml-4 mt-2 collapsed">
                                                    @if ($folder->topics->isEmpty())
                                                        <p class="text-sm text-gray-500">No topics</p>
                                                    @else
                                                        <h6 class="text-md font-medium text-gray-700 mb-2">Topics</h6>
                                                        @foreach ($folder->topics as $topic)
                                                            <div class="bg-white p-2 rounded mt-2">
                                                                <h7 class="text-sm font-medium text-indigo-600">{{ $topic->name }}</h7>
                                                                <button onclick="openEditModal('topic', {{ $topic->id }}, '{{ $topic->name }}', {{ $course->id }})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">Edit</button>
                                                                <div class="ml-4 mt-1">
                                                                    @if ($topic->recordings->isEmpty())
                                                                        <p class="text-sm text-gray-500">No recordings</p>
                                                                    @else
                                                                        @foreach ($topic->recordings as $recording)
                                                                            <div class="flex items-center justify-between mt-1">
                                                                                <a href="{{ $recording->video_url }}" target="_blank" class="text-sm text-blue-500 hover:underline">
                                                                                    📹 Recording {{ $recording->id }}
                                                                                </a>
                                                                                <div>
                                                                                    <span class="text-sm mr-2 {{ $recording->locked ? 'text-red-500' : 'text-green-500' }}">
                                                                                        {{ $recording->locked ? 'Locked' : 'Unlocked' }}
                                                                                    </span>
                                                                                    <button onclick="toggleLock('recording', {{ $recording->id }})" class="bg-{{ $recording->locked ? 'green' : 'red' }}-500 text-white px-2 py-1 rounded text-sm hover:bg-{{ $recording->locked ? 'green' : 'red' }}-600">
                                                                                        {{ $recording->locked ? 'Unlock' : 'Lock' }}
                                                                                    </button>
                                                                                    <button onclick="openEditModal('recording', {{ $recording->id }}, '{{ $recording->video_url }}', {{ $course->id }})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm ml-2 hover:bg-blue-600">Edit</button>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <button onclick="openAddTopicAndRecordingModal('{{ $folder->id }}', {{ $course->id }})" class="bg-green-500 text-white px-2 py-1 rounded text-sm mt-2 hover:bg-green-600">Add Topic & Recording</button>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add Modal (for Folder) -->
        <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded shadow-lg w-1/3">
                <h2 id="addModalTitle" class="text-xl font-bold mb-4"></h2>
                <form id="addForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="addType" name="type">
                    <input type="hidden" id="addParentId" name="parent_id">
                    <div class="mb-4">
                        <label class="block text-gray-700">Name</label>
                        <input id="addName" name="name" type="text" class="w-full p-2 border rounded" required>
                    </div>
                    @if (isset($course))
                        <div class="mb-4">
                            <label class="block text-gray-700">Course</label>
                            <select id="addCourseId" name="course_id" class="w-full p-2 border rounded" required>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeAddModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Topic & Recording Modal -->
        <div id="addTopicAndRecordingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded shadow-lg w-1/3">
                <h2 id="addTopicAndRecordingModalTitle" class="text-xl font-bold mb-4">Add Topic & Recording</h2>
                <form id="addTopicAndRecordingForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="addTopicAndRecordingFolderId" name="folder_id">
                    <input type="hidden" id="addTopicAndRecordingCourseId" name="course_id">
                    <div class="mb-4">
                        <label class="block text-gray-700">Topic Name</label>
                        <input id="addTopicName" name="topic_name" type="text" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Recording Link</label>
                        <input id="addRecordingLink" name="recording_link" type="url" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeAddTopicAndRecordingModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded shadow-lg w-1/3">
                <h2 id="editModalTitle" class="text-xl font-bold mb-4"></h2>
                <form id="editForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="editId" name="id">
                    <input type="hidden" id="editType" name="type">
                    <div class="mb-4">
                        <label class="block text-gray-700">Name/Link</label>
                        <input id="editName" name="name" type="text" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Course</label>
                        <select id="editCourseId" name="course_id" class="w-full p-2 border rounded" required>
                            @foreach ($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
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
            fetch(`/admin-int/${type}/${id}/toggle-lock?name=${encodeURIComponent(type)}&id=${id}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function openAddModal(type, courseId, batchId = null) {
            const modal = document.getElementById('addModal');
            const title = document.getElementById('addModalTitle');
            const form = document.getElementById('addForm');
            const addType = document.getElementById('addType');
            const addParentId = document.getElementById('addParentId');
            const addName = document.getElementById('addName');
            const addCourseId = document.getElementById('addCourseId');

            title.textContent = 'Add ' + (type.charAt(0).toUpperCase() + type.slice(1));
            addType.value = type;
            addParentId.value = batchId ?? '';
            addName.value = '';
            if (addCourseId) addCourseId.value = courseId;

            modal.classList.remove('hidden');
            form.action = `/admin-int/${type}/create`;
            form.method = 'GET';
        }

        function closeAddModal() {
            const modal = document.getElementById('addModal');
            modal.classList.add('hidden');
        }

        function openAddTopicAndRecordingModal(folderId, courseId) {
            const modal = document.getElementById('addTopicAndRecordingModal');
            const form = document.getElementById('addTopicAndRecordingForm');
            const folderIdInput = document.getElementById('addTopicAndRecordingFolderId');
            const courseIdInput = document.getElementById('addTopicAndRecordingCourseId');

            folderIdInput.value = folderId;
            courseIdInput.value = courseId;

            modal.classList.remove('hidden');
            form.action = '/admin-int/topic-and-recording/create';
            form.method = 'GET';
        }

        function closeAddTopicAndRecordingModal() {
            const modal = document.getElementById('addTopicAndRecordingModal');
            modal.classList.add('hidden');
        }

        function openEditModal(type, id, name, courseId = null) {
            const modal = document.getElementById('editModal');
            const title = document.getElementById('editModalTitle');
            const form = document.getElementById('editForm');
            const editId = document.getElementById('editId');
            const editType = document.getElementById('editType');
            const editName = document.getElementById('editName');
            const editCourseId = document.getElementById('editCourseId');

            title.textContent = 'Edit ' + (type.charAt(0).toUpperCase() + type.slice(1));
            editId.value = id;
            editType.value = type;
            editName.value = name || '';
            if (editCourseId) {
                editCourseId.value = courseId ? courseId.toString() : '';
                console.log('Setting course_id to:', editCourseId.value);
            }

            modal.classList.remove('hidden');
            form.action = `/admin-int/${type === 'folder' ? 'folder' : 'item'}/${id}?name=&course_id=`;
            form.method = 'GET';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
        }

        document.getElementById('addForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const type = document.getElementById('addType').value;
            const parentId = document.getElementById('addParentId').value;
            const name = document.getElementById('addName').value;
            const courseId = document.getElementById('addCourseId').value;

            const url = `/admin-int/${type}/create?name=${encodeURIComponent(name)}&course_id=${encodeURIComponent(courseId)}&batch_id=${encodeURIComponent(parentId)}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeAddModal();
                    location.reload();
                } else {
                    alert(data.message || 'Error adding item');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('addTopicAndRecordingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const folderId = document.getElementById('addTopicAndRecordingFolderId').value;
            const courseId = document.getElementById('addTopicAndRecordingCourseId').value;
            const topicName = document.getElementById('addTopicName').value;
            const recordingLink = document.getElementById('addRecordingLink').value;

            const url = `/admin-int/topic-and-recording/create?folder_id=${encodeURIComponent(folderId)}&course_id=${encodeURIComponent(courseId)}&topic_name=${encodeURIComponent(topicName)}&recording_link=${encodeURIComponent(recordingLink)}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeAddTopicAndRecordingModal();
                    location.reload();
                } else {
                    alert(data.message || 'Error adding topic and recording');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const type = document.getElementById('editType').value;
            const id = document.getElementById('editId').value;
            const name = document.getElementById('editName').value;
            const courseId = document.getElementById('editCourseId').value;

            const url = `/admin-int/${type === 'folder' ? 'folder' : 'item'}/${id}?name=${encodeURIComponent(name)}&course_id=${encodeURIComponent(courseId)}&type=${encodeURIComponent(type)}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeEditModal();
                    location.reload();
                } else {
                    alert('Error updating item: ' + (data.message || 'Unknown error') + '\nDetails: ' + JSON.stringify(data.errors || {}));
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
    <style>
        .collapsed {
            display: none;
        }
        .expanded {
            display: block;
        }
    </style>
@endsection
