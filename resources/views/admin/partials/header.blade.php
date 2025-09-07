
<div>
    <h2 class="text-3xl font-extrabold text-gray-900 tracking-wide">
        Welcome, <span class="text-indigo-600">{{ auth()->user()->name }}</span> 👋
    </h2>
    <p class="text-gray-500 text-sm">Hope you have a great day ahead!</p>

</div>


<!-- User Profile Dropdown -->
<div x-data="{ open: false }" class="relative">
<!-- Profile Section -->
<div @click="open = !open" 
    class="flex items-center gap-4 bg-white shadow-lg px-5 py-2 rounded-xl cursor-pointer transition hover:bg-gray-100 border border-gray-300">
    <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-user.png') }}"
 alt="User"
 class="w-12 h-12 rounded-full border border-gray-400">

    <div class="text-left">
        <span class="block text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</span>
        <span class="text-sm text-gray-500">
@if(auth()->user()->role == 1)
    Admin
@elseif(auth()->user()->role == 2)
    Teacher
@elseif(auth()->user()->role == 3)
    Student
@endif
</span>

    </div>
    <i class="fas fa-chevron-down text-gray-600 transition-transform duration-200" 
        :class="open ? 'rotate-180' : 'rotate-0'"></i>
</div>

<!-- Dropdown Menu -->
<div x-show="open" @click.away="open = false"
    class="absolute right-0 mt-3 w-60 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden transform origin-top transition-all duration-200 scale-95"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90">
    
    <ul class="text-gray-800">
        <li>
            <a href="{{ route('profile')  }}" class="flex items-center px-5 py-3 hover:bg-gray-100 transition-all">
                <i class="fas fa-user text-indigo-500 mr-4 text-lg"></i> Profile
            </a>
        </li>
        <li>
            <a href="" class="flex items-center px-5 py-3 hover:bg-gray-100 transition-all">
                <i class="fas fa-cog text-blue-500 mr-4 text-lg"></i> Settings
            </a>
        </li>
        <li>
            <a href="" class="flex items-center px-5 py-3 hover:bg-gray-100 transition-all">
                <i class="fas fa-bell text-yellow-500 mr-4 text-lg"></i> Notifications
            </a>
        </li>
        <li class="border-t">
            <a href="{{ route('logout')  }}" class="flex items-center px-5 py-3 text-red-600 hover:bg-red-50 transition-all">
                <i class="fas fa-sign-out-alt text-red-500 mr-4 text-lg"></i> Logout
            </a>
        </li>
    </ul>
</div>
</div>
