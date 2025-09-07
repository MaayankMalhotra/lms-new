@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">YouTube Reviews</h2>
            <a href="{{ route('admin.youtubereview.create') }}" class="inline-block px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition">
                + Add New Review
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-200 rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($reviews as $review)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="relative">
                        <img src="{{ $review->thumbnail_url }}" alt="{{ $review->title }}" class="w-full h-48 object-cover">
                        <a href="https://www.youtube.com/watch?v={{ $review->video_id }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 hover:bg-opacity-30 transition">
                            <i class="fas fa-play text-white text-3xl"></i>
                        </a>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $review->title }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $review->description }}</p>

                        <div class="mt-4 flex justify-between items-center">
                            <a href="{{ route('admin.youtubereview.edit', $review->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                            <form action="{{ route('admin.youtubereview.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 col-span-3">No YouTube reviews found.</p>
            @endforelse
        </div>
    </div>
</div>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this testimonial?");
    }
</script>
@endsection
