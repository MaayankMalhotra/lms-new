@extends('website.layouts.app')
@section('title', 'News')
@section('content')
<!-- Page Header -->
<div class="container mx-auto px-4 pt-24">
    <h1 class="text-3xl font-bold">All <span class="text-blue-500">News</span></h1>
</div>

<div class="container mx-auto px-4 py-4">
    <div class="flex flex-wrap -mx-4">
        <!-- Left Column: News Cards Grid -->
        <div class="w-full lg:w-2/3 px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="newsGrid">
                @forelse ($news as $item)
                    <div class="col">
                        <a href="{{ route('news.show', $item->slug) }}" class="block no-underline text-inherit">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-48 object-cover" />
                                <div class="p-4">
                                    <span class="inline-block bg-yellow-400 text-gray-800 px-2 py-1 rounded text-sm mb-2">{{ $item->category->name ?? 'Uncategorized' }}</span>
                                    <h6 class="font-semibold">{{ $item->title }}</h6>
                                    <p class="text-sm text-gray-500">{{ Str::limit($item->description, 100) }}</p>
                                    <div class="flex justify-between items-center mt-3">
                                        <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> {{ $item->published_at->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-600"><i class="fas fa-user text-gray-500"></i> {{ $item->user->name ?? 'Unknown' }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500">
                        <p>No news found. Check back later!</p>
                    </div>
                @endforelse
            </div>
            {{ $news->links() }}
        </div>

        <!-- Right Column: Sidebar (Search, Categories, Recent News) -->
        <div class="w-full lg:w-1/3 px-4">
            <!-- Search Box -->
            <div class="mb-6">
                <form action="{{ route('news.index') }}" method="GET">
                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                        <input type="text" name="search" class="w-full px-4 py-2 outline-none" placeholder="Search here..." value="{{ request('search') }}" />
                        <button class="bg-yellow-400 px-4 py-2 hover:bg-yellow-500 transition duration-300">
                            <i class="fas fa-search text-gray-800"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Categories -->
            <div class="mb-6">
                <h6 class="text-lg font-semibold mb-3">Categories</h6>
                <form action="{{ route('news.index') }}" method="GET">
                    <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Recent News -->
            <div class="mb-6">
                <h6 class="text-lg font-semibold mb-3">Recent Posts <span class="text-red-500">•</span></h6>
                <div class="space-y-4">
                    @forelse ($recentNews as $item)
                        <div class="flex items-center bg-white rounded-lg shadow-md overflow-hidden">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-24 h-24 object-cover" />
                            <div class="p-4">
                                <h6 class="font-semibold">{{ $item->title }}</h6>
                                <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> {{ $item->published_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No recent news available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection