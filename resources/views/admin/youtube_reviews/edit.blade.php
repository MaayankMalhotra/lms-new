@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-yellow-500 mr-2"></i>Edit YouTube Review
            </h2>
            <p class="text-gray-500 mt-1">Update the review details below.</p>
        </div>

        <form method="POST" action="{{ route('admin.youtubereview.update', $review->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title', $review->title) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-yellow-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">YouTube Video ID</label>
                    <input type="text" name="video_id" value="{{ old('video_id', $review->video_id) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-yellow-400 focus:outline-none" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-yellow-400 focus:outline-none" required>{{ old('description', $review->description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Thumbnail URL</label>
                    <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url', $review->thumbnail_url) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-yellow-400 focus:outline-none" required>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.youtubereview.index') }}"
                   class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg transition-all">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg transition-all">
                    Update Review
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
