<!-- resources/views/admin/internship-submissions.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-500 relative">
    <!-- Container -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <!-- Header -->
        <header class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl md:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight animate__animated animate__fadeInDown">
                    Submissions for {{ $internship->name }}
                </h1>
            </div>

        </header>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-8 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-2xl shadow-lg flex items-center animate__animated animate__fadeIn">
                <i class="fas fa-check-circle mr-3 text-green-600 dark:text-green-400 text-xl"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-8 p-4 bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-2xl shadow-lg flex items-center animate__animated animate__fadeIn">
                <i class="fas fa-exclamation-circle mr-3 text-red-600 dark:text-red-400 text-xl"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Submissions Section -->
        <section class="flex flex-col lg:flex-row lg:space-x-8">
            <!-- Filter Sidebar -->
            <aside class="lg:w-1/4 mb-8 lg:mb-0">
                <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Filter Submissions
                        </h3>
                        <button class="lg:hidden text-gray-600 dark:text-gray-400 focus:outline-none filter-toggle" onclick="this.nextElementSibling.classList.toggle('hidden')">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                    <div class="hidden lg:block mt-4 filter-content">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    Student
                                </label>
                                <select class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option>All</option>
                                    @foreach ($submissions->pluck('enrollment.user.name')->unique() as $student)
                                        <option>{{ $student }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    Sort By
                                </label>
                                <select class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option>Submission Date</option>
                                    <option>Mark</option>
                                    <option>Task Title</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Submission List -->
            <div class="lg:w-3/4">
                @forelse ($submissions as $submission)
                    <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-6 mb-8 border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-500 transition-all duration-300 group transform hover:-translate-y-2 hover:shadow-2xl">
                        <!-- Submission Card -->
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                            <!-- Left: Submission Details -->
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    Task: {{ $submission->content->title }}
                                </h3>
                                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:space-x-6">
                                    <!-- Student -->
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2 text-indigo-500 dark:text-indigo-400 text-lg"></i>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Student: {{ $submission->enrollment->user->name }}
                                        </span>
                                    </div>
                                    <!-- Submission Date -->
                                    <div class="mt-2 sm:mt-0 flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-indigo-500 dark:text-indigo-400 text-lg"></i>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Submitted: {{ \Carbon\Carbon::parse($submission->created_at)->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center">
                                    <i class="fas fa-file-download mr-2 text-indigo-500 dark:text-indigo-400 text-lg"></i>
                                    <span class="text-gray-600 dark:text-gray-300">
                                        File: 
                                        <a href="{{ asset('storage/' . $submission->submission_file) }}"
                                           target="_blank"
                                           class="text-indigo-500 dark:text-indigo-400 hover:underline">
                                            Download Submission
                                        </a>
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center">
                                    <i class="fas fa-star mr-2 text-indigo-500 dark:text-indigo-400 text-lg"></i>
                                    <span class="text-gray-600 dark:text-gray-300">
                                        Current Mark: 
                                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                            {{ $submission->mark ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                            {{ $submission->mark ? number_format($submission->mark, 2) : 'Not Assigned' }}
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <!-- Right: Mark Assignment -->
                            <div class="mt-6 md:mt-0 md:ml-8 w-full md:w-64">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                                    Assign Mark
                                </h4>
                                <form action="{{ route('admin.internship.submission.feedback', $submission) }}"
                                      method="POST"
                                      class="space-y-4">
                                    @csrf
                                    <div class="flex items-center">
                                        <input type="number"
                                               name="mark"
                                               id="mark-{{ $submission->id }}"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               value="{{ old('mark', $submission->mark) }}"
                                               class="border-gray-200 dark:border-gray-600 rounded-xl p-2 w-24 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition"
                                               required>
                                        <button type="submit"
                                                class="ml-4 px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Submit Mark
                                        </button>
                                    </div>
                                    @error('mark')
                                        <p class="text-red-500 dark:text-red-400 text-sm mt-2 animate__animated animate__shakeX">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-8 text-center border border-gray-100 dark:border-gray-700">
                        <i class="fas fa-folder-open w-12 h-12 mx-auto text-gray-500 dark:text-gray-400 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">
                            No submissions found for this internship.
                        </p>
                       
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
</style>

<script>
    // Filter Sidebar Toggle
    document.querySelectorAll('.filter-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            content.classList.toggle('hidden');
        });
    });
</script>
@endsection