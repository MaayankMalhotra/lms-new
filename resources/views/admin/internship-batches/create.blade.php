<!-- resources/views/admin/internship-batches/create.blade.php -->

@extends('admin.layouts.app')

@section('content')
<!-- TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="max-w-7xl mx-auto bg-white p-6 mt-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Create Internship Batch</h1>

    <form action="{{ route('admin.internship-batches.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Internship</label>
                <select id="internship-select" name="internship_id" class="w-full border rounded p-2" required>
                    <option value="">Select Internship</option>
                    @foreach ($internships as $internship)
                        <option value="{{ $internship->id }}" {{ old('internship_id') == $internship->id ? 'selected' : '' }}>
                            {{ $internship->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Batch Name</label>
                <input type="text" name="batch_name" value="{{ old('batch_name') }}" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="w-full border rounded p-2" required>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Class Schedule</label>
                <textarea name="class_schedule" class="w-full border rounded p-2" rows="3" required>{{ old('class_schedule') }}</textarea>
            </div>
        </div>

        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Batch</button>
    </form>
</div>

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect("#internship-select", {
        placeholder: "Search or select an internship",
        maxItems: 1,
        create: false,
        allowEmptyOption: true
    });
</script>
@endsection
