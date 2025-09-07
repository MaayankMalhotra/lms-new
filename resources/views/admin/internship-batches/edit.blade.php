@extends('admin.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="max-w-7xl mx-auto bg-white p-6 mt-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Edit Batch</h1>

    <form action="{{ route('admin.internship-batches.update', $batch->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label class="block text-sm font-medium text-gray-700 mb-1">Select Internship</label>
        <select id="internship_id" name="internship_id" class="w-full border rounded p-2" required>
            <option value="" disabled selected>Select Internship</option>
            @foreach ($internships as $internship)
            <option value="{{ $internship->id }}" {{ old('internship_id', $batch->internship_id ?? '') == $internship->id ? 'selected' : '' }}>
                {{ $internship->name }}
            </option>
            @endforeach
        </select>


        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Batch Name</label>
            <input type="text" name="batch_name" value="{{ old('batch_name', $batch->batch_name) }}"
                class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
            <input type="datetime-local" name="start_time"
                value="{{ old('start_time', \Carbon\Carbon::parse($batch->start_time)->format('Y-m-d\TH:i')) }}"
                class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
            <input type="datetime-local" name="end_time"
                value="{{ old('end_time', \Carbon\Carbon::parse($batch->end_time)->format('Y-m-d\TH:i')) }}"
                class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Class Schedule</label>
            <textarea name="class_schedule" rows="4" class="w-full border rounded p-2"
                required>{{ old('class_schedule', $batch->class_schedule) }}</textarea>
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Batch</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect('#internship_id',{
        placeholder: 'Select Internship',
        maxItems: 1,
        create: false,
        allowEmptyOption: true,
    });
</script>

@endsection