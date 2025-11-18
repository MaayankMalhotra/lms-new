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
                                No applications yet.
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
