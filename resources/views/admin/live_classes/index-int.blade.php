@extends('admin.layouts.app')

@section('content')
    @php
        use App\Models\Recording; // Declare the Recording model
    @endphp

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Manage Internship Classes</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.live_classes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4 inline-block">Add New Class</a>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($batches as $batch)
                @foreach($batch->liveClasses as $class)
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition-shadow duration-300 relative group">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $batch->internship->name }}</h3>
                        <p class="text-gray-600 mb-2">Batch: {{ $batch->start_date->format('Y-m-d') }}</p>
                        <p class="text-gray-600 mb-2">Topic: {{ $class->topic }}</p>
                        <p class="text-gray-600 mb-2">Class Time: {{ Carbon\Carbon::parse($class->class_datetime)->format('Y-m-d H:i') }}</p>
                        <p class="text-gray-600 mb-4">Status: <span class="px-2 py-1 rounded {{ $class->status == 'Scheduled' ? 'bg-yellow-200 text-yellow-800' : ($class->status == 'Live' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">{{ $class->status }}</span></p>
                        <div class="flex justify-between items-center">
                            <button onclick="openModal('edit-modal-{{ $class->id }}')" class="text-blue-500 hover:underline">Edit</button>
                            <form action="{{ route('admin.live_classes.destroy', $class->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <a href="{{ route('teacher.assignments.view', $class->id) }}" class="text-green-500 hover:underline">View Assignment</a>
                        </div>
                        <button onclick="openModal('folder-recording-modal-{{ $class->id }}')" class="mt-4 w-full bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition duration-200">View Folders & Recordings</button>
                    </div>

                    <!-- Edit Modal -->
                    <div id="edit-modal-{{ $class->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg max-w-lg w-full">
                            <h3 class="text-lg font-bold mb-4">Edit Live Class</h3>
                            <form action="{{ route('admin.live_classes.update', $class->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-gray-700">Batch</label>
                                    <select name="batch_id" id="batch_id_{{ $class->id }}" class="w-full p-2 border rounded" required>
                                        @foreach($batches as $b)
                                            <option value="{{ $b->id }}" {{ $b->id == $class->batch_id ? 'selected' : '' }}>{{ $b->internship->name }} - {{ $b->start_date->format('Y-m-d') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Select Recording Topic (Optional)</label>
                                    <select name="recording_id" id="recording_id_{{ $class->id }}" class="w-full p-2 border rounded">
                                        <option value="">No recording topic</option>
                                        @php
                                            $recordingIds = explode(',', $class->recording_id);
                                            $firstRecordingId = !empty($recordingIds) ? $recordingIds[0] : null;
                                            $recording = $firstRecordingId ? Recording::find($firstRecordingId) : null;
                                        @endphp
                                        @if($recording)
                                            <option value="{{ $recording->id }}" selected>{{ $recording->topic->name }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Topic</label>
                                    <input type="text" name="topic" id="topic_{{ $class->id }}" value="{{ $class->topic }}" class="w-full p-2 border rounded" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Google Meet Link</label>
                                    <input type="url" name="google_meet_link" value="{{ $class->google_meet_link }}" class="w-full p-2 border rounded" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Class Date & Time</label>
                                    <input type="datetime-local" name="class_datetime" value="{{ Carbon\Carbon::parse($class->class_datetime)->format('Y-m-d\TH:i') }}" class="w-full p-2 border rounded" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Duration (minutes)</label>
                                    <input type="number" name="duration_minutes" value="{{ $class->duration_minutes }}" class="w-full p-2 border rounded" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Status</label>
                                    <select name="status" class="w-full p-2 border rounded" required>
                                        <option value="Scheduled" {{ $class->status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="Live" {{ $class->status == 'Live' ? 'selected' : '' }}>Live</option>
                                        <option value="Ended" {{ $class->status == 'Ended' ? 'selected' : '' }}>Ended</option>
                                    </select>
                                </div>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
                                <button type="button" onclick="closeModal('edit-modal-{{ $class->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancel</button>
                            </form>
                        </div>
                    </div>

                    <!-- Folder & Recording Modal -->
                    <div id="folder-recording-modal-{{ $class->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg max-w-2xl w-full overflow-y-auto max-h-[80vh]">
                            <h3 class="text-lg font-bold mb-4">Related Folders & Recordings for {{ $class->topic }}</h3>
                            <button type="button" onclick="closeModal('folder-recording-modal-{{ $class->id }}')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                            
                            @if($class->folder_id)
                                <div class="ml-4">
                                    <p class="text-gray-600 mb-2"><strong>Folder:</strong> {{ $class->folder->name ?? 'N/A' }}</p>
                                    @foreach($class->folder->topics as $topic)
                                        <div class="ml-4 mt-2">
                                            <p class="text-gray-600 mb-1"><strong>Topic:</strong> {{ $topic->name }}</p>
                                            @foreach($topic->recordings as $recording)
                                                <a href="{{ $recording->video_url }}" target="_blank" class="inline-block mt-1 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition duration-200">Recording {{ $recording->id }}</a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600">No folder associated.</p>
                            @endif
                            <button type="button" onclick="closeModal('folder-recording-modal-{{ $class->id }}')" class="mt-4 w-full bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition duration-200">Close</button>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <script>
        @foreach($batches as $batch)
            @foreach($batch->liveClasses as $class)
                document.getElementById('batch_id_{{ $class->id }}').addEventListener('change', function() {
                    const batchId = this.value;
                    const recordingSelect = document.getElementById('recording_id_{{ $class->id }}');
                    recordingSelect.innerHTML = '<option value="">Loading...</option>';

                    if (batchId) {
                        fetch("{{ route('admin.live_classes.recordings', '') }}/" + batchId)
                            .then(response => response.json())
                            .then(data => {
                                recordingSelect.innerHTML = '<option value="">No recording topic</option>';
                                data.forEach(recording => {
                                    const isSelected = {{ $class->recording_id ? explode(',', $class->recording_id)[0] : 'null' }} === recording.id;
                                    recordingSelect.innerHTML += `<option value="${recording.id}" ${isSelected ? 'selected' : ''}>${recording.topic}</option>`;
                                });
                            })
                            .catch(error => {
                                recordingSelect.innerHTML = '<option value="">Error loading topics</option>';
                                console.error(error);
                            });
                    } else {
                        recordingSelect.innerHTML = '<option value="">No recording topic</option>';
                    }
                });

                document.getElementById('recording_id_{{ $class->id }}').addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const topicInput = document.getElementById('topic_{{ $class->id }}');
                    if (selectedOption.value) {
                        topicInput.value = selectedOption.text; // Update topic field with selected recording topic
                    } else {
                        topicInput.value = 'Untitled Live Class'; // Reset if no selection
                    }
                });

                // Trigger initial load for current batch
                document.getElementById('batch_id_{{ $class->id }}').dispatchEvent(new Event('change'));
            @endforeach
        @endforeach

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>

    <style>
        .collapsed {
            display: none;
        }
        .expanded {
            display: block;
        }
        .group:hover {
            transform: translateY(-2px); /* Slight lift on hover for better UX */
        }
        a.text-blue-500:hover {
            text-decoration: underline;
        }
        #folder-recording-modal-{{ $class->id }} .max-h-\[80vh\] {
            max-height: 80vh;
        }
        #folder-recording-modal-{{ $class->id }} .overflow-y-auto {
            overflow-y: auto;
        }
        a.bg-blue-500 {
            margin-top: 0.25rem;
            display: inline-block;
        }
        #folder-recording-modal-{{ $class->id }} .text-2xl {
            font-size: 1.5rem; /* Ensure close button size is consistent */
        }
    </style>
@endsection