@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="min-h-screen px-6 py-8"
     style="background: url('https://img.freepik.com/free-vector/abstract-digital-wave-background_23-2148398028.jpg') no-repeat center center fixed; background-size: cover;">
    
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 drop-shadow-md">
                    <i class="fas fa-chalkboard-teacher mr-2 text-blue-500"></i>Trainer List
                </h1>
                <p class="text-gray-700 mt-1">Manage all trainers in the system</p>
            </div>
            <div class="flex items-center space-x-4">
                <form id="deleteAllTrainersForm" action="{{ route('admin.trainers.deleteAll') }}" method="POST" onsubmit="return confirm('Delete all trainers? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg transition-all flex items-center shadow-lg">
                        <i class="fas fa-trash mr-2"></i>Delete All
                    </button>
                </form>
                <button onclick="openModal('createTrainerModal')"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-all flex items-center shadow-lg">
                    <i class="fas fa-plus-circle mr-2"></i>Add New Trainer
                </button>
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

        <!-- Trainer Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($trainers as $index => $trainer)
                <div class="backdrop-blur-md bg-white/80 rounded-xl shadow-lg p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $trainer->name }}
                        </h2>
                        <span class="text-xs text-gray-500">#{{ $index + 1 }}</span>
                    </div>

                    <!-- Details -->
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-envelope mr-2 text-blue-400"></i>{{ $trainer->email }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-phone mr-2 text-blue-400"></i>{{ $trainer->phone ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-briefcase mr-2 text-blue-400"></i>Experience: {{ $trainer->trainerDetail->experience ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-clock mr-2 text-blue-400"></i>Teaching Hours: {{ $trainer->trainerDetail->teaching_hours ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-book mr-2 text-blue-400"></i>Courses: {{ $trainer->course_names ?? 'None' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-users mr-2 text-blue-400"></i>Batches: {{ $trainer->batch_names ?? 'None' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        <i class="fas fa-calendar-alt mr-2 text-blue-400"></i>
                        Registered: {{ $trainer->created_at ? date('d M Y', strtotime($trainer->created_at)) : 'N/A' }}
                    </p>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-3 border-t">
                        @if($trainer->trainerDetail)
                            <button onclick="openEditModal('{{ $trainer->trainerDetail->id }}')" 
                                    class="text-blue-500 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit text-lg"></i>
                            </button>
                        @endif
                        @php
                            $deleteId = $trainer->trainerDetail->id ?? $trainer->id;
                            $deleteType = $trainer->trainerDetail ? 'detail' : 'user';
                        @endphp
                        <button onclick="openDeleteModal({{ $deleteId }}, '{{ $deleteType }}')" 
                                class="text-red-500 hover:text-red-600" title="Delete">
                            <i class="fas fa-trash text-lg"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-700 bg-white/70 backdrop-blur-sm p-10 rounded-xl shadow-md">
                    <i class="fas fa-inbox text-4xl mb-2 text-gray-400"></i>
                    <p>No trainers found. Start by adding one!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Create Trainer Modal -->
<div id="createTrainerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Create New Trainer</h3>
                <button onclick="closeModal('createTrainerModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.trainers.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 rounded-lg border border-gray-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3 rounded-lg border border-gray-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" class="w-full px-4 py-3 rounded-lg border border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="Leave blank to auto-generate">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('createTrainerModal')" class="px-6 py-2 bg-gray-200 rounded-lg">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Trainer Modal -->
<div id="editTrainerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Edit Trainer</h3>
                <button onclick="closeModal('editTrainerModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editTrainerForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editTrainerId">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trainer</label>
                        <input type="text" id="edit_user_display" class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 cursor-not-allowed" disabled>
                        <p class="text-xs text-gray-400 mt-1">Trainer is fixed based on the original selection.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Experience</label>
                        <input type="text" name="experience" id="edit_experience" class="w-full px-4 py-3 rounded-lg border border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teaching Hours</label>
                        <input type="number" name="teaching_hours" id="edit_teaching_hours" class="w-full px-4 py-3 rounded-lg border border-gray-200">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('editTrainerModal')" class="px-6 py-2 bg-gray-200 rounded-lg">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow p-6 w-96">
        <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
        <p>Are you sure you want to delete this trainer?</p>
        <div class="flex justify-end mt-6">
            <button onclick="closeModal('deleteModal')" class="px-4 py-2 bg-gray-300 rounded mr-2">Cancel</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded">Delete</button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMetaTag ? csrfMetaTag.getAttribute('content') : '';
    const editTrainerForm = document.getElementById('editTrainerForm');

    function openEditModal(id) {
        if (editTrainerForm) {
            editTrainerForm.setAttribute('action', `/admin/trainers/${id}`);
        }
        // Fetch trainer details via AJAX (example only)
        fetch(`/admin/trainers/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editTrainerId').value = data.id;
                document.getElementById('edit_user_id').value = data.user_id;
                document.getElementById('edit_experience').value = data.experience;
                document.getElementById('edit_teaching_hours').value = data.teaching_hours;
                const label = data.user_label ?? `Trainer #${data.user_id}`;
                document.getElementById('edit_user_display').value = label;
                openModal('editTrainerModal');
            });
    }
    let deleteId = null;
    let deleteType = 'detail';
    function openDeleteModal(id, type = 'detail') {
        deleteId = id;
        deleteType = type;
        openModal('deleteModal');
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deleteId) {
            const typeParam = deleteType ? `?type=${encodeURIComponent(deleteType)}` : '';
            fetch(`/admin/trainers/${deleteId}/delete${typeParam}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(res => location.reload());
        }
    });
    
    // Create form uses a select; no JS needed besides normal submit

    if (editTrainerForm) {
        editTrainerForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const actionUrl = editTrainerForm.getAttribute('action');
            const formData = new FormData(editTrainerForm);

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(async response => {
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        const message = errorData.errors ? Object.values(errorData.errors).flat().join('\n') : 'Failed to update trainer.';
                        throw new Error(message);
                    }
                    return response.json();
                })
                .then(() => {
                    closeModal('editTrainerModal');
                    window.location.reload();
                })
                .catch(error => {
                    alert(error.message || 'Failed to update trainer.');
                });
        });
    }
</script>
@endsection
