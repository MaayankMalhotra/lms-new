@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 ">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-newspaper mr-2"></i> Edit News
                </h1>
                <p class="text-gray-500 mt-2">Update the news article details.</p>
            </div>
            <a href="{{ route('admin.news-categories.create') }}"
                class="bg-yellow-400 text-gray-800 px-4 py-2 rounded-lg hover:bg-yellow-500 transition duration-200 flex items-center">
                <i class="fas fa-folder-plus mr-2"></i> Add Category
            </a>
        </div>

        <!-- Warning/Success Message -->
        @if (session('warning'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('warning') }}
            </div>
        @elseif (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-[#2c1d56] p-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> News Details
                </h2>
            </div>
            <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-heading mr-1"></i> Title
                    </label>
                    <input type="text" name="title" id="title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('title') border-red-500 @enderror"
                        value="{{ old('title', $news->title) }}" placeholder="Enter news title">
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
                        placeholder="Write the news content here">{{ old('description', $news->description) }}</textarea>
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
                    @if ($news->image_url)
                        <img src="{{ $news->image_url }}" alt="{{ $news->title }}"
                            class="w-32 h-32 object-cover mt-2 rounded-lg">
                    @endif
                    @error('image')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Category Dropdown -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-folder mr-1"></i> Category
                    </label>
                    <select name="category_id" id="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('category_id') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $news->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Published At -->
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-calendar-alt mr-1"></i> Published At
                    </label>
                    <input type="date" name="published_at" id="published_at"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('published_at') border-red-500 @enderror"
                        value="{{ old('published_at', $news->published_at->format('Y-m-d')) }}">
                    @error('published_at')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.news.index') }}"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200 flex items-center">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center {{ $categories->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $categories->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-save mr-1"></i> Update News
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection