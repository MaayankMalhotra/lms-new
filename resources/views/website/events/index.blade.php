@extends('website.layouts.app')

@section('title', 'Events')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">
                All <span class="text-blue-500">Events</span>
            </h1>
            <p class="text-gray-500 mt-2">Discover upcoming events and join the excitement.</p>
        </div>

        <div class="flex flex-wrap -mx-4">
            <!-- Left Column: Search & Recent Events -->
            <div class="w-full lg:w-1/3 px-4 mb-8">
                <!-- Search Box -->
                <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-search mr-2"></i> Find Event
                    </h2>
                    <form action="{{ route('events.index') }}" method="GET">
                        <div class="mb-4">
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <span class="px-3 py-2 bg-gray-100"><i class="fas fa-search text-gray-500"></i></span>
                                <input type="text" name="search" class="w-full px-3 py-2 outline-none"
                                    placeholder="Search events..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <span class="px-3 py-2 bg-gray-100"><i class="fas fa-map-marker-alt text-gray-500"></i></span>
                                <select name="location" class="w-full px-3 py-2 outline-none"
                                    onchange="this.form.submit()">
                                    <option value="">All Locations</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location }}"
                                            {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <span class="px-3 py-2 bg-gray-100"><i class="fas fa-folder text-gray-500"></i></span>
                                <select name="category" class="w-full px-3 py-2 outline-none"
                                    onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}"
                                            {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-search mr-2"></i> Search Now
                        </button>
                    </form>
                </div>

                <!-- Recent Events -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i> Recent Events <span class="text-red-500">•</span>
                    </h2>
                    <div class="space-y-4">
                        @forelse ($recentEvents as $event)
                            <a href="{{ route('events.show', $event->slug) }}"
                                class="block bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                                <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                                    class="w-full h-32 object-cover">
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800">{{ Str::limit($event->title, 30) }}</h3>
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $event->event_date ? $event->event_date->format('M d, Y') : 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location ?? 'N/A' }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <p class="text-gray-500 text-sm">No recent events available.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Event Grid -->
            <div class="w-full lg:w-2/3 px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($events as $event)
                        <a href="{{ route('events.show', $event->slug) }}"
                            class="block bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                                class="w-full h-48 object-cover">
                            <div class="p-4">
                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $event->event_date ? $event->event_date->format('M d, Y') : 'N/A' }} at
                                    {{ $event->event_time ? $event->event_time->format('h:i A') : 'N/A' }}
                                </p>
                                <h3 class="font-semibold text-gray-800 mt-2">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500">{{ Str::limit($event->description, 100) }}</p>
                                <p class="text-sm text-gray-600 flex items-center mt-2">
                                    <i class="fas fa-folder mr-1"></i> {{ $event->category->name ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-600 flex items-center mt-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location ?? 'N/A' }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="col-span-full text-center text-gray-500">No events found. Check back later!</p>
                    @endforelse
                </div>
       
            </div>
        </div>
    </div>
@endsection