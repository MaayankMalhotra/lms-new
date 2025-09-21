@extends('admin.layouts.app')

@section('content')
<div class="px-3">

    <!-- ===== STATS CARDS ===== -->
    <section class="py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

            <!-- Enrolled Batches -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Enrolled Batches</h3>
                @forelse($studentBatches as $batch)
                    <p class="text-gray-600">📘 {{ $batch->batch_name }}</p>
                @empty
                    <p class="text-gray-500">No batches found</p>
                @endforelse
            </div>

            <!-- Total Assignments -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Assignments</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $totalAssignments }}</p>
            </div>

            <!-- Quizzes -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Quizzes</h3>
                <p class="text-2xl font-bold text-red-600">{{ $quizSets->count() }}</p>
            </div>

            <!-- Fees -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Fees</h3>
                <p class="text-gray-600">✅ Paid: ₹{{ $payments->total_completed ?? 0 }}</p>
                <p class="text-gray-600">❌ Pending: ₹{{ $payments->total_pending ?? 0 }}</p>
            </div>
        </div>
    </section>

    <!-- ===== EVENTS ===== -->
    <section class="bg-white p-6 rounded-lg shadow-md mt-5">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Upcoming Events</h3>
        <div class="space-y-4">
            @forelse($events as $event)
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-semibold text-gray-800">{{ $event->title }}</h4>
                    <p class="text-sm text-gray-600">{{ $event->description }}</p>
                    <p class="text-sm text-gray-600">📍 {{ $event->location }}</p>
                    <p class="text-sm text-gray-600">📅 {{ $event->event_date }} | ⏰ {{ $event->event_time }}</p>
                </div>
            @empty
                <p class="text-gray-500">No events found</p>
            @endforelse
        </div>
    </section>

    <!-- ===== QUIZ LIST ===== -->
    <section class="bg-white p-6 rounded-lg shadow-md mt-5">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Available Quizzes</h3>
        <div class="space-y-4">
            @forelse($quizSets as $quiz)
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-semibold text-gray-800">{{ $quiz->title ?? 'Untitled Quiz' }}</h4>
                    <p class="text-sm text-gray-600">Batch ID: {{ $quiz->batch_id }}</p>
                </div>
            @empty
                <p class="text-gray-500">No quizzes found</p>
            @endforelse
        </div>
    </section>

</div>
@endsection
