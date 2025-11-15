@extends('admin.layouts.app')

@section('title', 'Recordings')

@section('content')
@php
    use Illuminate\Support\Str;
    $folders = $folders ?? collect();
    $recordingsCount = $recordingsCount ?? 0;
@endphp

<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-lg p-6 text-gray-800 border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
            <div>
                <p class="text-xs uppercase tracking-widest text-indigo-600">Your batch recordings</p>
                <h1 class="text-3xl font-bold text-gray-900 mt-2">
                    @if($course) {{ $course->name }} @else Your recordings @endif
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $batch && $batch->batch_name ? "Batch: {$batch->batch_name}" : 'Batch assignments pending' }}
                </p>
            </div>
            <div class="text-right space-y-1">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Unlocked videos</p>
                <p class="text-3xl font-semibold text-blue-600">{{ $recordingsCount }}</p>
                @if($course)
                    <p class="text-sm text-gray-500">{{ $course->batches->count() }} batch{{ $course->batches->count() === 1 ? '' : 'es' }} available</p>
                @endif
            </div>
        </div>
        @if($error ?? session('error'))
            <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ $error ?? session('error') }}
            </div>
        @endif
    </div>

    @if(!$course)
        <div class="bg-white rounded-xl shadow-lg p-6 border border-dashed border-gray-300 text-center text-gray-500">
            No course information available yet. Please contact your administrator to get access.
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 space-y-6">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Course</p>
                    <h2 class="text-2xl font-semibold text-indigo-600">{{ $course->name }}</h2>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Your batch</p>
                    <p class="text-lg font-semibold text-gray-700">{{ $batch->batch_name ?? 'Unnamed batch' }}</p>
                </div>
            </div>

            @forelse($folders as $folder)
                <div class="border border-gray-100 rounded-2xl p-5 shadow-sm bg-gradient-to-b from-white to-gray-50">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-indigo-700">{{ $folder->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Folder ID: {{ $folder->id }}</p>
                        </div>
                        <span class="text-sm font-semibold {{ $folder->locked ? 'text-red-500' : 'text-green-600' }}">
                            {{ $folder->locked ? 'Locked by admin' : 'Unlocked' }}
                        </span>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse($folder->topics as $topic)
                            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm space-y-3">
                                <div class="flex items-start justify-between gap-6">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">{{ $topic->name }}</h4>
                                        @if($topic->discussion)
                                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">
                                                {{ Str::limit($topic->discussion, 120) }}
                                            </p>
                                        @endif
                                    </div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400">{{ $topic->recordings->count() }} video{{ $topic->recordings->count() === 1 ? '' : 's' }}</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @forelse($topic->recordings as $recording)
                                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-400">Recording ID {{ $recording->id }}</p>
                                    </div>
                                            <div class="flex flex-col gap-1">
                                            <button
                                                type="button"
                                                class="text-left text-indigo-600 hover:text-indigo-800 font-semibold"
                                                onclick="openModal('{{ $recording->video_url }}')"
                                            >
                                                Watch recording
                                            </button>
                                                @if($recording->created_at)
                                                    <p class="text-[0.7rem] text-gray-400">Uploaded {{ $recording->created_at->format('Y-m-d') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full rounded-xl border border-dashed border-gray-200 bg-white/50 text-center text-sm text-gray-500 py-3">
                                            No unlocked recordings yet.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 border border-dashed border-gray-200 rounded-xl p-4 text-center">
                                No topics or recordings are available in this folder yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500 border border-dashed border-gray-200 rounded-xl p-6 text-center">
                    Your batch's course does not have any unlocked folders yet.
                </div>
            @endforelse
        </div>
    @endif
</div>

<!-- Full Page Modal -->
<div id="videoModal" style="display: none;" class="fixed inset-0 z-50 bg-black bg-opacity-70 flex items-center justify-center px-4">
    <div id="videoModalContent" class="relative w-full bg-white rounded-2xl p-4 flex flex-col shadow-2xl">
        <button
            type="button"
            onclick="closeModal()"
            class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-3xl font-bold z-20"
        >×</button>
            <div class="flex-grow w-full h-full" id="videoContainer"></div>
        <div id="seekBarContainer" class="w-full pt-4">
            <input type="range" id="seekBar" min="0" max="100" value="0" class="w-full h-2 rounded-full appearance-none bg-gray-200 cursor-pointer">
            <div class="flex justify-between text-xs text-gray-500 mt-1">
                <span id="currentTime">0:00</span>
                <span id="duration">0:00</span>
            </div>
        </div>
        <div id="uiOverlay" class="pointer-events-none absolute inset-x-0 top-16 bottom-16 bg-transparent"></div>
    </div>
</div>

<script src="https://www.youtube.com/iframe_api"></script>
<script>
    let player;
    let video;
    let currentVideoUrl = '';

    function onYouTubeIframeAPIReady() {
        console.log('YouTube API ready');
    }

    function isYouTubeUrl(url) {
        return !!(url && (url.includes('youtube.com') || url.includes('youtu.be')));
    }

    function getYouTubeEmbedUrl(url) {
        let videoId = '';
        if (url.includes('youtube.com/watch?v=')) {
            videoId = url.split('v=')[1].split('&')[0];
        } else if (url.includes('youtu.be/')) {
            videoId = url.split('youtu.be/')[1].split('?')[0];
        }
        return videoId
            ? `https://www.youtube.com/embed/${videoId}?controls=0&modestbranding=1&iv_load_policy=3&rel=0&fs=0&enablejsapi=1&origin=${encodeURIComponent(window.location.origin)}`
            : '';
    }

    function formatTime(seconds) {
        if (isNaN(seconds)) return '0:00';
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
    }

    function updateSeekBar() {
        const seekBar = document.getElementById('seekBar');
        const currentTime = document.getElementById('currentTime');
        const duration = document.getElementById('duration');

        if (isYouTubeUrl(currentVideoUrl) && player && player.getCurrentTime && player.getDuration) {
            try {
                const current = player.getCurrentTime();
                const total = player.getDuration();
                seekBar.value = (current / total) * 100 || 0;
                currentTime.textContent = formatTime(current);
                duration.textContent = formatTime(total);
            } catch (error) {
                console.warn('YouTube timing error', error);
            }
        } else if (video) {
            seekBar.value = (video.currentTime / video.duration) * 100 || 0;
            currentTime.textContent = formatTime(video.currentTime);
            duration.textContent = formatTime(video.duration);
        }
    }

    function seekVideo() {
        const seekBar = document.getElementById('seekBar');
        if (isYouTubeUrl(currentVideoUrl) && player && player.seekTo) {
            try {
                const target = (seekBar.value / 100) * player.getDuration();
                player.seekTo(target, true);
            } catch (error) {
                console.error('YouTube seek failed', error);
            }
        } else if (video) {
            video.currentTime = (seekBar.value / 100) * video.duration;
        }
    }

    const seekSlider = document.getElementById('seekBar');
    if (seekSlider) {
        seekSlider.addEventListener('input', seekVideo);
    }

    function openModal(videoUrl) {
        if (!videoUrl) {
            alert('Recording link is missing.');
            return;
        }
        currentVideoUrl = videoUrl;

        const modal = document.getElementById('videoModal');
        const container = document.getElementById('videoContainer');
        const uiOverlay = document.getElementById('uiOverlay');
        const seekBar = document.getElementById('seekBar');
        const currentTime = document.getElementById('currentTime');
        const duration = document.getElementById('duration');

        container.innerHTML = '';
        seekBar.value = 0;
        currentTime.textContent = '0:00';
        duration.textContent = '0:00';

        if (isYouTubeUrl(videoUrl)) {
            const embedUrl = getYouTubeEmbedUrl(videoUrl);
            if (!embedUrl) {
                alert('Cannot load this YouTube link');
                return;
            }
            container.innerHTML = `
                <iframe
                    id="youtubePlayer"
                    class="w-full h-full rounded-xl border border-gray-200"
                    src="${embedUrl}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            `;
            uiOverlay.style.display = 'block';
            if (window.YT && window.YT.Player) {
                player = new window.YT.Player('youtubePlayer', {
                    events: {
                        onReady: function (event) {
                            player = event.target;
                            updateSeekBar();
                            setInterval(updateSeekBar, 1000);
                        },
                        onStateChange: function () {
                            updateSeekBar();
                        }
                    }
                });
            }
        } else {
            container.innerHTML = `
                <video id="modalVideo" class="w-full h-full object-contain rounded-xl" controls>
                    <source src="${videoUrl}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            `;
            uiOverlay.style.display = 'none';
            video = document.getElementById('modalVideo');
            video.load();
            video.play().catch(error => console.warn('Autoplay blocked', error));
            video.addEventListener('timeupdate', updateSeekBar);
            video.addEventListener('loadedmetadata', () => {
                duration.textContent = formatTime(video.duration);
            });
        }

        modal.style.display = 'flex';
    }

    function closeModal() {
        const modal = document.getElementById('videoModal');
        const container = document.getElementById('videoContainer');
        const uiOverlay = document.getElementById('uiOverlay');

        if (video) {
            video.pause();
            video.removeEventListener('timeupdate', updateSeekBar);
            video = null;
        }
        if (player) {
            player.destroy();
            player = null;
        }
        if (container) {
            container.innerHTML = '';
        }
        if (uiOverlay) {
            uiOverlay.style.display = 'none';
        }
        modal.style.display = 'none';
    }
</script>

<style>
    #videoModal {
        z-index: 999;
    }
    #videoModalContent {
        width: min(1400px, 96vw);
        height: min(90vh, 820px);
        background: #000;
        object-fit: contain;
    }
    #videoContainer {
        flex: 1;
        min-height: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #videoContainer iframe,
    #videoContainer video {
        width: 100%;
        height: 100%;
        border-radius: 1rem;
    }
    #seekBar::-webkit-slider-thumb {
        background: #6366f1;
    }
</style>

@endsection
