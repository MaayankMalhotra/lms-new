@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg p-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-user-tie text-[#ff7b00]"></i>
                    Mentor Applications
                </h1>
                <p class="text-slate-500">Track and process every mentor interested in joining Think Champ.</p>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-3 text-sm text-slate-500">
                <span>
                    Total Applications: <span class="font-semibold text-slate-800">{{ $applications->total() }}</span>
                </span>
                <a href="{{ route('admin.mentor-applications.export') }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 text-white rounded-lg text-xs font-semibold uppercase tracking-wide hover:bg-slate-800 transition">
                    📥 Export CSV
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 bg-white rounded-2xl shadow overflow-hidden">
            @if($applications->isEmpty())
                <div class="p-10 text-center text-slate-500">
                    <i class="fas fa-inbox text-4xl mb-3"></i>
                    <p>No mentor applications yet. When a professional applies via the website, the details will appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Name</th>
                                <th class="px-4 py-3 text-left">Expertise</th>
                                <th class="px-4 py-3 text-left">Experience</th>
                                <th class="px-4 py-3 text-left">Contact</th>
                                <th class="px-4 py-3 text-left">Links</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Applied</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($applications as $application)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-slate-900">{{ $application->name }}</div>
                                        @if($application->message)
                                            <p class="text-slate-500 text-xs mt-1 overflow-hidden" style="-webkit-line-clamp:2;-webkit-box-orient:vertical;display:-webkit-box;">
                                                {{ $application->message }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <p class="font-medium text-slate-800">{{ $application->specialization ?? '—' }}</p>
                                        <p class="text-xs text-slate-500">{{ $application->teaching_hours ? $application->teaching_hours.' hrs taught' : 'Hours NA' }}</p>
                                    </td>
                                    <td class="px-4 py-4">
                                        {{ $application->experience_years ? $application->experience_years.' yrs' : '—' }}
                                    </td>
                                    <td class="px-4 py-4 space-y-1">
                                        <p><i class="fas fa-envelope text-[#ff7b00] mr-1"></i>{{ $application->email ?? '—' }}</p>
                                        <p><i class="fas fa-phone text-[#ff7b00] mr-1"></i>{{ $application->phone ?? '—' }}</p>
                                    </td>
                                    <td class="px-4 py-4 space-y-1">
                                        @if($application->linkedin_url)
                                            <a href="{{ $application->linkedin_url }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs">
                                                <i class="fab fa-linkedin mr-1"></i>LinkedIn
                                            </a><br>
                                        @endif
                                        @if($application->portfolio_url)
                                            <a href="{{ $application->portfolio_url }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs">
                                                <i class="fas fa-globe mr-1"></i>Portfolio
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <form method="POST" action="{{ route('admin.mentor-applications.update-status', $application) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="text-sm border-slate-200 rounded-lg focus:ring-[#ff7b00] focus:border-[#ff7b00]">
                                                @foreach(['pending','reviewing','contacted','accepted','rejected'] as $status)
                                                    <option value="{{ $status }}" {{ $application->status === $status ? 'selected' : '' }}>
                                                        {{ ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-3 py-1 bg-[#ff7b00] text-white rounded-lg text-xs hover:bg-[#ff5500]">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-4 text-slate-500 text-xs">
                                        {{ optional($application->created_at)->format('d M Y, h:i A') ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
