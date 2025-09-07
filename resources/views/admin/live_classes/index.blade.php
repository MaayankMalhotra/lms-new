@extends('admin.layouts.app')

@section('content')
    @php
        use App\Models\Recording; // Declare the Recording model
    @endphp

    <style>
        body {
            background: url("https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg") no-repeat center center fixed;
            background-size: cover;
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Manage Live Classes</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.live_classes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4 inline-block">Add New Live Class</a>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($batches as $batch)
                @foreach($batch->liveClasses as $class)
                    @if(Carbon\Carbon::parse($class->class_datetime)->gte(Carbon\Carbon::now()))
                        <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition-shadow duration-300 relative group">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $batch->course->name }}</h3>
                            <p class="text-gray-600 mb-2">Batch: {{ $batch->start_date->format('Y-m-d') }}</p>
                            <p class="text-gray-600 mb-2">Topic: {{ $class->topic }}</p>
                            <p class="text-gray-600 mb-2">Class Time: {{ Carbon\Carbon::parse($class->class_datetime)->format('Y-m-d H:i') }}</p>
                            <p class="text-gray-600 mb-4">Status: 
                                <span class="px-2 py-1 rounded {{ $class->status == 'Scheduled' ? 'bg-yellow-200 text-yellow-800' : ($class->status == 'Live' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">
                                    {{ $class->status }}
                                </span>
                            </p>
                            <div class="flex justify-between items-center">
                                <button onclick="openModal('edit-modal-{{ $class->id }}')" class="text-blue-500 hover:underline">Edit</button>
                                <form action="{{ route('admin.live_classes.destroy', $class->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <a href="{{ route('teacher.assignments.view', $class->id) }}" class="text-green-500 hover:underline">View Assignment</a>
                            </div>
                            <button onclick="openModal('folder-recording-modal-{{ $class->id }}')" class="mt-4 w-full bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition duration-200">View Folders & Recordings</button>
                        </div>

                        <!-- Edit Modal -->
                        {{-- (unchanged code for edit modal) --}}

                        <!-- Folder & Recording Modal -->
                        {{-- (unchanged code for folder-recording modal) --}}
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

    <script>
        {{-- unchanged JS --}}
    </script>

    <style>
        .collapsed { display: none; }
        .expanded { display: block; }
        .group:hover { transform: translateY(-2px); }
        a.text-blue-500:hover { text-decoration: underline; }
        a.bg-blue-500 { margin-top: 0.25rem; display: inline-block; }
    </style>
@endsection
