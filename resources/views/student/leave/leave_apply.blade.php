<!-- resources/views/student/leave/leave_apply.blade.php -->
@extends('admin.layouts.app')

@section('content')
    <div class="container py-8 mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-8">
            My Attendance & Leave
        </h1>

        <!-- Attendance Records -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 relative pb-2 mb-6">
                Attendance - {{ \Carbon\Carbon::create()->month($month)->year($year)->format('F Y') }}
                <span class="absolute bottom-0 left-0 w-12 h-1 bg-green-600 rounded-full"></span>
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-gray-600 font-medium">Date</th>
                            <th class="px-6 py-3 text-gray-600 font-medium">Class ID</th>
                            <th class="px-6 py-3 text-gray-600 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendance as $record)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($record->date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ $record->live_class_id }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-sm">Present</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No attendance records</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Leave Application -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-semibold text-gray-800 relative pb-2 mb-6">
                Leave Applications
                <span class="absolute bottom-0 left-0 w-12 h-1 bg-indigo-600 rounded-full"></span>
            </h2>

            <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                <form action="{{ route('leave.apply') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Leave Date</label>
                        <input type="date" name="leave_date" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        @error('leave_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Reason</label>
                        <textarea name="reason" required rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                  placeholder="Enter your reason for leave"></textarea>
                        @error('reason')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        Submit Leave Application
                    </button>
                </form>
                @if(session('success'))
                    <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-gray-600 font-medium">Date</th>
                            <th class="px-6 py-3 text-gray-600 font-medium">Reason</th>
                            <th class="px-6 py-3 text-gray-600 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($leave->leave_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ $leave->reason }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-sm">
                                        @if($leave->status == 'approved')
                                            <span class="bg-green-100 text-green-700">{{ $leave->status }}</span>
                                        @elseif($leave->status == 'rejected')
                                            <span class="bg-red-100 text-red-700">{{ $leave->status }}</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700">{{ $leave->status }}</span>
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No leave applications</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection