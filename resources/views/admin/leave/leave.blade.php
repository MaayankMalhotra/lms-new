<!-- resources/views/admin/leaves.blade.php -->
@extends('admin.layouts.app')

@section('content')
    <div class="container py-8 mx-auto">
        <div class="bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-semibold text-gray-800 relative pb-2 mb-6">
                Manage Leave Applications
                <span class="absolute bottom-0 left-0 w-12 h-1 bg-indigo-600 rounded-full"></span>
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-gray-600 font-medium">Student Name</th> <!-- Changed to Name -->
                            <th class="px-6 py-3 text-gray-600 font-medium">Date</th>
                            <th class="px-6 py-3 text-gray-600 font-medium">Reason</th>
                            <th class="px-6 py-3 text-gray-600 font-medium">Status</th>
                            <th class="px-6 py-3 text-gray-600 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $leave->name }}</td> <!-- Use student_name -->
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($leave->leave_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ $leave->reason }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-sm">
                                      
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($leave->status === 'pending')
                                        <form action="{{ route('leave.approve', $leave->id) }}" method="POST" class="flex space-x-2">
                                            @csrf
                                            <input type="text" name="leave_id" value="{{ $leave->id }}" hidden>
                                            <button type="submit" name="status" value="approved" 
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg transition duration-200">
                                                Approve
                                            </button>
                                            <button type="submit" name="status" value="rejected" 
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg transition duration-200">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">Processed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No leave applications</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $leaves->links() }}
            </div>

            @if(session('success'))
                <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
@endsection