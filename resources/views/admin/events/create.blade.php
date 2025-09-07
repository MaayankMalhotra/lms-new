@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i> Create Event
                </h1>
                <p class="text-gray-500 mt-2">Add a new event to engage your audience.</p>
            </div>
            <a href="{{ route('admin.event-categories.create') }}"
                class="bg-yellow-400 text-gray-800 px-4 py-2 rounded-lg hover:bg-yellow-500 transition duration-200 flex items-center">
                <i class="fas fa-folder-plus mr-2"></i> Add Category
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Event Details
                </h2>
            </div>
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-heading mr-1"></i> Title
                    </label>
                    <input type="text" name="title" id="title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('title') border-red-500 @enderror"
                        value="{{ old('title') }}" placeholder="Enter event title">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-align-left mr-1"></i> Description
                    </label>
                    <textarea name="description" id="description" rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('description') border-red-500 @enderror"
                        placeholder="Describe the event">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-image mr-1"></i> Image
                    </label>
                    <div
                        class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition duration-200 @error('image') border-red-500 @enderror">
                        <input type="file" name="image" id="image"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600">Drag and drop an image or <span
                                    class="text-blue-500 hover:underline">browse</span></p>
                            <p class="text-xs text-gray-500 mt-1">Supported formats: JPG, JPEG, PNG (Max 2MB)</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-folder mr-1"></i> Category
                    </label>
                    <select name="category_id" id="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('category_id') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-map-marker-alt mr-1"></i> Location
                    </label>
                    <input type="text" name="location" id="location"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('location') border-red-500 @enderror"
                        value="{{ old('location') }}" placeholder="Event location">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Event Date -->
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-calendar-alt mr-1"></i> Event Date
                    </label>
                    <input type="date" name="event_date" id="event_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('event_date') border-red-500 @enderror"
                        value="{{ old('event_date') }}">
                    @error('event_date')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Event Time -->
                <div>
                    <label for="event_time" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-clock mr-1"></i> Event Time
                    </label>
                    <input type="time" name="event_time" id="event_time"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('event_time') border-red-500 @enderror"
                        value="{{ old('event_time') }}">
                    @error('event_time')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.events.index') }}"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200 flex items-center">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                        <i class="fas fa-save mr-1"></i> Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection