@extends('admin.layouts.app')

@section('content')
<div class="mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Enrollment Details (Total: {{ is_iterable($enrollments) ? count($enrollments) : 0 }})
        </h1>
    </div>

    <!-- Error Message -->
    @if (isset($error))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
            {{ $error }}
        </div>
    @endif

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <form action="{{ route('admin.enrollment.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <select name="student_name" class="border p-2 rounded">
                <option value="">Student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->name }}" {{ request('student_name') == $student->name ? 'selected' : '' }}>
                        {{ $student->name }}
                    </option>
                @endforeach
            </select>

            <select name="course_name" class="border p-2 rounded">
                <option value="">Course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->name }}" {{ request('course_name') == $course->name ? 'selected' : '' }}>
                        {{ $course->name }}
                    </option>
                @endforeach
            </select>

            <select name="batch_name" class="border p-2 rounded">
                <option value="">Batch</option>
                @foreach ($batches as $batch)
                    <option value="{{ $batch->batch_name }}" {{ request('batch_name') == $batch->batch_name ? 'selected' : '' }}>
                        {{ $batch->batch_name }}
                    </option>
                @endforeach
            </select>

            <select name="payment_status" class="border p-2 rounded">
                <option value="">Payment Status</option>
                <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            <a href="{{ route('admin.enrollment.index') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Reset</a>
        </form>
    </div>

    <!-- Enrollments Grid -->
    @if (is_iterable($enrollments) && count($enrollments) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($enrollments as $enrollment)
                <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition-all duration-300">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold text-lg text-gray-800">{{ $enrollment->student_name ?? 'N/A' }}</h2>
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $enrollment->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($enrollment->payment_status ?? 'N/A') }}
                        </span>
                    </div>

                    <!-- Details -->
                    <p class="text-sm text-gray-600"><i class="fas fa-envelope mr-1 text-blue-500"></i> {{ $enrollment->student_email }}</p>
                    <p class="text-sm text-gray-600"><i class="fas fa-phone mr-1 text-green-500"></i> {{ $enrollment->phone }}</p>
                    <p class="text-sm text-gray-600"><i class="fas fa-book mr-1 text-purple-500"></i> {{ $enrollment->course_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><i class="fas fa-calendar mr-1 text-pink-500"></i> {{ $enrollment->start_date ? \Carbon\Carbon::parse($enrollment->start_date)->format('d M Y') : 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><i class="fas fa-user-tie mr-1 text-yellow-500"></i> Instructor: {{ $enrollment->instructor_name ?? 'N/A' }}</p>

                    <!-- Amount -->
                    <div class="mt-4">
                        <p class="font-semibold text-gray-800">Amount: ₹{{ number_format($enrollment->amount ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500">Batch Price: ₹{{ number_format($enrollment->batch_price ?? 0, 2) }}</p>
                        @php
                            $nextEmi = $enrollment->next_emi ?? null;
                            $nextEmiDate = isset($nextEmi['due_date']) ? \Carbon\Carbon::parse($nextEmi['due_date'])->format('d M Y') : null;
                            $nextEmiAmount = $nextEmi['amount'] ?? null;
                        @endphp
                        @if($enrollment->is_emi ?? false)
                            <p class="text-sm text-gray-600 mt-2">
                                <span class="font-semibold">Next installment:</span>
                                @if($nextEmiDate)
                                    {{ $nextEmiDate }} @if($nextEmiAmount) (₹{{ number_format($nextEmiAmount, 2) }}) @endif
                                @else
                                    Not scheduled / all paid
                                @endif
                            </p>
                        @endif
                    </div>

                    <!-- EMI Info -->
                    @if (($enrollment->is_emi ?? false) && !empty($enrollment->emi_schedule_array))
                        <button class="mt-3 bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded"
                                onclick="openEMIModal('emiModal{{ $enrollment->enrollment_id }}')">
                            View EMI Details
                        </button>
                    @else
                        <p class="mt-3 text-xs text-gray-500">No EMI</p>
                    @endif

                    <!-- Status -->
                    <div class="mt-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $enrollment->enrollment_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                            {{ ucfirst($enrollment->enrollment_status ?? 'N/A') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- EMI Detail Modals --}}
        @foreach ($enrollments as $enrollment)
            @if (($enrollment->is_emi ?? false) && !empty($enrollment->emi_schedule_array))
                @php
                    $modalId = 'emiModal' . $enrollment->enrollment_id;
                @endphp
                <div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-xl shadow-xl w-[95%] max-w-lg p-6 relative">
                        <button class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl"
                                onclick="closeEMIModal('{{ $modalId }}')">&times;</button>
                        <h3 class="text-xl font-bold mb-3">EMI Schedule - {{ $enrollment->student_name }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ $enrollment->course_name ?? 'Course' }} | Batch: {{ $enrollment->batch_name ?? 'N/A' }}</p>
                        <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                            @foreach ($enrollment->emi_schedule_array as $emi)
                                <div class="border rounded-lg p-3 {{ ($emi['status'] ?? 'pending') === 'paid' ? 'bg-green-50 border-green-200' : 'bg-orange-50 border-orange-200' }}">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-gray-800">Installment {{ $emi['installment_number'] ?? '-' }}</p>
                                            <p class="text-sm text-gray-600">
                                                @if(!empty($emi['due_date']))
                                                    Due: {{ \Carbon\Carbon::parse($emi['due_date'])->format('d M Y') }}
                                                @elseif(!empty($emi['paid_date']))
                                                    Paid on: {{ \Carbon\Carbon::parse($emi['paid_date'])->format('d M Y') }}
                                                @else
                                                    Date: N/A
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-gray-800">₹{{ number_format($emi['amount'] ?? 0, 2) }}</p>
                                            <span class="text-xs px-2 py-1 rounded-full {{ ($emi['status'] ?? 'pending') === 'paid' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ ucfirst($emi['status'] ?? 'pending') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Upcoming installments summary --}}
        @php
            $upcomingEmis = [];
            foreach ($enrollments as $enrollment) {
                if (!empty($enrollment->emi_schedule_array)) {
                    foreach ($enrollment->emi_schedule_array as $emi) {
                        $status = $emi['status'] ?? 'pending';
                        if ($status === 'paid') {
                            continue;
                        }
                        $date = $emi['due_date'] ?? ($emi['paid_date'] ?? null);
                        if (!$date) {
                            continue;
                        }
                        $upcomingEmis[] = [
                            'date' => $date,
                            'student' => $enrollment->student_name ?? 'N/A',
                            'course' => $enrollment->course_name ?? 'N/A',
                            'batch' => $enrollment->batch_name ?? 'N/A',
                            'amount' => $emi['amount'] ?? 0,
                            'status' => $status,
                        ];
                    }
                }
            }
            usort($upcomingEmis, function($a, $b) {
                return \Carbon\Carbon::parse($a['date'])->timestamp <=> \Carbon\Carbon::parse($b['date'])->timestamp;
            });
        @endphp

        @if(count($upcomingEmis))
            <div class="mt-10 bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Upcoming Installments</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600 border-b">
                                <th class="py-2 pr-4">Date</th>
                                <th class="py-2 pr-4">Student</th>
                                <th class="py-2 pr-4">Course</th>
                                <th class="py-2 pr-4">Batch</th>
                                <th class="py-2 pr-4">Amount</th>
                                <th class="py-2 pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingEmis as $emi)
                                <tr class="border-b last:border-0">
                                    <td class="py-2 pr-4">{{ \Carbon\Carbon::parse($emi['date'])->format('d M Y') }}</td>
                                    <td class="py-2 pr-4">{{ $emi['student'] }}</td>
                                    <td class="py-2 pr-4">{{ $emi['course'] }}</td>
                                    <td class="py-2 pr-4">{{ $emi['batch'] }}</td>
                                    <td class="py-2 pr-4">₹{{ number_format($emi['amount'], 2) }}</td>
                                    <td class="py-2 pr-4">
                                        <span class="px-2 py-1 rounded-full text-xs {{ $emi['status'] === 'pending' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($emi['status']) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @else
        <div class="text-center text-gray-500 mt-12">
            <i class="fas fa-inbox text-3xl mb-2"></i>
            <p>No enrollments found.</p>
        </div>
    @endif
</div>
<script>
function openEMIModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.remove('hidden');
}
function closeEMIModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('hidden');
}
</script>
@endsection
