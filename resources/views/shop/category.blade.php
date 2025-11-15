@extends('shop.layouts.app')

@section('title', $category->name . ' | Aromea Market')

@section('content')
    <div class="mx-auto max-w-7xl px-4 space-y-10">
        <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Aromea / Category / {{ $category->name }}</p>

        <header class="rounded-3xl bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Collection</p>
                    <h1 class="text-3xl font-semibold text-slate-900">{{ $category->name }}</h1>
                    <p class="mt-2 text-gray-500 max-w-2xl">{{ $category->description }}</p>
                </div>
                <form method="GET" class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm">
                        <span>Min ₹</span>
                        <input type="number" name="price_min" value="{{ request('price_min') }}" class="w-24 border-none focus:outline-none" min="0">
                    </label>
                    <label class="flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm">
                        <span>Max ₹</span>
                        <input type="number" name="price_max" value="{{ request('price_max') }}" class="w-24 border-none focus:outline-none" min="0">
                    </label>
                    <button type="submit" class="rounded-full bg-slate-900 px-6 py-2 text-sm font-semibold text-white">Apply</button>
                    <a href="{{ route('shop.category', $category->slug) }}" class="rounded-full border border-gray-300 px-6 py-2 text-sm font-semibold text-slate-900">Reset</a>
                </form>
            </div>
        </header>

        @if($products->count())
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($products as $product)
                    @include('shop.partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-16 text-center">
                <p class="text-xl font-semibold text-slate-900">No drops match the filters.</p>
                <p class="mt-2 text-gray-500">Try a different price range or browse another category.</p>
                <a href="{{ route('shop.index') }}" class="mt-6 inline-flex rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Back to home</a>
            </div>
        @endif
    </div>
@endsection
