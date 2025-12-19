@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center p-8" style="background-image: url('{{ asset('images/bg-pattern.jpg') }}');">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 bg-white/90 backdrop-blur-md p-5 rounded-2xl shadow">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i class="fas fa-briefcase"></i>
                    </span>
                    Job Roles
                </h2>
                <p class="text-gray-600 mt-1">Review roles, applicants, and quick actions.</p>
            </div>
            <a href="{{ route('admin.job-roles.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow font-semibold">
                + Add Job Role
            </a>
        </div>

        <!-- Job Roles Cards -->
        @if($jobRoles->isEmpty())
            <div class="text-center text-gray-200 py-12">
                <i class="fas fa-briefcase text-4xl mb-3"></i>
                <p>No job roles found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobRoles as $jobRole)
                    @php
                        $techList = collect($jobRole->technologies ?? []);
                        $lastDate = $jobRole->last_date_to_apply ? $jobRole->last_date_to_apply->format('d M Y') : 'Not set';
                        $apps = $jobRole->applications ?? collect();
                    @endphp
                    <div class="bg-white/95 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 overflow-hidden border border-gray-100">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 flex items-start justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wide opacity-80">Company</p>
                                <h3 class="text-lg font-semibold">{{ $jobRole->title }}</h3>
                                <p class="text-sm opacity-90">{{ $jobRole->company_name }}</p>
                            </div>
                            <span class="text-xs bg-white/20 px-3 py-1 rounded-full">ID #{{ $jobRole->id }}</span>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between text-sm text-gray-700">
                                <span class="font-semibold flex items-center gap-1"><i class="fas fa-rupee-sign text-green-500"></i>{{ $jobRole->salary_package ?? 'N/A' }}</span>
                                <span class="flex items-center text-gray-600 gap-1"><i class="fas fa-map-marker-alt text-red-400"></i>{{ $jobRole->location ?? 'N/A' }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm text-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-indigo-500"></i>
                                    <span>Last date: {{ $lastDate }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-link text-blue-500"></i>
                                    <a href="{{ $jobRole->apply_link }}" target="_blank" rel="noreferrer" class="text-blue-600 hover:underline">
                                        Apply link
                                    </a>
                                </div>
                            </div>

                            @if($techList->isNotEmpty())
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-code text-blue-500"></i> Technologies
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($techList as $tech)
                                            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                                                {{ $tech['name'] ?? '' }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($jobRole->suggestions)
                                <p class="text-xs text-gray-700 bg-gray-50 border border-gray-100 rounded p-3">{{ $jobRole->suggestions }}</p>
                            @endif

                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                        <i class="fas fa-users text-blue-500"></i>
                                        Applicants ({{ $apps->count() }})
                                    </div>
                                    <span class="text-xs text-gray-500">Latest first</span>
                                </div>
                                @if($apps->isEmpty())
                                    <p class="text-xs text-gray-500">No applications yet.</p>
                                @else
                                    <div class="space-y-2 max-h-32 overflow-y-auto pr-1">
                                        @foreach($apps->take(5) as $application)
                                            <div class="flex items-start justify-between text-xs bg-white rounded-lg p-2 border border-gray-100">
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $application->user->name ?? 'Student' }}</p>
                                                    <p class="text-gray-500">{{ $application->user->email ?? 'N/A' }}</p>
                                                    <p class="text-gray-400">Applied {{ $application->created_at?->format('d M Y, h:i A') }}</p>
                                                </div>
                                                <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="text-blue-600 hover:underline">Resume</a>
                                            </div>
                                        @endforeach
                                        @if($apps->count() > 5)
                                            <p class="text-[11px] text-gray-500">+ {{ $apps->count() - 5 }} more</p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-1 text-xs text-gray-500">
                                <div class="space-y-1">
                                    <p><i class="fas fa-calendar-plus mr-1 text-green-500"></i>Created: {{ $jobRole->created_at->format('d M Y, h:i A') }}</p>
                                    <p><i class="fas fa-calendar-check mr-1 text-indigo-500"></i>Updated: {{ $jobRole->updated_at->format('d M Y, h:i A') }}</p>
                                </div>
                                <div class="flex items-center space-x-3 text-sm">
                                    <a href="{{ route('admin.job-roles.edit', $jobRole->id) }}"
                                       class="text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.job-roles.destroy', $jobRole->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this job role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 flex items-center gap-1">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $jobRoles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
