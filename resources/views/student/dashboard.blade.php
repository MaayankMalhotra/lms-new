@extends('admin.layouts.app')

@section('content')
<div class="px-3">

    <!-- ===== SNAPSHOT CARDS ===== -->
    <section class="py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Progress -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Progress</h3>
                @php $progress = $progressPercent ?? null; @endphp
                <p class="text-sm text-gray-500 mb-2">{{ $progress ? $progress.'% Completed' : 'No progress data yet' }}</p>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ $progress ? $progress : 0 }}%"></div>
                </div>
            </div>

            <!-- Live Class Schedule -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Live Class Schedule</h3>
                @if(!empty($nextLiveClass))
                    <p class="text-gray-700 font-semibold">{{ $nextLiveClass['title'] ?? 'Live Class' }}</p>
                    <p class="text-gray-500 text-sm">
                        {{ $nextLiveClass['date'] ?? '-' }} {{ $nextLiveClass['time'] ?? '' }}
                    </p>
                @else
                    <p class="text-gray-500">No upcoming live class</p>
                @endif
            </div>

            <!-- Fees -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Fee Status</h3>
                <p class="text-gray-600">✅ Paid: ₹{{ $payments->total_completed ?? 0 }}</p>
                <p class="text-gray-600">❌ Pending: ₹{{ $payments->total_pending ?? 0 }}</p>
                @if(!empty($nextInstallment))
                    <div class="mt-3 p-3 rounded-lg bg-orange-50 border border-orange-200">
                        <p class="text-sm text-gray-700 font-semibold">Next installment:</p>
                        <p class="text-sm text-gray-700">{{ $nextInstallment['due'] }} (₹{{ number_format($nextInstallment['amount'], 2) }})</p>
                        <p class="text-xs text-gray-500">{{ $nextInstallment['course'] }} | {{ $nextInstallment['batch'] }}</p>
                        <button id="pay-next-installment"
                                data-payment="{{ $nextInstallment['payment_id'] }}"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-semibold rounded shadow hover:from-blue-600 hover:to-indigo-700 transition">
                            Pay Next Installment
                        </button>
                        <p id="pay-next-message" class="text-xs mt-2 text-gray-600"></p>
                    </div>
                @endif
            </div>

            <!-- Assignments & Quizzes -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Assignments & Quizzes</h3>
                <p class="text-gray-700">Assignments: <span class="font-bold text-yellow-600">{{ $totalAssignments }}</span></p>
                <p class="text-gray-700">Quizzes: <span class="font-bold text-red-600">{{ $quizSets->count() }}</span></p>
            </div>
        </div>
    </section>

    <!-- ===== UPCOMING & ACTIONS ===== -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Coding Exam -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-2">Upcoming Coding Exam</h3>
            @if(!empty($nextCodingExam))
                <p class="text-gray-800 font-semibold">{{ $nextCodingExam['title'] ?? 'Coding Test' }}</p>
                <p class="text-gray-500 text-sm">{{ $nextCodingExam['date'] ?? '' }} {{ $nextCodingExam['time'] ?? '' }}</p>
            @else
                <p class="text-gray-500">No coding exam scheduled</p>
            @endif
        </div>

        <!-- Mock Interview -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-2">Mock Interview</h3>
            @if(!empty($nextMockInterview))
                <p class="text-gray-800 font-semibold">{{ $nextMockInterview['title'] ?? 'Mock Interview' }}</p>
                <p class="text-gray-500 text-sm">{{ $nextMockInterview['date'] ?? '' }} {{ $nextMockInterview['time'] ?? '' }}</p>
            @else
                <p class="text-gray-500">No mock interview scheduled</p>
            @endif
        </div>

        <!-- Placement Drives -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-2">Placement Drives & Achievements</h3>
            @php $placementUpdates = $placementUpdates ?? []; @endphp
            @forelse($placementUpdates as $update)
                <div class="mb-3">
                    <p class="text-gray-800 font-semibold">{{ $update['title'] ?? 'Placement Update' }}</p>
                    <p class="text-gray-500 text-sm">{{ $update['description'] ?? '' }}</p>
                </div>
            @empty
                <p class="text-gray-500">No placement updates yet</p>
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

    <!-- ===== CALENDAR / UPCOMING EVENTS ===== -->
    <section class="bg-white p-6 rounded-lg shadow-md mt-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-700">Upcoming Events</h3>
            <span class="text-sm text-gray-500">Calendar view coming soon</span>
        </div>
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

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('pay-next-installment');
    if (!payBtn) return;

    const messageEl = document.getElementById('pay-next-message');

    payBtn.addEventListener('click', async () => {
        messageEl.textContent = 'Processing payment...';
        try {
            const res = await fetch('{{ route('student.emi.pay_next') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Payment failed');
            }
            messageEl.textContent = 'Payment recorded. Refreshing...';
            setTimeout(() => window.location.reload(), 800);
        } catch (e) {
            messageEl.textContent = e.message || 'Something went wrong. Please try again.';
            messageEl.classList.add('text-red-600');
        }
    });
});
</script>
@endpush
@endsection
