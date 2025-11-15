@extends('shop.layouts.app')

@section('title', 'Amazon Style Landing')

@section('content')
    <section class="bg-gradient-to-r from-[#131921] to-[#232f3e] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-16 lg:grid-cols-12">
            <div class="space-y-4 lg:col-span-8">
                <p class="text-sm uppercase tracking-wide text-[#febd69]">Welcome to Aromea Market</p>
                <h1 class="text-4xl font-semibold leading-tight">
                    Your Amazon-style marketplace for perfumes, motion sneakers & ritual care kits.
                </h1>
                <p class="text-sm text-[#d5dbe4]">
                    Browse every drop in one scroll, then tap the AI assistant in the nav whenever you want concierge help.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('shop.category', optional($categories->first())->slug ?? '') }}"
                        class="rounded-full bg-[#ff9900] px-6 py-2 text-sm font-semibold text-[#131921] shadow hover:bg-[#f09c1a]">
                        Shop featured category
                    </a>
                    <a href="{{ route('shop.index') }}"
                        class="rounded-full border border-white/40 px-6 py-2 text-sm font-semibold text-white hover:border-white">
                        Chat with Aromea AI
                    </a>
                </div>
            </div>
            <div class="rounded-3xl bg-white/5 p-6 lg:col-span-4">
                @if($heroProduct)
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-xs uppercase tracking-wide text-[#febd69]">Hero pick</p>
                        <h3 class="text-xl font-semibold text-white">{{ $heroProduct->name }}</h3>
                        <img src="{{ $heroProduct->hero_image_url ?? asset('images/catalog/placeholder.jpg') }}"
                            alt="{{ $heroProduct->name }}" class="mt-4 h-40 w-full rounded-lg object-cover">
                        <p class="mt-3 text-sm text-[#d5dbe4] line-clamp-3">{{ $heroProduct->short_description }}</p>
                        <p class="mt-2 text-xl font-semibold text-white">₹{{ number_format($heroProduct->price, 0) }}</p>
                        <a href="{{ route('shop.product', $heroProduct->slug) }}"
                            class="mt-3 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-[#131921]">
                            View details <i class="fa fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl space-y-12 px-4 py-10">
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-[#131921]">Browse departments</h2>
                <a href="{{ route('shop.search') }}" class="text-sm font-semibold text-[#007185] hover:text-[#c7511f]">See all</a>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @foreach($categories as $category)
                    <a href="{{ route('shop.category', $category->slug) }}"
                        class="rounded-2xl bg-white p-5 shadow hover:shadow-lg">
                        <img src="{{ $category->hero_image ? asset($category->hero_image) : asset('images/catalog/placeholder.jpg') }}"
                            alt="{{ $category->name }}" class="h-40 w-full rounded-xl object-cover">
                        <div class="mt-4">
                            <p class="text-lg font-semibold text-[#131921]">{{ $category->name }}</p>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $category->description }}</p>
                            <span class="mt-2 inline-flex text-xs font-semibold text-[#007185]">Shop now</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-[#131921]">All products</h2>
                <div class="text-sm text-gray-600">Showing {{ $products->count() }} listings</div>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($products as $product)
                    <div class="flex flex-col rounded-2xl bg-white p-4 shadow-sm hover:shadow-lg">
                        <img src="{{ $product->hero_image_url ?? asset('images/catalog/placeholder.jpg') }}"
                            alt="{{ $product->name }}" class="h-48 w-full rounded-xl object-cover">
                        <div class="mt-4 flex-1 space-y-2">
                            <p class="text-xs uppercase tracking-wide text-gray-500">{{ $product->categories->pluck('name')->first() }}</p>
                            <h3 class="text-lg font-semibold text-[#131921] leading-tight">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-3">{{ $product->short_description }}</p>
                            <p class="text-2xl font-bold text-[#B12704]">₹{{ number_format($product->price, 0) }}</p>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <form data-add-to-cart method="POST" action="{{ route('cart.store') }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit"
                                    class="w-full rounded-full border border-[#131921] px-3 py-2 text-sm font-semibold text-[#131921] hover:bg-[#131921] hover:text-white">
                                    Add to cart
                                </button>
                            </form>
                            <a href="{{ route('shop.product', $product->slug) }}"
                                class="flex-1 rounded-full bg-[#ffd814] px-3 py-2 text-center text-sm font-semibold text-[#131921] hover:bg-[#f7ca00]">
                                View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
