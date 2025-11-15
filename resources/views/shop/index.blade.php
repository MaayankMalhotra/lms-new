@extends('shop.layouts.app')

@section('title', 'Amazon-style Storefront')

@section('content')
    <div class="mx-auto max-w-7xl space-y-10 px-4">
        <section class="grid gap-6 lg:grid-cols-4">
            <div class="rounded-lg bg-white p-6 shadow-sm lg:col-span-3">
                <img src="{{ asset('images/catalog/hero-perfume.jpg') }}" alt="Hero"
                    class="h-64 w-full rounded-md object-cover">
                <div class="mt-4 space-y-1">
                    <p class="text-sm text-gray-500 uppercase">Top deals for you</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Signature perfumes & motion sneakers</h1>
                    <p class="text-sm text-gray-600">Curated fragrances, limited sneakers, and ritual kits built for high-performing founders.</p>
                    <div class="flex gap-4 pt-2">
                        <a href="{{ route('shop.category', optional($featuredCategories->first())->slug ?? '') }}"
                            class="rounded-md bg-[#ffd814] px-4 py-2 text-sm font-semibold text-[#111] hover:bg-[#f7ca00]">Shop collections</a>
                        <a href="{{ route('shop.search', ['q' => 'gift']) }}"
                            class="rounded-md bg-[#f0c14b] px-4 py-2 text-sm font-semibold text-[#111] hover:bg-[#e6b535]">Gift studio</a>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                @foreach($featuredProducts->take(2) as $product)
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <p class="text-xs uppercase text-gray-500">Featured</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $product->name }}</p>
                        <img src="{{ $product->hero_image_url ?? asset('images/catalog/placeholder.jpg') }}"
                            class="mt-3 h-32 w-full rounded-md object-cover" alt="{{ $product->name }}">
                        <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $product->short_description }}</p>
                        <a href="{{ route('shop.product', $product->slug) }}"
                            class="mt-2 inline-flex text-xs font-semibold text-[#007185]">See details</a>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-slate-900">Shop by category</h2>
                <a href="{{ route('shop.search') }}" class="text-sm font-semibold text-[#007185]">See more</a>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                @foreach($featuredCategories as $category)
                    <a href="{{ route('shop.category', $category->slug) }}"
                        class="rounded-lg bg-white p-4 shadow-sm hover:shadow-md">
                        <img src="{{ $category->hero_image ? asset($category->hero_image) : asset('images/catalog/placeholder.jpg') }}"
                            class="h-40 w-full rounded-md object-cover" alt="{{ $category->name }}">
                        <p class="mt-3 text-base font-semibold text-slate-900">{{ $category->name }}</p>
                        <p class="text-sm text-gray-500 line-clamp-2">{{ $category->description }}</p>
                        <span class="mt-2 inline-flex text-xs font-semibold text-[#007185]">Explore now</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-slate-900">Best sellers</h2>
                <a href="{{ route('shop.search', ['q' => 'signature']) }}" class="text-sm font-semibold text-[#007185]">View all</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($featuredProducts as $product)
                    @include('shop.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>

        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-slate-900">New arrivals</h2>
                <a href="{{ route('shop.search', ['q' => 'new']) }}" class="text-sm font-semibold text-[#007185]">Track launches</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($newArrivals as $product)
                    @include('shop.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    </div>
@endsection
