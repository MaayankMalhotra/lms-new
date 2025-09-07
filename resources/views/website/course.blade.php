@extends('website.layouts.app')

@section('title', 'Course Page')
@section('content')
<style>
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 5s ease infinite;
    }

    .floating {
        animation: float 4s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
  <!-- Courses Section -->
  <section class="py-20 px-4 md:px-8 bg-[#fcf8f3]">
    <div class="container mx-auto">
        <h2 class="text-4xl md:text-5xl font-bold text-center mb-12" data-aos="zoom-in">
            <span class="text-orange-500">Courses</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
           @foreach($courses as $course)
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow floating" 
                 data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-xl text-center font-bold mb-4">{{ $course->name }}</h3>
                <img src="{{ asset( $course->logo) }}" class="w-16 mx-auto mb-4 mt-6 text-center" alt="Python">
                <div class="space-y-2 text-gray-600 mb-6 text-center">
                    <p><i class="far fa-clock mr-2"></i>Duration: {{ $course->duration }}</p>
                    <p><i class="fas fa-users mr-2"></i>{{ $course->placed_learner }}+ Placed</p>
                    <p>⭐ {{ $course->rating }}</p>
                </div>
                <button 
                    class="w-full bg-orange-500 text-center text-white py-2 rounded-lg hover:bg-orange-600 transition-colors" 
                    data-course-slug="{{ $course->slug }}" 
                    onclick="redirectToCourseDetail(this)"
                >
                    Register Now
                </button>
            </div>
            @endforeach

        </div>
    </div>
</section>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-out-back'
    });
    function redirectToCourseDetail(button) {
    const courseSlug = button.getAttribute('data-course-slug'); 
    const baseUrl = '{{ env('APP_URL', 'http://localhost:8000') }}';
    window.location.href = `${baseUrl}course_details/${courseSlug}`; 
}
</script>
@endsection