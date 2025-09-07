@extends('admin.layouts.app')

@section('content')
    <div class="container py-8 mx-auto">
        <!-- Ongoing Classes Section -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 relative pb-2">
                    Ongoing Classes
                    <span class="absolute bottom-0 left-0 w-12 h-1 bg-green-600 rounded-full"></span>
                </h2>
                <span class="bg-green-600 text-white text-sm font-medium px-3 py-1 rounded-full">
                    {{ count($ongoingClasses) }}
                </span>
            </div>
            
            @if (count($ongoingClasses) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($ongoingClasses as $class)
                        <div class="group">
                            <div class="h-full bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-lg">
                                <div class="bg-green-600 px-6 py-4">
                                    <h5 class="text-white font-medium text-lg truncate">{{ $class->topic ?? 'N/A' }}</h5>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <i class="far fa-calendar-alt text-green-600 mr-3"></i>
                                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($class->class_datetime)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center mb-4">
                                        <i class="far fa-clock text-green-600 mr-3"></i>
                                        <span class="text-gray-700">{{ $class->duration_minutes }} minutes</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-video text-green-600 mr-3"></i>
                                        <span class="text-gray-700 truncate">{{ parse_url($class->google_meet_link, PHP_URL_HOST) ?? 'Online Meeting' }}</span>
                                    </div>
                                </div>
                                <div class="px-6 pb-6 pt-0">
                                    @if ($class->hasAttended(Auth::user()->user_id))
                                        <button class="block w-full bg-green-600 text-white font-medium py-2 px-4 rounded-lg text-center" disabled>
                                            <i class="fas fa-check-circle mr-2"></i> Attendance Marked
                                        </button>
                                    @else
                                        <a href="{{ route('student.join-class', $class->id) }}" 
                                           class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 text-center">
                                            <i class="fas fa-video mr-2"></i> Join Class
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <div class="text-gray-400 text-5xl mb-4">
                        <i class="far fa-calendar-times"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No ongoing classes</h3>
                    <p class="text-gray-500">No classes are currently in progress.</p>
                </div>
            @endif
        </div>

        <!-- Upcoming Classes Section -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 relative pb-2">
                    Upcoming Classes
                    <span class="absolute bottom-0 left-0 w-12 h-1 bg-blue-600 rounded-full"></span>
                </h2>
                <span class="bg-blue-600 text-white text-sm font-medium px-3 py-1 rounded-full">
                    {{ count($upcomingClasses) }}
                </span>
            </div>
            
            @if (count($upcomingClasses) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($upcomingClasses as $class)
                        <div class="group">
                            <div class="h-full bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-lg">
                                <div class="bg-blue-600 px-6 py-4">
                                    <h5 class="text-white font-medium text-lg truncate">{{ $class->topic ?? 'N/A' }}</h5>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <i class="far fa-calendar-alt text-blue-600 mr-3"></i>
                                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($class->class_datetime)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center mb-4">
                                        <i class="far fa-clock text-blue-600 mr-3"></i>
                                        <span class="text-gray-700">{{ $class->duration_minutes }} minutes</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-video text-blue-600 mr-3"></i>
                                        <span class="text-gray-700 truncate">{{ parse_url($class->google_meet_link, PHP_URL_HOST) ?? 'Online Meeting' }}</span>
                                    </div>
                                </div>
                                <div class="px-6 pb-6 pt-0">
                                    <button class="block w-full border border-gray-300 text-gray-600 font-medium py-2 px-4 rounded-lg text-center cursor-not-allowed" disabled>
                                        <i class="fas fa-hourglass-start mr-2"></i> Not Yet Started
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <div class="text-gray-400 text-5xl mb-4">
                        <i class="far fa-calendar-times"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No upcoming classes</h3>
                    <p class="text-gray-500">You don't have any classes scheduled yet.</p>
                </div>
            @endif
        </div>

        <!-- Ended Classes Section -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 relative pb-2">
                    Past Classes
                    <span class="absolute bottom-0 left-0 w-12 h-1 bg-gray-600 rounded-full"></span>
                </h2>
                <span class="bg-gray-600 text-white text-sm font-medium px-3 py-1 rounded-full">
                    {{ count($endedClasses) }}
                </span>
            </div>
            
            @if (count($endedClasses) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($endedClasses as $class)
                        <div class="group">
                            <div class="h-full bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-lg">
                                <div class="bg-gray-600 px-6 py-4">
                                    <h5 class="text-white font-medium text-lg truncate">{{ $class->topic ?? 'N/A' }}</h5>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <i class="far fa-calendar-alt text-gray-600 mr-3"></i>
                                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($class->class_datetime)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center mb-4">
                                        <i class="far fa-clock text-gray-600 mr-3"></i>
                                        <span class="text-gray-700">{{ $class->duration_minutes }} minutes</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-video text-gray-600 mr-3"></i>
                                        <span class="text-gray-700 truncate">{{ parse_url($class->google_meet_link, PHP_URL_HOST) ?? 'Online Meeting' }}</span>
                                    </div>
                                </div>
                                <div class="px-6 pb-6 pt-0">
                                    <button class="w-full border border-gray-300 text-gray-600 font-medium py-2 px-4 rounded-lg text-center cursor-not-allowed" disabled>
                                        <i class="fas fa-check-circle mr-2"></i> Class Completed
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <div class="text-gray-400 text-5xl mb-4">
                        <i class="far fa-calendar-check"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No past classes</h3>
                    <p class="text-gray-500">Your completed classes will appear here.</p>
                </div>
            @endif
        </div>
    </div>
@endsection