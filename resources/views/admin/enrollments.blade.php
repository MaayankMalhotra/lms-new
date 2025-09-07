@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <!-- Edit Enrollment Modal -->
    <div id="editEnrollmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold">Edit Enrollment</h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="editEnrollmentForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Student Name (Read-only) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-400"></i>Student Name
                            </label>
                            <input type="text" id="edit_student_name" readonly
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 text-gray-600">
                        </div>

                        <!-- Course Name (Read-only) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-book-open mr-2 text-blue-400"></i>Course Name
                            </label>
                            <input type="text" id="edit_course_name" readonly
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 text-gray-600">
                        </div>

                        <!-- Batch Name (Read-only) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-book mr-2 text-blue-400"></i>Batch Name
                            </label>
                            <input type="text" id="edit_batch_name" readonly
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 text-gray-600">
                        </div>

                        <!-- Teacher Name (Read-only) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-chalkboard-teacher mr-2 text-blue-400"></i>Teacher Name
                            </label>
                            <input type="text" id="edit_teacher_name" readonly
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 text-gray-600">
                        </div>

                        <!-- Start Date (Read-only) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-blue-400"></i>Start Date
                            </label>
                            <input type="text" id="edit_start_date" readonly
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 text-gray-600">
                        </div>

                        <!-- Amount (Read-only) -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-money-bill-wave mr-2 text-blue-400"></i>Amount
                            </label>
                            <input type="text" id="edit_amount" readonly
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 text-gray-600">
                        </div>

                        <!-- Enrollment Status -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-check-circle mr-2 text-blue-400"></i>Status
                            </label>
                            <select name="status" id="edit_status" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-6 rounded-xl transition-all">
                                Update Enrollment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-users mr-2 text-blue-500"></i>Enrollment Management
                </h1>
                <p class="text-gray-500 mt-2">Manage all student enrollments in the system</p>
            </div>
            {{-- <a href="{{ route('admin.enrollment.add') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-all">
                <i class="fas fa-plus-circle mr-2"></i>Add New Enrollment
            </a> --}}
        </div>

        <!-- Enrollments Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Student Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Course Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Batch Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Teacher Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Start Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Amount</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($enrollments as $enrollment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $enrollment->user->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $enrollment->batch->course->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $enrollment->batch->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $enrollment->batch->teacher->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                @if($enrollment->batch && $enrollment->batch->start_date)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 text-blue-700">
                                        <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                                        {{ \Carbon\Carbon::parse($enrollment->batch->start_date)->format('F d, Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                @if($enrollment->payment)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md bg-green-50 text-green-700">
                                        <i class="fas fa-money-bill-wave mr-1 text-green-500"></i>
                                        {{ number_format($enrollment->payment->amount, 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $enrollment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($enrollment->status == 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($enrollment->status == 'active' ? 'bg-blue-100 text-blue-800' : 
                                   ($enrollment->status == 'inactive' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-4">
                                <!-- Approve Button -->
                                @if($enrollment->status === 'pending')
                                    <form action="{{ route('admin.enrollment.approve', $enrollment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-500 hover:text-green-600"
                                                onclick="return confirm('Are you sure you want to approve this enrollment?')">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Edit Button -->
                                <button onclick="openEditModal(`{{ route('admin.enrollment.edit', $enrollment->id) }}`)"
                                        class="text-blue-500 hover:text-blue-600">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.enrollment.destroy', $enrollment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600"
                                            onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($enrollments->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p class="text-lg">No enrollments found. Start by adding a new enrollment!</p>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($enrollments->hasPages())
        <div class="mt-8">
            {{ $enrollments->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function openEditModal(editUrl) {
    fetch(editUrl, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Populate form fields
        document.getElementById('edit_student_name').value = data.student_name;
        document.getElementById('edit_course_name').value = data.course_name;
        document.getElementById('edit_batch_name').value = data.batch_name;
        document.getElementById('edit_teacher_name').value = data.teacher_name;
        document.getElementById('edit_start_date').value = data.start_date;
        document.getElementById('edit_amount').value = data.amount;
        document.getElementById('edit_status').value = data.status;

        // Set form action
        document.getElementById('editEnrollmentForm').action = 
            "{{ route('admin.enrollment.update', '') }}/" + data.id;

        // Show modal
        document.getElementById('editEnrollmentModal').classList.remove('hidden');
    });
}

function closeModal() {
    document.getElementById('editEnrollmentModal').classList.add('hidden');
}
</script>
@endsection