@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-plus-circle text-blue-500 mr-2"></i>Add New Webinar
            </h2>
            <p class="text-gray-500 mt-1">Fill in the details to add a new webinar entry.</p>
        </div>

        <form method="POST" action="{{ route('admin.webinar.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Start Time</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Registration Deadline</label>
                    <input type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Entry Type</label>
                    <input type="text" name="entry_type" value="{{ old('entry_type') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Participants Count</label>
                    <input type="number" name="participants_count" value="{{ old('participants_count', 0) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-400 focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Tags</label>
                    <input type="text" name="tags" value="{{ old('tags') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-400 focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-400 focus:outline-none">{{ old('description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Image URL</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-400 focus:outline-none">
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.webinar.index') }}"
                   class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg transition-all">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-all">
                    Save Webinar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection