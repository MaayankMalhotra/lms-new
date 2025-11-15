@extends('shop.layouts.app')

@section('title', $product->name . ' | Aromea Market')

@section('content')
    <div class="mx-auto max-w-7xl px-4 space-y-8">
        <p class="text-xs uppercase tracking-[0.3em] text-gray-500">
            Aromea / {{ $product->categories->first()->name ?? 'Collection' }} / {{ $product->name }}
        </p>

        <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
            <div class="space-y-6">
                <div class="grid gap-4 md:grid-cols-[120px,1fr]">
                    <div class="space-y-3">
                        @foreach($product->images as $image)
                            <img src="{{ asset($image->path) }}" alt="{{ $product->name }}"
                                class="w-full rounded-md border">
                        @endforeach
                    </div>
                    <div class="rounded-md bg-white p-4">
                        <img src="{{ $product->hero_image_url ?? asset('images/catalog/placeholder.jpg') }}"
                            alt="{{ $product->name }}" class="w-full rounded-md object-cover">
                    </div>
                </div>

                <section class="rounded-3xl bg-white p-8 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Product story</p>
                    <h1 class="mt-3 text-3xl font-semibold text-slate-900">{{ $product->name }}</h1>
                    <p class="mt-4 text-lg text-gray-600">{{ $product->short_description }}</p>
                    <div class="mt-6 prose max-w-none text-gray-600">
                        {!! $product->description !!}
                    </div>
                </section>

                @if($product->specifications)
                    <section class="rounded-3xl bg-white p-8 shadow-sm">
                        <div class="grid gap-6 sm:grid-cols-2">
                            @foreach($product->specifications as $label => $value)
                                <div class="rounded-2xl border border-gray-200 p-4">
                                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $label }}</p>
                                    @if(is_array($value))
                                        <ul class="mt-3 list-disc space-y-1 pl-4 text-sm text-gray-600">
                                            @foreach($value as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-3 text-lg text-slate-900">{{ $value }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Prime pricing</p>
                    <div class="flex items-baseline gap-3">
                        <p class="text-4xl font-semibold text-slate-900">₹{{ number_format($product->price, 0) }}</p>
                        @if($product->compare_at_price)
                            <p class="text-sm text-gray-400 line-through">₹{{ number_format($product->compare_at_price, 0) }}</p>
                        @endif
                    </div>
                    <p class="text-sm text-emerald-600 font-semibold">
                        {{ $product->inventory > 0 ? 'In stock — ships in 24h' : 'Waitlist open — ships soon' }}
                    </p>

                    <form method="POST" action="{{ route('cart.store') }}" class="space-y-4" data-product-detail-form data-add-to-cart>
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label class="block text-sm font-semibold text-slate-700">
                            Quantity
                            <input type="number" name="quantity" min="1" value="1"
                                class="mt-2 w-20 rounded-xl border border-gray-300 px-3 py-2 text-center">
                        </label>
                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Add to cart</button>
                            <button type="button" data-buy-now data-product-id="{{ $product->id }}"
                                class="w-full rounded-full border border-slate-900 px-4 py-3 text-sm font-semibold text-slate-900">Buy now</button>
                        </div>
                    </form>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold text-slate-900">Prime Perks</p>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li>• Free returns within 10 days</li>
                        <li>• Meta ads ready pixel events</li>
                        <li>• Concierge styling support</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('meta_pixel_events')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent', {
                    content_ids: ['{{ $product->sku ?: $product->id }}'],
                    content_name: '{{ $product->name }}',
                    content_type: 'product',
                    value: {{ $product->price }},
                    currency: '{{ config('commerce.currency', 'INR') }}'
                });
            }
        });
    </script>
@endpush
