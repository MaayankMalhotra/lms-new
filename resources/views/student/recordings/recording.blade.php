@extends('admin.layouts.app') <!-- Adjust to student.layouts.app if needed -->

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Your Recordings</h1>

        <!-- Debug: Display recordings count -->
        <p class="text-gray-600 mb-4">Total Recordings: {{ count($recordings) }}</p>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow-md">
                {{ session('error') }}
            </div>
        @endif

        @if ($recordings->isEmpty())
            <p class="text-gray-500 text-lg">No recordings available.</p>
        @else
            <div class="space-y-8">
                @foreach ($recordings->groupBy('topic.folder.name') as $folderName => $folderRecordings)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold text-purple-600 mb-4 border-b-2 border-purple-200 pb-2">
                            {{ $folderName }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($folderRecordings->groupBy('topic.name') as $topicName => $topicRecordings)
                                @foreach ($topicRecordings as $recording)
                                    <div class="bg-gray-50 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300">
                                        <h3 class="text-md font-medium text-indigo-600 mb-2">{{ $topicName }}</h3>
                                        <button 
                                            type="button"
                                            class="text-blue-500 hover:text-blue-700 text-lg"
                                            onclick="openModal('{{ $recording->video_url }}')"
                                        >
                                            Recording {{ $recording->id }}
                                        </button>
                                        <p class="text-sm text-gray-600 mt-2">Date: {{ $recording->created_at->format('Y-m-d') }}</p>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Full Page Modal -->
    <div id="videoModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="relative w-full h-full max-w-[95vw] max-h-[95vh] bg-white rounded-lg p-4 flex flex-col">
            <button 
                type="button"
                onclick="closeModal()" 
                class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-2xl font-bold z-20"
            >
                ×
            </button>
            <div class="flex-grow w-full h-full" id="videoContainer">
                <!-- Video or iframe will be injected here -->
            </div>
            <!-- Custom Seek Bar -->
            <div id="seekBarContainer" class="w-full p-2 bg-gray-200 flex items-center">
                <input type="range" id="seekBar" min="0" max="100" value="0" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer" onchange="seekVideo()">
                <span id="currentTime" class="ml-2 text-sm text-gray-700">0:00</span> / <span id="duration" class="text-sm text-gray-700">0:00</span>
            </div>
            <!-- Overlay to block YouTube UI -->
            <div id="uiOverlay" class="absolute top-0 left-0 w-full h-full bg-transparent z-10 pointer-events-auto" style="top: 40px; height: calc(100% - 40px - 40px);"></div>
        </div>
    </div>

    <!-- Modal JavaScript -->
    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        console.log('Modal script loaded at:', new Date().toISOString());

        let player;
        let video;
        let currentVideoUrl = '';

        function onYouTubeIframeAPIReady() {
            console.log('YouTube IFrame API ready');
        }

        function isYouTubeUrl(url) {
            return url.includes('youtube.com') || url.includes('youtu.be');
        }

        function getYouTubeEmbedUrl(url) {
            let videoId = '';
            if (url.includes('youtube.com/watch?v=')) {
                videoId = url.split('v=')[1].split('&')[0];
            } else if (url.includes('youtu.be/')) {
                videoId = url.split('youtu.be/')[1].split('?')[0];
            }
            return videoId ? `https://www.youtube.com/embed/${videoId}?controls=0&modestbranding=1&iv_load_policy=3&rel=0&fs=0&showinfo=0&enablejsapi=1&origin=${encodeURIComponent(window.location.origin)}` : '';
        }

        function formatTime(seconds) {
            if (isNaN(seconds)) return "0:00";
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
        }

        function updateSeekBar() {
            const seekBar = document.getElementById('seekBar');
            const currentTime = document.getElementById('currentTime');
            const duration = document.getElementById('duration');

            if (isYouTubeUrl(currentVideoUrl) && player && typeof player.getCurrentTime === 'function' && typeof player.getDuration === 'function') {
                try {
                    const current = player.getCurrentTime();
                    const total = player.getDuration();
                    seekBar.value = (current / total) * 100 || 0;
                    currentTime.textContent = formatTime(current);
                    duration.textContent = formatTime(total);
                } catch (e) {
                    console.warn('YouTube time access failed:', e);
                }
            } else if (video) {
                seekBar.value = (video.currentTime / video.duration) * 100 || 0;
                currentTime.textContent = formatTime(video.currentTime);
                duration.textContent = formatTime(video.duration);
            }
        }

        function seekVideo() {
            const seekBar = document.getElementById('seekBar');
            if (isYouTubeUrl(currentVideoUrl) && player && typeof player.seekTo === 'function') {
                try {
                    const time = (seekBar.value / 100) * player.getDuration();
                    player.seekTo(time, true);
                    console.log('Seeking to:', time);
                } catch (e) {
                    console.error('YouTube seek failed:', e);
                }
            } else if (video) {
                const time = (seekBar.value / 100) * video.duration;
                video.currentTime = time;
            }
        }

        function openModal(videoUrl) {
            console.log('openModal called with URL:', videoUrl);
            currentVideoUrl = videoUrl;

            const modal = document.getElementById('videoModal');
            const videoContainer = document.getElementById('videoContainer');
            const seekBar = document.getElementById('seekBar');
            const currentTime = document.getElementById('currentTime');
            const duration = document.getElementById('duration');
            const uiOverlay = document.getElementById('uiOverlay');

            if (!modal || !videoContainer || !seekBar || !currentTime || !duration || !uiOverlay) {
                console.error('Modal elements not found:', { modal, videoContainer, seekBar, currentTime, duration, uiOverlay });
                alert('Error: Modal elements not found');
                return;
            }

            if (!videoUrl) {
                console.error('No video URL provided');
                alert('Error: No video URL');
                return;
            }

            // Clear previous content
            videoContainer.innerHTML = '';
            seekBar.value = 0;
            currentTime.textContent = '0:00';
            duration.textContent = '0:00';

            if (isYouTubeUrl(videoUrl)) {
                const embedUrl = getYouTubeEmbedUrl(videoUrl);
                if (!embedUrl) {
                    console.error('Invalid YouTube URL:', videoUrl);
                    alert('Error: Invalid YouTube URL');
                    return;
                }
                console.log('YouTube embed URL:', embedUrl);
                videoContainer.innerHTML = `
                    <iframe 
                        id="youtubePlayer" 
                        class="w-full h-full" 
                        src="${embedUrl}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                    ></iframe>
                `;
                uiOverlay.style.display = 'block'; // Show overlay for YouTube
                // Initialize player with API
                if (window.YT && window.YT.Player) {
                    player = new YT.Player('youtubePlayer', {
                        events: {
                            'onReady': function(event) {
                                player = event.target;
                                console.log('YouTube player ready');
                                updateSeekBar(); // Initial update
                                setInterval(updateSeekBar, 1000); // Update every second
                            },
                            'onStateChange': function(event) {
                                if (event.data === YT.PlayerState.PLAYING) {
                                    updateSeekBar();
                                }
                            }
                        }
                    });
                } else {
                    console.error('YouTube API not loaded');
                    alert('YouTube API failed to load, check internet or script');
                }
            } else {
                videoContainer.innerHTML = `
                    <video id="modalVideo" class="w-full h-full object-contain">
                        <source id="videoSource" src="${videoUrl}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
                uiOverlay.style.display = 'none'; // Hide overlay for normal videos
                video = document.getElementById('modalVideo');
                video.load();
                video.play().catch(error => {
                    console.error('Video playback error:', error);
                    alert('Video error: ' + error.message);
                });
                video.addEventListener('timeupdate', updateSeekBar);
                video.addEventListener('loadedmetadata', () => {
                    duration.textContent = formatTime(video.duration);
                });
            }

            console.log('Modal display before:', modal.style.display);
            modal.style.display = 'block';
            console.log('Modal display after:', modal.style.display);
        }

        function closeModal() {
            console.log('closeModal called');

            const modal = document.getElementById('videoModal');
            const videoContainer = document.getElementById('videoContainer');
            const uiOverlay = document.getElementById('uiOverlay');

            if (modal && videoContainer) {
                modal.style.display = 'none';
                videoContainer.innerHTML = '';
                if (uiOverlay) uiOverlay.style.display = 'none';
                if (video) {
                    video.pause();
                    video.removeEventListener('timeupdate', updateSeekBar);
                    video = null;
                }
                if (player) {
                    player.destroy();
                    player = null;
                }
                console.log('Modal hidden');
            } else {
                console.error('Modal or container not found');
                alert('Error: Modal elements not found');
            }
        }
    </script>
@endsection

<style>
    /* Video and iframe fit */
    #videoContainer iframe, #videoContainer video {
        max-width: 100%;
        max-height: 100%;
    }

    /* Overlay to block YouTube UI (excluding close button and seek bar) */
    #uiOverlay {
        cursor: default;
        z-index: 10;
    }
    #seekBarContainer {
        z-index: 15; /* Above overlay but below close button */
    }
    button {
        z-index: 20; /* Above all */
    }
</style>