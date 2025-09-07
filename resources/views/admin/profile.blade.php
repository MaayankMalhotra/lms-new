@extends('admin.layouts.app')
@section('content')
<div class="px-3">
    <section class="bg-white p-6 rounded-lg shadow-md mt-5">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-700">Profile Details</h3>
            <a href="{{ route('edit-profile') }}" class="text-orange-500 text-sm font-semibold hover:underline">Edit Profile</a>
        </div>
        <!-- User Details List -->
        <div class="space-y-4">
            <!-- Name -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn-icons-png.flaticon.com/128/3135/3135715.png" alt="Name" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Name</h4>
                    <p class="text-sm text-gray-600">{{ $user->name }}</p>
                </div>
            </div>

            <!-- Email -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn-icons-png.flaticon.com/128/732/732200.png" alt="Email" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Email</h4>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                </div>
            </div>

            <!-- Phone -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn-icons-png.flaticon.com/128/724/724664.png" alt="Phone" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Phone</h4>
                    <p class="text-sm text-gray-600">{{ $user->phone }}</p>
                </div>
            </div>

            <!-- College/Company -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn-icons-png.flaticon.com/128/2991/2991160.png" alt="College/Company" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">College/Company</h4>
                    <p class="text-sm text-gray-600">{{ $user->college_company }}</p>
                </div>
            </div>

            <!-- Qualification -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn-icons-png.flaticon.com/128/3135/3135773.png" alt="Qualification" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Qualification</h4>
                    <p class="text-sm text-gray-600">{{ $user->qualification }}</p>
                </div>
            </div>

            <!-- Role -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn-icons-png.flaticon.com/128/2921/2921222.png" alt="Role" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Role</h4>
                    <p class="text-sm text-gray-600">
                        @if ($user->role == 1)
                            admin
                        @elseif ($user->role == 2)
                            teacher
                        @elseif ($user->role == 3)
                            student
                        @else
                            unknown
                        @endif
                    </p>
                </div>
            </div>

            <!-- Joined On -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn-icons-png.flaticon.com/128/3135/3135755.png" alt="Joined On" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Joined On</h4>
                    <p class="text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="mt-8">
            @csrf
            <button type="submit" class="w-full bg-red-500 text-white font-semibold py-3 rounded-lg hover:bg-red-600 transition-colors">
                Logout
            </button>
        </form>
    </section>
</div>
@endsection