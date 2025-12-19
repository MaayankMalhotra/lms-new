@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-briefcase text-[#ff9800]"></i>
                Job Roles
            </h1>
            <p class="text-gray-600 text-sm">Browse roles and apply via the external link.</p>
        </div>
    </div>

    @php
        $applications = $applications ?? collect();
        $applicationsByJobRole = $applicationsByJobRole ?? $applications->keyBy('job_role_id');
        $statusOptions = $applicationStatusOptions ?? [];
        $statusColors = [
            'applied' => 'bg-green-100 text-green-700',
            'got_email' => 'bg-blue-100 text-blue-700',
            'interview_scheduled' => 'bg-indigo-100 text-indigo-700',
            'offer_received' => 'bg-emerald-100 text-emerald-700',
            'rejected' => 'bg-red-100 text-red-700',
            'no_response' => 'bg-gray-100 text-gray-700',
        ];
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
                            </div>
                            @php
                                $status = $application->status ?? 'applied';
                                $statusLabel = $statusOptions[$status] ?? ucfirst(str_replace('_', ' ', $status));
                                $statusClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">{{ $statusLabel }}</span>
                                <form action="{{ route('student.job-roles.applications.status', $application->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <select name="status" class="text-xs border rounded px-2 py-1 focus:outline-none focus:ring focus:ring-[#ff9800]">
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}" @selected($application->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="text-xs bg-[#ff9800] text-white px-3 py-1 rounded hover:bg-[#e07d00]">
                                        Update
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-4 text-sm text-gray-600 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i>
                You haven't applied to any roles yet. Click a role's external link to mark as applied.
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
                    $lastDate = $jobRole->last_date_to_apply ? $jobRole->last_date_to_apply->format('d M Y') : 'Not set';
                @endphp
                <div class="bg-white shadow-lg rounded-xl overflow-hidden flex flex-col">
                    @if($jobRole->image_url)
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ $jobRole->image_url }}');"></div>
                    @endif
                    <div class="p-5 flex flex-col gap-3 flex-1">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex flex-col">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Company</p>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $jobRole->title }}</h2>
                                <p class="text-sm text-gray-600">{{ $jobRole->company_name }}</p>
                                <p class="text-xs text-gray-500">Role ID #{{ $jobRole->id }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-700">
                                    <span class="px-2 py-1 bg-green-50 text-green-700 rounded-md flex items-center gap-1">
                                        <i class="fas fa-rupee-sign"></i>{{ $jobRole->salary_package ?? 'N/A' }}
                                    </span>
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md flex items-center gap-1">
                                        <i class="fas fa-map-marker-alt"></i>{{ $jobRole->location ?? 'N/A' }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <i class="fas fa-calendar-alt text-indigo-500"></i>Last date: {{ $lastDate }}
                                </p>
                                <a href="{{ $jobRole->apply_link }}" target="_blank" rel="noreferrer"
                                   class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                    <i class="fas fa-external-link-alt"></i>External apply link
                                </a>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">
                                <i class="fas fa-code text-blue-500 mr-1"></i> Technologies
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @forelse(collect($jobRole->technologies) as $tech)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">{{ $tech['name'] ?? '' }}</span>
                                @empty
                                    <p class="text-sm text-gray-600">Not specified</p>
                                @endforelse
                            </div>
                        </div>

                        @if($jobRole->suggestions)
                            <div class="text-xs text-gray-700 bg-gray-50 border border-gray-100 rounded p-3">
                                {{ $jobRole->suggestions }}
                            </div>
                        @endif

                        <form action="{{ route('student.job-roles.external-apply', $jobRole->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full text-center bg-[#ff9800] hover:bg-[#e68900] text-white font-semibold py-2 rounded transition">
                                Go to external apply link
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
