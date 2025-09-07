@extends('website.layouts.app')

@section('title', $news->title)

@section('content')
    <div class="container mx-auto px-4 py-12 lg:px-8">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('news.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to News
            </a>
        </div>

        <!-- News Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Hero Image -->
            <div class="relative">
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}"
                    class="w-full h-80 object-cover">
                <div
                    class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-black/60 to-transparent">
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Title -->
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $news->title }}</h1>

                <!-- Metadata -->
                <div class="flex flex-wrap items-center mb-6 space-x-4">
                    <span
                        class="inline-flex items-center bg-yellow-400 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-folder mr-1"></i> {{ $news->category->name    ?? 'Uncategorized' }}
                    </span>
                    <span class="text-sm text-gray-600 flex items-center">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ $news->published_at->format('M d, Y') }}
                    </span>
                    <span class="text-sm text-gray-600 flex items-center">
                        <i class="fas fa-user mr-1"></i> {{ $news->user->name }}
                    </span>
                </div>

                <!-- Description -->
                <div class="prose prose-lg max-w-none text-gray-700 mb-8">
                    {!! nl2br(e($news->description)) !!}
                </div>

                <!-- Social Sharing -->
                <div class="flex items-center space-x-4">
                    <p class="text-sm font-medium text-gray-600">Share this article:</p>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($news->title) }}"
                        target="_blank"
                        class="text-blue-500 hover:text-blue-700 transition duration-200">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="text-blue-600 hover:text-blue-800 transition duration-200">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($news->title) }}"
                        target="_blank"
                        class="text-blue-700 hover:text-blue-900 transition duration-200">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection