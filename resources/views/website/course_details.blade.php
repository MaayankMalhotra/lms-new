@extends('website.layouts.app')

@section('title', $course->name ?? 'Course Details')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    body {
        background-color: #ffffff;
    }
    /* Custom rotate animation for the chevron */
    .rotate-180 {
        transform: rotate(180deg);
    }
    .batch-card {
        cursor: pointer;
        transition: border-color 0.3s;
    }
    .batch-card.active {
        border-color: #f97316 !important;
        background-color: #fff7ed;
    }
    .field-error {
        border-color: #ef4444 !important;
    }
</style>

<div class="mt-10 container mx-auto px-5 py-10 max-w-7xl bg-white rounded-lg shadow-md">
    <div class="content flex flex-wrap justify-between items-center gap-5">
        <div class="left-section flex-1 min-w-[300px] text-center md:text-left order-2 md:order-1">
            <p class="audience text-blue-500 text-sm font-bold uppercase mb-2">
                FOR BEGINNER AND EXPERIENCED LEARNERS
            </p>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight mb-5">
                {{ $course->name ?? 'NA' }}
            </h1>
            <p class="description text-base md:text-lg text-gray-600 leading-relaxed mb-5">
                {{ $course_details->course_description ?? 'NA' }}
            </p>
            <div class="flex flex-col sm:flex-row items-center gap-5 mb-5">
                
                <div class="flex items-center gap-1">
                    <span class="text-orange-500 text-lg font-bold">{{ $course_details->course_rating ?? 'NA' }}</span>
                    <div class="flex">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="text-orange-500 text-lg">★</span>
                        @endfor
                    </div>
                    <span class="text-gray-600 text-sm">({{ $course_details->course_rating_student_number ?? 'NA' }}K+ student)</span>
                </div>
            </div>
            <div class="stats flex flex-col md:flex-row gap-5 mt-5 bg-blue-100 p-5 rounded-lg justify-between max-w-lg mx-auto md:mx-0">
                <div class="stat text-center">
                    <span class="rating text-orange-500 text-2xl font-bold">
                        {{ $course_details->course_rating ?? 'NA' }} <span class="star text-xl">★</span>
                    </span>
                    <p class="text-sm text-gray-600 mt-1">{{ $course_details->course_learner_enrolled ?? 'NA' }}K+ Learners enrolled</p>
                </div>
                <div class="stat text-center">
                    <span class="text-2xl font-bold text-gray-800">{{ $course_details->course_lecture_hours ?? 'NA' }}+</span>
                    <p class="text-sm text-gray-600 mt-1">Hours of lectures</p>
                </div>
                <div class="stat text-center">
                    <span class="text-2xl font-bold text-gray-800">{{ $course_details->course_problem_counts ?? 'NA' }}+</span>
                    <p class="text-sm text-gray-600 mt-1">Problems</p>
                </div>
            </div>
        </div>
        <div class="right-section flex-1 min-w-[300px] text-center order-1 md:order-2">
            <img src="{{ $course_details ? asset('storage/' . ($course_details->course_banner ?? '')) : 'https://via.placeholder.com/600x400?text=NA' }}" alt="Person studying with laptop and books" class="max-w-full h-auto rounded-lg">
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="tabs flex flex-col sm:flex-row justify-center items-center mx-auto w-11/12 sm:w-auto sm:max-w-7xl gap-2 sm:gap-4 md:gap-8 mt-5 sm:mt-10 bg-white p-2 sm:p-3 rounded-lg sm:rounded-full shadow-md">
    <a href="#about" class="w-full sm:w-auto text-center text-orange-500 font-bold text-sm sm:text-base md:text-lg px-3 sm:px-4 py-1 sm:py-2 hover:text-orange-500 transition-colors">About the course</a>
    <a href="#batches" class="w-full sm:w-auto text-center text-gray-600 text-sm sm:text-base md:text-lg px-3 sm:px-4 py-1 sm:py-2 hover:text-orange-500 transition-colors">Batches</a>

    <a href="#instructors" class="w-full sm:w-auto text-center text-gray-600 text-sm sm:text-base md:text-lg px-3 sm:px-4 py-1 sm:py-2 hover:text-orange-500 transition-colors">Instructors</a>
    <a href="#faqs" class="w-full sm:w-auto text-center text-gray-600 text-sm sm:text-base md:text-lg px-3 sm:px-4 py-1 sm:py-2 hover:text-orange-500 transition-colors">FAQs</a>
</div>

<!-- Course Batches Section -->
<div class="container mx-auto px-5 py-10 max-w-7xl" id="batches">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-5">
        Course <span class="text-orange-500">Batches</span>
    </h2>
    <div class="bg-white p-5 rounded-lg shadow-md">
        <!-- Online Classroom Header -->
        <div class="flex items-center gap-3 mb-5">
            <h3 class="text-xl font-semibold text-gray-800">Online Classroom</h3>
            <span class="bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded">PREFERRED</span>
        </div>
        <!-- Features List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
            <div class="flex items-center gap-2">
                <span class="text-green-500">✔</span>
                <p class="text-sm text-gray-600">Everything in self-paced Learning</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-green-500">✔</span>
                <p class="text-sm text-gray-600">
                    {{ $course_details->course_lecture_hours ?? 'NA' }} Hrs of instructor-led training
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-green-500">✔</span>
                <p class="text-sm text-gray-600">One-to-one doubt resolution sessions</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-green-500">✔</span>
                <p class="text-sm text-gray-600">Attend as many batches as you want for a lifetime</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-green-500">✔</span>
                <p class="text-sm text-gray-600"><span id="available-slots">90</span> Slots available</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-green-500">✔</span>
                <p class="text-sm text-gray-600"><span id="filled-slots">80</span> Slots Filled</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-green-500">✔</span>
                <p class="text-sm text-gray-600"><span id="mode-of-teaching">Online</span> Mode of teaching</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-green-500">✔</span>
                <p class="text-sm text-gray-600">Job assistance</p>
            </div>
        </div>
        <!-- Batch Cards and Pricing -->
        <div class="flex flex-col lg:flex-row items-center lg:items-end gap-5">
            <!-- Batch Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 flex-1" id="batch-cards">
                <!-- Batches will be dynamically inserted here -->
            </div>
            <!-- Pricing and Enroll Button -->
            <div class="text-center lg:text-right">
                <p class="text-2xl font-bold text-gray-800" id="batch-price">₹40,014</p>
                <p class="text-sm text-gray-600 line-through" id="batch-original-price">₹44,460</p>
                <p class="text-sm text-gray-600" id="batch-discount">10% OFF</p>
                <button class="bg-orange-500 text-white font-bold py-3 px-6 rounded-md mt-3 hover:bg-orange-600 transition-colors" id="batch-enroll-button">
                    Enroll Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- About Course Overview Section -->
<div class="container mx-auto px-5 py-10 max-w-7xl bg-gray-50">
    <h2 class="text-3xl font-bold mb-6 text-orange-500">About Course Overview</h2>
    <p class="text-gray-600 mb-6">
        {{ $course_details->course_overview_description ?? 'NA' }}
    </p>
    <h3 class="text-2xl font-bold mb-4 text-gray-900">Learning Outcomes:</h3>
    <ul class="list-disc pl-6 mb-6 text-gray-700 space-y-2">
        @if($course_details && is_array($course_details->learning_outcomes) && count($course_details->learning_outcomes) > 0)
            @foreach($course_details->learning_outcomes as $outcome)
                <li>{{ $outcome }}</li>
            @endforeach
        @else
            <li>NA</li>
        @endif
    </ul>
    <h3 class="text-2xl font-bold mb-4 text-gray-900">Instructor Info:</h3>
    @php
        $instructorNames = ($instructors ?? collect())->pluck('name')->filter()->implode(', ');
    @endphp
    <p class="text-gray-600 mb-6">
        @if($instructorNames)
            <span class="font-semibold text-gray-900">Instructors:</span> {{ $instructorNames }}<br>
        @endif
        {{ $course_details->instructor_info ?? 'NA' }}
    </p>
    <h3 class="text-2xl font-bold mb-4 text-gray-900">Additional Benefits:</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md text-center border border-gray-200">
            <div class="text-4xl mb-4 text-gray-400">📄</div>
            <h4 class="text-lg font-semibold text-gray-800 mb-2">Project Icon</h4>
            <p class="text-gray-600">Real-world Projects<br>Work on live projects that enhance your practical skills and prepare you for the industry.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center border border-gray-200">
            <div class="text-4xl mb-4 text-gray-400">👨‍💼</div>
            <h4 class="text-lg font-semibold text-gray-800 mb-2">Internship Icon</h4>
            <p class="text-gray-600">Free Internship<br>If you choose the internship, you'll get hands-on experience in the field with a free internship placement.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center border border-gray-200">
            <div class="text-4xl mb-4 text-gray-400">🎙️</div>
            <h4 class="text-lg font-semibold text-gray-800 mb-2">Interview Icon</h4>
            <p class="text-gray-600">Mock Interviews<br>Prepare for the real-world job market with mock interviews conducted by industry experts.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center border border-gray-200">
            <div class="text-4xl mb-4 text-gray-400">🎓</div>
            <h4 class="text-lg font-semibold text-gray-800 mb-2">Certificate Icon</h4>
            <p class="text-gray-600">ISO Certified & ACITE Approved<br>Get a certificate that is ISO certified and ACITE approved, recognized globally.</p>
        </div>
    </div>
</div>

<!-- Key Features Section -->
<div class="container mx-auto px-5 py-10 max-w-7xl bg-white">
    <h2 class="text-3xl font-bold mb-6 text-gray-900">Key Features</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @if($course_details && is_array($course_details->key_features) && count($course_details->key_features) > 0)
            @foreach($course_details->key_features as $feature)
                <div class="bg-white p-6 rounded-lg shadow-md text-center border border-gray-200">
                    <div class="text-3xl mb-4 text-gray-400">
                        @if(!empty($feature['icon']) && preg_match('/^(fas|far|fal|fad|fab) fa-/', $feature['icon']))
                            <i class="{{ $feature['icon'] }}"></i>
                        @elseif(!empty($feature['icon']))
                            {!! $feature['icon'] !!}
                        @else
                            <i class="fas fa-question"></i>
                        @endif
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $feature['topic'] ?? 'NA' }}</h4>
                    <p class="text-gray-600">{{ $feature['description'] ?? 'NA' }}</p>
                </div>
            @endforeach
        @else
            <p class="text-gray-600 text-center col-span-full">NA</p>
        @endif
    </div>
</div>

<!-- Curriculum and Demo Syllabus Tabs Section -->
<div class="container mx-auto px-4 py-12 max-w-7xl" id="curriculum-tabs">
    <div class="course-container bg-white rounded-xl shadow-lg p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
            <div class="max-w-2xl">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">Course Curriculum & Demo Syllabus</h2>
                <p class="text-gray-600 text-base leading-relaxed">
                    This online master’s course is designed to empower working professionals. Explore a multi-domain curriculum that encourages learners to carve their own paths to success.
                </p>
            </div>
            <a href="#" class="bg-teal-700 text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-teal-800 transition duration-300 shadow-sm">
                Download Curriculum
            </a>
        </div>
        <!-- Tabs -->
        <div x-data="{ activeTab: 'demo' }" class="mb-6">
            <div class="flex border-b border-gray-200">
                <button 
                    x-on:click="activeTab = 'demo'" 
                    :class="{ 'tab-active': activeTab === 'demo' }" 
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none"
                >
                    Demo Syllabus
                </button>
                <button 
                    x-on:click="activeTab = 'curriculum'" 
                    :class="{ 'tab-active': activeTab === 'curriculum' }" 
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none"
                >
                    Course Curriculum
                </button>
            </div>
            <!-- Demo Syllabus Content -->
            <div x-show="activeTab === 'demo'" class="mt-6">
                <div class="text-xl font-semibold text-gray-800 mb-6 border-b-2 border-teal-100 pb-3">
                    Demo Syllabus Modules
                </div>
                @if($course_details && is_array($course_details->demo_syllabus) && count($course_details->demo_syllabus) > 0)
                    @forelse($course_details->demo_syllabus as $index => $module)
                        <div class="accordion-item mb-4" x-data="{ open: {{ $index < 2 ? 'true' : 'false' }} }">
                            <div class="accordion-title flex justify-between items-center p-5 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition duration-200" role="button" @click="open = !open">
                                <div class="flex items-center space-x-4">
                                    <span class="bg-teal-100 text-teal-800 text-sm font-medium px-4 py-1.5 rounded-sm">
                                        Module {{ $module['module_number'] ?? 'NA' }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $module['title'] ?? 'NA' }}</h3>
                                </div>
                                <svg class="chevron w-6 h-6 text-gray-500 transform transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="accordion-content p-6 bg-white rounded-b-lg shadow-sm border border-gray-100" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-y-0" x-transition:enter-end="opacity-100 transform scale-y-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-y-100" x-transition:leave-end="opacity-0 transform scale-y-0">
                                <div class="flex items-center space-x-3 text-gray-600 mb-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $module['duration'] ?? 'NA' }}</span>
                                </div>
                                <p class="text-gray-600 text-base leading-relaxed mb-6">{{ $module['description'] ?? 'NA' }}</p>
                                <ul class="space-y-4">
                                    @if(is_array($module['topics']) && count($module['topics']) > 0)
                                        @foreach($module['topics'] as $topic)
                                            <li class="text-gray-700">
                                                <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $topic['category'] ?? 'NA' }}</h4>
                                                <ul class="ml-5 mt-2 space-y-2 list-disc">
                                                    @if(is_array($topic['subtopics']) && count($topic['subtopics']) > 0)
                                                        @foreach($topic['subtopics'] as $subtopic)
                                                            <li class="text-gray-600 text-sm">{{ trim($subtopic) ?? 'NA' }}</li>
                                                        @endforeach
                                                    @else
                                                        <li class="text-gray-600 text-sm">NA</li>
                                                    @endif
                                                </ul>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-gray-600 text-sm">NA</li>
                                    @endif
                                </ul>
                                @php
                                    $videoUrl = $module['video_url'] ?? config('app.demo_video_fallback_url');
                                @endphp
                                @if($videoUrl)
                                    <div class="mt-6 text-right">
                                        <a href="{{ $videoUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#ff7300] to-[#ff4500] rounded-lg shadow hover:shadow-lg transition">
                                            <i class="fas fa-play mr-2"></i>
                                            Watch Demo Video
                                        </a>
                                    </div>
                                @endif
                                @if(auth()->check() && auth()->user()->role === 1)
                                    <div class="mt-2 text-right">
                                        <button type="button" onclick="openDemoVideoModal('course', {{ $course_details->id }}, {{ $index }}, '{{ $module['video_url'] ?? '' }}')" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                            Update YouTube Demo
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 text-center py-8">NA</p>
                    @endforelse
                @else
                    <p class="text-gray-600 text-center py-8">NA</p>
                @endif
            </div>
            <!-- Course Curriculum Content -->
            <div x-show="activeTab === 'curriculum'" class="mt-6">
                <div class="text-xl font-semibold text-gray-800 mb-6 border-b-2 border-teal-100 pb-3">
                    Course Curriculum Modules
                </div>
                @if($course_details && is_array($course_details->course_curriculum) && count($course_details->course_curriculum) > 0)
                    @forelse($course_details->course_curriculum as $index => $module)
                        <div class="accordion-item mb-4" x-data="{ open: {{ $index < 2 ? 'true' : 'false' }} }">
                            <div class="accordion-title flex justify-between items-center p-5 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition duration-200" role="button" @click="open = !open">
                                <div class="flex items-center space-x-4">
                                    <span class="bg-teal-100 text-teal-800 text-sm font-medium px-4 py-1.5 rounded-sm">
                                        Module {{ $module['module_number'] ?? 'NA' }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $module['title'] ?? 'NA' }}</h3>
                                </div>
                                <svg class="chevron w-6 h-6 text-gray-500 transform transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="accordion-content p-6 bg-white rounded-b-lg shadow-sm border border-gray-100" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-y-0" x-transition:enter-end="opacity-100 transform scale-y-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-y-100" x-transition:leave-end="opacity-0 transform scale-y-0">
                                <div class="flex items-center space-x-3 text-gray-600 mb-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $module['duration'] ?? 'NA' }}</span>
                                </div>
                                <p class="text-gray-600 text-base leading-relaxed mb-6">{{ $module['description'] ?? 'NA' }}</p>
                                <ul class="space-y-4">
                                    @if(is_array($module['topics']) && count($module['topics']) > 0)
                                        @foreach($module['topics'] as $topic)
                                            <li class="text-gray-700">
                                                <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $topic['category'] ?? 'NA' }}</h4>
                                                <ul class="ml-5 mt-2 space-y-2 list-disc">
                                                    @if(is_array($topic['subtopics']) && count($topic['subtopics']) > 0)
                                                        @foreach($topic['subtopics'] as $subtopic)
                                                            <li class="text-gray-600 text-sm">{{ trim($subtopic) ?? 'NA' }}</li>
                                                        @endforeach
                                                    @else
                                                        <li class="text-gray-600 text-sm">NA</li>
                                                    @endif
                                                </ul>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-gray-600 text-sm">NA</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 text-center py-8">NA</p>
                    @endforelse
                @else
                    <p class="text-gray-600 text-center py-8">NA</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Course Instructor Section -->
<div class="container mx-auto px-5 py-10 max-w-7xl" id="instructors">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-5">
        Course <span class="text-orange-500">Instructor</span>
    </h2>
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @if($course_details && is_array($course_details->instructor_ids) && count($course_details->instructor_ids) > 0)
                @foreach($course_details->instructor_ids as $instructor)
                    @php
                        $teacher = \App\Models\User::find($instructor);
                    @endphp
                    <div class="swiper-slide">
                        <div class="bg-white p-5 rounded-lg shadow-md text-center">
                            <img src="https://via.placeholder.com/150" alt="Instructor" class="w-24 h-24 rounded-full mx-auto mb-3">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $teacher->name ?? 'NA' }}</h3>
                            <p class="text-sm text-gray-600 flex items-center justify-center gap-1 mt-1">
                                <i class="fas fa-clock text-orange-500"></i>
                                1600+ hours taught
                            </p>
                            <p class="text-sm text-gray-600 mt-1">Courses | teach</p>
                            <p class="text-sm text-gray-600">Web Development</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="swiper-slide">
                    <div class="bg-white p-5 rounded-lg shadow-md text-center">
                        <img src="https://via.placeholder.com/150" alt="Instructor" class="w-24 h-24 rounded-full mx-auto mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">NA</h3>
                        <p class="text-sm text-gray-600 flex items-center justify-center gap-1 mt-1">
                            <i class="fas fa-clock text-orange-500"></i>
                            NA
                        </p>
                        <p class="text-sm text-gray-600 mt-1">NA</p>
                        <p class="text-sm text-gray-600">NA</p>
                    </div>
                </div>
            @endif
        </div>
        <div class="swiper-pagination mt-5"></div>
    </div>
</div>

<!-- FAQs Section -->
<div class="container mx-auto px-5 py-10 max-w-7xl" id="faqs">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-5">
        Wait! I Have Some <span class="text-orange-500">Questions</span>
    </h2>
    <div x-data="{ openAccordion: null }" class="space-y-2">
        @if($course_details && is_array($course_details->faqs) && count($course_details->faqs) > 0)
            @foreach($course_details->faqs as $index => $faq)
                <div class="border border-blue-500 rounded-lg">
                    <button @click="openAccordion = openAccordion === {{ $index + 1 }} ? null : {{ $index + 1 }}" class="w-full flex justify-between items-center p-4 text-left text-gray-800 font-semibold">
                        <span>{{ $faq['question'] ?? 'NA' }}</span>
                        <i :class="openAccordion === {{ $index + 1 }} ? 'fa-minus' : 'fa-plus'" class="fas text-blue-500"></i>
                    </button>
                    <div x-show="openAccordion === {{ $index + 1 }}" x-transition class="p-4 bg-white">
                        <p class="text-gray-600">
                            {{ $faq['answer'] ?? 'NA' }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="border border-blue-500 rounded-lg">
                <button @click="openAccordion = openAccordion === 1 ? null : 1" class="w-full flex justify-between items-center p-4 text-left text-gray-800 font-semibold">
                    <span>NA</span>
                    <i :class="openAccordion === 1 ? 'fa-minus' : 'fa-plus'" class="fas text-blue-500"></i>
                </button>
                <div x-show="openAccordion === 1" x-transition class="p-4 bg-white">
                    <p class="text-gray-600">
                        NA
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Facilities Providing Section -->
<div class="container mx-auto px-5 py-10 max-w-7xl">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-5">
        Facilities <span class="text-orange-500">Providing</span>
    </h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-code text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">Coding exam</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-users text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">Mock interview</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-video text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">Recording classes</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-book text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">Materials</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-users-cog text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">GD rounds</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-question-circle text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">Spot doubts</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-chalkboard-teacher text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">LIVE classes</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-briefcase text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">Career guidance</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-hands-helping text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">Placement assistance</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
            <i class="fas fa-graduation-cap text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-gray-600">Internship courses</p>
        </div>
    </div>
</div>

<!-- Course Certificates Section -->
<div class="container mx-auto px-5 py-10 max-w-7xl">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-5">
        Course <span class="text-orange-500">Certificates</span>
    </h2>
    <div class="flex flex-col md:flex-row gap-6">
        <div class="flex-1">
            @if($course_details && is_array($course_details->certificate_description) && count($course_details->certificate_description) > 0)
                @foreach($course_details->certificate_description as $description)
                    <p class="text-gray-600 mb-4">
                        {{ $description['text'] ?? 'NA' }}
                    </p>
                @endforeach
            @else
                <p class="text-gray-600 mb-4">
                    NA
                </p>
            @endif
            @if($course_details && is_array($course_details->certifications) && count($course_details->certifications) > 0)
                <ul class="space-y-2">
                    @foreach($course_details->certifications as $certification)
                        <li class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-check text-green-500"></i>
                            {{ $certification['name'] ?? 'NA' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">
                    NA
                </p>
            @endif
        </div>
        <div class="flex-1">
            @if($course_details && $course_details->certificate_image)
                <img src="{{ asset('storage/' . $course_details->certificate_image) }}" alt="Certificate" class="w-full rounded-lg shadow-md">
            @else
                <img src="https://via.placeholder.com/600x400?text=NA" alt="No Certificate" class="w-full rounded-lg shadow-md">
            @endif
        </div>
    </div>
</div>

@php
    $course_id_detail = $course_details->course_id ?? null;
@endphp
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Initialize Swiper
    var swiper = new Swiper('.mySwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 20
            },
        },
    });

    // Batch Section JavaScript
    let batches = [];
    const courseId = "{{ $course_id_detail ?? '' }}";

    // Function to fetch batches dynamically
    async function fetchBatches() {
        const batchCardsContainer = document.getElementById('batch-cards');
        batchCardsContainer.innerHTML = '<p class="text-gray-600 text-center">Loading batches...</p>';

        if (!courseId) {
            console.error('Course ID is missing');
            batchCardsContainer.innerHTML = '<p class="text-red-600 text-center">Error: Course ID is missing. Please ensure the course is correctly configured.</p>';
            return;
        }

        try {
            console.log('Fetching batches for course ID:', courseId);
            const response = await fetch(`/api/batches?id=${encodeURIComponent(courseId)}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            console.log('API Response Status:', response.status);

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('API Error:', errorData);
                let errorMessage = 'Failed to load batches. Please try again later.';
                if (response.status === 404) {
                    errorMessage = 'No batches found for this course.';
                } else if (response.status === 500) {
                    errorMessage = 'Server error occurred while fetching batches.';
                } else if (errorData.error) {
                    errorMessage = errorData.error;
                }
                batchCardsContainer.innerHTML = `<p class="text-red-600 text-center">${errorMessage}</p>`;
                return;
            }

            const allBatches = await response.json();
            console.log('All Batches:', allBatches);

            if (!Array.isArray(allBatches)) {
                console.error('Invalid response format: Expected an array, got:', allBatches);
                batchCardsContainer.innerHTML = '<p class="text-red-600 text-center">Error: Invalid batch data format from server.</p>';
                return;
            }

            batches = allBatches.filter(batch => {
                const requiredFields = ['id', 'date', 'price', 'slotsAvailable', 'slotsFilled', 'status', 'startDate'];
                return requiredFields.every(field => batch[field] !== undefined && batch[field] !== null);
            });
            console.log('Filtered Batches:', batches);

            if (batches.length === 0) {
                batchCardsContainer.innerHTML = '<p class="text-gray-600 text-center">No valid batches available for this course. Please check back later or contact support.</p>';
                return;
            }

            renderBatchCards(batches);

            // Select the first batch by default
            if (batches.length > 0) {
                selectBatch(batches[0].id);
            }

        } catch (error) {
            console.error('Error fetching batches:', error);
            batchCardsContainer.innerHTML = `<p class="text-red-600 text-center">Failed to load batches: ${error.message || 'Network or server error'}. Please check your connection and try again.</p>`;
        }
    }

    // Function to render batch cards dynamically
    function renderBatchCards(batches) {
        const batchCardsContainer = document.getElementById('batch-cards');
        batchCardsContainer.innerHTML = '';

        if (batches.length === 0) {
            batchCardsContainer.innerHTML = '<p class="text-gray-600 text-center">No batches available for this course. Please check back later or contact support.</p>';
            return;
        }

        batches.forEach((batch, index) => {
            try {
                const batchCard = document.createElement('div');
                batchCard.classList.add('border', 'rounded-lg', 'p-4', 'text-center', 'relative', 'max-w-xs', 'batch-card');
                batchCard.dataset.batchId = String(batch.id);

                // Highlight the first batch by default
                if (index === 0) {
                    batchCard.classList.add('active', 'border-orange-500');
                } else if (batch.status === 'started') {
                    batchCard.classList.add('active', 'border-orange-500');
                } else if (batch.status === 'soon') {
                    batchCard.classList.add('soon', 'border-gray-300');
                } else {
                    batchCard.classList.add('border-gray-300');
                }

                const classSchedule = [batch.days, batch.duration].filter(Boolean).join(' | ') || 'Schedule to be announced';
                const timeSlot = batch.timeSlot || batch.time_slot || 'Timing will be shared soon';
                const mode = batch.mode || 'Online';
                batchCard.addEventListener('click', () => selectBatch(batch.id));

                const cardContent = `
                    <div class="batch-date text-sm text-gray-600 font-semibold">${batch.date}</div>
                    <div class="batch-details">
                        <p class="text-sm text-gray-600 mt-1">${
                            batch.status === 'started' ? 'Batch Started' :
                            batch.status === 'soon' ? 'Starting Soon' : 'Upcoming'
                        }</p>
                        <p class="text-sm text-gray-600">${mode}</p>
                        <p class="text-sm text-gray-600">${classSchedule}</p>
                        <p class="text-sm text-gray-600 mt-2">${timeSlot}</p>
                    </div>
                `;
                batchCard.innerHTML = cardContent;
                batchCardsContainer.appendChild(batchCard);
            } catch (error) {
                console.error(`Error rendering batch card for batch ${batch.id}:`, error);
            }
        });
    }

    // Function to update batch details
    function updateBatchDetails(batch) {
        try {
            console.log('Updating batch details:', batch);

            // Update price (total price after discount)
            const discount = batch.discount_info ? parseFloat(batch.discount_info.replace('%', '')) : 0;
            const discountedPrice = batch.price - (batch.price * (discount / 100));
            document.getElementById('batch-price').innerText = `₹${discountedPrice.toLocaleString('en-IN')}`;
            // document.getElementById('batch-price').innerText = `₹${batch.price.toLocaleString('en-IN')}`;

            // Update original price
            document.getElementById('batch-original-price').innerText = batch.price
                ? `₹${batch.price.toLocaleString('en-IN')}`
                : `₹${batch.price.toLocaleString('en-IN')}`;

            // Update discount info (remove timer for upcoming batches)
            const discountInfo = batch.discount_info;
            const discountText = discountInfo
                ? `${discountInfo}${String(discountInfo).includes('%') ? '' : '%'} OFF`
                : 'No discount available';
            document.getElementById('batch-discount').innerText = discountText;

            // Update enroll button
            const enrollStartDate = new Date(batch.startDate);
            enrollStartDate.setDate(enrollStartDate.getDate() - 25);
            const now = new Date();
            const enrollButton = document.getElementById('batch-enroll-button');
            enrollButton.disabled = now < enrollStartDate;
            enrollButton.innerText = now >= enrollStartDate
                ? (batch.status === 'started' ? 'Request to Join' : 'Enroll Now')
                : `Registration starts on ${enrollStartDate.toLocaleDateString('en-IN')}`;

            // Update slots and mode
            document.getElementById('available-slots').innerText = batch.slotsAvailable;
            document.getElementById('filled-slots').innerText = batch.slotsFilled;
            document.getElementById('mode-of-teaching').innerText = batch.mode || 'Online';

            // Store selected batch
            window.selectedBatch = batch;
        } catch (error) {
            console.error('Error updating batch details:', error);
            document.getElementById('batch-price').innerText = 'Error';
        }
    }

    // Function to select batch
    function selectBatch(batchId) {
        const selectedBatch = batches.find(batch => String(batch.id) === String(batchId));
        if (!selectedBatch) {
            console.error('Batch not found:', batchId);
            return;
        }
        updateBatchDetails(selectedBatch);
        document.querySelectorAll('.batch-card').forEach(card => {
            card.classList.remove('active', 'border-orange-500');
            card.classList.add('border-gray-300');
            if (card.dataset.batchId === String(batchId)) {
                card.classList.add('active', 'border-orange-500');
                card.classList.remove('border-gray-300');
            }
        });
    }

    // Function to calculate countdown (kept for reference, not used for upcoming batches)
    function calculateCountdown(startDate) {
        try {
            const now = new Date();
            const start = new Date(startDate);
            if (isNaN(start.getTime())) {
                return 'Invalid Date';
            }
            const timeDiff = start - now;
            if (timeDiff <= 0) return 'Expired';
            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
            return `${days}d ${hours}h ${minutes}m ${seconds}s`;
        } catch (error) {
            // console.error('Error calculating countdown:', error);
            return 'Error';
        }
    }

    // Handle Enroll Now button click
    document.getElementById('batch-enroll-button').addEventListener('click', async function() {
        if (!window.selectedBatch) {
            alert('Please select a batch first');
            return;
        }
        const batch = window.selectedBatch;
        console.log('Selected batch:', batch);
        try {
            const response = await fetch('/store-batch-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    batch_id: batch.id
                })
            });
            console.log('Response status:', response.status);
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to store batch data');
            }
            const data = await response.json();
            console.log('Success:', data);
            window.location.href = '/register';
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to proceed to registration: ' + error.message);
        }
    });

    // Fetch batches on page load
    document.addEventListener('DOMContentLoaded', fetchBatches);
</script>

<script>
    // Accordion toggle for curriculum (unchanged)
    function toggleAccordion(element) {
        const content = element.nextElementSibling;
        const chevron = element.querySelector('.chevron');
        content.classList.toggle('active');
        chevron.classList.toggle('active');
    }

    // Ensure the first two modules are expanded by default
    document.querySelectorAll('.accordion-title').forEach((title, index) => {
        if (index < 2) {
            title.nextElementSibling.classList.add('active');
            title.querySelector('.chevron').classList.add('active');
        }
    });
</script>
@include('partials.demo_video_uploader')
@endsection
