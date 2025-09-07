@extends('admin.layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow-md">
    <form method="POST" action="{{ route('assign.students.to.batch') }}">
        @csrf

      <!-- Batch Selection (Single Select with Search) -->
      <div class="mb-4">
        <label for="batch_id" class="block text-sm font-medium text-gray-700">Select Batch</label>
        <select name="batch_id" id="batch_id" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
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

        <div class="mb-4">
            <label for="student_ids" class="block text-sm font-medium text-gray-700">Select Students</label>
            <select name="student_ids[]" id="student_ids" multiple class="mt-1 block w-full border border-gray-300 rounded p-2">
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->student_name }} ({{ $student->email }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Assign to Batch
        </button>
    </form>
</div>
<!-- Tom Select -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect('#student_ids', {
        plugins: ['remove_button'],
        persist: false,
        create: false,
        maxOptions: 500,
        placeholder: "Search and select students...",
    });
    new TomSelect('#batch_id', {
        create: false,
        placeholder: "Search and select a batch...",
        allowEmptyOption: false
    });
</script>
@endsection


