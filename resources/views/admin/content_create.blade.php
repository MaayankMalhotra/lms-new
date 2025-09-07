@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Add Internship Content</h1>
    <form action="{{ route('admin.internship.content.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium">Internship</label>
            <select name="internship_id" class="w-full p-2 border rounded" required>
                <option value="">Select Internship</option>
                @foreach ($internships as $internship)
                    <option value="{{ $internship->id }}">{{ $internship->name }}</option>
                @endforeach
            </select>
            @error('internship_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Title</label>
            <input type="text" name="title" class="w-full p-2 border rounded" required>
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="w-full p-2 border rounded"></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">File (PDF)</label>
            <input type="file" name="file" class="w-full p-2 border rounded" accept=".pdf">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Deadline</label>
            <input type="date" name="deadline" class="w-full p-2 border rounded">
        </div>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Add Content</button>
    </form>
</div>
@endsection