@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-users mr-2"></i> Event Enrollments
            </h1>
            <p class="text-gray-500 mt-2">View and manage enrollments for all events.</p>
        </div>

        <!-- Success/Error Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Enrollments Table Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-list mr-2"></i> Enrollment List
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                            <th class="p-4 text-left"><i class="fas fa-calendar-alt mr-1"></i> Event</th>
                            <th class="p-4 text-left"><i class="fas fa-user mr-1"></i> Name</th>
                            <th class="p-4 text-left"><i class="fas fa-envelope mr-1"></i> Email</th>
                            <th class="p-4 text-left"><i class="fas fa-phone mr-1"></i> Phone</th>
                            <th class="p-4 text-left"><i class="fas fa-comment mr-1"></i> Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-200">
                                <td class="p-4">{{ $enrollment->event->title ?? 'N/A' }}</td>
                                <td class="p-4">{{ $enrollment->name }}</td>
                                <td class="p-4">{{ $enrollment->email }}</td>
                                <td class="p-4">{{ $enrollment->phone ?? 'N/A' }}</td>
                                <td class="p-4">{{ $enrollment->comments ? Str::limit($enrollment->comments, 100) : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">
                                    <i class="fas fa-users text-2xl mb-2"></i>
                                    <p>No enrollments found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>
@endsection