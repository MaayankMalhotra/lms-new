@extends('website.layouts.app')

@section('title', $event->title)

@section('content')
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('events.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to Events
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Event Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Hero Image -->
            <div class="relative">
                <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                    class="w-full h-80 object-cover">
                <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-black/60 to-transparent"></div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Title -->
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $event->title }}</h1>

                <!-- Metadata -->
                <div class="flex flex-wrap items-center mb-6 space-x-4">
                    <span
                        class="inline-flex items-center bg-yellow-400 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-folder mr-1"></i> {{ $event->category->name ?? 'N/A' }}
                    </span>
                    <span class="text-sm text-gray-600 flex items-center">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ $event->event_date ? $event->event_date->format('M d, Y') : 'N/A' }} at {{ $event->event_time ? $event->event_time->format('h:i A') : 'N/A' }}
                    </span>
                    <span class="text-sm text-gray-600 flex items-center">
                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location ?? 'N/A' }}
                    </span>
                    <span class="text-sm text-gray-600 flex items-center">
                        <i class="fas fa-user mr-1"></i> {{ $event->user->name ?? 'N/A' }}
                    </span>
                </div>

                <!-- Description -->
                <div class="prose prose-lg max-w-none text-gray-700 mb-8">
                    {!! nl2br(e($event->description)) !!}
                </div>

                <!-- Enrollment Button -->
                <div x-data="{ isModalOpen: false }" class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-ticket-alt mr-2"></i> Enroll in This Event
                    </h2>
                    <button @click="isModalOpen = true"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-ticket-alt mr-2"></i> Enroll Now
                    </button>

                    <!-- Modal -->
                    <div x-show="isModalOpen" @keydown.escape="isModalOpen = false" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-90"
                            class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
                            <!-- Modal Header -->
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 rounded-t-xl flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-white flex items-center">
                                    <i class="fas fa-ticket-alt mr-2"></i> Event Enrollment
                                </h2>
                                <button @click="isModalOpen = false"
                                    class="text-white hover:text-gray-200 focus:outline-none">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6">
                                <form action="{{ route('events.enroll', $event->slug) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <!-- Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                            <i class="fas fa-user mr-1"></i> Name
                                        </label>
                                        <input type="text" name="name" id="name"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('name') border-red-500 @enderror"
                                            value="{{ old('name') }}" placeholder="Your full name">
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                            <i class="fas fa-envelope mr-1"></i> Email
                                        </label>
                                        <input type="email" name="email" id="email"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror"
                                            value="{{ old('email') }}" placeholder="Your email address">
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                            <i class="fas fa-phone mr-1"></i> Phone (Optional)
                                        </label>
                                        <input type="text" name="phone" id="phone"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('phone') border-red-500 @enderror"
                                            value="{{ old('phone') }}" placeholder="Your phone number">
                                        @error('phone')
                                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Comments -->
                                    <div>
                                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                            <i class="fas fa-comment mr-1"></i> Comments (Optional)
                                        </label>
                                        <textarea name="comments" id="comments" rows="4"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('comments') border-red-500 @enderror"
                                            placeholder="Any additional comments">{{ old('comments') }}</textarea>
                                        @error('comments')
                                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex justify-end space-x-4">
                                        <button type="button" @click="isModalOpen = false"
                                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200 flex items-center">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                                            <i class="fas fa-ticket-alt mr-1"></i> Enroll Now
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection