@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Add New Live Class</h1>

        <form action="{{ route('admin.live_classes.store') }}" method="POST" class="bg-white p-6 rounded shadow">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Batch</label>
                <select name="batch_id" id="batch_id" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Choose a batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->course->name }} - {{ $batch->start_date->format('Y-m-d') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Folder</label>
                <select name="folder_id" id="folder_id" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Choose a folder</option>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Recordings (Optional)</label>
                <select name="recording_id[]" id="recording_id" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" multiple>
                    <option value="">Choose recordings</option>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Google Meet Link</label>
                <input type="url" name="google_meet_link" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Class Date & Time</label>
                <input type="datetime-local" name="class_datetime" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Duration (minutes)</label>
                <input type="number" name="duration_minutes" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Save Live Class</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
        $(document).ready(function() {
            $('#recording_id').select2({
                placeholder: "Choose recordings",
                allowClear: true,
                width: '100%'
            });

            document.getElementById('batch_id').addEventListener('change', function() {
                const batchId = this.value;
                const folderSelect = document.getElementById('folder_id');
                const recordingSelect = $('#recording_id');
                folderSelect.innerHTML = '<option value="">Loading...</option>';
                recordingSelect.val(null).trigger('change'); // Clear select2

                if (batchId) {
                    fetch(`/live-classes/folders/${batchId}`)
                        .then(response => response.json())
                        .then(data => {
                            folderSelect.innerHTML = '<option value="">Choose a folder</option>';
                            if (data.length > 0) {
                                data.forEach(folder => {
                                    folderSelect.innerHTML += `<option value="${folder.id}">${folder.name}</option>`;
                                });
                            }
                            recordingSelect.val(null).trigger('change'); // Clear recordings
                        })
                        .catch(error => {
                            folderSelect.innerHTML = '<option value="">Error loading folders</option>';
                            recordingSelect.val(null).trigger('change');
                            console.error('Error fetching folders:', error);
                        });
                } else {
                    folderSelect.innerHTML = '<option value="">Choose a folder</option>';
                    recordingSelect.val(null).trigger('change');
                }
            });

            document.getElementById('folder_id').addEventListener('change', function() {
                const folderId = this.value;
                const recordingSelect = $('#recording_id');
                recordingSelect.val(null).trigger('change'); // Clear select2
                recordingSelect.html('<option value="">Loading...</option>').trigger('change');

                if (folderId) {
                    fetch(`/live-classes/recordings/${folderId}`)
                        .then(response => response.json())
                        .then(data => {
                            recordingSelect.html('<option value="">Choose recordings</option>').trigger('change');
                            if (data.length > 0) {
                                data.forEach(topic => {
                                    const optgroup = document.createElement('optgroup');
                                    optgroup.label = topic.name;
                                    topic.recordings.forEach(recording => {
                                        const option = document.createElement('option');
                                        option.value = recording.id;
                                        option.textContent = recording.name;
                                        optgroup.appendChild(option);
                                    });
                                    if (optgroup.children.length > 0) {
                                        recordingSelect.append(optgroup).trigger('change');
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            recordingSelect.html('<option value="">Error loading recordings</option>').trigger('change');
                            console.error('Error fetching recordings:', error);
                        });
                } else {
                    recordingSelect.html('<option value="">Choose recordings</option>').trigger('change');
                }
            });
        });
    </script>

    <style>
        .collapsed {
            display: none;
        }
        .expanded {
            display: block;
        }
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' class='w-4 h-4 absolute right-2 top-1/2 transform -translate-y-1/2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            padding-right: 2rem;
        }
        select:focus {
            border-color: #3b82f6;
        }
        optgroup {
            font-weight: bold;
            background-color: #f9fafb;
            padding: 0.25rem 0;
        }
        option {
            padding: 0.25rem 0.5rem;
            background-color: white;
        }
        option:hover {
            background-color: #e5e7eb;
        }
        select[multiple] {
            height: auto;
            min-height: 100px;
        }
        select[multiple] option:checked {
            background-color: #3b82f6;
            color: white;
        }
        .select2-container .select2-selection--multiple {
            min-height: 100px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            margin: 0.125rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-left: 0.5rem;
            cursor: pointer;
        }
    </style>
@endsection