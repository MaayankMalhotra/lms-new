@extends('admin.layouts.app')

@section('content')
    <div class="px-3">
        <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-transparent rounded-3xl shadow-2xl p-6 text-white mb-6">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Demo Video Uploader</h1>
                    <p class="text-sm text-gray-200 max-w-2xl">
                        Push curated YouTube demos directly to each module. Pick a course or internship, choose
                        the matching module, and paste the secure URL. Only logged-in admins can save changes.
                    </p>
                </div>
                <a href="{{ route('admin.demoVideoUploader') }}#course-uploads"
                   class="px-4 py-2 rounded-xl border border-white/40 hover:bg-white/10 text-sm font-semibold inline-flex items-center gap-2">
                    <i class="fas fa-link"></i> Jump to Uploads
                </a>
            </div>
        </div>

        <section id="course-uploads" class="grid gap-6 lg:grid-cols-2">
            <div class="bg-white rounded-3xl shadow-lg p-6 space-y-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Courses</h2>
                    <span class="text-sm text-gray-500">{{ $courseDetails->count() }} detail(s)</span>
                </div>
                <div class="space-y-4">
                    @forelse($courseDetails as $detail)
                        <div class="rounded-2xl border border-dashed border-gray-200 p-4 bg-gray-50 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <p class="font-semibold text-gray-700">{{ optional($detail->course)->name ?? 'Unnamed course' }}</p>
                                    <p class="text-gray-500">Course detail #{{ $detail->id }}</p>
                                </div>
                                <span class="text-xs text-gray-400">Modules: {{ count($detail->demo_syllabus ?? []) }}</span>
                            </div>
                            <div class="grid gap-3 md:grid-cols-2">
                                @foreach($detail->demo_syllabus ?? [] as $index => $module)
                                    <div class="rounded-xl bg-white border border-gray-200 p-3 flex flex-col gap-2 shadow-sm">
                                        <p class="text-xs text-gray-500">Module {{ $module['module_number'] ?? ($index + 1) }}</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $module['title'] ?? 'Untitled' }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $module['video_url'] ?? 'No video set' }}</p>
                                        <button type="button"
                                            onclick="openDemoVideoModal('course', {{ $detail->id }}, {{ $index }}, '{{ $module['video_url'] ?? '' }}')"
                                            class="mt-1 text-xs font-semibold text-blue-600 hover:text-blue-800">Set YouTube Link</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-gray-200 p-6 text-center text-gray-500">
                            Course detail data is missing. Add a detail entry first.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="bg-white rounded-3xl shadow-lg p-6 space-y-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Internships</h2>
                    <span class="text-sm text-gray-500">{{ $internshipDetails->count() }} detail(s)</span>
                </div>
                <div class="space-y-4">
                    @forelse($internshipDetails as $detail)
                        <div class="rounded-2xl border border-dashed border-gray-200 p-4 bg-gray-50 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <p class="font-semibold text-gray-700">{{ optional($detail->internship)->name ?? 'Unnamed internship' }}</p>
                                    <p class="text-gray-500">Internship detail #{{ $detail->id }}</p>
                                </div>
                                <span class="text-xs text-gray-400">Modules: {{ count($detail->demo_syllabus ?? []) }}</span>
                            </div>
                            <div class="grid gap-3 md:grid-cols-2">
                                @foreach($detail->demo_syllabus ?? [] as $index => $module)
                                    <div class="rounded-xl bg-white border border-gray-200 p-3 flex flex-col gap-2 shadow-sm">
                                        <p class="text-xs text-gray-500">Module {{ $module['module_number'] ?? ($index + 1) }}</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $module['title'] ?? 'Untitled' }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $module['video_url'] ?? 'No video set' }}</p>
                                        <button type="button"
                                            onclick="openDemoVideoModal('internship', {{ $detail->id }}, {{ $index }}, '{{ $module['video_url'] ?? '' }}')"
                                            class="mt-1 text-xs font-semibold text-blue-600 hover:text-blue-800">Set YouTube Link</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-gray-200 p-6 text-center text-gray-500">
                            Internship detail data is missing. Add a detail entry first.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <div class="mt-6">
            @include('partials.demo_video_uploader')
        </div>
    </div>
@endsection
