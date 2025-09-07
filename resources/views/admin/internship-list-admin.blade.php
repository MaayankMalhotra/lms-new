<!-- resources/views/admin/internship.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-500 relative">
    <!-- Container -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <!-- Header -->
        <header class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight animate__animated animate__fadeInDown">
                    Manage Internships
                </h1>
            </div>
            <!-- Profile Widget -->
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

        <!-- Internships Section -->
        <section class="flex flex-col lg:flex-row lg:space-x-8">
            <!-- Filter Sidebar -->
            <aside class="lg:w-1/4 mb-8 lg:mb-0">
                <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Filter Internships
                        </h3>
                        <button class="lg:hidden text-gray-600 dark:text-gray-400 focus:outline-none filter-toggle" onclick="this.nextElementSibling.classList.toggle('hidden')">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                    <div class="hidden lg:block mt-4 filter-content">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    Status
                                </label>
                                <select class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option>All</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    Sort By
                                </label>
                                <select class="w-full p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option>Name</option>
                                    <option>Created Date</option>
                                    <option>Submissions</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Internship List -->
            <div class="lg:w-3/4">
                @forelse ($internships as $internship)
                    <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-6 mb-8 border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-500 transition-all duration-300 group transform hover:-translate-y-2 hover:shadow-2xl">
                        <!-- Internship Card -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <!-- Left: Internship Details -->
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $internship->name }}
                                </h3>
                                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:space-x-6">
                                    <!-- Status -->
                                    <div class="flex items-center">
                                        <i class="fas fa-circle-check mr-2 text-indigo-500 dark:text-indigo-400 text-lg"></i>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Status: 
                                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full animate__animated animate__pulse animate__infinite
                                                {{ $internship->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                                {{ ucfirst($internship->status ?? 'inactive') }}
                                            </span>
                                        </span>
                                    </div>
                                    <!-- Created Date -->
                                    <div class="mt-2 sm:mt-0 flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-indigo-500 dark:text-indigo-400 text-lg"></i>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Created: {{ \Carbon\Carbon::parse($internship->created_at)->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Action Button -->
                            <div class="mt-6 md:mt-0 md:ml-8">
                                <a href="{{ route('admin.internship.submissions', $internship) }}"
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-users mr-2"></i>
                                    View Submissions
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-20 backdrop-blur-lg rounded-2xl shadow-xl p-8 text-center border border-gray-100 dark:border-gray-700">
                        <i class="fas fa-folder-open w-12 h-12 mx-auto text-gray-500 dark:text-gray-400 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">
                            No internships available.
                        </p>
                        <a href="{{ route('admin.internship.add') }}"
                           class="mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>
                            Add Internship
                        </a>
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