@extends('website.layouts.app')
@section('content')

<div class="font-sans bg-gray-100 px-10">
       <!-- Image Section -->
       <div class="mx-auto mt-10">
        <img src="https://d1d5cy0fmpy9m8.cloudfront.net/images/17462649220b4fd04b664e18c9453cce7b2c17539931f08657_(3).jpg" alt="People at Table" class="w-full h-96 object-fit rounded-lg shadow-md">
      </div>
    
    <!-- Event Header Section -->
    <div class="bg-white p-6 rounded-lg shadow-md  mx-auto mt-6">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-gray-500 uppercase text-sm">Event</p>
          <h1 class="text-2xl font-bold text-gray-800">{{ $webinar->title }}</h1>
          <p class="text-gray-500 mt-2">Entry Fee</p>
          <p class="text-lg font-semibold text-gray-800">{{$webinar->entry_type}}</p>
          <p class="text-gray-500">{{$webinar->participants_count}} other are participating</p>
        </div>
        <div class="bg-indigo-100 text-indigo-800 rounded-lg p-4 text-center">
          <p class="text-sm">Contest starts in</p>
          {{-- <div class="flex space-x-2 mt-2">
            <div class="bg-indigo-500 text-white rounded-lg p-2">
              <p class="text-lg font-bold">{{ $days }}</p>
              <p class="text-xs">Days</p>
            </div>
            <div class="bg-indigo-500 text-white rounded-lg p-2">
              <p class="text-lg font-bold">{{ $hours }}</p>
              <p class="text-xs">Hours</p>
            </div>
            <div class="bg-indigo-500 text-white rounded-lg p-2">
              <p class="text-lg font-bold">{{ $minutes }}</p>
              <p class="text-xs">Mins</p>
            </div>
            <div class="bg-indigo-500 text-white rounded-lg p-2">
              <p class="text-lg font-bold">{{ $seconds }}</p>
              <p class="text-xs">Sec</p>
            </div>
          </div> --}}
          <div class="flex space-x-2 mt-2" id="countdown-timer" data-start-time="{{ $webinar->start_time ? \Carbon\Carbon::parse($webinar->start_time)->toIso8601String() : '' }}">
            <div class="bg-indigo-500 text-white rounded-lg p-2">
                <p class="text-lg font-bold" id="days">0</p>
                <p class="text-xs">Days</p>
            </div>
            <div class="bg-indigo-500 text-white rounded-lg p-2">
                <p class="text-lg font-bold" id="hours">0</p>
                <p class="text-xs">Hours</p>
            </div>
            <div class="bg-indigo-500 text-white rounded-lg p-2">
                <p class="text-lg font-bold" id="minutes">0</p>
                <p class="text-xs">Mins</p>
            </div>
            <div class="bg-indigo-500 text-white rounded-lg p-2">
                <p class="text-lg font-bold" id="seconds">0</p>
                <p class="text-xs">Sec</p>
            </div>
        </div>
        </div>
      </div>
      <button class="bg-orange-500 text-white font-semibold py-2 px-6 rounded-lg mt-4 hover:bg-orange-600" onclick="openRegistationModal()">
      Register Now
      </button>
    </div>
  
 
    <!-- Webinar Details Section -->
    <div class="bg-[#FDF5E6] p-6 rounded-lg shadow-md  mx-auto mt-6">
      <h2 class="text-2xl font-bold text-gray-800 text-center">Webinar details</h2>
      <h3 class="text-lg font-semibold text-gray-800 mt-4">ROUND 1:</h3>
      <div class="grid grid-cols-4 gap-4 mt-4 text-center">
        <div>
          <p class="text-gray-500">Participation points</p>
          <p class="text-2xl font-bold text-gray-800">{{$webinar->learning_points ?? ''}}</p>
        </div>
        <div>
          <p class="text-gray-500">Start time</p>
          <p class="text-lg font-semibold text-gray-800">{{ \Carbon\Carbon::parse($webinar->start_time)->format('d-M-y') }}</p>
          <p class="text-gray-500">({{ \Carbon\Carbon::parse($webinar->start_time)->setTimezone('Asia/Kolkata')->format('h:i A \I\S\T') }})</p>
          
        </div>
        <div>
          <p class="text-gray-500">End time</p>
          <p class="text-lg font-semibold text-gray-800">{{ \Carbon\Carbon::parse($webinar->start_time)->addMinutes($webinar->duration * 60)->format('d-M-y') }}</p>
          <p class="text-gray-500">({{ \Carbon\Carbon::parse($webinar->start_time)->addMinutes($webinar->duration * 60)->setTimezone('Asia/Kolkata')->format('h:i A \I\S\T') }})</p>
        </div>
        <div>
          <p class="text-gray-500">Duration:</p>
          <p class="text-lg font-semibold text-gray-800">{{$webinar->duration}} hour</p>
        </div>
      </div>
      <p class="text-gray-600 mt-4"></p>
       @php
      $skills = explode(',', $webinar->learn_skills ?? '');
      $formattedSkills = collect($skills)
        ->map(function($skill) {
            return '<strong>' . e(trim($skill)) . '</strong>';
        })
        ->implode(', ');
      @endphp
      <p>
      Learn the skills as {!! $formattedSkills !!}.
      </p>
      <p class="text-gray-800 mt-4">
        <span class="font-semibold">TOPIC:</span> <span class="text-orange-500">{{$webinar->topic}}</span>
      </p>
      <p class="text-gray-800">
        <span class="font-semibold">SPEAKER:</span> <span class="text-orange-500">{{ $webinar->speaker_name ?? ''}} ({{ $webinar->speaker_designation ?? ''}}) </span>
      </p>
      <p class="text-gray-800 flex items-center mt-2">
        <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm1 2h6v2H7V4zm-3 4h12v8H4V8zm2 2v4h2v-4H6zm4 0v4h2v-4h-2z"/>
        </svg>
        <span class="text-orange-500">{{ $webinar->updated_at ? \Carbon\Carbon::parse($webinar->updated_at)->format('F j, Y') : '' }}</span>
      </p>
      <h3 class="text-lg font-semibold text-gray-800 mt-4">About Speaker:</h3>
      <p class="text-gray-600 mt-2">
        {{$webinar->speaker_bio ?? ''}}
      </p>
      <h3 class="text-lg font-semibold text-gray-800 mt-4">What You'll Learn?</h3>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>What skills do product companies look for?</li>
        <li>How to prepare and master these skills?</li>
        <li>How to showcase your skills?</li>
      </ul>
      <p class="text-gray-800 mt-4 font-semibold">Register today to secure your spot.</p>
      <p class="text-gray-800 font-semibold">See you at the masterclass!!</p>
    </div>
</div>

<!-- Modal -->
    <div id="openRegistationModal"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 rounded-t-xl flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-ticket-alt mr-2"></i> Webinar Registration
                </h2>
                <button onclick="closeModal()"
                        class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form action="{{ route('webinars.enroll', $webinar->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-user mr-1"></i> Name
                        </label>
                        <input type="text" name="name" id="name"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" placeholder="Your full name">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-envelope mr-1"></i> Email
                        </label>
                        <input type="email" name="email" id="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}" placeholder="Your email address">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-phone mr-1"></i> Phone (Optional)
                        </label>
                        <input type="text" name="phone" id="phone"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('phone') border-red-500 @enderror"
                               value="{{ old('phone') }}" placeholder="Your phone number">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Comments -->
                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-comment mr-1"></i> Comments (Optional)
                        </label>
                        <textarea name="comments" id="comments" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('comments') border-red-500 @enderror"
                                  placeholder="Any additional comments">{{ old('comments') }}</textarea>
                        @error('comments')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeModal()"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200 flex items-center">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                            <i class="fas fa-ticket-alt mr-1"></i> Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const timerElement = document.getElementById('countdown-timer');
    const startTimeStr = timerElement ? timerElement.getAttribute('data-start-time') : '';
    
    if (!startTimeStr) {
        updateTimer(0, 0, 0, 0);
        return;
    }

    const startTime = new Date(startTimeStr);
    
    function updateTimer(days, hours, minutes, seconds) {
        document.getElementById('days').textContent = days;
        document.getElementById('hours').textContent = hours;
        document.getElementById('minutes').textContent = minutes;
        document.getElementById('seconds').textContent = seconds;
    }

    function calculateTimeRemaining() {
        const now = new Date();
        const diffMs = startTime - now;
        
        if (diffMs <= 0) {
            updateTimer(0, 0, 0, 0);
            return;
        }

        const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diffMs % (1000 * 60)) / 1000);
        
        updateTimer(days, hours, minutes, seconds);
    }

    calculateTimeRemaining();
    setInterval(calculateTimeRemaining, 1000);
});

function openRegistationModal(){
  document.getElementById('openRegistationModal').classList.remove('hidden');
}
function closeModal() {
  document.getElementById('openRegistationModal').classList.add('hidden');
}
</script>

@endsection