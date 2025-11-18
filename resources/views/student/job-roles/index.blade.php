@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-briefcase text-[#ff9800]"></i>
                Job Roles
            </h1>
            <p class="text-gray-600 text-sm">Browse roles and apply directly with your resume.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($jobRoles->isEmpty())
        <div class="text-center text-gray-600 bg-white shadow rounded p-10">
            <p>No job roles are available right now.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($jobRoles as $jobRole)
                <div class="bg-white shadow-lg rounded-xl p-5 flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <h2 class="text-lg font-semibold text-gray-800">{{ $jobRole->title }}</h2>
                        <span class="text-xs text-gray-500">#{{ $jobRole->id }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-code text-blue-500 mr-1"></i> Technologies
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ collect($jobRole->technologies)->pluck('name')->implode(', ') ?: 'Not specified' }}
                        </p>
                    </div>
                    <form action="{{ route('student.job-roles.apply', $jobRole->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="resume-{{ $jobRole->id }}">Upload Resume</label>
                            <input id="resume-{{ $jobRole->id }}" name="resume" type="file" required
                                   class="block w-full text-sm text-gray-700 border rounded px-3 py-2 focus:ring-[#ff9800] focus:border-[#ff9800]">
                            @error('resume')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="message-{{ $jobRole->id }}">Note (optional)</label>
                            <textarea id="message-{{ $jobRole->id }}" name="message" rows="3"
                                      class="block w-full text-sm text-gray-700 border rounded px-3 py-2 focus:ring-[#ff9800] focus:border-[#ff9800]"
                                      placeholder="Anything you want to share with the admin..."></textarea>
                            @error('message')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="w-full bg-[#ff9800] hover:bg-[#e68900] text-white font-semibold py-2 rounded transition">
                            Apply
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
