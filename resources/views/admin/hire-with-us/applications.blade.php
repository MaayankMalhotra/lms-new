@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6 bg-white shadow rounded-lg px-5 py-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-users text-[#ff9800]"></i>
                Job Role Applications
            </h1>
            <p class="text-sm text-gray-600">Track which students applied to which roles.</p>
        </div>
        <a href="{{ route('admin.job-roles.index') }}"
           class="text-sm text-[#ff9800] hover:text-[#e07d00] font-semibold">
            ← Back to Job Roles
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-4 mb-4">
        <form method="GET" action="{{ route('admin.job-roles.applications') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="job_role_id" class="block text-sm font-medium text-gray-700">Job Role</label>
                <select
                    id="job_role_id"
                    name="job_role_id"
                    class="mt-1 block w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:ring-orange-200"
                >
                    <option value="">All roles</option>
                    @foreach($jobRoles as $role)
                        <option value="{{ $role->id }}" {{ (string) ($jobRoleId ?? '') === (string) $role->id ? 'selected' : '' }}>
                            {{ $role->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700">Search student / email / message</label>
                <input
                    id="search"
                    name="search"
                    type="text"
                    value="{{ $search ?? '' }}"
                    placeholder="e.g., student name or email"
                    class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-orange-200"
                >
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-[#ff9800] text-white rounded hover:bg-[#e07d00]">
                    Apply filters
                </button>
                <a href="{{ route('admin.job-roles.applications') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resume</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $application)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ $application->jobRole->title ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                <div class="font-semibold">{{ $application->name ?? $application->user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $application->email ?? $application->user->email }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a class="text-[#ff9800] hover:text-[#e07d00] font-semibold"
                                   href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" rel="noreferrer">
                                    View Resume
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 max-w-xs">
                                <div class="truncate" title="{{ $application->message ?? '' }}">{{ $application->message ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $application->created_at?->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                No applications found. @if(($jobRoleId ?? null) || ($search ?? null)) Try adjusting or clearing the filters.@endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 bg-gray-50">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection
