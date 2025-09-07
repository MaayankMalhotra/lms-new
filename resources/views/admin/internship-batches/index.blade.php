@extends('admin.layouts.app')

@section('content')
<!-- TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="max-w-7xl mx-auto bg-white p-6 mt-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Manage Internship Batches</h1>

    <!-- Filter Form -->
    <form action="{{ route('admin.internship-batches.index') }}" method="GET" class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Internship</label>
            <select id="internship-select" name="internship_id" class="w-full border rounded p-2">
                <option value="">Select Internship</option>
                @foreach ($internships as $internship)
                    <option value="{{ $internship->id }}" {{ request('internship_id') == $internship->id ? 'selected' : '' }}>
                        {{ $internship->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Batch Name</label>
            <input type="text" name="batch_name" value="{{ request('batch_name') }}" class="w-full border rounded p-2" placeholder="Search by Batch Name">
        </div>

        <div class="flex items-end items-center gap-2 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            <a href="{{ route('admin.internship-batches.index') }}" class="text-blue-600 underline bg-red-500 px-4 py-2 rounded">Reset</a>
        </div>
    </div>
</form>


    <!-- Create Form -->
    <form action="{{ route('admin.internship-batches.store') }}" method="POST">
        @csrf
        <!-- Your Create Form Here (as before) -->
        <!-- Same code as your original create form -->
    </form>

    <!-- Existing Batches List -->
    <h2 class="text-xl font-semibold mt-10 mb-4">Existing Batches</h2>
    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Internship</th>
                <th class="border px-4 py-2">Batch Name</th>
                <th class="border px-4 py-2">Start</th>
                <th class="border px-4 py-2">End</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batches as $batch)
                <tr>
                    <td class="border px-4 py-2">{{ $batch->id }}</td>
                    <td class="border px-4 py-2">{{ $batch->internship ? $batch->internship->name : 'No Internship' }}</td>
                    <td class="border px-4 py-2">{{ $batch->batch_name }}</td>
                    <td class="border px-4 py-2">{{ $batch->start_time }}</td>
                    <td class="border px-4 py-2">{{ $batch->end_time }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="{{ route('admin.internship-batches.edit', $batch->id) }}" class="text-blue-600">Edit</a>
                        <form action="{{ route('admin.internship-batches.destroy', $batch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $batches->links() }}
    </div>
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
