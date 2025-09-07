<!-- resources/views/student/internshipdash.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-500 relative">
    <!-- Container -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">
                    Track your internship progress and dive into your tasks.
                </h1>
        
            </div>
            

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-8 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-2xl shadow-lg flex items-center animate__animated animate__fadeIn">
                <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Internships Section -->
        <section class="flex flex-col lg:flex-row lg:space-x-8">
            <!-- Filter Sidebar -->
            <aside class="lg:w-1/4 mb-8 lg:mb-0">
                <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Filter Internships
                        </h3>
                        <button class="lg:hidden text-gray-600 dark:text-gray-400 focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="hidden lg:block mt-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    Status
                                </label>
                                <select class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option>All</option>
                                    <option>Active</option>
                                    <option>Completed</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    Sort By
                                </label>
                                <select class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option>Progress</option>
                                    <option>Name</option>
                                    <option>Marks</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Internship List -->
            <div class="lg:w-3/4">
                @forelse ($enrollments as $enrollment)
                    <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-6 mb-8 border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-500 transition-all duration-300 group transform hover:-translate-y-2 hover:shadow-2xl">
                        <!-- Internship Card -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <!-- Left: Internship Details -->
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $enrollment->internship->name }}
                                </h3>
                                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:space-x-6">
                                    <!-- Status -->
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Status: 
                                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full animate__animated animate__pulse animate__infinite
                                                {{ $enrollment->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </span>
                                    </div>
                                    <!-- Marks -->
                                    <div class="mt-2 sm:mt-0 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Marks: {{ $enrollment->average_mark }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Circular Progress -->
                                <div class="mt-6">
                                    <p class="text-gray-600 dark:text-gray-300 font-semibold mb-3">
                                        Progress: {{ number_format($enrollment->progress, 2) }}%
                                    </p>
                                    <div class="relative w-24 h-24">
                                        <svg class="w-full h-full" viewBox="0 0 36 36">
                                            <path d="M18 2.0845
                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                a 15.9155 15.9155 0 0 1 0 -31.831"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                class="text-gray-200 dark:text-gray-700" />
                                            <path d="M18 2.0845
                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                a 15.9155 15.9155 0 0 1 0 -31.831"
                                                fill="none"
                                                stroke="url(#progressGradient)"
                                                stroke-width="2"
                                                stroke-dasharray="{{ $enrollment->progress }}, 100"
                                                class="animate-progress" />
                                            <defs>
                                                <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                    <stop offset="0%" style="stop-color:#4F46E5" />
                                                    <stop offset="100%" style="stop-color:#EC4899" />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center text-gray-900 dark:text-gray-100 font-bold text-lg">
                                            {{ number_format($enrollment->progress, 0) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Action Button -->
                            <div class="mt-6 md:mt-0 md:ml-8">
                                <a href="{{ route('student.internship.content', $enrollment) }}"
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View Tasks
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-8 text-center border border-gray-100 dark:border-gray-700">
                        <svg class="w-12 h-12 mx-auto text-gray-500 dark:text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">
                            No internships enrolled. Start your journey today!
                        </p>
                        {{-- <a href="{{ route('internships.index') }}"
                           class="mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                            Explore Internships
                        </a> --}}
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<style>
    /* Custom Animation for Pulse */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    .animate-pulse {
        animation: pulse 2s infinite;
    }

    /* Progress Animation */
    @keyframes progress {
        from { stroke-dasharray: 0, 100; }
        to { stroke-dasharray: {{ $enrollment->progress ?? 0 }}, 100; }
    }
    .animate-progress {
        animation: progress 1s ease-out forwards;
    }
</style>
@endsection