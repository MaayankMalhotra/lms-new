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

    @php
        $applications = $applications ?? collect();
        $applicationsByJobRole = $applicationsByJobRole ?? $applications->keyBy('job_role_id');
    @endphp

    <div class="mb-6">
        @if($applications->isNotEmpty())
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <h2 class="text-lg font-semibold text-gray-800">Your Applications</h2>
                    </div>
                    <span class="text-xs font-semibold bg-green-100 text-green-700 px-2 py-1 rounded-full">
                        {{ $applications->count() }} {{ $applications->count() === 1 ? 'role' : 'roles' }}
                    </span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($applications as $application)
                        <div class="py-3 flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">
                                    {{ $application->jobRole->title ?? 'Job Role' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Applied {{ $application->created_at?->format('d M Y, h:i A') }}
                                </div>
                                @if($application->message)
                                    <p class="text-sm text-gray-700 mt-1">{{ $application->message }}</p>
                                @endif
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <a class="text-[#ff9800] hover:text-[#e07d00] text-sm font-semibold"
                                   href="{{ asset('storage/' . $application->resume_path) }}"
                                   target="_blank" rel="noreferrer">
                                    View Resume
                                </a>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Applied</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-4 text-sm text-gray-600 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i>
                You haven't applied to any roles yet. Submit an application below to get started.
            </div>
        @endif
    </div>

    @if($jobRoles->isEmpty())
        <div class="text-center text-gray-600 bg-white shadow rounded p-10">
            <p>No job roles are available right now.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($jobRoles as $jobRole)
                @php
                    $existingApplication = $applicationsByJobRole->get($jobRole->id);
                @endphp
                <div class="bg-white shadow-lg rounded-xl p-5 flex flex-col gap-4">
                    <div class="flex justify-between items-start gap-3">
                        <div class="flex flex-col">
                            <h2 class="text-lg font-semibold text-gray-800">{{ $jobRole->title }}</h2>
                            <p class="text-xs text-gray-500">Role ID #{{ $jobRole->id }}</p>
                        </div>
                        @if($existingApplication)
                            <span class="text-xs font-semibold bg-green-100 text-green-700 px-2 py-1 rounded-full h-fit">
                                Applied
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-code text-blue-500 mr-1"></i> Technologies
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ collect($jobRole->technologies)->pluck('name')->implode(', ') ?: 'Not specified' }}
                        </p>
                    </div>
                    @if($existingApplication)
                        <div class="bg-green-50 border border-green-100 rounded-lg p-3 text-sm text-green-900">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold">Application submitted on {{ $existingApplication->created_at?->format('d M Y') }}</div>
                                    @if($existingApplication->message)
                                        <p class="mt-1 text-green-800">{{ $existingApplication->message }}</p>
                                    @endif
                                </div>
                                <a class="text-[#ff9800] hover:text-[#e07d00] font-semibold text-sm"
                                   href="{{ asset('storage/' . $existingApplication->resume_path) }}"
                                   target="_blank" rel="noreferrer">
                                    View Resume
                                </a>
                            </div>
                        </div>
                    @else
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
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
