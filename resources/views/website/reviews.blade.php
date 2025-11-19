@extends('website.layouts.app')

@section('title', 'Career Highlights & Reviews')

@section('content')
@php
    $highlightStats = optional($highlight)->stats ?? collect();
@endphp

<div class="bg-white text-slate-900 font-poppins">
    {{-- Hero / Highlights --}}
    <section class="bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-16 lg:py-20 grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-indigo-200">Career Highlights</p>
                <h1 class="text-3xl sm:text-4xl font-bold mt-3 leading-snug">
                    {{ optional($highlight)->heading_line ?? 'Learners who trusted Think Champ' }}
                    <span class="text-orange-400 block">
                        {{ optional($highlight)->heading_highlight ?? 'Now lead the next wave of tech talent' }}
                    </span>
                </h1>
                <p class="mt-4 text-slate-200 text-base leading-relaxed">
                    Stories, stats, and shout-outs from graduates who turned their ambition into offers from top startups,
                    global technology teams, and high-growth product companies.
                </p>

                <div class="mt-8 grid sm:grid-cols-2 gap-4">
                    @forelse ($highlightStats as $stat)
                        <div class="rounded-2xl bg-white/10 backdrop-blur px-5 py-4 border border-white/10">
                            <div class="flex items-center text-sm font-semibold text-orange-300">
                                <i class="{{ $stat->icon ?? 'fas fa-star' }} mr-2"></i>
                                {{ $stat->label ?? 'Milestone' }}
                            </div>
                            <p class="text-2xl font-semibold mt-2 text-white">
                                {{ $stat->value ?? '—' }}
                            </p>
                        </div>
                    @empty
                        <div class="col-span-2 rounded-2xl bg-white/10 px-5 py-4 text-sm text-slate-200">
                            Publish a career highlight in admin to showcase live stats here.
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    @if (!empty(optional($highlight)->cta_text))
                        <a href="{{ route('website.contact') }}"
                           class="inline-flex items-center bg-orange-500 hover:bg-orange-400 text-white px-6 py-3 rounded-xl font-semibold transition">
                            {{ optional($highlight)->cta_text }}
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('website.contact') }}"
                           class="inline-flex items-center text-orange-300 hover:text-orange-200 font-semibold">
                            Talk to career experts
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <div class="rounded-3xl bg-white text-slate-900 shadow-2xl p-6 md:p-8 space-y-6">
                <div>
                    <p class="text-sm font-semibold text-orange-500 uppercase tracking-widest">From the community</p>
                    <h2 class="text-2xl font-semibold mt-1">Snapshots from recent hires</h2>
                </div>
                @if($testimonials->count())
                    <div class="space-y-5 max-h-[420px] overflow-y-auto pr-2">
                        @foreach($testimonials->take(4) as $testimonial)
                            <article class="flex items-center gap-4 border border-slate-100 rounded-2xl p-4">
                                <img src="{{ $testimonial->image_url ?? 'https://via.placeholder.com/60' }}"
                                     alt="{{ $testimonial->name }}"
                                     class="w-14 h-14 rounded-full object-cover">
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $testimonial->name }}</h3>
                                    <p class="text-sm text-slate-500">{{ $testimonial->position }} @ {{ $testimonial->company }}</p>
                                    <div class="mt-1 text-yellow-400 text-lg tracking-tight leading-none">
                                        @for ($i = 1; $i <= 5; $i++)
                                            {!! $i <= $testimonial->rating ? '★' : '☆' !!}
                                        @endfor
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">Testimonials will appear after you create them from the admin panel.</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Testimonials Deck --}}
    <section class="py-16 lg:py-20">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto">
                <p class="text-sm uppercase tracking-[0.25em] text-orange-500">Placements</p>
                <h2 class="text-3xl md:text-4xl font-bold mt-2">
                    Our seniors share their placement success & reviews
                </h2>
                <p class="mt-4 text-slate-500">
                    Every batch graduates with proof of work, industry mentorship, and tailored interview prep.
                </p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($testimonials as $testimonial)
                    <article class="rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition p-6 flex flex-col">
                        <div class="flex items-center gap-4">
                            <img src="{{ $testimonial->image_url ?? 'https://via.placeholder.com/64' }}"
                                 alt="{{ $testimonial->name }}"
                                 class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $testimonial->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $testimonial->department }}</p>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-600 leading-relaxed">
                            {{ \Illuminate\Support\Str::limit($testimonial->message ?? 'This student is yet to add detailed feedback.', 160) }}
                        </p>
                        <div class="mt-5">
                            <p class="text-sm font-semibold text-slate-700">{{ $testimonial->position }}</p>
                            <p class="text-sm text-orange-500">{{ $testimonial->company }}</p>
                        </div>
                        <div class="mt-4 text-yellow-400 text-lg">
                            @for ($i = 1; $i <= 5; $i++)
                                {!! $i <= $testimonial->rating ? '★' : '☆' !!}
                            @endfor
                        </div>
                    </article>
                @empty
                    <p class="text-center text-slate-500 col-span-full">No testimonials available yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Video Reviews --}}
    <section class="bg-slate-50 py-16">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Video Reviews</p>
                    <h2 class="text-3xl font-bold text-slate-900">Hear it directly from Think Champ alumni</h2>
                    <p class="mt-2 text-slate-500 max-w-2xl">
                        Bite-sized reflections from those who converted their projects into high-impact offers. Fresh uploads
                        appear here automatically.
                    </p>
                </div>
                <a href="{{ route('website.contact') }}"
                   class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-slate-900">
                    Want to feature your story?
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($youtubeReviews as $review)
                    <article class="rounded-2xl overflow-hidden bg-white border border-slate-100 shadow-sm hover:shadow-lg transition">
                        @if($review->video_id)
                            <iframe class="w-full aspect-video"
                                    src="https://www.youtube.com/embed/{{ $review->video_id }}"
                                    title="{{ $review->title }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        @endif
                        <div class="p-5">
                            <p class="text-sm font-semibold text-orange-500 uppercase tracking-wide">{{ $review->category ?? 'Learner Story' }}</p>
                            <h3 class="text-lg font-semibold mt-1">{{ $review->title ?? 'YouTube Review' }}</h3>
                            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                {{ $review->description ?? 'Learner story published from the admin panel.' }}
                            </p>
                        </div>
                    </article>
                @empty
                    <p class="text-center text-slate-500 col-span-full">Add YouTube reviews in the admin to showcase them here.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
