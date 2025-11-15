@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center p-8"
     style="background-image: url('{{ asset('images/bg-pattern.jpg') }}');">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 bg-white/70 backdrop-blur-md p-4 rounded-xl shadow">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i> Webinars List
                </h2>
                <p class="text-gray-600 mt-1">Manage all webinars from the panel below.</p>
            </div>
            <a href="{{ route('admin.webinar.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Add Webinar
            </a>
        </div>

        @if(session('success'))
            <div class="mx-auto max-w-7xl mb-6 bg-green-100 text-green-700 px-6 py-4 rounded-xl shadow">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <div class="mb-6 bg-white/70 backdrop-blur-md p-4 rounded-xl shadow">
            <form action="{{ route('admin.webinar.index') }}" method="GET" class="flex items-center space-x-4">
                <div class="w-full max-w-sm">
                    <select name="tag"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Tags</option>
                        @foreach($uniqueTags as $tag)
                            <option value="{{ $tag }}" {{ request('tag') == $tag ? 'selected' : '' }}>
                                {{ $tag }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-search"></i>
                </button>
                @if (request('tag'))
                    <a href="{{ route('admin.webinar.index') }}" class="text-sm text-blue-500 hover:text-blue-700">Clear Filter</a>
                @endif
            </form>
        </div>

        <!-- Webinars as Cards -->
        @if($webinars->isEmpty())
            <div class="text-center text-gray-100 py-12">
                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                <p>No webinars found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($webinars as $webinar)
                    <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-xl hover:shadow-2xl transition duration-300 overflow-hidden">
                        <!-- Header strip -->
                        <div class="h-2 bg-gradient-to-r from-blue-400 to-indigo-500"></div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ $webinar->title }}</h3>

                            <p class="text-sm text-gray-600 mb-2 flex items-center">
                                <i class="fas fa-clock text-blue-500 mr-2"></i>
                                {{ $webinar->start_time->format('d M Y, h:i A') }}
                            </p>

                            <p class="text-sm text-gray-600 mb-2 flex items-center">
                                <i class="fas fa-hourglass-end text-red-500 mr-2"></i>
                                Deadline: {{ $webinar->registration_deadline->format('d M Y, h:i A') }}
                            </p>

                            <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Entry:</span> {{ ucfirst($webinar->entry_type) }}</p>
                            <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Participants:</span> {{ $webinar->participants_count }}</p>

                            <p class="text-sm text-gray-700 mt-2">
                                <span class="font-semibold">Tags:</span>
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs">
                                    {{ $webinar->tags ?? '-' }}
                                </span>
                            </p>
                        </div>

                        <!-- Card Footer -->
                        <div class="flex justify-between items-center bg-gray-50/70 px-6 py-4 border-t">
                            <a href="{{ route('admin.webinar.edit', $webinar->id) }}"
                               class="text-blue-500 hover:text-blue-700 flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.webinar.destroy', $webinar->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this webinar?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 flex items-center">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $webinars->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
