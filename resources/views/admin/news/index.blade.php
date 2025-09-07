@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">News Management</h1>
            <p class="text-gray-500 mt-2">Manage your news articles efficiently.</p>
        </div>
        <a href="{{ route('admin.news.create') }}"
           class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center shadow">
            <i class="fas fa-plus-circle mr-2"></i> Create News
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- News Cards -->
    @if($news->isEmpty())
        <div class="text-center text-gray-500 py-12">
            <i class="fas fa-newspaper text-4xl mb-4"></i>
            <p class="text-lg">No news articles found. Start by creating one!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($news as $item)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden">
                    <!-- Image -->
                    <div class="h-48 w-full bg-gray-200">
                        @if ($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" 
                                 class="h-48 w-full object-cover">
                        @else
                            <div class="h-48 w-full flex items-center justify-center bg-gray-200">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ Str::limit($item->title, 60) }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-3">
                            <i class="fas fa-folder mr-1 text-blue-500"></i>
                            {{ $item->category->name ?? 'Uncategorized' }}
                        </p>
                        <p class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-calendar-alt mr-1 text-indigo-500"></i>
                            {{ $item->published_at->format('M d, Y') }}
                        </p>

                        <!-- Actions -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.news.edit', $item) }}"
                               class="text-blue-500 hover:text-blue-700 flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this news article?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 flex items-center">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
