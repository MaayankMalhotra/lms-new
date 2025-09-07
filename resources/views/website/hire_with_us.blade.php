@extends('website.layouts.app')

@section('title', 'Hire with us')
@section('content')
<!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-900 to-purple-900 text-white py-16">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
        <!-- Left Content -->
        <div class="md:w-1/2 md:pr-8 mb-8 md:mb-0">
            <h1 class="text-4xl font-bold mb-4">Hiring Simplified – Best Developers for Tomorrow’s <span class="text-orange-400">Job Roles</span></h1>
            <p class="text-lg text-white/80">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
        </div>

        <!-- Right Image -->
        <div class="md:w-1/2">
            <img src="https://firmbee.com/wp-content/uploads/Miniatura-do-wpisu-1-78-scaled.jpg" alt="Hero Image" class="w-full rounded-lg">
        </div>
    </div>
</section>


    <!-- Why TechBit Section -->
    <section class="bg-orange-50 py-16 ">
    <div class="container mx-auto px-4 pb-2">
        <h2 class="text-3xl font-bold text-center mb-12">
            Why <span class="text-orange-400">Knowledge Hut?</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-orange-50 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                <img src="https://5.imimg.com/data5/SELLER/Default/2023/2/MF/OY/TD/25550088/5s-certification.png"
                     alt="Icon" class="w-16 h-16 mx-auto mb-4 border-2 border-orange-500 rounded-full">
                <h3 class="text-lg font-semibold mb-2">Best Matches</h3>
                <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-orange-50 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                <img src="https://5.imimg.com/data5/SELLER/Default/2023/3/291772532/QX/UW/LN/25550088/10002-2004-quality-management-customer-satisfaction-500x500.jpg"
                     alt="Icon" class="w-16 h-16 mx-auto mb-4 border-2 border-orange-500 rounded-full">
                <h3 class="text-lg font-semibold mb-2">Proven Track Record</h3>
                <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-orange-50 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                <img src="https://5.imimg.com/data5/SELLER/Default/2023/2/MF/OY/TD/25550088/5s-certification.png"
                     alt="Icon" class="w-16 h-16 mx-auto mb-4 border-2 border-orange-500 rounded-full">
                <h3 class="text-lg font-semibold mb-2">Zero Hiring Cost</h3>
                <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-orange-50 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                <img src="https://5.imimg.com/data5/SELLER/Default/2023/3/291772532/QX/UW/LN/25550088/10002-2004-quality-management-customer-satisfaction-500x500.jpg"
                     alt="Icon" class="w-16 h-16 mx-auto mb-4 border-2 border-orange-500 rounded-full">
                <h3 class="text-lg font-semibold mb-2">One Stop App Solution</h3>
                <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </div>
        <!-- Centered Button -->
        <div class="flex justify-center mt-12">
            <a href="https://wa.me/919876543210?text=Hi%20I%20want%20to%20Start%20Hiring%20through%20Thinkchamp%20👨‍💻" 
   target="_blank"
   class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-lg shadow-md transition">
   Start Hiring
</a>

        </div>
    </div>
</section>



    <!-- Job Roles Section -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Job Roles Available for Hiring</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <!-- Front End Developer -->
            {{-- @foreach ($jobRoles as $jobRole)
                <div class="bg-blue-50 border border-blue-200 p-6 rounded-2xl shadow-sm text-center hover:shadow-md transition">
                <span class="inline-block bg-blue-700 text-white text-base font-semibold px-8 py-3 rounded-md mb-4">
                    Front End Developer
                </span>
                <div class="flex justify-center gap-4">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" alt="HTML" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg" alt="CSS" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg" alt="JavaScript" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg" alt="React" class="w-8 h-8">
                </div>
                </div>
            @endforeach --}}
            @foreach($jobRoles as $jobRole)
                <div class="bg-{{ $loop->iteration % 3 == 1 ? 'blue' : ($loop->iteration % 3 == 2 ? 'yellow' : 'green') }}-50 border border-{{ $loop->iteration % 3 == 1 ? 'blue' : ($loop->iteration % 3 == 2 ? 'yellow' : 'green') }}-200 p-6 rounded-2xl shadow-sm text-center hover:shadow-md transition">
                    <span class="inline-block bg-{{ $loop->iteration % 3 == 1 ? 'blue' : ($loop->iteration % 3 == 2 ? 'yellow' : 'green') }}-700 text-white text-base font-semibold px-8 py-3 rounded-md mb-4">
                        {{ $jobRole->title }}
                    </span>
                    <div class="flex justify-center gap-4 flex-wrap">
                        @foreach($jobRole->technologies as $tech)
                            <img src="{{ $tech['image_url'] }}" alt="{{ $tech['name'] }}" class="w-8 h-8 {{ $tech['name'] == 'Express' ? 'bg-white rounded-full p-1' : '' }}">
                        @endforeach
                    </div>
                </div>
            @endforeach
            

            {{-- <!-- Backend Developer -->
            <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-2xl shadow-sm text-center hover:shadow-md transition">
                <span class="inline-block bg-yellow-600 text-white text-base font-semibold px-8 py-3 rounded-md mb-4">
                    Backend Developer
                </span>
                <div class="flex justify-center gap-4">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg" alt="Node.js" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg" alt="Python" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg" alt="Java" class="w-8 h-8">
                </div>
            </div>

            <!-- Full Stack Developer -->
            <div class="bg-green-50 border border-green-200 p-6 rounded-2xl shadow-sm text-center hover:shadow-md transition">
                <span class="inline-block bg-green-700 text-white text-base font-semibold px-8 py-3 rounded-md mb-4">
                    Full Stack Developer
                </span>
                <div class="flex justify-center gap-4">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg" alt="MongoDB" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/express/express-original.svg" alt="Express" class="w-8 h-8 bg-white rounded-full p-1">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg" alt="React" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg" alt="Node.js" class="w-8 h-8">
                </div>
            </div>
            <!-- Front End Developer -->
            <div class="bg-blue-50 border border-blue-200 p-6 rounded-2xl shadow-sm text-center hover:shadow-md transition">
                <span class="inline-block bg-blue-700 text-white text-base font-semibold px-8 py-3 rounded-md mb-4">
                    Front End Developer
                </span>
                <div class="flex justify-center gap-4">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" alt="HTML" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg" alt="CSS" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg" alt="JavaScript" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg" alt="React" class="w-8 h-8">
                </div>
            </div>

            <!-- Backend Developer -->
            <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-2xl shadow-sm text-center hover:shadow-md transition">
                <span class="inline-block bg-yellow-600 text-white text-base font-semibold px-8 py-3 rounded-md mb-4">
                    Backend Developer
                </span>
                <div class="flex justify-center gap-4">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg" alt="Node.js" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg" alt="Python" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg" alt="Java" class="w-8 h-8">
                </div>
            </div>

            <!-- Full Stack Developer -->
            <div class="bg-green-50 border border-green-200 p-6 rounded-2xl shadow-sm text-center hover:shadow-md transition">
                <span class="inline-block bg-green-700 text-white text-base font-semibold px-8 py-3 rounded-md mb-4">
                    Full Stack Developer
                </span>
                <div class="flex justify-center gap-4">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg" alt="MongoDB" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/express/express-original.svg" alt="Express" class="w-8 h-8 bg-white rounded-full p-1">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg" alt="React" class="w-8 h-8">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg" alt="Node.js" class="w-8 h-8">
                </div>
            </div> --}}

        </div>
    </div>
</section>



    <!-- Instructors Section -->
    <section class="bg-orange-50 py-16">
    <h2 class="text-3xl font-bold mb-8 text-center">Learn to Code from our Instructor</h2>
    <div class="container mx-auto px-4 flex flex-col lg:flex-row gap-8">
        
        <!-- Left: Instructors -->
<div class="w-full lg:w-2/3 mx-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Instructor Badge -->
        @foreach ($instructors as $instructor)
          <div class="flex flex-col items-center p-4 bg-white rounded-2xl shadow-sm hover:shadow-md transition text-center">    
            <img src="{{$instructor->image}}" alt="{{$instructor->name}}" class="w-20 h-20 border-2 border-orange-500 rounded-full mb-2">
            <h3 class="text-lg font-semibold text-green-700">{{$instructor->name}}</h3>
            <div class="flex items-center justify-center text-sm text-gray-600 mb-1">
                <i class="fas fa-clock text-orange-500 mr-1"></i>
                {{$instructor->teaching_hours}} Hours Taught
            </div>
            <p class="text-xs text-gray-600">Courses | Teach <br> {{$instructor->specialization}}</p>
            {{-- <div class="flex justify-center gap-4 mt-2">
                @if($instructor->linkedin_url)
                    <a href="{{ $instructor->linkedin_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        <i class="fab fa-linkedin fa-lg"></i>
                    </a>
                @endif
                @if($instructor->facebook_url)
                    <a href="{{ $instructor->facebook_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        <i class="fab fa-facebook fa-lg"></i>
                    </a>
                @endif
            </div> --}}
        </div>  
        @endforeach

        {{-- <div class="flex flex-col items-center p-4 bg-white rounded-2xl shadow-sm hover:shadow-md transition text-center">
            <img src="https://t4.ftcdn.net/jpg/02/45/56/35/360_F_245563558_XH9Pe5LJI2kr7VQuzQKAjAbz9PAyejG1.jpg" alt="Instructor" class="w-20 h-20 border-2 border-orange-500 rounded-full mb-2">
            <h3 class="text-lg font-semibold text-green-700">Jane Smith</h3>
            <div class="flex items-center justify-center text-sm text-gray-600 mb-1">
                <i class="fas fa-clock text-orange-500 mr-1"></i>
                1600+ Hours Taught
            </div>
            <p class="text-xs text-gray-600">Courses | Teach <br> Web Development</p>
        </div>
        <div class="flex flex-col items-center p-4 bg-white rounded-2xl shadow-sm hover:shadow-md transition text-center">
            <img src="https://t4.ftcdn.net/jpg/02/45/56/35/360_F_245563558_XH9Pe5LJI2kr7VQuzQKAjAbz9PAyejG1.jpg" alt="Instructor" class="w-20 h-20 border-2 border-orange-500 rounded-full mb-2">
            <h3 class="text-lg font-semibold text-green-700">Jane Smith</h3>
            <div class="flex items-center justify-center text-sm text-gray-600 mb-1">
                <i class="fas fa-clock text-orange-500 mr-1"></i>
                1600+ Hours Taught
            </div>
            <p class="text-xs text-gray-600">Courses | Teach <br> Web Development</p>
        </div>
        <div class="flex flex-col items-center p-4 bg-white rounded-2xl shadow-sm hover:shadow-md transition text-center">
            <img src="https://t4.ftcdn.net/jpg/02/45/56/35/360_F_245563558_XH9Pe5LJI2kr7VQuzQKAjAbz9PAyejG1.jpg" alt="Instructor" class="w-20 h-20 border-2 border-orange-500 rounded-full mb-2">
            <h3 class="text-lg font-semibold text-green-700">Jane Smith</h3>
            <div class="flex items-center justify-center text-sm text-gray-600 mb-1">
                <i class="fas fa-clock text-orange-500 mr-1"></i>
                1600+ Hours Taught
            </div>
            <p class="text-xs text-gray-600">Courses | Teach <br> Web Development</p>
        </div> --}}

    </div>
</div>


        <!-- Right: Badge Panel -->
        <div class="lg:w-1/3 bg-orange-50 p-6 rounded-2xl flex flex-col justify-between">
            <div>
                <h3 class="text-3xl font-bold mb-4">Zero Compromise on Trainer Quality</h3>
                <p class="text-sm text-gray-700 mb-4">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-award text-orange-500"></i> Certified trainers
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-blue-500"></i> Industry experience
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fab fa-google text-red-500"></i> Hands-on training
                    </li>
                </ul>
            </div>
            <!-- Apply Button -->
            <div class="mt-6">
                <button type="button" id="apply-mentor-btn" class="inline-block bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-6 py-2 rounded-md shadow">
                    Apply as Mentor
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Mentor Application Modal -->
<div id="mentor-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-lg w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Apply as Mentor</h3>
            <button type="button" id="close-mentor-modal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('mentor.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="e.g., John Doe" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="teaching_hours" class="block text-sm font-medium text-gray-700">Teaching Hours <span class="text-red-500">*</span></label>
                <input type="number" name="teaching_hours" id="teaching_hours" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="e.g., 500" min="0" required>
                @error('teaching_hours')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization <span class="text-red-500">*</span></label>
                <input type="text" name="specialization" id="specialization" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="e.g., Web Development" required>
                @error('specialization')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                <input type="text" name="phone_number" id="phone_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="e.g., 5234567890" required>
                @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn URL (Optional)</label>
                <input type="url" name="linkedin_url" id="linkedin_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="e.g., https://linkedin.com/in/username">
                @error('linkedin_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook URL (Optional)</label>
                <input type="url" name="facebook_url" id="facebook_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="e.g., https://facebook.com/username">
                @error('facebook_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" id="cancel-mentor-modal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-md">Cancel</button>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-md">Submit Application</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('mentor-modal');
    const openBtn = document.getElementById('apply-mentor-btn');
    const closeBtn = document.getElementById('close-mentor-modal');
    const cancelBtn = document.getElementById('cancel-mentor-modal');

    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
</script>


@endsection