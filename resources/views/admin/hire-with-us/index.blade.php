@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center p-8" style="background-image: url('{{ asset('images/bg-pattern.jpg') }}');">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 bg-white/80 backdrop-blur-md p-4 rounded-xl shadow">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-briefcase text-blue-500 mr-2"></i> Job Roles List
                </h2>
                <p class="text-gray-600 mt-1">Manage all job roles from the panel below.</p>
            </div>
            <a href="{{ route('admin.job-roles.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
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
                    <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-lg hover:shadow-2xl transition duration-300 p-6">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $jobRole->title }}
                            </h3>
                            <span class="text-xs text-gray-500">#{{ $jobRole->id }}</span>
                        </div>

                        <div class="mt-3">
                            <p class="text-sm text-gray-600 font-semibold mb-1">
                                <i class="fas fa-code text-blue-500 mr-1"></i>Technologies:
                            </p>
                            <p class="text-sm text-gray-700">
                                {{ collect($jobRole->technologies)->pluck('name')->implode(', ') ?: 'None' }}
                            </p>
                        </div>

                        <div class="mt-4 text-xs text-gray-500 space-y-1">
                            <p><i class="fas fa-calendar-plus mr-1 text-green-500"></i> Created: {{ $jobRole->created_at->format('d M Y, h:i A') }}</p>
                            <p><i class="fas fa-calendar-check mr-1 text-indigo-500"></i> Updated: {{ $jobRole->updated_at->format('d M Y, h:i A') }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="mt-5 flex justify-between items-center">
                            <a href="{{ route('admin.job-roles.edit', $jobRole->id) }}"
                               class="text-blue-500 hover:text-blue-700 flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.job-roles.destroy', $jobRole->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this job role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 flex items-center">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </form>
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
