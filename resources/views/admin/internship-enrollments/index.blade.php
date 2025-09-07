@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen px-6 py-8" 
     style="background: url('https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg') no-repeat center center fixed; background-size: cover;">

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 drop-shadow-md">
                    <i class="fas fa-user-graduate mr-2 text-blue-500"></i>Internship Enrollments
                </h1>
                <p class="text-gray-700 mt-1">Manage all internship enrollments</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="backdrop-blur-md bg-white/80 rounded-xl shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('admin.internship-enrollment-view') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Internship</label>
                        <select name="internship_id" 
                                class="w-full border border-gray-300 rounded-md p-2">
                            <option value="">All Internships</option>
                            @foreach ($internships as $internship)
                                <option value="{{ $internship->id }}" 
                                    {{ request('internship_id') == $internship->id ? 'selected' : '' }}>
                                    {{ $internship->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" 
                                class="w-full border border-gray-300 rounded-md p-2">
                            <option value="">All</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex items-end gap-4">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.internship-enrollment-view') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Enrollment Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($enrollments as $enrollment)
                <div class="backdrop-blur-md bg-white/80 rounded-xl shadow-lg p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $enrollment->name }}
                        </h2>
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                            {{ $enrollment->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </div>

                    <!-- Details -->
                    <p class="text-sm text-gray-600 mb-1">
                        📧 {{ $enrollment->email }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        📱 {{ $enrollment->phone }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        🎓 Internship: <span class="font-medium">{{ $enrollment->internship->name ?? '-' }}</span>
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        💰 Amount: <span class="font-semibold">₹{{ number_format($enrollment->amount, 2) }}</span>
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        🔑 Payment ID: <span class="font-mono">{{ $enrollment->payment_id }}</span>
                    </p>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2 mt-3">
                        <!-- Toggle Status -->
                        <form method="POST" 
                              action="{{ route('admin.internship-enrollments.toggleStatus', $enrollment->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                class="text-xs px-3 py-1 rounded text-white 
                                    {{ $enrollment->status == 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                {{ $enrollment->status == 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <!-- Edit Button -->
                        <button onclick="openEditModal(this)"
                            data-id="{{ $enrollment->id }}"
                            data-edit-url="{{ route('admin.internship-enrollments.edit', $enrollment->id) }}"
                            data-update-url="{{ route('admin.internship-enrollments.update', $enrollment->id) }}"
                            class="text-xs bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-700 bg-white/70 backdrop-blur-sm p-10 rounded-xl shadow-md">
                    <i class="fas fa-inbox text-4xl mb-2 text-gray-400"></i>
                    <p>No enrollments found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $enrollments->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed z-50 inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
        <button onclick="closeEditModal()" class="absolute top-2 right-3 text-gray-500 text-xl font-bold">&times;</button>
        <form method="POST" id="editEnrollmentForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="enrollment_id" id="editEnrollmentId">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="editName" 
                       class="w-full border border-gray-300 p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" id="editPhone" 
                       class="w-full border border-gray-300 p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Amount</label>
                <input type="number" name="amount" id="editAmount" 
                       class="w-full border border-gray-300 p-2 rounded" step="0.01">
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(button) {
        const id = button.getAttribute('data-id');
        const editUrl = button.getAttribute('data-edit-url');
        const updateUrl = button.getAttribute('data-update-url');

        fetch(editUrl)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editEnrollmentForm').action = updateUrl;
                document.getElementById('editEnrollmentId').value = id;
                document.getElementById('editName').value = data.name;
                document.getElementById('editPhone').value = data.phone;
                document.getElementById('editAmount').value = data.amount;
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
            });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
</script>

@endsection
