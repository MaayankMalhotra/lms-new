@extends('website.layouts.app')
@section('title', 'Webinars')
@section('content')
<!-- All Webinars Section -->
<section class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-6">All <span class="text-blue-500">Webinars</span></h2>
  
    <!-- Tags Box on Top (Optional) -->
    <!-- Tags Filter -->
<div class="bg-gray-100 p-4 rounded mb-6">
    <h4 class="font-semibold mb-2">Filter by Tag:</h4>
    <div class="flex flex-wrap gap-2">
        @foreach($uniqueTags as $tag)
            <a href="{{ route('webinar.show', ['tag' => $tag]) }}"
               class="px-3 py-1 rounded-full border text-sm
                      {{ $selectedTag === $tag ? 'bg-blue-500 text-white' : 'bg-white text-gray-800 border-gray-300' }}">
                {{ $tag }}
            </a>
        @endforeach

        @if($selectedTag)
            <a href="{{ route('webinar.show') }}"
               class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm border border-red-300">
                Clear Filter
            </a>
        @endif
    </div>
</div>
  
    <!-- 4 Webinar Cards in 2 rows x 2 columns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Card 1 -->
      @foreach($webinars as $webinar) 
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <img
          src="{{ $webinar->image_url ?? 'https://via.placeholder.com/400x200' }}"
          alt="{{ $webinar->title }}"
          class="w-full h-48 object-cover"
        />
        <div class="p-6">
          <h5 class="text-xl font-semibold mb-2">Event: {{ $webinar->title }}</h5>
          <div class="text-gray-600 mb-2">
            Starts on <strong>{{ \Carbon\Carbon::parse($webinar->start_time)->format('h:i A, d M Y') }}</strong>
          </div>
          <div class="text-gray-600 mb-2">
            {{ $webinar->entry_type }}  | Registration Open till {{ \Carbon\Carbon::parse($webinar->registration_deadline)->format('d M Y') }}
          </div>
          <div class="text-gray-600 mb-4">
            {{ Str::limit($webinar->description, 150) }}
          </div>
          <div class="text-gray-600 mb-4">{{ number_format($webinar->participants_count) }} participants Registered</div>
          {{-- <button class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
            Register Now
          </button> --}}
          @if(\Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($webinar->registration_deadline)))
            <button class="bg-red-500 text-white px-6 py-2 rounded-lg opacity-50 cursor-not-allowed" disabled>
              Registration Closed
            </button>
          @else
            <a href="{{ route('webinars.show', $webinar->id) }}"
               class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-300 inline-block">
              Register Now
            </a>
          @endif
        </div>
      </div>
      @endforeach
    </div>

  </section>
  
  <!-- Course Certificates Section -->
  <section class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-6">Course <span class="text-blue-500">Certificates</span></h2>
  
    <!-- Description Paragraph -->
    <p class="text-gray-600 mb-8">
      Our business analyst Master's program is led by industry experts who will make you proficient in the field of business analytics. The projects and case studies that are provided as part of this course will help you gain industry-grade experience, which will be a bonus in your resume.
    </p>
  
    <!-- Two-Column Layout -->
    <div class="flex flex-col md:flex-row gap-8">
      <!-- Left Column: Bullet Points -->
      <div class="w-full md:w-1/2">
        <ul class="space-y-4">
          <li class="flex items-center text-gray-700">
            <span class="mr-2">•</span> CEBA – Certification of Competency in Business Analysis
          </li>
          <li class="flex items-center text-gray-700">
            <span class="mr-2">•</span> Agile Scrum Foundation
          </li>
          <li class="flex items-center text-gray-700">
            <span class="mr-2">•</span> Digital Transformation Course for Leaders
          </li>
        </ul>
      </div>
  
      <!-- Right Column: Certificate Image -->
      <div class="w-full md:w-1/2 flex justify-center">
        <img
          src="https://media.licdn.com/dms/image/v2/D5622AQGoUBZSCAP82g/feedshare-shrink_2048_1536/feedshare-shrink_2048_1536/0/1731245943907?e=2147483647&v=beta&t=55eBVsL3PaAH74TFdAM3qEz8RBRcwxX_ZHYYpst400I"
          alt="Certificate Sample"
          class="w-full max-w-md rounded-lg shadow-md"
        />
      </div>
    </div>
  </section>
  
  <!-- Optional Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- 🚀 GSAP Scroll Animations -->
  <script>
    gsap.registerPlugin(ScrollTrigger);
  
    gsap.from(".footer-grid", {
      opacity: 0,
      y: 50,
      duration: 1.2,
      ease: "power2.out",
      scrollTrigger: {
        trigger: ".footer",
        start: "top 95%",
        toggleActions: "play none none reverse",
      },
    });
  
    gsap.from(".footer-section h3, .footer-section ul li", {
      opacity: 0,
      y: 20,
      stagger: 0.2,
      duration: 1,
      ease: "power2.out",
      scrollTrigger: {
        trigger: ".footer",
        start: "top 95%",
        toggleActions: "play none none reverse",
      },
    });
  </script>
@endsection