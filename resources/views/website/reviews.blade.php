@extends('website.layouts.app')

@section('title', 'Reviews')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />



    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#2c0b57",
                        secondary: "#0c3c7c",
                        accent: "#ff7f00",
                        "accent-hover": "#e76e00",
                    },
                    fontFamily: {
                        poppins: ["Poppins", "sans-serif"],
                    },
                    animation: {
                        gradientBG: "gradientBG 5s infinite alternate-reverse",
                        shine: "shine 3s infinite",
                        footerGlow: "footerGlow 3s infinite alternate",
                    },
                    keyframes: {
                        gradientBG: {
                            "0%": {
                                backgroundPosition: "0% 50%"
                            },
                            "50%": {
                                backgroundPosition: "100% 50%"
                            },
                            "100%": {
                                backgroundPosition: "0% 50%"
                            },
                        },
                        shine: {
                            "0%": {
                                left: "-100%"
                            },
                            "50%": {
                                left: "100%"
                            },
                            "100%": {
                                left: "-100%"
                            },
                        },
                        footerGlow: {
                            "0%": {
                                boxShadow: "0px 0px 10px rgba(255, 115, 0, 0.3)"
                            },
                            "50%": {
                                boxShadow: "0px 0px 20px rgba(255, 115, 0, 0.6)"
                            },
                            "100%": {
                                boxShadow: "0px 0px 10px rgba(255, 115, 0, 0.3)"
                            },
                        },
                    },
                },
            },
        };
    </script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <!-- Animation Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <section class="bg-gradient-to-r from-primary to-secondary text-white py-20">
        <div class="container mx-auto px-4 py-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-5">
                <!-- Stats Section -->
                <div class="lg:w-2/5">
                    <h2 class="text-4xl font-bold leading-snug mb-6">
                        {{ $highlight->heading_line ?? '' }}
                        <br />
                        <span class="text-accent">{{ $highlight->heading_highlight ?? '' }}</span>
                    </h2>
        
                    <ul class="space-y-3">
                        @if (!empty($highlight->stats) && count($highlight->stats))
                            @foreach ($highlight->stats as $stat)
                                <li class="flex items-center text-lg">
                                    <i class="{{ $stat->icon ?? 'fas fa-question-circle' }} text-accent mr-3"></i>
                                    <span>
                                        @if (!empty($stat->value))
                                            <strong>{{ $stat->value }}</strong> {{ $stat->label ?? '' }}
                                        @else
                                            {{ $stat->label ?? '' }}
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        @else
                            <li class="text-gray-500">No stats available.</li>
                        @endif
                    </ul>
        
                    @if (!empty($highlight->cta_text))
                        <button class="mt-6 bg-accent text-white px-6 py-3 rounded-lg font-semibold hover:bg-accent-hover transition">
                            {{ $highlight->cta_text }}
                        </button>
                    @endif
                </div>
        
                <!-- Swiper Testimonials -->
                <!-- Swiper Container -->
<div class="lg:w-3/5 w-full">
    <div class="swiper mySwiper w-full">
        <div class="swiper-wrapper">
            @foreach($testimonials as $testimonial)
                <div class="swiper-slide">
                    <div class="testimonial-common bg-white rounded-lg shadow-lg p-6 text-center">
                        <img src="{{ $testimonial->image_url ?? 'https://via.placeholder.com/80' }}"
                             alt="{{ $testimonial->name }}"
                             class="w-20 h-20 rounded-full mx-auto mb-4" />
                        <h5 class="text-lg font-semibold">{{ $testimonial->name }}</h5>
                        <div class="text-sm text-gray-600">{{ $testimonial->department }}</div>
                        <div class="text-sm text-gray-600">{{ $testimonial->position }}</div>
                        <div class="text-sm text-yellow-500 mt-2">
                            {{ $testimonial->company }}
                            <div class="mt-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $testimonial->rating)
                                        ⭐
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Pagination inside swiper container -->
        {{-- <div class="swiper-pagination mt-4"></div> --}}
    </div>
</div>

<!-- Swiper Initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.mySwiper', {
            loop: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            spaceBetween: 20,
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    });
</script>

            </div>
        
          
            
        </div>
        
    </section>
    <section class="container mx-auto px-4 py-16">
        <!-- Section Heading -->
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-8">
            Our Seniors share their placement success and reviews
            <br />
            <span class="text-orange-500">Launching Great Software Careers</span>
        </h2>

        <!-- Testimonial Sets Container -->
        <div class="testimonial-sets-container relative ">
            <!-- SET 1: 2 rows x 4 columns (8 total) -->
            <div class="testimonial-set active grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @if($testimonials->count())
                    @foreach ($testimonials as $testimonial)
                        <div class="testimonial-common bg-white rounded-lg shadow-lg p-6 text-center">
                            {{-- Image --}}
                            @if($testimonial->image_url)
                                <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}"
                                     class="w-20 h-20 rounded-full mx-auto mb-4" />
                            @endif
        
                            {{-- Name --}}
                            <h5 class="text-lg font-semibold">{{ $testimonial->name }}</h5>
        
                            {{-- Department --}}
                            @if($testimonial->department)
                                <div class="text-sm text-gray-600">{{ $testimonial->department }}</div>
                            @endif
        
                            {{-- Position --}}
                            @if($testimonial->position)
                                <div class="text-sm text-gray-600">{{ $testimonial->position }}</div>
                            @endif
        
                            {{-- Company + Rating --}}
                            <div class="text-sm text-yellow-500 mt-2">
                                {{ $testimonial->company }}
                                <span class="ml-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {!! $i <= $testimonial->rating ? '⭐' : '☆' !!}
                                    @endfor
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="col-span-full text-center text-gray-500">No testimonials available at the moment.</p>
                @endif
            </div>
        
            {{-- Future Set 2 (Commented out) --}}
            {{-- 
            <div class="testimonial-set hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Additional testimonials can go here -->
            </div> 
            --}}
        </div>
        
    </section>

    <section class="container mx-auto px-4 mb-5">
        <h2 class="text-3xl font-bold text-center mb-8">
            YouTube <span class="text-blue-600">Reviews</span>
        </h2>

        <div class="text-center mb-8">
            <div class="font-bold text-yellow-500 text-lg mb-2">
                5 ★★★★★ (10,000)
            </div>
            <p class="text-gray-600 md:text-lg">
                Over 10,000 satisfied learners sharing their success stories!
            </p>
        </div>

        <!-- Video Container -->
        <div class="overflow-hidden">
            
            <!-- Active Video Set 1 -->
            <div class="flex flex-wrap -mx-2" id="videoSet1">
                @foreach($youtubeReviews as $review)
                <div class="w-full md:w-1/2 px-2 mb-4">
                    {{-- <div class="relative cursor-pointer group" data-bs-toggle="modal" data-bs-target="#youtubeModal"
                        data-video-id="{{ $review->video_id }}">
                        <img src="{{ $review->thumbnail_url }}"
                            alt="{{$review->title}}" class="w-full h-48 object-cover rounded-lg">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center rounded-lg transition-opacity group-hover:bg-opacity-20">
                            <div
                                class="w-12 h-12 bg-white rounded-full flex items-center justify-center transform transition-transform group-hover:scale-110">
                                <i class="fas fa-play text-blue-600"></i>
                            </div>
                        </div>
                    </div> --}}
                    <div class="relative cursor-pointer group video-thumbnail-wrapper" data-video-id="{{ $review->video_id }}">
                        {{-- Thumbnail image --}}
                        <img src="{{ $review->thumbnail_url }}"
                             alt="{{ $review->title }}"
                             class="w-full h-48 object-cover rounded-lg">
                    
                        {{-- Play icon overlay --}}
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center rounded-lg transition-opacity group-hover:bg-opacity-20">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center transform transition-transform group-hover:scale-110">
                                <i class="fas fa-play text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="font-semibold mt-2">{{$review->title}}</div>
                    <div class="text-gray-600 text-sm">{{$review->description}}</div>
                </div>
                @endforeach
            </div>
        </div>
        
       
    </section>
     
    
    
    
    
    <script>
        const set1 = document.getElementById("testimonialSet1");
        const set2 = document.getElementById("testimonialSet2");

        let showingSet1 = true;

        setInterval(() => {
            if (showingSet1) {
                set1.classList.add("hidden");
                set2.classList.remove("hidden");
            } else {
                set2.classList.add("hidden");
                set1.classList.remove("hidden");
            }
            showingSet1 = !showingSet1;
        }, 5000); // Switch every 5 seconds
    </script>
    <script>
        // Hero Card Rotation
        const card1 = document.querySelector("#carouselCard1");
        const card2 = document.querySelector("#carouselCard2");
        const card3 = document.querySelector("#carouselCard3");

        let positions = [card1, card2, card3];

        function rotateHeroCards() {
            positions.push(positions.shift());
            positions[0].classList.add("card-center");
            positions[1].classList.add("card-right");
            positions[2].classList.add("card-left");
        }

        setInterval(rotateHeroCards, 3000);
    </script>
 
    <script>
        document.querySelectorAll('.video-thumbnail-wrapper').forEach(wrapper => {
            wrapper.addEventListener('click', function () {
                const videoId = this.getAttribute('data-video-id');
    
                // Create iframe
                const iframe = document.createElement('iframe');
                iframe.setAttribute('src', `https://www.youtube.com/embed/${videoId}?autoplay=1`);
                iframe.setAttribute('frameborder', '0');
                iframe.setAttribute('allowfullscreen', '');
                iframe.setAttribute('allow', 'autoplay; encrypted-media');
                iframe.classList.add('w-full', 'h-48', 'rounded-lg');
    
                // Create close ("X") button
                const closeBtn = document.createElement('button');
                closeBtn.innerHTML = '&times;';
                closeBtn.classList.add('absolute', 'top-2', 'right-2', 'text-white', 'text-2xl', 'bg-black', 'bg-opacity-50', 'rounded-full', 'w-8', 'h-8', 'flex', 'items-center', 'justify-center', 'z-10');
                closeBtn.style.cursor = 'pointer';
    
                // Store original thumbnail HTML to restore later
                const originalContent = this.innerHTML;
    
                // Clear and replace with iframe + close
                this.innerHTML = '';
                this.appendChild(iframe);
                this.appendChild(closeBtn);
    
                // Close logic
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent re-triggering the video play
                    this.innerHTML = originalContent;
                });
            });
        });
    </script>
    
    
    
@endsection

