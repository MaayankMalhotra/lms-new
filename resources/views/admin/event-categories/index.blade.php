@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-folder mr-2"></i> Event Category Management
            </h1>
            <p class="text-gray-500 mt-2">Manage event categories for better organization.</p>
        </div>
        <a href="{{ route('admin.event-categories.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Create Category
        </a>
    </div>

    <!-- Success/Error Message -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Categories as Cards -->
    @if ($categories->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($categories as $category)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all p-6 relative group">
                    <!-- Category Icon -->
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-folder text-blue-500 mr-2"></i>{{ $category->name }}
                        </h2>
                        <span class="text-xs text-gray-400">#{{ $category->id }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('admin.event-categories.edit', $category) }}"
                           class="bg-blue-100 text-blue-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 flex items-center">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>

                        <form action="{{ route('admin.event-categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-100 text-red-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-200 flex items-center">
                                <i class="fas fa-trash-alt mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $categories->links() }}
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-folder text-4xl mb-3 text-gray-400"></i>
            <p class="text-lg">No categories found. Start by creating one!</p>
        </div>
    @endif
</div>
@endsection
