@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-folder mr-2"></i> News Category Management
            </h1>
            <p class="text-gray-500 mt-2">Manage news categories for better organization.</p>
        </div>
        <a href="{{ route('admin.news-categories.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center shadow-md">
            <i class="fas fa-folder-plus mr-2"></i> Create Category
        </a>
    </div>

    <!-- Success/Error Message -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Categories Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($categories as $category)
            <div class="backdrop-blur-md bg-white/80 rounded-xl shadow-lg p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <!-- Icon + Name -->
                <div class="flex items-center mb-4">
                    <i class="fas fa-folder text-yellow-500 text-2xl mr-3"></i>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h2>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 border-t pt-3">
                    <a href="{{ route('admin.news-categories.edit', $category) }}"
                        class="text-blue-500 hover:text-blue-700 transition duration-200 flex items-center">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.news-categories.destroy', $category) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-500 hover:text-red-700 transition duration-200 flex items-center"
                            onclick="return confirm('Are you sure you want to delete this category?')">
                            <i class="fas fa-trash-alt mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center bg-white/70 p-10 rounded-xl shadow-md">
                <i class="fas fa-folder text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-600 text-lg">No categories found. Start by creating one!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
