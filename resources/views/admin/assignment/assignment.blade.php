@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Upload Assignment</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.assignments.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label for="live_class_id" class="block text-gray-700 font-medium mb-2">Select Batch</label>
                <select name="live_class_id" id="live_class_id" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200">
                    @foreach ($liveClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->batch_name }} ({{ $class->start_date->format('Y-m-d H:i') }})</option>
                    @endforeach
                </select>
                @error('live_class_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                <input type="text" name="title" id="title" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200" value="{{ old('title') }}">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">Description (Optional)</label>
                <textarea name="description" id="description" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-gray-700 font-medium mb-2">Due Date</label>
                <input type="datetime-local" name="due_date" id="due_date" class="w-full border-gray-500 border-2 rounded-md p-2 focus:ring focus:ring-blue-200" value="{{ old('due_date') }}">
                @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="file" class="block text-gray-700 font-medium mb-2">Upload File (Photo/PDF)</label>
                <input type="file" name="file" id="file" class="w-full border-gray-500 border-2 rounded-md p-2">
                @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">Upload Assignment</button>
        </form>
    </div>
@endsection