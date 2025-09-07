@extends('admin.layouts.app')

@section('content')
<!-- Tom Select CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="max-w-7xl mx-auto bg-white p-6 mt-8 rounded shadow">
    <form action="{{ route('admin.internship-classes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Batch Selection -->
        <div class="mb-4">
            <label for="batch_id" class="block text-sm font-medium text-gray-700 mb-1">Select Batch</label>
            <select name="batch_id" id="batch_id" class="block w-full border-gray-300 rounded p-2" required>
                <option value="" disabled selected>Select a batch</option>
                @foreach ($batches as $batch)
                <option value="{{ $batch->id }}">
                    {{ $batch->batch_name }} | 
                    {{ \Carbon\Carbon::parse($batch->start_time)->format('d M Y h:i A') }} - 
                    {{ \Carbon\Carbon::parse($batch->end_time)->format('d M Y h:i A') }}
                    @if($batch->class_schedule)
                        | {{ $batch->class_schedule }}
                    @endif
                </option>
                
                
                @endforeach
            </select>
        </div>
<!-- Recording Course Selection -->
<div class="mb-4">
    <label for="recording_course_id" class="block text-sm font-medium text-gray-700 mb-1">Select Recording Course</label>
    <select name="recording_course_id" id="recording_course_id" class="block w-full border-gray-300 rounded p-2">
        <option value="" selected>Select a course</option>
        @foreach ($recordingCourses as $course)
        <option value="{{ $course->id }}">{{ $course->course_name }}</option>
        @endforeach
    </select>
</div>

<!-- Recording Selection -->
<div class="mb-4">
    <label for="recording_id" class="block text-sm font-medium text-gray-700 mb-1">Select Recording</label>
    <select name="recording_id" id="recording_id" class="block w-full border-gray-300 rounded p-2">
        <option value="" selected>Select a recording</option>
    </select>
</div>
        <!-- Date and Time -->
        <div class="mb-4">
            <label for="class_date_time" class="block text-sm font-medium text-gray-700 mb-1">Class Date & Time (UTC)</label>
            <input type="datetime-local" name="class_date_time" id="class_date_time" class="w-full border border-gray-300 rounded p-2" required>
        </div>

        <!-- Meeting Link -->
        <div class="mb-4">
            <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Meeting Link (Zoom/Google Meet)</label>
            <input type="url" name="link" id="link" class="w-full border border-gray-300 rounded p-2" required>
        </div>

        <!-- Thumbnail Upload -->
        <div class="mb-4">
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Optional Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" class="w-full border border-gray-300 rounded p-2">
        </div>
         <!-- Thumbnail Upload -->
         <div class="mb-4">
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Enter Subject (example. Html)</label>
            <input type="text" name="subject" id="subject" class="w-full border border-gray-300 rounded p-2">
        </div>
         <!-- Thumbnail Upload -->

        <!-- Submit -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create Class
        </button>
    </form>
</div>

<!-- Tom Select JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
    // Initialize TomSelect for batch and recording course
    new TomSelect('#batch_id', {
        create: false,
        placeholder: "Search and select a batch...",
        allowEmptyOption: false
    });

    new TomSelect('#recording_course_id', {
        create: false,
        placeholder: "Search and select a course...",
        allowEmptyOption: true
    });

    new TomSelect('#recording_id', {
        create: false,
        placeholder: "Search and select a recording...",
        allowEmptyOption: true
    });


</script>
<script>
    const baseRoute = "{{ url('admin/internship-recordings-by-course') }}";

    document.getElementById('recording_course_id').addEventListener('change', function() {
        const courseId = this.value;
        console.log(courseId);
        const recordingSelect = document.getElementById('recording_id');
        recordingSelect.tomselect.clear();
        recordingSelect.tomselect.clearOptions();

        if (courseId) {
            fetch(`${baseRoute}/${courseId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(recording => {
                        recordingSelect.tomselect.addOption({
                            value: recording.id,
                            text: `${recording.topic}: ${recording.title}`
                        });
                    });
                });
        }
    });
</script>

@endsection
