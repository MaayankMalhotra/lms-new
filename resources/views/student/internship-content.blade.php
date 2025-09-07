<!-- resources/views/student/internship-content.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-500 relative">
    <!-- Container -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <!-- Header -->
     
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-8 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-2xl shadow-lg flex items-center animate__animated animate__fadeIn">
                <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Content List -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-8">
                Content & Tasks
            </h2>

            @forelse ($contents as $content)
                <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-6 mb-8 border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-500 transition-all duration-300 group transform hover:-translate-y-2 hover:shadow-2xl">
                    <!-- Accordion Header -->
                    <button class="w-full flex items-center justify-between text-left focus:outline-none accordion-button"
                            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180')">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $content->title }}
                            </h3>
                            <p class="mt-1 text-gray-600 dark:text-gray-400 text-sm">
                                {{ Str::limit($content->description ?? 'No description available', 50) }}
                            </p>
                        </div>
                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Accordion Content -->
                    <div class="hidden mt-4">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                            <!-- Left: Content Details -->
                            <div class="flex-1">
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $content->description ?? 'No description available' }}
                                </p>
                                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:space-x-6">
                                    <!-- Deadline -->
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Deadline: 
                                            @if ($content->deadline)
                                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                                    {{ now()->greaterThan($content->deadline) ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : (now()->diffInDays($content->deadline) <= 3 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                                    {{ \Carbon\Carbon::parse($content->deadline)->format('M d, Y') }}
                                                    @if (now()->lessThan($content->deadline))
                                                        ({{ now()->diffInDays($content->deadline) }} days left)
                                                    @endif
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <!-- File Download -->
                                    <div class="mt-4 sm:mt-0">
                                        @if ($content->file_path)
                                            <a href="{{ Storage::url($content->file_path) }}"
                                               download="{{ $content->title . '.pdf' }}"
                                               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Download Material
                                            </a>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">No material available</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Submission -->
                            <div class="mt-6 md:mt-0 md:ml-8 w-full md:w-80">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                                    Your Submission
                                </h4>
                                @if ($content->submission_file)
                                    <a href="{{ Storage::url($content->submission_file) }}"
                                       download="submission_{{ $content->id }}.pdf"
                                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold rounded-xl shadow-lg hover:from-green-600 hover:to-teal-600 transform hover:scale-105 transition-all duration-300 w-full justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download Submission
                                    </a>
                                @else
                                    <form action="{{ route('student.internship.submit', $content->id) }}"
                                          method="POST"
                                          enctype="multipart/form-data"
                                          class="space-y-4 drop-zone">
                                        @csrf
                                        <div class="relative border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl p-4 text-center hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                                            <input type="file"
                                                   name="submission_file"
                                                   accept=".pdf"
                                                   required
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                            <svg class="w-8 h-8 mx-auto text-indigo-500 dark:text-indigo-400 mb-2 animate__animated animate__pulse animate__infinite" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 0116 8c1.8 0 3.3 1.2 3.8 2.9A4 4 0 0116 20H7z"></path>
                                            </svg>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                Drag & drop your PDF or click to upload
                                            </p>
                                        </div>
                                        <button type="submit"
                                                class="w-full px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                                            Submit Task
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-8 text-center border border-gray-100 dark:border-gray-700">
                    <svg class="w-12 h-12 mx-auto text-gray-500 dark:text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-300 text-lg">
                        No tasks or materials available for this internship.
                    </p>
                    <a href="{{ route('student.internships.index') }}"
                       class="mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                        Back to Dashboard
                    </a>
                </div>
            @endforelse
        </section>

        <!-- Back Button -->
        <div class="mt-10">
            <a href="{{ route('student.internships.index') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-800 dark:from-gray-700 dark:to-gray-900 text-white font-semibold rounded-xl shadow-lg hover:from-gray-700 hover:to-gray-900 transform hover:scale-105 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
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
</style>
@endsection