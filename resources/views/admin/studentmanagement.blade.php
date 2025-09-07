@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen px-6 py-8"
     style="background: url('https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg') no-repeat center center fixed; background-size: cover;">
    
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 drop-shadow-md">
                    <i class="fas fa-users mr-2 text-blue-500"></i>Student List
                </h1>
                <p class="text-gray-700 mt-1">Manage all students in the system</p>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Student Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($students as $index => $student)
                <div class="backdrop-blur-md bg-white/80 rounded-xl shadow-lg p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $student['name'] }}
                        </h2>
                        <span class="text-xs text-gray-500">#{{ $index + 1 }}</span>
                    </div>

                    <!-- Details -->
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-envelope mr-2 text-blue-400"></i> {{ $student['email'] }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-phone mr-2 text-blue-400"></i> {{ $student['phone'] ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        <i class="fas fa-calendar-alt mr-2 text-blue-400"></i>
                        Registered: {{ date('d M Y', strtotime($student['created_at'])) }}
                    </p>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-3 border-t">
                        <button class="text-blue-500 hover:text-blue-600 edit-student"
                                data-id="{{ $student['id'] }}"
                                data-url="{{ route('admin.student.edit', $student['id']) }}"
                                title="Edit">
                            <i class="fas fa-edit text-lg"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-600 delete-student"
                                data-id="{{ $student['id'] }}"
                                data-url="{{ route('admin.student.delete', $student['id']) }}"
                                title="Delete">
                            <i class="fas fa-trash text-lg"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-700 bg-white/70 backdrop-blur-sm p-10 rounded-xl shadow-md">
                    <i class="fas fa-inbox text-4xl mb-2 text-gray-400"></i>
                    <p>No students found.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Edit Student</h3>
                <button class="text-gray-500 hover:text-gray-700 modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editStudentForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="student_id">
                <div class="space-y-6">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" id="name"
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <span class="error text-red-500 text-sm" id="name_error"></span>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email"
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <span class="error text-red-500 text-sm" id="email_error"></span>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone (Optional)</label>
                        <input type="text" name="phone" id="phone"
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <span class="error text-red-500 text-sm" id="phone_error"></span>
                    </div>
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="button" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg modal-close">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow p-6 w-96">
        <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
        <p>Are you sure you want to delete this Student?</p>
        <div class="flex justify-end mt-6">
            <button class="px-4 py-2 bg-gray-300 rounded mr-2 modal-close">Cancel</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded">Delete</button>
        </div>
    </div>
</div>

<!-- Scripts (same as before, no change needed) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Same AJAX Edit + Delete logic from your existing code
    // Just works with cards instead of table
</script>
@endsection
