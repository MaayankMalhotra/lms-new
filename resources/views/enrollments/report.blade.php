@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Enrollment Report</h1>
        <p class="text-gray-600 mb-6">Total Enrollments: {{ count($enrollments) }}</p>

        <!-- Error Message -->
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow-md">
                {{ session('error') }}
            </div>
        @endif
        <div id="error-message" class="hidden bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow-md"></div>

        @if ($enrollments->isEmpty())
            <p class="text-gray-500 text-lg">No enrollments available for batches that have started.</p>
        @else
            <!-- Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($enrollments as $enrollment)
                    <div class="backdrop-blur-md bg-white/80 border border-gray-200 rounded-xl shadow-lg p-6 hover:shadow-2xl transition duration-300">
                        <!-- Card Header -->
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-user text-blue-500 mr-2"></i>{{ $enrollment->name }}
                            </h2>
                            <span class="text-xs text-gray-500">#{{ $loop->iteration }}</span>
                        </div>

                        <!-- Details -->
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><i class="fas fa-envelope text-indigo-500 mr-2"></i>{{ $enrollment->email }}</p>
                            <p><i class="fas fa-phone text-green-500 mr-2"></i>{{ $enrollment->phone ?? 'N/A' }}</p>
                        </div>

                        <!-- Action -->
                        <div class="mt-5">
                            <button 
                                type="button"
                                class="send-offer-btn w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                                data-user-id="{{ $enrollment->user_id }}"
                                data-email="{{ $enrollment->email }}"
                                data-name="{{ $enrollment->name }}"
                                @if($enrollment->internship) disabled @endif
                            >
                                {{ $enrollment->internship ? 'Offer Sent' : 'Send Offer Letter' }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    document.querySelectorAll('.send-offer-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const email = this.dataset.email;
            const name = this.dataset.name;
            const errorMessageDiv = document.getElementById('error-message');

            if (!userId || !email || !name) {
                errorMessageDiv.textContent = 'Error: Missing user data';
                errorMessageDiv.classList.remove('hidden');
                return;
            }

            fetch('/enrollment-report/send-offer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ user_id: userId, email: email, name: name })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Offer letter sent successfully!');
                    button.textContent = 'Offer Sent';
                    button.disabled = true;
                    button.classList.replace('bg-blue-500', 'bg-gray-400');
                } else {
                    errorMessageDiv.textContent = data.message || 'Failed to send offer letter';
                    errorMessageDiv.classList.remove('hidden');
                }
            })
            .catch(err => {
                errorMessageDiv.textContent = 'Error: Network issue';
                errorMessageDiv.classList.remove('hidden');
            });
        });
    });
</script>
@endsection
