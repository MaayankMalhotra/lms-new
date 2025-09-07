@extends('website.layouts.app')

@section('title', 'Home Page')

@section('content')
{{-- =========================================================
     Loader (shows on page load) + User Data Popup (after load)
   ========================================================= --}}
<!-- Loader (Education themed: flipping book) -->
<div id="page-loader" class="fixed inset-0 bg-gradient-to-r from-purple-600 to-blue-600 flex items-center justify-center z-[9999]">
  <div class="edu-loader text-center text-white">
    <div class="book mx-auto">
      <span class="page"></span>
      <span class="page"></span>
      <span class="page"></span>
    </div>
    <div class="mt-4 text-lg font-semibold">Welcome To Thinkchamp...</div>
    <div class="typing mt-1 text-sm opacity-90">
      Loading<span class="dot">.</span><span class="dot">.</span><span class="dot">.</span>
    </div>
  </div>
</div>


<!-- User Popup -->
<div id="user-popup" class="fixed inset-0 bg-black/60 flex items-center justify-center hidden z-[9998]">
    <div class="bg-white rounded-xl shadow-lg w-[92%] max-w-[420px] p-6 relative animate-fade-in">
        <button id="close-popup" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl leading-none">&times;</button>
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">👋 Welcome!</h2>
        <p class="text-gray-600 text-center mb-6">Enter your details to continue your journey 🚀</p>
<!-- Popup Form -->
<form id="popupForm"  method="POST">
    @csrf
    <div class="mb-4">
        <label class="block text-sm font-medium">Full Name</label>
        <input type="text" name="name" class="w-full mt-1 p-3 border rounded-lg" required />
    </div>
    <div class="mb-4">
        <label class="block text-sm font-medium">Email Address</label>
        <input type="email" name="email" class="w-full mt-1 p-3 border rounded-lg" />
    </div>
    <div class="mb-6">
        <label class="block text-sm font-medium">Phone Number</label>
        <input type="tel" name="phone" class="w-full mt-1 p-3 border rounded-lg" />
    </div>
    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-bold">Submit</button>
</form>


    </div>
</div>

{{-- =========================================================
     Page Styles (scoped)
   ========================================================= --}}
<!-- Swiper CSS (if your layout already includes it, you can remove the next line) -->
<link rel="stylesheet" href="https://unpkg.com/swiper@9/swiper-bundle.min.css"/>

<style>

  /* Small utilities used below */
  .hide-scrollbar::-webkit-scrollbar{display:none;}
  .hide-scrollbar{-ms-overflow-style:none;scrollbar-width:none;}

  /* Intro anims */
  @keyframes fade-in-up {0%{opacity:0;transform:translateY(20px)}100%{opacity:1;transform:translateY(0)}}
  .animate-fade-in-up{animation:fade-in-up .8s ease-out forwards}
  .delay-100{animation-delay:.2s}
  @keyframes fade-in {0%{opacity:0;transform:scale(.96)}100%{opacity:1;transform:scale(1)}}
  .animate-fade-in{animation:fade-in .25s ease-out forwards}

  /* Swiper nav tweaks */
  .swiper-button-prev::after,.swiper-button-next::after{content:'';display:none}
  .swiper-button-prev,.swiper-button-next{position:static!important;margin-top:0!important;transform:translateY(0)!important;display:flex;align-items:center;justify-content:center;background:none!important}
  .swiper-pagination-bullet-active{transform:scale(1.15);}

  /* Optional: make bullets prettier */
  .swiper-pagination-bullet{width:.5rem;height:.5rem;background:#cbd5e1;opacity:1}

  /* Hero arrow icons sizing if any */
  .swiper-button-next svg,.swiper-button-prev svg{width:100%;height:64%;object-fit:contain;transform-origin:center}
  /* Education loader: flipping book + typing dots */
.edu-loader .book{
  width: 88px; height: 64px;
  position: relative;
  border: 3px solid #fff; border-radius: 10px;
  overflow: hidden; perspective: 600px;
  background: rgba(255,255,255,0.06);
  box-shadow: 0 8px 30px rgba(0,0,0,.15), inset 0 0 0 1px rgba(255,255,255,.15);
}
.edu-loader .page{
  position: absolute; top: 6px; bottom: 6px; left: 8px;
  width: calc(100% - 16px);
  background: #fff; border-radius: 4px;
  transform-origin: left center;
  animation: pageFlip 1.1s ease-in-out infinite;
  box-shadow: 0 2px 8px rgba(0,0,0,.08);
}
.edu-loader .page:nth-child(2){ animation-delay: .15s; }
.edu-loader .page:nth-child(3){ animation-delay: .30s; }

@keyframes pageFlip{
  0%   { transform: rotateY(0deg);   opacity: 1; }
  40%  { transform: rotateY(-150deg); opacity: .95; }
  60%  { transform: rotateY(-180deg); opacity: 0.9; }
  100% { transform: rotateY(-180deg); opacity: 0.9; }
}

/* Typing dots under the caption */
.edu-loader .typing .dot{
  display: inline-block; width: .35em; text-align: center;
  animation: blink 1.2s infinite;
}
.edu-loader .typing .dot:nth-child(2){ animation-delay: .15s; }
.edu-loader .typing .dot:nth-child(3){ animation-delay: .30s; }

@keyframes blink{
  0%, 20% { opacity: 0; transform: translateY(0); }
  30%     { opacity: 1; transform: translateY(-1px); }
  60%,100%{ opacity: 0; transform: translateY(0); }
}

/* ===== Loader background (education-tech) ===== */
#page-loader{
  /* Fallback color in case image fails */
  background-color:#1b1743;

  /* Education/tech themed background image */
  background-image:
    image-set(
      url("https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=1920&q=80") 1x,
      url("https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=2880&q=80") 2x
    );

  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed; /* subtle parallax while loading */
  position: fixed; /* your HTML already has fixed + inset-0, this just reinforces */
  inset: 0;
}

/* Mobile-friendly alternative image */
@media (max-width: 640px){
  #page-loader{
    background-image:
      url("https://images.unsplash.com/photo-1523246191331-3ad3de2a7d86?auto=format&fit=crop&w=1200&q=80");
  }
}

/* Soft gradient overlay for better text/icon contrast */
#page-loader::before{
  content:"";
  position:absolute;
  inset:0;
  /* purple/indigo glassy overlay */
  background:
    radial-gradient(120% 100% at 10% 10%, rgba(76,29,149,.55) 0%, rgba(30,27,75,.75) 55%, rgba(17,24,39,.85) 100%),
    linear-gradient( to bottom right, rgba(88,28,135,.35), rgba(37,99,235,.25) );
  pointer-events:none;
  z-index:1;
}

/* Make sure the loader content stays above the overlay */
#page-loader .edu-loader{
  position:relative;
  z-index:2;
}


</style>

{{-- =========================================================
     HERO / PLACEMENTS
   ========================================================= --}}
<section class="relative bg-gradient-to-r from-[#161c44] to-[#0c3c7c] text-white overflow-hidden">
    <div class="container mx-auto px-4 py-16 md:py-24">
        <div class="pt-10 flex flex-col md:flex-row items-center gap-8">
            <!-- Left Content -->
            <div class="md:w-1/2">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 animate-fade-in-up">
                    Change the world <br>
                    with <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Test</span>
                </h1>
                <p class="text-lg md:text-xl mb-8 opacity-0 animate-fade-in-up delay-100">
                    Learn coding using 500+ courses, practice problems, and AI. Become
                    job ready <strong class="font-semibold">10x faster</strong>.
                </p>
                <div class="text-center">
                    <a href="#" class="inline-block bg-gradient-to-r from-orange-500 to-amber-400 text-white px-8 py-4 rounded-full text-lg font-bold hover:scale-105 transition-transform duration-300 shadow-lg hover:shadow-xl hover:shadow-orange-500/30">
                        Start your coding journey
                    </a>
                </div>
            </div>
            <!-- Right Content -->
            <div class="md:w-1/2 w-full mt-8 md:mt-0">
                <div class="grid gap-16 md:gap-6 md:grid-cols-2">
                    @foreach($placements as $placement)
                        <div class="relative bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 group">
                            <div class="flex flex-col">
                                <div class="mb-16">
                                    <h3 class="text-black font-bold text-lg">{{ $placement->name }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $placement->qualification }}</p>
                                </div>
                                <img src="{{ asset('storage/' . $placement->image) }}" alt="{{ $placement->name }}" class="absolute w-24 h-24 bg-white object-cover rounded-lg -top-12 right-4 shadow-md">
                                <div class="space-y-2">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $placement->tags) as $tag)
                                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-3 py-1 rounded-full">
                                                {{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <p class="text-black text-sm"><strong>🏢 Placed At:</strong> {{ $placement->company }}</p>
                                    <p class="text-black text-sm"><strong>💰 Package:</strong> ₹{{ $placement->package }}</p>
                                    <p class="text-red-500 text-sm font-semibold">🔥 Limited Seats Available</p>
                                </div>
                                <a href="https://wa.me/919876543210?text=Hi%20I%20am%20interested%20in%20your%20courses%20😊" 
   target="_blank" 
   class="mt-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-center py-2 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
   REQUEST CALLBACK
</a>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Bottom Curve -->
    <div class="absolute bottom-0 left-0 w-full">
        <svg viewBox="0 0 1440 120" class="fill-current text-[#161c44]">
            <path d="M0,240 L720,300 L1440,250 L1440,320 L0,320Z"></path>
        </svg>
    </div>
</section>

{{-- =========================================================
     INSTANTLY & INTERACTIVELY (Tabs: Courses/Upcoming/Internships)
   ========================================================= --}}
<section class="bg-[#f8eeea] py-6">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl lg:text-5xl font-bold text-center text-[#2c0b57]">
            Instantly & <span class="text-[#ffb31a]">Interactively </span>
        </h2>
        <p class="text-lg text-center text-gray-600 mt-4">
            Learn from carefully curated learning paths with up-to-date interactive courses.
        </p>

        <!-- Tabs -->
        <div class="mt-8 overflow-x-auto hide-scrollbar">
            <div class="inline-flex w-full md:w-auto md:justify-center">
                <ul class="flex space-x-4 whitespace-nowrap">
                    <li>
                        <a href="#studyCourses" class="nav-tab text-base md:text-lg font-bold hover:text-[#ff7300] transition-all duration-300 data-[active=true]:text-white data-[active=true]:bg-[#ff7300] data-[active=true]:py-2 data-[active=true]:px-4 data-[active=true]:rounded-xl" data-tab="studyCourses" data-active="true">Courses</a>
                    </li>
                    <li>
                        <a href="#studyUpcoming" class="nav-tab text-base md:text-lg font-bold hover:text-[#ff7300] transition-all duration-300 data-[active=true]:text-white data-[active=true]:bg-[#ff7300] data-[active=true]:py-2 data-[active=true]:px-4 data-[active=true]:rounded-xl" data-tab="studyUpcoming">Upcoming Courses</a>
                    </li>
                    <li>
                        <a href="#studyInternships" class="nav-tab text-base md:text-lg font-bold hover:text-[#ff7300] transition-all duration-300 data-[active=true]:text-white data-[active=true]:bg-[#ff7300] data-[active=true]:py-2 data-[active=true]:px-4 data-[active=true]:rounded-xl" data-tab="studyInternships">Internships</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="mt-12">
            <!-- Courses Tab -->
            <div id="studyCourses" class="tab-pane">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="study-box bg-white rounded-lg shadow-md p-6 text-center transition-all hover:scale-105 hover:shadow-lg">
                            <h3 class="text-xl font-bold text-[#2c0b57]">{{ $course->title }}</h3>
                            <img src="{{ $course->image }}" class="w-12 h-12 mx-auto my-4 transition-transform hover:[transform:rotateY(180deg)]" alt="{{ $course->title }}" />
                            <div class="text-gray-600 space-y-2">
                                <p><i class="far fa-clock"></i> Duration: <span class="font-bold">{{ $course->duration }}</span></p>
                                <p><i class="fas fa-users"></i> <span class="font-bold">{{ $course->placed_count }}+ Placed</span></p>
                                <p>⭐ {{ $course->rating }} ({{ $course->student_count }}+ students)</p>
                            </div>
                            <button class="mt-4 w-full bg-[#ff7b00] text-white py-2 rounded-lg font-bold hover:bg-[#ff5500] transition-all">Register Now</button>
                        </div>
                    @endforeach
                </div>
<div id="viewAllCourses" class="text-center mt-8">
    <a href="{{ route('website.course') }}"
       class="inline-block bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg hover:shadow-orange-300 transition-all">
        View All Courses
    </a>
</div>

            </div>

            <!-- Upcoming Courses Tab -->
            <div id="studyUpcoming" class="tab-pane hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($upcomingCourses as $upcomingCourse)
                        <div class="study-box bg-white rounded-lg shadow-md p-6 text-center transition-all hover:scale-105 hover:shadow-lg">
                            <h3 class="text-xl font-bold text-[#2c0b57]">{{ $upcomingCourse->title }}</h3>
                            <img src="{{ $upcomingCourse->image }}" class="w-12 h-12 mx-auto my-4 transition-transform hover:[transform:rotateY(180deg)]" alt="{{ $upcomingCourse->title }}" />
                            <p class="text-gray-600"><i class="fas fa-calendar"></i> Start Date: <span class="font-bold">{{ \Carbon\Carbon::parse($upcomingCourse->start_date)->format('F d, Y') }}</span></p>
                            <p class="text-green-600 mt-2"><i class="fas fa-check-circle"></i> {{ $upcomingCourse->slots_open ? 'Slots Open' : 'Slots Closed' }}</p>
                            <button class="mt-4 w-full bg-[#ff7b00] text-white py-2 rounded-lg font-bold hover:bg-[#ff5500] transition-all">Pre-register Now</button>
                        </div>
                    @endforeach
                </div>
                <div id="viewAllUpcoming" class="text-center mt-8 hidden">
                    <a href="#" class="inline-block bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg hover:shadow-orange-300 transition-all">View All Upcoming Courses</a>
                </div>
            </div>

            <!-- Internships Tab -->
            <div id="studyInternships" class="tab-pane hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($internships as $internship)
                        <div class="study-box bg-white rounded-lg shadow-md p-6 text-center transition-all hover:scale-105 hover:shadow-lg">
                            <h4 class="text-xl font-bold text-[#2c0b57]">{{ $internship->title }}</h4>
                            <img src="{{ $internship->image }}" class="w-12 h-12 mx-auto my-4 transition-transform hover:[transform:rotateY(180deg)]" alt="{{ $internship->title }}" />
                            <div class="text-gray-600 space-y-2">
                                <div class="flex justify-around">
                                    <span><i class="far fa-clock"></i> {{ $internship->duration }}</span>
                                    <span><i class="fas fa-tasks"></i> {{ $internship->project_count }} Projects</span>
                                </div>
                                <p>⭐ {{ $internship->rating }} ({{ $internship->applicant_count }}+ applicants)</p>
                            </div>
                            <span class="inline-block bg-[#ffd700] text-[#333] px-3 py-1 rounded-full text-sm font-bold mt-2">{{ $internship->certification }}</span>
                            <button class="mt-4 w-full bg-[#ff7b00] text-white py-2 rounded-lg font-bold hover:bg-[#ff5500] transition-all">Register Now</button>
                        </div>
                    @endforeach
                </div>
                <div id="viewAllInternships" class="text-center mt-8 hidden">
                    <a href="internship_coures.html" class="inline-block bg-gradient-to-r from-[#ff7300] to-[#ff4500] text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg hover:shadow-orange-300 transition-all">View All Internships</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================
     TRUST / METRICS
   ========================================================= --}}
<section class="py-6 md:py-10 px-6 lg:px-16">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 text-center">
            <span class="text-sm font-semibold text-blue-600 tracking-wide">TRUSTED BY INDUSTRY LEADERS</span>
            <h2 class="text-3xl md:text-5xl font-extrabold mt-2">Globally Recognized Excellence</h2>
            <div class="flex justify-center space-x-10 overflow-x-auto py-6">
                <img src="./images/aicte.png" alt="AICTE" class="h-12 lg:h-20 object-contain opacity-80 hover:opacity-100 transition rounded">
                <img src="./images/iso.jpg" alt="ISO Certified" class="h-12 lg:h-20 object-contain opacity-80 hover:opacity-100 transition rounded">
                <img src="./images/msme.png" alt="MSME" class="h-12 lg:h-20 object-contain opacity-80 hover:opacity-100 transition rounded">
            </div>
        </div>
        <div class="grid lg:grid-cols-2 gap-8 items-center">
            <div class="relative group">
                <div class="absolute -inset-4 bg-gradient-to-r from-blue-200 to-purple-200 rounded-2xl blur-lg opacity-40 group-hover:opacity-60 transition"></div>
                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/0f938c1f3d199500f30705cd757a025543cd56a6a374094b8fbbaa4f83e5a0b0" alt="Student Success" class="relative w-full rounded-2xl shadow-2xl">
            </div>
            <div class="space-y-4 md:space-y-6">
                <h3 class="text-3xl md:text-5xl font-bold leading-tight">
                    Shaping the Future of <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Tech Education</span>
                </h3>
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                    Join 10,000+ students who transformed their careers through our immersive learning programs.
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100">
                        <div class="text-4xl font-bold text-blue-600">300+</div>
                        <p class="text-gray-600 font-semibold">Hiring Partners</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100">
                        <div class="text-4xl font-bold text-purple-600">5.5K+</div>
                        <p class="text-gray-600 font-semibold">Career Transitions</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100">
                        <div class="text-4xl font-bold text-blue-600">500+</div>
                        <p class="text-gray-600 font-semibold">Live Projects</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100">
                        <div class="text-4xl font-bold text-purple-600">98%</div>
                        <p class="text-gray-600 font-semibold">Success Rate</p>
                    </div>
                </div>
                <div class="mt-12 text-center">
                    <a href="https://wa.me/919876543210?text=Hi%20I%20want%20to%20start%20a%20free%20trial%20🚀" 
   target="_blank"
   class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-purple-600 text-white md:px-8 px-6 py-3 md:py-5 rounded-full text-lg font-bold hover:shadow-xl hover:scale-105 transition-all">
    Start Free Trial Today
    <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
    </svg>
</a>

                </div>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================
     ENROLLMENT PROCESS
   ========================================================= --}}
<section class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 py-6 lg:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto text-center">
        <span class="inline-block px-4 py-1 bg-blue-100 text-blue-700 text-sm font-semibold tracking-wider uppercase rounded-full mb-4">Enrollment Process</span>
        <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mt-3 mb-12 leading-tight">
            Start Your Child's Coding Journey
            <span class="block mt-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">In 4 Simple Steps</span>
        </h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-8 mt-8 relative">
        <div class="hidden lg:block absolute top-1/2 left-0 w-full">
            <div class="h-1 bg-gradient-to-r from-blue-200 via-indigo-300 to-purple-200 rounded-full animate-pulse"></div>
        </div>
        <!-- Step 1 -->
        <div class="group relative bg-white p-8 rounded-3xl shadow-lg border border-blue-50 hover:border-blue-100 transform hover:-translate-y-2 transition-all duration-300">
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-blue-500 to-blue-600 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold shadow-lg group-hover:scale-110 transition-transform">1</div>
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-100 rounded-2xl rotate-6 transition-transform group-hover:rotate-12"></div>
                    <img src="https://img.freepik.com/free-vector/sign-up-concept-illustration_114360-7865.jpg" class="relative w-36 h-36 object-cover rounded-2xl shadow-md transform group-hover:scale-105" alt="Sign Up">
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">Sign Up</h3>
            <p class="text-gray-600">Begin your child's exciting journey with our free demo session!</p>
        </div>
        <!-- Step 2 -->
        <div class="group relative bg-white p-8 rounded-3xl shadow-lg border border-indigo-50 hover:border-indigo-100 transform hover:-translate-y-2 transition-all duration-300">
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold shadow-lg group-hover:scale-110">2</div>
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-indigo-100 rounded-2xl rotate-6 transition-transform group-hover:rotate-12"></div>
                    <img src="https://img.freepik.com/free-vector/online-education-concept_52683-37480.jpg" class="relative w-36 h-36 object-cover rounded-2xl shadow-md transform group-hover:scale-105" alt="Select Course">
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">Select Course</h3>
            <p class="text-gray-600">Choose the perfect course for your child's age and skill level.</p>
        </div>
        <!-- Step 3 -->
        <div class="group relative bg-white p-8 rounded-3xl shadow-lg border border-purple-50 hover:border-purple-100 transform hover:-translate-y-2 transition-all duration-300">
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-purple-500 to-purple-600 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold shadow-lg group-hover:scale-110">3</div>
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-purple-100 rounded-2xl rotate-6 transition-transform group-hover:rotate-12"></div>
                    <img src="https://img.freepik.com/free-vector/online-tutorials-concept_52683-37481.jpg" class="relative w-36 h-36 object-cover rounded-2xl shadow-md transform group-hover:scale-105" alt="Start Learning">
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">Start Learning</h3>
            <p class="text-gray-600">Pick from 200+ expert mentors at your preferred time.</p>
        </div>
        <!-- Step 4 -->
        <div class="group relative bg-white p-8 rounded-3xl shadow-lg border border-blue-50 hover:border-blue-100 transform hover:-translate-y-2 transition-all duration-300">
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-blue-500 to-blue-600 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold shadow-lg group-hover:scale-110">4</div>
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-100 rounded-2xl rotate-6 transition-transform group-hover:rotate-12"></div>
                    <img src="https://img.freepik.com/free-vector/certificate-concept-illustration_114360-5743.jpg" class="relative w-36 h-36 object-cover rounded-2xl shadow-md transform group-hover:scale-105" alt="Get Certificate">
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">Get Certified</h3>
            <p class="text-gray-600">Earn an industry-recognized certificate upon completion.</p>
        </div>
    </div>
</section>

{{-- =========================================================
     INSTRUCTORS
   ========================================================= --}}
<section class="bg-[#f8eeea] py-6 lg:py-10 px-4">
    <div class="container mx-auto">
        <h2 class="text-center text-3xl md:text-5xl font-extrabold text-gray-900 md:mb-10 mb-6 leading-tight">
            Learn to Code from our <span class="text-[#ed8610]">Instructors</span>
        </h2>
        <div class="grid lg:grid-cols-2 gap-8 items-start">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:gap-8">
                @foreach($instructors as $instructor)
                    <div class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 text-center">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-100 to-purple-100 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <img src="{{ asset('storage/' . $instructor->image) }}" alt="{{ $instructor->name }}" class="relative mx-auto rounded-full w-24 md:w-32 border-4 border-white shadow-lg">
                        </div>
                        <h4 class="mt-5 text-xl md:text-2xl font-bold text-gray-900">{{ $instructor->name }}</h4>
                        <div class="mt-3 flex justify-center items-center bg-blue-50 px-4 py-2 rounded-full mx-auto w-fit">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/02d1f6ec7bc2f9edf0e80994b3ac27cc85554083b07b37408f4abd328b683522" class="w-5 h-5 mr-2" alt="Hours">
                            <span class="text-sm font-medium text-blue-600">{{ $instructor->teaching_hours }}+ hours</span>
                        </div>
                        <p class="mt-4 text-gray-600 font-medium">{{ $instructor->specialization }}</p>
                        <div class="mt-4 flex justify-center space-x-3 opacity-80 group-hover:opacity-100 transition-opacity">
                            @if($instructor->linkedin_url)
                                <a href="{{ $instructor->linkedin_url }}" class="text-blue-500 hover:text-blue-600" aria-label="LinkedIn">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14A5 5 0 000 5v14a5 5 0 005 5h14a5 5 0 005-5V5a5 5 0 00-5-5zM8 19H5V8h3v11zM6.5 6.732A1.75 1.75 0 116.5 3.2a1.75 1.75 0 010 3.532zM20 19h-3v-5.604c0-3.368-4-3.113-4 0V19h-3V8h3v1.765C14.396 7.179 20 6.988 20 12.241V19z"/></svg>
                                </a>
                            @endif
                            @if($instructor->facebook_url)
                                <a href="{{ $instructor->facebook_url }}" class="text-purple-500 hover:text-purple-600" aria-label="Facebook">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0a12 12 0 1012 12A12.014 12.014 0 0012 0zm3 8h-1.35c-.538 0-.65.221-.65.778V10h2l-.209 2h-1.791v7h-3v-7H8v-2h2V7.692C10 6.392 10.931 5 13.029 5H15z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="md:pl-0 lg:pl-10">
                <h3 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Elite Trainer Standards</h3>
                <p class="mt-4 text-lg md:text-xl text-gray-600 leading-relaxed">We implement a 5-stage vetting process to ensure only top-tier educators join our team.</p>
                <div class="mt-8 space-y-6">
                    <div class="flex items-start p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><span class="text-blue-600 font-bold">1%</span></div>
                        <div class="ml-4"><h4 class="text-lg font-semibold text-gray-900">Rigorous Selection</h4><p class="mt-1 text-gray-600">Only top 1% of applicants make it through</p></div>
                    </div>
                    <div class="flex items-start p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                        <div class="ml-4"><h4 class="text-lg font-semibold text-gray-900">Proven Experts</h4><p class="mt-1 text-gray-600">Industry leaders from top tech companies</p></div>
                    </div>
                    <div class="flex items-start p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><span class="text-green-600 font-bold">4.9</span></div>
                        <div class="ml-4"><h4 class="text-lg font-semibold text-gray-900">Student Approved</h4><p class="mt-1 text-gray-600">Consistently top-rated teaching quality</p></div>
                    </div>
                </div>
                <div class="text-center">
                   <a href="https://wa.me/919876543210?text=Hi%20I%20am%20interested%20in%20joining%20the%20Thinkchamp%20team%20🤝" 
   target="_blank"
   class="mt-8 inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition-all hover:scale-[1.02]">
    <span>Join Our Team</span>
    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
    </svg>
</a>

                </div>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================
     AWARDS STRIP
   ========================================================= --}}
<section class="bg-cover bg-center py-3 md:py-6" style="background-image: url('https://img.freepik.com/free-vector/realistic-navy-blue-glitter-background_23-2150020453.jpg');">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/3 mt-4 text-white">
                <h2 class="text-2xl">Recognized as</h2>
                <h2 class="text-2xl">Best Tech Skilling EdTech</h2>
                <h2 class="text-2xl">Company of the year 2022</h2>
                <p class="mt-3">Our trainers go through a unique selection process to ensure there’s no compromise in quality of teaching kids are endowed with.</p>
            </div>
            <div class="w-full md:w-2/3">
                <div class="flex flex-wrap">
                    <div class="w-1/2 mt-4">
                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/65ef900b7499bb80cf778e3951ca7ae47f10b24e30402a24dc0ea921477b9792" alt="Award Image" class="w-full h-auto" />
                    </div>
                    <div class="w-1/2 mb-3">
                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/bcc2e01c48312dfb6ae5527985d740713afd42647672912e37e7de5a83104cb3" alt="Award Logo" class="w-full h-auto" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================
     TESTIMONIALS (Swiper)
   ========================================================= --}}
<section class="bg-amber-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12 items-center">
            <div class="order-1 lg:order-none">
                <img src="./images/testimonals.png" alt="Happy students" class="w-full h-auto rounded-xl shadow-xl border-2">
            </div>
            <div class="space-y-4">
                <div class="text-center space-y-4">
                    <h2 class="text-3xl lg:text-5xl font-bold bg-gradient-to-r from-orange-500 to-amber-600 bg-clip-text text-transparent">Testimonials</h2>
                    <p class="text-xl lg:text-2xl font-semibold text-gray-800">Our Students Are Our Strength.<br>See What They Say About Us</p>
                </div>
                <div class="swiper testimonialSwiper relative">
                    <div class="swiper-wrapper">
                        @foreach($testimonials as $testimonial)
                            <div class="swiper-slide">
                                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 relative">
                                    <p class="text-gray-600 text-lg relative z-10">{{ $testimonial->content }}</p>
                                    <div class="flex items-center gap-6 mt-8">
                                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-800">{{ $testimonial->name }}</h4>
                                            <p class="text-gray-600">{{ $testimonial->designation }}</p>
                                            <div class="flex items-center mt-2">
                                                <div class="flex text-amber-400">{!! str_repeat('★', floor($testimonial->rating)) !!}{!! str_repeat('☆', 5 - floor($testimonial->rating)) !!}</div>
                                                <span class="ml-2 text-gray-500">{{ $testimonial->rating }}/5</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-center gap-4 mt-8">
                        <div class="swiper-button-prev carousel-prev"></div>
                        <div class="swiper-pagination !relative !w-auto"></div>
                        <div class="swiper-button-next carousel-next"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
  .swiper-slide{opacity:.5;transform:scale(.97);transition:transform .3s,opacity .3s}
  .swiper-slide-active{opacity:1;transform:scale(1)}
</style>
<svg style="display:none">
    <symbol id="left-arrow" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></symbol>
    <symbol id="right-arrow" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></symbol>
</svg>

{{-- =========================================================
     FREE ACCESS (Countdown + Form)
   ========================================================= --}}
<div class="container mx-auto lg:my-10 p-8 bg-gradient-to-r from-purple-600 via-indigo-500 to-blue-500 text-white rounded-3xl shadow-2xl overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-8">
        <div class="w-full md:w-6/12 space-y-6">
            <h1 class="text-3xl lg:text-5xl font-extrabold leading-tight">🚀 Free Access to 200+ Premium Courses!</h1>
            <p class="text-lg font-medium">Hurry up! This exclusive offer expires soon! ⏳</p>
            <div class="flex gap-3 sm:gap-4">
                <div class="text-center">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-lg text-white flex flex-col items-center justify-center rounded-xl shadow-lg">
                        <span id="days" class="text-3xl font-extrabold">00</span><small class="text-sm uppercase">Days</small>
                    </div>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-lg text-white flex flex-col items-center justify-center rounded-xl shadow-lg">
                        <span id="hours" class="text-3xl font-extrabold">00</span><small class="text-sm uppercase">Hours</small>
                    </div>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-lg text-white flex flex-col items-center justify-center rounded-xl shadow-lg">
                        <span id="minutes" class="text-3xl font-extrabold">00</span><small class="text-sm uppercase">Minutes</small>
                    </div>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-lg text-white flex flex-col items-center justify-center rounded-xl shadow-lg">
                        <span id="seconds" class="text-3xl font-extrabold">00</span><small class="text-sm uppercase">Seconds</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full md:w-5/12">
            <div class="bg-white text-gray-900 p-8 rounded-2xl shadow-xl">
                <h3 class="text-2xl font-bold mb-5 text-center">✨ Sign Up for Free</h3>
              <!-- Free Access Section Form -->
<form id="freeAccessForm" action="{{ route('leads.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Full Name</label>
        <input type="text" name="name" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required />
    </div>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" />
    </div>
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Phone</label>
        <input type="tel" name="phone" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" />
    </div>
    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-bold">Submit</button>
</form>


            </div>
        </div>
    </div>
</div>

{{-- =========================================================
     SPECIAL OFFERS STRIP
   ========================================================= --}}
<section class="relative bg-cover bg-center text-center text-gray-900 py-8 lg:py-12" style="background-image: url('https://via.placeholder.com/1200x600'); background-color: rgb(252, 247, 241);">
    <div class="container mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full md:w-10/12 lg:w-7/12 bg-[rgba(246,237,233,0.6)] rounded-[20px] p-6 md:p-[50px] relative z-10">
                <h2 class="text-3xl md:text-4xl font-bold">Special <span class="text-[#ed8610]">Offers</span></h2>
                <p class="text-gray-600 mt-4 text-sm md:text-base">
                    It is a long-established fact that a reader will be distracted by the readable content of a page when looking at its layout...
                </p>
                <a href="https://wa.me/919876543210?text=Hi%20I%20am%20interested%20in%20the%20Special%20Offers%20🎉" 
   target="_blank"
   class="inline-block mt-6 px-6 py-2 md:px-8 md:py-3 bg-[#f8ad56] text-white text-base md:text-lg rounded-full hover:bg-[#e67e22] transition duration-300">
   Join Now
</a>

            </div>
        </div>
    </div>
    <img src="./images/special offer.png" alt="3D Character Left" class="hidden md:block absolute left-[1%] bottom-0 w-[300px] lg:w-[400px] z-1 hover:scale-105 transition-transform duration-300">
    <img src="./images/special_offers3.png" alt="3D Character Right" class="hidden md:block absolute right-[1%] bottom-0 w-[300px] lg:w-[400px] z-1 rounded-lg hover:scale-105 transition-transform duration-300">
</section>

{{-- =========================================================
     FAQs (Alpine x-collapse)
   ========================================================= --}}
<section class="py-6 lg:py-10 px-6">
    <h1 class="text-3xl lg:text-5xl font-[700] md:mb-8">Wait! I Have Some <span class="text-[#ed8610]">Questions</span></h1>
    <div class="w-full space-y-4 mt-4">
        <div class="flex w-full flex-col gap-4">
            @foreach($faqs as $index => $faq)
                <div x-data="{ isExpanded: false }" class="divide-y divide-slate-300 overflow-hidden rounded-lg border border-slate-300 bg-white">
                    <button id="controlsAccordionItem{{ $index + 1 }}" type="button" class="flex w-full items-center justify-between gap-2 bg-white text-base p-3 lg:p-5 lg:text-[18px] text-left underline-offset-2 font-[600]" aria-controls="accordionItem{{ $index + 1 }}" @click="isExpanded = !isExpanded" :class="isExpanded ? 'text-[#024c84] font-[600]' : 'font-[600]'" :aria-expanded="isExpanded ? 'true' : 'false'">
                        {{ $faq->question }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0 transition" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor" :class="isExpanded ? 'rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                        </svg>
                    </button>
                    <div x-show="isExpanded" id="accordionItem{{ $index + 1 }}" role="region" class="p-4" x-collapse>
                        <p class="text-[16px] font-[400]">{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- =========================================================
     Scripts (scoped)
   ========================================================= --}}
<!-- Swiper JS (if your layout already includes it, you can remove the next line) -->
<script src="https://unpkg.com/swiper@9/swiper-bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    /* Loader -> after 2s, hide and show popup */
    const loader = document.getElementById("page-loader");
    const popup  = document.getElementById("user-popup");
    const closeBtn = document.getElementById("close-popup");
    const form   = document.getElementById("userForm");

    setTimeout(() => {
        loader.style.display = "none";
        popup.classList.remove("hidden");
    }, 2000);

    closeBtn.addEventListener("click", () => popup.classList.add("hidden"));
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(form).entries());
        console.log("User Popup Data:", data);
        alert("🎉 Thank you, " + data.name + "! Your details have been saved.");
        popup.classList.add("hidden");
    });

    /* Tabs logic */
    const tabs = document.querySelectorAll(".nav-tab");
    const tabPanes = document.querySelectorAll(".tab-pane");
    const viewAllButtons = document.querySelectorAll("[id^='viewAll']");

    function activateTab(tab) {
        tabs.forEach(t => t.setAttribute("data-active", "false"));
        tabPanes.forEach(pane => pane.classList.add("hidden"));
        viewAllButtons.forEach(btn => btn.classList.add("hidden"));

        tab.setAttribute("data-active", "true");
        const targetTab = tab.dataset.tab;
        document.getElementById(targetTab).classList.remove("hidden");

        const viewAllId = `viewAll${targetTab.replace("study", "")}`;
        const viewAll = document.getElementById(viewAllId);
        if (viewAll) viewAll.classList.remove("hidden");
    }
    tabs.forEach(tab => tab.addEventListener("click", function (e) { e.preventDefault(); activateTab(this); }));
    if (tabs[0]) activateTab(tabs[0]);

    /* Swiper (Testimonials) */
    new Swiper('.testimonialSwiper', {
        loop: true,
        grabCursor: true,
        spaceBetween: 30,
        autoplay: { delay: 8000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        breakpoints: { 640: { slidesPerView: 1 }, 1024: { slidesPerView: 1, spaceBetween: 40 } }
    });

    /* Custom arrows for swiper buttons */
    document.querySelectorAll('.swiper-button-prev').forEach(btn => {
        btn.innerHTML = `<svg class="w-8 h-8 text-white bg-[#2c0b57] rounded-full p-1"><use href="#left-arrow"></use></svg>`;
    });
    document.querySelectorAll('.swiper-button-next').forEach(btn => {
        btn.innerHTML = `<svg class="w-8 h-8 text-white bg-[#2c0b57] rounded-full p-1"><use href="#right-arrow"></use></svg>`;
    });

    /* Countdown (to 7 days from now) */
    const end = new Date(Date.now() + 7*24*60*60*1000).getTime();
    const d = document.getElementById('days'), h = document.getElementById('hours'),
          m = document.getElementById('minutes'), s = document.getElementById('seconds');
    function tick(){
        const now = Date.now();
        let diff = Math.max(0, end - now);
        const dd = Math.floor(diff / (1000*60*60*24)); diff -= dd*24*60*60*1000;
        const hh = Math.floor(diff / (1000*60*60));    diff -= hh*60*60*1000;
        const mm = Math.floor(diff / (1000*60));       diff -= mm*60*1000;
        const ss = Math.floor(diff / 1000);
        d.textContent = String(dd).padStart(2,'0');
        h.textContent = String(hh).padStart(2,'0');
        m.textContent = String(mm).padStart(2,'0');
        s.textContent = String(ss).padStart(2,'0');
    }
    tick(); setInterval(tick, 1000);
});


function attachLeadHandler(formId){
    const form = document.getElementById(formId);
    if(!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        try {
            const fd = new FormData(form);
            const res = await fetch("{{ route('leads.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
                body: fd
            });

            const data = await res.json().catch(()=> ({}));

            if (!res.ok) {
                const msg = data?.message
                    || (data?.errors && Object.values(data.errors)[0][0])
                    || "Something went wrong. Please try again.";
                alert("❌ " + msg);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
                return;
            }

            alert("🎉 Thank you, " + (fd.get('name') || 'there') + "! Your details have been saved.");
            form.reset();
            document.getElementById("user-popup")?.classList.add("hidden");
        } catch (err) {
            console.error(err);
            alert("❌ Network error. Please try again.");
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit';
        }
    });
}

// Attach to both forms
attachLeadHandler("popupForm");
attachLeadHandler("freeAccessForm");

</script>
@endsection
