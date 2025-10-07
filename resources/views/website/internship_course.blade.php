@extends('website.layouts.app')

@section('title', 'Internship')
@section('content')
<style>
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #ddd;
    }
    ::-webkit-scrollbar-thumb {
        background: #ff7300;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #ff4500;
    }
    
</style>

 <!-- Internships Section -->
 <section class="py-20 px-4 md:px-8 ">
    <div class=" mx-auto">
        <h2 class="text-4xl md:text-5xl font-bold text-center mb-12 text-orange-500">
            Internships
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Internship Card -->
             @foreach($internships as $internship)
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200">
                <h3 class="text-xl font-bold mb-4 text-center">{{ $internship->name }}</h3>
                <img src="{{ asset( $internship->logo) }}" 
                     class="w-16 h-16 mx-auto mb-4" alt="React Icon">
                <div class="flex justify-between text-gray-600 mb-4 text-center px-4">
                    <span class="flex items-center">
                        <i class="far fa-clock mr-2"></i>{{ $internship->duration }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-tasks mr-2"></i>{{ $internship->project }} Projects
                    </span>
                </div>
                <div class="text-orange-700 font-semibold mb-4 text-center">
                    ⭐ {{ $internship->applicant }}
                </div>
                <div class="text-orange-700 font-semibold mb-4 text-center">
             Price:   ₹ {{ $internship->price }}
                </div>
                <div class="bg-amber-300 text-black py-2 px-4 rounded-lg text-center mb-4 font-bold text-center">
                    {{ $internship->certified_button }}
                </div>
                <a href="{{ route('website.internship_details', $internship->id) }}" 
                           class="block w-full bg-orange-500 text-white py-2.5 rounded-lg hover:bg-orange-600 transition-colors text-center font-semibold">
                            Register Now
                        </a>
            </div>
            @endforeach
         

        </div>
    </div>
</section>

@endsection
