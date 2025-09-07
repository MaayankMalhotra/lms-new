@extends('admin.layouts.app')
@section('content')
<div class="px-3">
    <section class="bg-white p-6 rounded-lg shadow-md mt-5">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-700">Edit Profile</h3>
            <a href="{{ route('profile') }}" class="text-orange-500 text-sm font-semibold hover:underline">Back to Profile</a>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Profile Form -->
        <form action="{{ route('update-profile') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700" required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700" required>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" pattern="[0-9]{10}" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700" required>
                </div>

                <!-- College/Company -->
                <div>
                    <label for="college_company" class="block text-sm font-semibold text-gray-700 mb-1">College/Company</label>
                    <input type="text" name="college_company" id="college_company" value="{{ old('college_company', $user->college_company) }}" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700" required>
                </div>

                <!-- Qualification -->
                <div>
                    <label for="qualification" class="block text-sm font-semibold text-gray-700 mb-1">Qualification</label>
                    <input type="text" name="qualification" id="qualification" value="{{ old('qualification', $user->qualification) }}" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700" required>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="w-full bg-orange-500 text-white font-semibold py-3 rounded-lg hover:bg-orange-600 transition-colors">
                        Update Profile
                    </button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection