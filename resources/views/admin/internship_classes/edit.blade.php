@extends('admin.layouts.app')

@section('content')
<!-- Tom Select CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="max-w-7xl mx-auto bg-white p-6 mt-8 rounded shadow">
    <form action="{{ route('admin.internship.class.update', $internshipClass->id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <!-- Batch Selection with Tom Select -->
        <div class="mb-4">
            <label for="batch_id" class="block text-sm font-medium text-gray-700 mb-1">Select Batch</label>
            <select name="batch_id" id="batch_id" class="block w-full border-gray-300 rounded p-2" required>
                <option value="" disabled selected>Select a batch</option>
                @foreach ($batches as $batch)
                    <option value="{{ $batch->id }}" 
                        @if ($batch->id == $internshipClass->batch_id) selected @endif>
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

        <!-- Date and Time -->
        <div class="mb-4">
            <label for="class_date_time" class="block text-sm font-medium text-gray-700 mb-1">Class Date & Time (UTC)</label>
            <input type="datetime-local" name="class_date_time" id="class_date_time" class="w-full border border-gray-300 rounded p-2" value="{{ \Carbon\Carbon::parse($internshipClass->class_date_time)->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="mb-4">
            <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Meeting Link (Zoom/Google Meet)</label>
            <input type="url" name="link" id="link" class="w-full border border-gray-300 rounded p-2" value="{{ $internshipClass->link }}" required>
        </div>

        <div class="mb-4">
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Optional Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" class="w-full border border-gray-300 rounded p-2">
            @if($internshipClass->thumbnail)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $internshipClass->thumbnail) }}" alt="Thumbnail" class="w-16 h-16 object-cover rounded">
                </div>
            @endif
        </div>

        <!-- Class Status -->
        <div class="mb-4">
            <label for="status" class="block  text-sm font-medium text-gray-700 mb-1">Class Status</label>
            <select name="status" id="status" class="border block w-full border-gray-300 rounded p-2" required>
                <option value="upcoming" @if($internshipClass->status == 'upcoming') selected @endif>Upcoming</option>
                <option value="ongoing" @if($internshipClass->status == 'ongoing') selected @endif>Ongoing</option>
                <option value="ended" @if($internshipClass->status == 'ended') selected @endif>Ended</option>
                <option value="cancelled" @if($internshipClass->status == 'cancelled') selected @endif>Cancelled</option>
                <option value="rescheduled" @if($internshipClass->status == 'rescheduled') selected @endif>Rescheduled</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Class</button>
    </form>
</div>

<!-- Tom Select JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
    // Tom Select for Batch Selection
    new TomSelect('#batch_id', {
        create: false,
        placeholder: "Search and select a batch...",
        allowEmptyOption: false,
        maxItems: 1
    });

</script>
@endsection
