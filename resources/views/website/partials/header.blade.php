<style>
    /* Mobile menu styles */
    #mobile-menu {
        display: none;
    }

    #mobile-menu.is-open {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Dropdown styles */
    .dropdown-content {
        display: none;
    }

    .dropdown-content.is-open {
        display: block;
        animation: fadeIn 0.2s ease-in-out;
    }

    .mobile-dropdown-content {
        display: none;
        padding-left: 1rem;
    }

    .mobile-dropdown-content.is-open {
        display: block;
        animation: fadeIn 0.2s ease-in-out;
    }

    .rotate-180 {
        transform: rotate(180deg);
    }

    .transition-transform {
        transition: transform 0.2s ease-in-out;
    }
</style>

<nav class="bg-gradient-to-r from-[#2c0b57] to-[#0c3c7c] shadow-xl fixed w-full z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('home-page') }}" class="flex items-center">
                <img src="./images/THINK%20CHAMP%20logo2.png" alt="Logo" class="h-10" />
            </a>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="lg:hidden text-white focus:outline-none" aria-label="Toggle Menu">
                <!-- Hamburger Icon -->
                <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <!-- Close Icon -->
                <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('home-page') }}"
                    class="text-white hover:text-amber-400 transition-colors duration-300">Home</a>

                <!-- Courses Dropdown -->
                <div class="relative group">
                    <button id="courses-btn"
                        class="text-white hover:text-amber-400 flex items-center gap-1 transition-colors duration-300">
                        Courses <i id="courses-chevron" class="fas fa-chevron-down text-xs transition-transform"></i>
                    </button>
                    <div id="courses-dropdown"
                        class="dropdown-content absolute bg-black/90 rounded-lg p-2 min-w-[240px] mt-2 shadow-lg z-50">
                        @php
                            $courses = DB::table('courses')->get();
                        @endphp
                        @foreach ($courses as $course)
                            <a href="{{ route('website.course_details', $course->slug) }}"
                                class="block px-1 py-2 text-white hover:bg-orange-500 rounded-md transition-colors text-sm">{{ $course->name }}</a>
                        @endforeach
                    </div>
                </div>

                <a href="https://maayank-malhotra.ddns.net/about" class="text-white hover:text-amber-400 transition-colors duration-300">About</a>

                <!-- What We Offer Dropdown -->
                <div class="relative group">
                    <button id="offer-btn"
                        class="text-white hover:text-amber-400 flex items-center gap-1 transition-colors duration-300">
                        What we offer <i id="offer-chevron"
                            class="fas fa-chevron-down text-xs transition-transform"></i>
                    </button>
                    <div id="offer-dropdown"
                        class="dropdown-content absolute bg-black/90 rounded-lg p-2 min-w-[200px] mt-2 shadow-lg z-50">
                        <a href="{{ route('website.course') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">Courses</a>
                        <a href="{{ route('website.internship') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">Internships</a>
                        
                    </div>
                </div>

                <!-- Update Dropdown -->
                <div class="relative group">
                    <button id="update-btn"
                        class="text-white hover:text-amber-400 flex items-center gap-1 transition-colors duration-300">
                        Update <i id="update-chevron" class="fas fa-chevron-down text-xs transition-transform"></i>
                    </button>
                    <div id="update-dropdown"
                        class="dropdown-content absolute bg-black/90 rounded-lg p-2 min-w-[200px] mt-2 shadow-lg z-50">
                        <a href="{{ route('events.index') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">Event</a>
                        <a href="{{ route('news.index') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">News</a>
                        <a href="{{ route('webinar.show') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">Webinars</a>
                    </div>
                </div>

                <a href="{{ route('career_hightlight_show') }}"
                    class="text-white hover:text-amber-400 transition-colors duration-300">Reviews</a>
                <a href="{{ route('hire.show') }}"
                    class="text-white hover:text-amber-400 transition-colors duration-300">Hire With Us</a>
                <a href="{{ route('website.contact') }}"
                    class="text-white hover:text-amber-400 transition-colors duration-300">Contact</a>
                <a href="{{ route('login') }}"
                    class="bg-gradient-to-r from-orange-600 to-amber-500 px-6 py-2 rounded-lg text-white font-semibold hover:shadow-lg hover:shadow-orange-300 transition-all">Login</a>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden bg-[#2c0b57] px-4 pb-4">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home-page') }}" class="text-white hover:text-amber-400 py-2">Home</a>
                <a href="/about" class="text-white hover:text-amber-400 py-2">About</a>

                <!-- Mobile Courses Dropdown -->
                <div>
                    <button id="mobile-courses-btn"
                        class="text-white hover:text-amber-400 flex items-center justify-between w-full py-2">
                        Courses
                        <svg id="mobile-courses-chevron" class="w-4 h-4 transform transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="mobile-courses-dropdown"
                        class="mobile-dropdown-content h-28 bg-black/90 rounded py-4 px-2 overflow-y-scroll">

                        @php
                            $courses = DB::table('courses')->get();
                        @endphp
                        @foreach ($courses as $course)
                            <a href="{{ route('website.course_details', $course->slug) }}"
                                class="block px-1 py-2 text-white hover:bg-orange-500 rounded-md transition-colors text-sm">{{ $course->name }}</a>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile What We Offer Dropdown -->
                <div>
                    <button id="mobile-offer-btn"
                        class="text-white hover:text-amber-400 flex items-center justify-between w-full py-2">
                        What we offer
                        <svg id="mobile-offer-chevron" class="w-4 h-4 transform transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="mobile-offer-dropdown" class="mobile-dropdown-content">
                        <a href="{{ route('website.course') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">Courses</a>
                        <a href="{{ route('website.internship') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">Internships</a>
                        
                    </div>
                </div>

                <!-- Mobile Update Dropdown -->
                <div>
                    <button id="mobile-update-btn"
                        class="text-white hover:text-amber-400 flex items-center justify-between w-full py-2">
                        Update
                        <svg id="mobile-update-chevron" class="w-4 h-4 transform transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="mobile-update-dropdown" class="mobile-dropdown-content">
                        <a href="{{ route('events.index') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">Event</a>
                        <a href="{{ route('news.index') }}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">News</a>
                        <a href="{{ route('webinar.show')}}"
                            class="block px-4 py-2 text-white hover:bg-orange-500 rounded-md transition-colors">Webinars</a>
                    </div>
                </div>
                <a href="{{ route('hire.show')}}" class="text-white hover:text-amber-400 py-2">Hire With Us</a>
                <a href="{{ route('career_hightlight_show')}}" class="text-white hover:text-amber-400 py-2">Reviews</a>
                <a href="{{ route('website.contact') }}" class="text-white hover:text-amber-400 py-2">Contact</a>
                <a href="{{ route('login') }}"
                    class="bg-orange-500 px-6 py-2 rounded-lg text-white text-center hover:bg-orange-600 mt-2">Login</a>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('is-open');
                menuIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        }

        // Close mobile menu when clicking a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('is-open');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            });
        });

        // Desktop dropdowns
        function setupDesktopDropdown(buttonId, dropdownId, chevronId) {
            const button = document.getElementById(buttonId);
            const dropdown = document.getElementById(dropdownId);
            const chevron = document.getElementById(chevronId);

            if (button && dropdown && chevron) {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('is-open');
                    chevron.classList.toggle('rotate-180');
                });

                // Close when clicking elsewhere
                document.addEventListener('click', function() {
                    dropdown.classList.remove('is-open');
                    chevron.classList.remove('rotate-180');
                });

                // Prevent dropdown from closing when clicking inside
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        }

        // Mobile dropdowns
        function setupMobileDropdown(buttonId, dropdownId, chevronId) {
            const button = document.getElementById(buttonId);
            const dropdown = document.getElementById(dropdownId);
            const chevron = document.getElementById(chevronId);

            if (button && dropdown && chevron) {
                button.addEventListener('click', function() {
                    dropdown.classList.toggle('is-open');
                    chevron.classList.toggle('rotate-180');
                });
            }
        }

        // Initialize dropdowns
        setupDesktopDropdown('courses-btn', 'courses-dropdown', 'courses-chevron');
        setupDesktopDropdown('offer-btn', 'offer-dropdown', 'offer-chevron');
        setupDesktopDropdown('update-btn', 'update-dropdown', 'update-chevron');

        setupMobileDropdown('mobile-courses-btn', 'mobile-courses-dropdown', 'mobile-courses-chevron');
        setupMobileDropdown('mobile-offer-btn', 'mobile-offer-dropdown', 'mobile-offer-chevron');
        setupMobileDropdown('mobile-update-btn', 'mobile-update-dropdown', 'mobile-update-chevron');
    });
</script>
