@extends('shop.layouts.app')

@section('title', 'Search ' . ($query ?: '') . ' | Aromea Market')

@section('content')
    <div class="mx-auto max-w-7xl px-4 space-y-8">
        <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Aromea / Search</p>

        <form method="GET" action="{{ route('shop.search') }}" class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search perfumes, sneakers, rituals..."
                    class="flex-1 rounded-full border border-gray-200 px-5 py-3">
                <select name="category" class="rounded-full border border-gray-200 px-5 py-3">
                    <option value="">All collections</option>
                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">
                    Search
                </button>
            </div>
        </form>

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
                <p class="text-xl font-semibold text-slate-900">No results for "{{ $query }}".</p>
                <p class="mt-2 text-gray-500">Try another keyword or explore our best sellers.</p>
                <a href="{{ route('shop.index') }}" class="mt-6 inline-flex rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Back to storefront</a>
            </div>
        @endif
    </div>
@endsection
