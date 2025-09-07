@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-8">
            <div class="mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-envelope text-blue-500 mr-2"></i>Contact Us Inquiries
                </h2>
                <p class="text-gray-500 mt-1">View and manage user-submitted inquiries.</p>
            </div>

            {{-- <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.contactus.index') }}"
                class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">User Type</label>
                    <select name="user_type" class="w-full mt-1 px-4 py-2 border rounded">
                        <option value="">All</option>
                        <option value="student" {{ request('user_type') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="employee" {{ request('user_type') === 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full mt-1 px-4 py-2 border rounded">
                        <option value="">All</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Unresolved</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full mt-1 px-4 py-2 border rounded">
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                        Search
                    </button>
                    <!-- Clear Button -->
                    <a href="{{ route('admin.contactus.index') }}"
                        class="bg-red-800 hover:bg-red-400 text-white px-6 py-2 rounded inline-block text-center">
                        Clear
                    </a>
                </div>

            </form> --}}

            <!-- Filter Form -->
<form method="GET" action="{{ route('admin.contactus.index') }}"
class="mb-6 flex flex-wrap items-end gap-4">

<div class="flex flex-col">
  <label class="text-sm font-medium text-gray-700">User Type</label>
  <select name="user_type" class="w-48 mt-1 px-4 py-2 border rounded">
      <option value="">All</option>
      <option value="student" {{ request('user_type') === 'student' ? 'selected' : '' }}>Student</option>
      <option value="employee" {{ request('user_type') === 'employee' ? 'selected' : '' }}>Employee</option>
  </select>
</div>

<div class="flex flex-col">
  <label class="text-sm font-medium text-gray-700">Status</label>
  <select name="status" class="w-48 mt-1 px-4 py-2 border rounded">
      <option value="">All</option>
      <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Unresolved</option>
      <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Resolved</option>
  </select>
</div>

<div class="flex flex-col">
  <label class="text-sm font-medium text-gray-700">Date</label>
  <input type="date" name="date" value="{{ request('date') }}"
         class="w-48 mt-1 px-4 py-2 border rounded">
</div>

<div class="flex gap-2 mt-4 md:mt-0">
  <button type="submit"
          class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
      Search
  </button>
  <a href="{{ route('admin.contactus.index') }}"
     class="bg-red-800 hover:bg-red-400 text-white px-6 py-2 rounded text-center">
      Clear
  </a>
</div>
</form>



            @if (session('success'))
                <div class="mb-4 text-green-700 bg-green-100 border border-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
                <script>
                    // Auto-hide success message
                    setTimeout(() => {
                        const msg = document.getElementById('successMsg');
                        if (msg) msg.style.display = 'none';
                    }, 2000);
                </script>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white uppercase bg-gray-700">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Full Name</th>
                            <th class="px-6 py-3">User Type</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Contact</th>
                            <th class="px-6 py-3">Message</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $contact->id }}</td>
                                <td class="px-6 py-4">{{ $contact->full_name }}</td>
                                <td class="px-6 py-4">
                                    @if ($contact->user_type=='student')
                                        <span
                                            class="text-sm px-2 py-0.5 bg-green-100 text-green-800 rounded-full">Student</span>
                                    @else
                                        <span
                                            class="text-sm px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full">Employee</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $contact->email }}</td>
                                <td class="px-6 py-4">{{ $contact->contact_number }}</td>
                                <td class="px-6 py-4">
                                    <!-- View button in Message column -->
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs"
                                        onclick="openModal('{{ $contact->message }}')">
                                        View
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($contact->status)
                                        <span
                                            class="text-sm px-2 py-0.5 bg-green-100 text-green-800 rounded-full">Resolved</span>
                                    @else
                                        <span
                                            class="text-sm px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full">Unresolved</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $contact->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 space-x-2">
                                    @if (!$contact->status)
                                        <!-- Button to mark as resolved -->
                                        <form action="{{ route('admin.contactus.resolve', $contact->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                Mark Resolved
                                            </button>
                                        </form>
                                    @else
                                        <!-- Resolved Button (disabled and changed color) -->
                                        <button class="bg-green-600 text-white px-3 py-1 rounded text-xs cursor-not-allowed"
                                            disabled>
                                            Resolved
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No contact inquiries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-6">
                    {{ $contacts->links() }}
                </div>

            </div>

            <!-- Modal -->
            <div id="messageModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
                <div class="bg-white p-6 rounded-lg w-3/4 max-w-3xl">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Message</h3>
                    <div id="modalMessage" class="text-gray-700"></div>
                    <button onclick="closeModal()" class="mt-4 bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Open modal and display message
        function openModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('messageModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('messageModal').classList.add('hidden');
        }
    </script>
@endsection
